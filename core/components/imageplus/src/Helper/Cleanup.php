<?php
/**
 * Cleanup
 *
 * @package imageplus
 * @subpackage helper
 */

namespace TreehillStudio\ImagePlus\Helper;

use FilesystemIterator;
use modMediaSource;
use modX;
use PDO;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;
use TreehillStudio\ImagePlus\ImagePlus;
use xPDO;

/**
 * Remove the unused image crop files of the Image+ template variables
 *
 * The crop paths in the values of all template variables, that contain image crops (Image+ values and MIGX values with
 * Image+ values), and in their default values are used. All other crop files in the crop folder of the media sources
 * are unused. Only files with the name of a crop file are regarded and files, that are newer than the minimal age, are
 * skipped, because a crop is saved before the resource is saved. Remote media sources can't be cleaned up.
 *
 * In dry run mode, nothing is removed and the unused files are only reported. The messages are logged in
 * core/cache/logs/imageplus_cleanup.log
 */
class Cleanup
{
    /** The name of a crop file: <image name>-<md5 hash>.<extension> */
    const CROP_FILENAME = '/-[0-9a-f]{32}\.(jpe?g|png|gif|webp)$/i';

    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    protected $modx;

    /**
     * A reference to the ImagePlus instance
     * @var ImagePlus $imageplus
     */
    protected $imageplus;

    /**
     * Report the unused files without removing them
     * @var bool $dryRun
     */
    protected $dryRun;

    /**
     * The collected log messages
     * @var array $messages
     */
    protected $messages = [];

    /**
     * Cleanup constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param bool $dryRun Report the unused files without removing them.
     */
    public function __construct(modX &$modx, bool $dryRun = true)
    {
        $this->modx =& $modx;
        $this->dryRun = $dryRun;

        $corePath = $this->modx->getOption('imageplus.core_path', null, $this->modx->getOption('core_path') . 'components/imageplus/');
        $this->imageplus = $this->modx->getService('imageplus', 'ImagePlus', $corePath . 'model/imageplus/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * Remove the unused crop files.
     *
     * @param string $path The crop folder in the media sources, the fixed part of the imageplus.crop_path system setting by default
     * @param int[] $sourceIds The media sources, the media sources of the Image+ template variables and the default media source by default
     * @param int $minAge Skip files, that are newer than this number of hours
     * @return void
     */
    public function run(string $path = '', array $sourceIds = [], int $minAge = 24)
    {
        $path = trim(str_replace('\\', '/', ($path !== '') ? $path : $this->getDefaultPath()), '/');
        if ($path === '' || preg_match('#(^|/)\.\.(/|$)#', $path)) {
            $this->log(xPDO::LOG_LEVEL_ERROR, 'The crop folder can\'t be determined from the imageplus.crop_path system setting. Please set it with the path property.');
            return;
        }
        if (!$sourceIds) {
            $sourceIds = array_merge($this->imageplus->getTvSourceIds(), [(int)$this->modx->getOption('default_media_source')]);
        }

        $usedPaths = $this->getUsedPaths();
        $this->modx->loadClass('sources.modMediaSource');
        foreach (array_unique($sourceIds) as $sourceId) {
            $source = modMediaSource::getDefaultSource($this->modx, $sourceId, false);
            if (!$source || !$source->getWorkingContext()) {
                $this->log(xPDO::LOG_LEVEL_ERROR, 'The media source ' . $sourceId . ' does not exist.');
                continue;
            }
            $source->initialize();
            $this->cleanupSource($source, $path, $usedPaths, $minAge);
        }
    }

    /**
     * Get the collected messages.
     *
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Get the crop folder from the part of the imageplus.crop_path system setting before the first placeholder.
     *
     * @return string
     */
    protected function getDefaultPath(): string
    {
        $cropPath = (string)$this->imageplus->getOption('crop_path', null, '');
        if (trim($cropPath, '/') === '') {
            // The crop processor uses this folder without the system setting
            return 'crop';
        }
        $position = strpos($cropPath, '[[');
        return ($position === false) ? $cropPath : substr($cropPath, 0, $position);
    }

    /**
     * Get the used crop paths in the values and the default values of the template variables.
     *
     * @return array The crop paths as keys
     */
    protected function getUsedPaths(): array
    {
        $usedPaths = [];
        $queries = [
            $this->modx->newQuery('modTemplateVarResource', ['value:LIKE' => '%sizes%']),
            $this->modx->newQuery('modTemplateVar', ['default_text:LIKE' => '%sizes%']),
        ];
        $queries[0]->select('value');
        $queries[1]->select('default_text');
        foreach ($queries as $c) {
            if ($c->prepare() && $c->stmt->execute()) {
                while ($value = $c->stmt->fetch(PDO::FETCH_COLUMN)) {
                    $this->collectPaths($value, $usedPaths);
                }
            }
        }
        return $usedPaths;
    }

    /**
     * Collect the crop paths of a value. JSON encoded values are decoded recursively, because the Image+ values in
     * MIGX values are encoded again.
     *
     * @param mixed $value
     * @param array $usedPaths
     * @param int $depth
     * @return void
     */
    protected function collectPaths($value, array &$usedPaths, int $depth = 0)
    {
        if ($depth > 20) {
            return;
        }
        if (is_string($value)) {
            $value = ltrim($value);
            if ($value !== '' && ($value[0] === '{' || $value[0] === '[')) {
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    $this->collectPaths($decoded, $usedPaths, $depth + 1);
                }
            }
            return;
        }
        if (!is_array($value)) {
            return;
        }
        if (isset($value['sizes']) && is_array($value['sizes'])) {
            foreach ($value['sizes'] as $size) {
                if (is_array($size) && !empty($size['image']) && is_string($size['image'])) {
                    $usedPaths[ltrim(str_replace('\\', '/', $size['image']), '/')] = true;
                }
            }
        }
        foreach ($value as $item) {
            if (is_array($item) || is_string($item)) {
                $this->collectPaths($item, $usedPaths, $depth + 1);
            }
        }
    }

    /**
     * Remove the unused crop files in the crop folder of a media source.
     *
     * @param modMediaSource $source
     * @param string $path
     * @param array $usedPaths
     * @param int $minAge
     * @return void
     */
    protected function cleanupSource($source, string $path, array $usedPaths, int $minAge)
    {
        $sourceName = $source->get('name') . ' (' . $source->get('id') . ')';
        $basePath = (string)$source->getBasePath($path);
        if ($basePath === '') {
            $this->log(xPDO::LOG_LEVEL_WARN, 'The media source ' . $sourceName . ' is not a local media source and can\'t be cleaned up.');
            return;
        }
        $folder = rtrim($basePath, '/') . '/' . $path;
        if (!is_dir($folder)) {
            return;
        }

        $countUsed = 0;
        $countUnused = 0;
        $countRecent = 0;
        $countErrors = 0;
        $size = 0;
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if (!$file->isFile() || !preg_match(self::CROP_FILENAME, $file->getFilename())) {
                continue;
            }
            $filePath = $path . '/' . ltrim(str_replace('\\', '/', substr($file->getPathname(), strlen($folder))), '/');
            if (isset($usedPaths[$filePath])) {
                $countUsed++;
                continue;
            }
            if ($minAge > 0 && $file->getMTime() > time() - $minAge * 3600) {
                $countRecent++;
                continue;
            }
            $fileSize = $file->getSize();
            if ($this->dryRun) {
                $this->log(xPDO::LOG_LEVEL_INFO, 'Would remove ' . $filePath . ' in the media source ' . $sourceName . '.');
            } else {
                try {
                    $removed = $source->removeObject($filePath);
                } catch (Throwable $e) {
                    $removed = false;
                }
                if (!$removed) {
                    $this->log(xPDO::LOG_LEVEL_ERROR, 'Could not remove ' . $filePath . ' in the media source ' . $sourceName . '.');
                    $countErrors++;
                    continue;
                }
                $this->log(xPDO::LOG_LEVEL_INFO, 'Removed ' . $filePath . ' in the media source ' . $sourceName . '.');
            }
            $countUnused++;
            $size += $fileSize;
        }

        $this->log(xPDO::LOG_LEVEL_INFO, sprintf('%s %d unused crop files (%s) in %s of the media source %s. %d crop files are used, %d crop files are newer than %d hours.',
            ($this->dryRun) ? 'Would remove' : 'Removed', $countUnused, $this->formatSize($size), $path, $sourceName, $countUsed, $countRecent, $minAge) .
            (($countErrors) ? sprintf(' %d crop files could not be removed.', $countErrors) : ''));
    }

    /**
     * Format a file size.
     *
     * @param int $size
     * @return string
     */
    protected function formatSize(int $size): string
    {
        return ($size >= 1048576) ? round($size / 1048576, 1) . ' MB' : round($size / 1024) . ' KB';
    }

    /**
     * Log a message and collect it.
     *
     * The message is logged in the default log target and in core/cache/logs/imageplus_cleanup.log and collected for
     * the snippet output.
     *
     * @param int $level
     * @param string $message
     * @return void
     */
    protected function log(int $level, string $message)
    {
        if ($this->dryRun) {
            $message = 'Dry run: ' . $message;
        }
        $this->messages[] = [
            'level' => $level,
            'message' => $message,
        ];
        $this->modx->log($level, $message);
        $this->modx->log($level, $message, [
            'target' => 'FILE',
            'options' => [
                'filename' => 'imageplus_cleanup.log',
                'filepath' => $this->modx->getOption(xPDO::OPT_CACHE_PATH, null, MODX_CORE_PATH . 'cache/') . 'logs/',
            ]
        ]);
    }
}
