<?php
/**
 * Image crop processor
 *
 * @package imageplus
 * @subpackage processors
 */

use TreehillStudio\ImagePlus\Helper\ImageCrop;
use TreehillStudio\ImagePlus\Processors\Processor;

class ImagePlusImageCropProcessor extends Processor
{
    /**
     * {@inheritDoc}
     */
    public function process()
    {
        register_shutdown_function([$this, 'shutdown']);

        if (!$this->getSource() || !$this->source->checkPolicy('create')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $filename = $this->prepareSrc((string)$this->getProperty('image'));
        if ($filename === '') {
            return $this->failure($this->modx->lexicon('imageplus.cropimage.error_file_not_set'));
        }

        $cropWidth = $this->getProperty('cropWidth');
        $cropHeight = $this->getProperty('cropHeight');
        if (empty($cropHeight) || empty($cropWidth)) {
            return $this->failure($this->modx->lexicon('imageplus.cropimage.error_cropheight_cropwidth'));
        }

        // A missing target size is sent as NaN or as empty string and is calculated by the aspect ratio of the crop
        $imageCrop = new ImageCrop($this->modx, $this->imageplus);
        $result = $imageCrop->create($this->source, $filename, [
            'x' => $this->getProperty('x'),
            'y' => $this->getProperty('y'),
            'cropWidth' => $cropWidth,
            'cropHeight' => $cropHeight,
            'scaleX' => $this->getProperty('scaleX'),
            'scaleY' => $this->getProperty('scaleY'),
            'targetWidth' => $this->getProperty('targetWidth'),
            'targetHeight' => $this->getProperty('targetHeight'),
        ], (int)$this->getProperty('resource_id'), (int)$this->getProperty('tv_id'));
        if (isset($result['error'])) {
            return $this->failure($result['error']);
        }
        return $this->success($this->modx->lexicon('imageplus.cropimage.success_image'), [
            'image' => $result['image'],
            'baseUrl' => $this->imageplus->getSourceObjectUrl($this->source, $result['image']),
            'width' => $cropWidth,
            'height' => $cropHeight
        ]);
    }

    /**
     * Display a shutdown message (i.e. in case of reaching the maximum
     * execution time) instead of just a blank result
     */
    public function shutdown()
    {
        $error = error_get_last();
        if (is_array($error) && isset($error['type']) && $error['type'] === E_ERROR) {
            $message = (isset($error['message'])) ? $error['message'] : 'Unknown Error!';
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Script terminated with the error: ' . print_r($error, true), '', 'ImagePlusImageCropProcessor');
            if (php_sapi_name() == 'cli') {
                fwrite(STDERR, $message . "\n");
                exit(1);
            } else {
                @session_write_close();
                http_response_code(200);
                exit(json_encode(array(
                    'success' => false,
                    'message' => $message,
                    'total' => 0,
                    'data' => [],
                    'object' => []
                )));
            }
        }
    }
}

return 'ImagePlusImageCropProcessor';
