<?php
/**
 * Upload files processor
 *
 * @package imageplus
 * @subpackage processors
 */

use TreehillStudio\ImagePlus\ImagePlus;

// Compatibility between 2.x/3.x
if (file_exists(MODX_PROCESSORS_PATH . 'browser/file/upload.class.php')) {
    require_once MODX_PROCESSORS_PATH . 'browser/file/upload.class.php';
} elseif (!class_exists('modBrowserFileUploadProcessor')) {
    class_alias(\MODX\Revolution\Processors\Browser\File\Upload::class, \modBrowserFileUploadProcessor::class);
}

class ImagePlusBrowserFileUploadProcessor extends modBrowserFileUploadProcessor
{
    /** @var ImagePlus $imageplus */
    public $imageplus;

    /**
     * {@inheritDoc}
     * @param modX $modx A reference to the modX instance
     * @param array $properties An array of properties
     */
    public function __construct(modX &$modx, array $properties = [])
    {
        parent::__construct($modx, $properties);

        $corePath = $this->modx->getOption('imageplus.core_path', null, $this->modx->getOption('core_path') . 'components/imageplus/');
        $this->imageplus = $this->modx->getService('imageplus', 'ImagePlus', $corePath . 'model/imageplus/', [
            'core_path' => $corePath
        ]);
    }

    public function initialize()
    {
        $this->setDefaultProperties([
            'source' => $this->modx->getOption('default_media_source', null, 1),
            'path' => false,
        ]);
        return true;
    }

    public function getLanguageTopics()
    {
        $langs = parent::getLanguageTopics();
        $langs[] = 'imageplus:default';
        return $langs;
    }

    public function process()
    {
        if (!$this->getSource()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();
        if (!$this->source->checkPolicy('create')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        if (count($_FILES) < 1) {
            return $this->failure($this->modx->lexicon('imageplus.cropimage.error_file_not_set'));
        }

        $path = $this->imageplus->parsePathPlaceholders($this->imageplus->getOption('upload_path', null, ''), $this->getProperty('resource_id'), $this->getProperty('tv_id'));
        $path = trim(preg_replace('/[.]{2,}/', '', $path), '/');
        if ($path !== '') {
            $this->source->createContainer($path, '');
            // Unset errors to prevent issues if the container already exists.
            $this->source->errors = [];
        }
        $files = $this->prepareFiles();
        $error = $this->checkMinSize($files);
        if ($error) {
            return $this->failure($error);
        }
        // The uploaded file is moved by the media source
        $file = reset($files);
        $imageSize = @getimagesize($file['tmp_name']);
        $success = $this->source->uploadObjectsToContainer($path, $files);
        if (empty($success)) {
            $msg = '';
            $errors = $this->source->getErrors();
            // Unset $errors['name'] to prevent issues if the container already exists.
            if (isset($errors['name'])) {
                unset($errors['name']);
            }
            if (count($errors) > 0) {
                foreach ($errors as $k => $msg) {
                    $this->modx->error->addField($k, $msg);
                }
                return $this->failure($msg);
            }
        }

        $fName = array_shift($files);
        $url = ($path === '') ? $fName['name'] : $path . '/' . $fName['name'];
        $url = preg_replace('/\/{2,}/', '/', $url);

        $sourceProperties = $this->source->getPropertyList();
        return $this->success('', [
            'relativeUrl' => $url,
            'fullRelativeUrl' => ($sourceProperties['baseUrl'] ?? '') . $url,
            'baseUrl' => $this->imageplus->getSourceObjectUrl($this->source, $url),
            'width' => $imageSize[0] ?? 0,
            'height' => $imageSize[1] ?? 0,
        ]);
    }

    /**
     * Check the minimal size of the uploaded images
     *
     * @param array $files
     * @return string The error message, if an image is smaller than the minimal size
     */
    private function checkMinSize(array $files)
    {
        $minWidth = (int)$this->getProperty('min_width', 0);
        $minHeight = (int)$this->getProperty('min_height', 0);
        if (!$minWidth && !$minHeight) {
            return '';
        }
        foreach ($files as $file) {
            $imageSize = @getimagesize($file['tmp_name']);
            if (!$imageSize) {
                return $this->modx->lexicon('imageplus.cropimage.error_image_not_valid');
            }
            if ($minWidth && $imageSize[0] < $minWidth) {
                return $this->modx->lexicon('imageplus.cropimage.error_min_width', [
                    'width' => $imageSize[0],
                    'min_width' => $minWidth
                ]);
            }
            if ($minHeight && $imageSize[1] < $minHeight) {
                return $this->modx->lexicon('imageplus.cropimage.error_min_height', [
                    'height' => $imageSize[1],
                    'min_height' => $minHeight
                ]);
            }
        }
        return '';
    }

    /**
     * Prepare file name
     */
    private function prepareFiles()
    {
        $files = $_FILES;

        foreach ($files as &$file) {
            $pathInfo = pathinfo($file['name']);
            $ext = strtolower($pathInfo['extension'] ?? '');

            $filename = $this->modx->filterPathSegment($pathInfo['filename']);
            $filename = preg_replace('/[^A-Za-z0-9_-]/', '', $filename);
            $filename = preg_replace('/-{2,}/', '-', $filename);
            $filename = trim($filename, '-');
            if ($filename === '') {
                $filename = 'image-' . time();
            }

            $file['name'] = $filename . '.' . $ext;
        }
        return $files;
    }
}

return 'ImagePlusBrowserFileUploadProcessor';
