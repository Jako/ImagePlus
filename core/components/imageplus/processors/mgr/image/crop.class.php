<?php
/**
 * Image crop processor
 *
 * @package imageplus
 * @subpackage processors
 */

use TreehillStudio\ImagePlus\Helper\ImageMemory;
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

        // Try to load the image
        $file = $this->source->getObjectContents($filename);
        $file = $file['content'];
        if (empty($file)) {
            return $this->failure('File ' . $filename . ' not found in source ' . $this->source->get('id'));
        }

        if (!empty($file)) {
            $filepath = $this->source->getBasePath($filename) . $filename;
            $pathinfo = pathinfo($filename);
            $imageExtension = $pathinfo['extension'];
            $imageName = $pathinfo['filename'];
            $imageDirname = ($pathinfo['dirname'] !== '.') ? $pathinfo['dirname'] . '/' : '';

            $x = $this->getProperty('x');
            $y = $this->getProperty('y');
            $cropWidth = $this->getProperty('cropWidth');
            $cropHeight = $this->getProperty('cropHeight');
            $scaleX = $this->getProperty('scaleX');
            $scaleY = $this->getProperty('scaleY');
            $targetWidth = $this->getProperty('targetWidth');
            $targetHeight = $this->getProperty('targetHeight');

            if (empty($cropHeight) || empty($cropWidth)) {
                return $this->failure($this->modx->lexicon('imageplus.cropimage.error_cropheight_cropwidth'));
            }

            if (($targetWidth === 'NaN' && $targetHeight === 'NaN') ||
                ($targetWidth === '' && $targetHeight === '')) {
                $targetWidth = $cropWidth;
                $targetHeight = $cropHeight;
            } elseif ($targetWidth === 'NaN' || $targetWidth === '') {
                $targetWidth = strval(floor($targetHeight / $cropHeight * $cropWidth));
            } elseif ($targetHeight === 'NaN' || $targetHeight === '') {
                $targetHeight = strval(floor($targetWidth / $cropWidth * $cropHeight));
            }

            $imageHash = implode('-', [$cropWidth, $cropHeight, $x, $y, $scaleX, $scaleY, $targetWidth, $targetHeight, is_file($filepath) ? filemtime($filepath) : md5($file)]);

            $cropPath = $this->imageplus->parsePathPlaceholders($this->imageplus->getOption('crop_path', null, ''), $this->getProperty('resource_id'), $this->getProperty('tv_id'));
            $cropPath = trim(preg_replace('/[.]{2,}/', '', $cropPath), '/');
            $imagePath = (($cropPath === '') ? 'crop' : $cropPath) . '/' . $imageDirname;

            $this->source->createContainer($imagePath, '/');
            // Unset errors to prevent issues if the container already exists.
            $this->source->errors = array();

            if (in_array(strtolower($imageExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                $cropName = $imageName . '-' . md5($imageHash) . '.' . $imageExtension;

                $imageSize = getimagesizefromstring($file);
                if (!$imageSize) {
                    return $this->failure($this->modx->lexicon('imageplus.cropimage.error_image_not_valid'));
                }
                $memoryNeeded = ImageMemory::estimate($imageSize, strlen($file), ImageMemory::getPngColorType($file), strtolower($imageExtension), (int)$targetWidth, (int)$targetHeight);
                if (!ImageMemory::reserve($memoryNeeded)) {
                    return $this->failure($this->modx->lexicon('imageplus.cropimage.error_memory', [
                        'width' => $imageSize[0],
                        'height' => $imageSize[1],
                        'memory' => ceil($memoryNeeded / 1048576) . 'M',
                        'limit' => ini_get('memory_limit'),
                    ]));
                }

                $sourceImage = imagecreatefromstring($file);
                unset($file);
                if (!$sourceImage) {
                    return $this->failure($this->modx->lexicon('imageplus.cropimage.error_image_not_valid'));
                }
                $sourceWidth = imagesx($sourceImage);
                $sourceHeight = imagesy($sourceImage);
                $targetImage = imagecreatetruecolor((int)$targetWidth, (int)$targetHeight);

                if ((int)$scaleX === -1) {
                    imageflip($sourceImage, IMG_FLIP_HORIZONTAL);
                }
                if ((int)$scaleY === -1) {
                    imageflip($sourceImage, IMG_FLIP_VERTICAL);
                }
                imagealphablending($sourceImage, true);

                imagealphablending($targetImage, false);
                imagesavealpha($targetImage, true);
                imagefill($targetImage, 0, 0, imagecolorallocatealpha($targetImage, 0, 0, 0, 127));

                // Copy the part of the crop area that is inside the source image directly into the target image.
                // Parts of the crop area outside the source image stay transparent.
                $ratioX = (int)$targetWidth / (int)$cropWidth;
                $ratioY = (int)$targetHeight / (int)$cropHeight;
                $srcX = max(0, (int)$x);
                $srcY = max(0, (int)$y);
                $srcWidth = min($sourceWidth, (int)$x + (int)$cropWidth) - $srcX;
                $srcHeight = min($sourceHeight, (int)$y + (int)$cropHeight) - $srcY;
                if ($srcWidth > 0 && $srcHeight > 0) {
                    imagecopyresampled($targetImage, $sourceImage,
                        (int)round(($srcX - (int)$x) * $ratioX), (int)round(($srcY - (int)$y) * $ratioY), $srcX, $srcY,
                        (int)round($srcWidth * $ratioX), (int)round($srcHeight * $ratioY), $srcWidth, $srcHeight
                    );
                }
                // Since PHP 8 imagedestroy has no effect, the image memory is released with unset
                imagedestroy($sourceImage);
                unset($sourceImage);

                ob_start();
                if (strtolower($imageExtension) === 'jpg') {
                    imagejpeg($targetImage);
                } else if (strtolower($imageExtension) === 'jpeg') {
                    imagejpeg($targetImage);
                } else if (strtolower($imageExtension) === 'png') {
                    imagepng($targetImage);
                } else if (strtolower($imageExtension) === 'gif') {
                    imagegif($targetImage);
                } else if (strtolower($imageExtension) === 'webp') {
                    imagewebp($targetImage);
                }
                $result = ob_get_clean();

                imagedestroy($targetImage);
                unset($targetImage);

                if ($result) {
                    if ($this->source->createObject($imagePath, $cropName, $result) ||
                        $this->source->updateObject($imagePath . $cropName, $result)
                    ) {
                        return $this->success($this->modx->lexicon('imageplus.cropimage.success_image'), [
                            'image' => $imagePath . $cropName,
                            'baseUrl' => $this->imageplus->getSourceObjectUrl($this->source, $imagePath . $cropName),
                            'width' => $cropWidth,
                            'height' => $cropHeight
                        ]);
                    }
                    return $this->failure($this->modx->lexicon('imageplus.cropimage.error_crop'));
                }
                return $this->failure($this->modx->lexicon('imageplus.cropimage.error_image'));
            }
            return $this->failure($this->modx->lexicon('imageplus.cropimage.error_image_not_valid'));
        }
        return $this->failure($this->modx->lexicon('imageplus.cropimage.error_image_not_exists'));
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
