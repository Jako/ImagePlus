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
 * Folders below the crop folder, that are empty afterwards, are removed too. The crop files, that the CropFile crop
 * engine creates for image crops without an existing crop file, are used too.
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
     * The image crops, the CropFile crop engine creates a crop file for them, if their crop file is missing
     * @var array $uncroppedSizes
     */
    protected $uncroppedSizes = [];

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
            // Values before Image+ 3.0 contain sourceImg
            $this->modx->newQuery('modTemplateVarResource', ['value:LIKE' => '%sizes%', 'OR:value:LIKE' => '%sourceImg%']),
            $this->modx->newQuery('modTemplateVar', ['default_text:LIKE' => '%sizes%', 'OR:default_text:LIKE' => '%sourceImg%']),
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
                if (is_array($size) && !empty($value['image']) && is_string($value['image'])) {
                    // The CropFile crop engine creates a crop file, if the crop file is missing
                    $this->addUncroppedSize($value['image'], $size);
                }
            }
        } elseif (isset($value['sourceImg']['src']) && is_string($value['sourceImg']['src']) && isset($value['crop']) && is_array($value['crop'])) {
            // A value before Image+ 3.0
            $this->addUncroppedSize($value['sourceImg']['src'], [
                'x' => $value['crop']['x'] ?? 0,
                'y' => $value['crop']['y'] ?? 0,
                'cropWidth' => $value['crop']['width'] ?? 0,
                'cropHeight' => $value['crop']['height'] ?? 0,
                'targetWidth' => $value['targetWidth'] ?? 0,
                'targetHeight' => $value['targetHeight'] ?? 0,
            ]);
        }
        foreach ($value as $item) {
            if (is_array($item) || is_string($item)) {
                $this->collectPaths($item, $usedPaths, $depth + 1);
            }
        }
    }

    /**
     * Remember an image crop, for which the CropFile crop engine could create a crop file.
     *
     * @param string $image The path of the image in the media source
     * @param array $size The image crop
     * @return void
     */
    protected function addUncroppedSize(string $image, array $size)
    {
        $crop = ImageCrop::normalizeCrop($size);
        if ($crop['cropWidth'] && $crop['cropHeight']) {
            $this->uncroppedSizes[] = [
                'image' => ltrim(str_replace('\\', '/', $image), '/'),
                'crop' => $crop,
            ];
        }
    }

    /**
     * Get the names of the crop files, that the CropFile crop engine creates for the image crops without an existing
     * crop file in a media source. The name does not depend on the crop folder.
     *
     * @param string $basePath The base path of the media source
     * @return array The names as keys
     */
    protected function getGeneratedNames(string $basePath): array
    {
        $names = [];
        foreach ($this->uncroppedSizes as $size) {
            $filepath = rtrim($basePath, '/') . '/' . $size['image'];
            if (is_file($filepath)) {
                $names[ImageCrop::getCropName($size['image'], $size['crop'], (string)filemtime($filepath))] = true;
            }
        }
        return $names;
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

        $generatedNames = $this->getGeneratedNames($basePath);
        $countUsed = 0;
        $countUnused = 0;
        $countRecent = 0;
        $countErrors = 0;
        $size = 0;
        $removedFiles = [];
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if (!$file->isFile() || !preg_match(self::CROP_FILENAME, $file->getFilename())) {
                continue;
            }
            $filePath = $path . '/' . ltrim(str_replace('\\', '/', substr($file->getPathname(), strlen($folder))), '/');
            if (isset($usedPaths[$filePath]) || isset($generatedNames[$file->getFilename()])) {
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
            $removedFiles[$file->getPathname()] = true;
        }
        $countFolders = 0;
        $this->removeEmptyFolders($folder, $path, $removedFiles, $sourceName, $countFolders);

        $verb = ($this->dryRun) ? 'Would remove' : 'Removed';
        $this->log(xPDO::LOG_LEVEL_INFO, sprintf('%s %s (%s) in %s of the media source %s. %s used, %s newer than %s.',
                $verb, $this->formatCount($countUnused, 'unused crop file', 'unused crop files'), $this->formatSize($size), $path, $sourceName,
                $this->formatCount($countUsed, 'crop file is', 'crop files are'), $this->formatCount($countRecent, 'crop file is', 'crop files are'),
                $this->formatCount($minAge, 'hour', 'hours')) .
            (($countFolders) ? sprintf(' %s %s.', $verb, $this->formatCount($countFolders, 'empty folder', 'empty folders')) : '') .
            (($countErrors) ? sprintf(' %s not be removed.', $this->formatCount($countErrors, 'crop file could', 'crop files could')) : ''));
    }

    /**
     * Remove the folders below a folder, that are empty after removing the unused crop files. In dry run mode, the
     * removed files are still there and the folders are only reported.
     *
     * @param string $folder The absolute path of the folder
     * @param string $path The path of the folder in the media source
     * @param array $removedFiles The absolute paths of the (would be) removed files as keys
     * @param string $sourceName
     * @param int $countFolders The number of the removed folders
     * @return bool The folder is empty
     */
    protected function removeEmptyFolders(string $folder, string $path, array $removedFiles, string $sourceName, int &$countFolders): bool
    {
        $empty = true;
        foreach (new FilesystemIterator($folder, FilesystemIterator::SKIP_DOTS) as $entry) {
            if (!$entry->isDir() || $entry->isLink()) {
                if (!isset($removedFiles[$entry->getPathname()])) {
                    $empty = false;
                }
                continue;
            }
            $subPath = $path . '/' . $entry->getFilename();
            if (!$this->removeEmptyFolders($entry->getPathname(), $subPath, $removedFiles, $sourceName, $countFolders)) {
                $empty = false;
            } elseif ($this->dryRun) {
                $this->log(xPDO::LOG_LEVEL_INFO, 'Would remove the empty folder ' . $subPath . ' in the media source ' . $sourceName . '.');
                $countFolders++;
            } elseif (@rmdir($entry->getPathname())) {
                $this->log(xPDO::LOG_LEVEL_INFO, 'Removed the empty folder ' . $subPath . ' in the media source ' . $sourceName . '.');
                $countFolders++;
            } else {
                $this->log(xPDO::LOG_LEVEL_ERROR, 'Could not remove the empty folder ' . $subPath . ' in the media source ' . $sourceName . '.');
                $empty = false;
            }
        }
        return $empty;
    }

    /**
     * Format a number with the singular or the plural of a noun.
     *
     * @param int $count
     * @param string $singular
     * @param string $plural
     * @return string
     */
    protected function formatCount(int $count, string $singular, string $plural): string
    {
        return $count . ' ' . (($count === 1) ? $singular : $plural);
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
