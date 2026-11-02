<?php
/**
 * CropFile crop engine
 *
 * @package imageplus
 * @subpackage cropengine
 */

namespace TreehillStudio\ImagePlus\CropEngines;

use modMediaSource;
use modTemplateVar;
use modX;
use TreehillStudio\ImagePlus\Helper\ImageCrop;
use TreehillStudio\ImagePlus\Helper\ImageOrientation;
use xPDO;

/**
 * Class CropFile
 *
 * Uses the image crop files in the media source without phpThumb. A missing crop file (i.e. of a value created before
 * Image+ 3.0) is created on the first output. The phpThumb parameters are only used in the options placeholders of the
 * output chunk, i.e. for a pthumb output filter.
 *
 * @package imageplus
 * @subpackage ImagePlus\CropEngines
 */
class CropFile extends AbstractCropEngine
{
    /**
     * Checks that all requirements are met for using this engine
     *
     * @param modX $modx
     * @return bool True if engine is usable
     */
    public static function engineRequirementsMet(modX $modx)
    {
        return function_exists('imagecreatefromstring');
    }

    /**
     * Parse image+ data and return the url of the image crop file
     *
     * @param $json
     * @param array $opts
     * @param modTemplateVar $tv
     * @return string
     */
    public function getImageUrl($json, $opts = [], $tv = null)
    {
        if ($json == '') {
            if ($this->imageplus->getOption('debug')) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The value is empty. Could not prepare the output.', '', 'Image+');
            }
            return ($tv) ? $tv->default_text : '';
        }

        // Parse json to object
        $data = json_decode($json);

        // If data is null, json was invalid or empty.
        // This is almost certainly because the TV is empty
        if (is_null($data)) {
            if ($this->imageplus->getOption('debug')) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The JSON value is invalid. Could not prepare the output.', '', 'Image+');
            }
            return ($tv) ? $tv->default_text : '';
        }

        // Load up the mediaSource
        /** @var modMediaSource $source */
        $source = $this->modx->getObject('sources.modMediaSource', $data->sourceImg->source);
        if (!$source instanceof modMediaSource) {
            if ($this->imageplus->getOption('debug')) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Invalid Media Source', '', 'Image+');
            }
            return 'Image+ Error: Invalid Media Source';
        }
        $this->modx->setPlaceholder('docid', $this->modx->getOption('docid', $opts, 0));
        $source->initialize();

        $src = (string)$data->sourceImg->src;
        $crop = ImageCrop::normalizeCrop([
            'x' => $data->crop->x,
            'y' => $data->crop->y,
            'cropWidth' => $data->crop->width,
            'cropHeight' => $data->crop->height,
            'scaleX' => $data->crop->scaleX ?? 1,
            'scaleY' => $data->crop->scaleY ?? 1,
            'targetWidth' => $data->targetWidth,
            'targetHeight' => $data->targetHeight,
        ]);
        $url = $this->imageplus->getSourceObjectUrl($source, $src);
        $width = (int)$data->sourceImg->width;
        $height = (int)$data->sourceImg->height;

        $generateUrl = $this->modx->getOption('generateUrl', $opts, 1);
        if ($generateUrl && !$this->isOriginal($crop, $width, $height)) {
            $cropImage = $this->getCropImage($source, $src, $crop, (string)($data->crop->image ?? ''), (int)$this->modx->getOption('docid', $opts, 0), ($tv) ? (int)$tv->get('id') : 0);
            if ($cropImage !== '') {
                $url = $this->imageplus->getSourceObjectUrl($source, $cropImage);
                $width = $crop['targetWidth'];
                $height = $crop['targetHeight'];
            }
        }

        // If an output chunk is selected, parse that
        $outputChunk = $this->modx->getOption('outputChunk', $opts, '');
        if ($outputChunk) {
            $basePath = (string)$source->getBasePath($src);
            $imgPath = ($basePath !== '') ? realpath($basePath . $src) : '';
            [$options, $cropOptions] = $this->getPhpThumbOptions($data, (string)$imgPath, (string)$this->modx->getOption('phpThumbParams', $opts, ''));
            $chunkParams = array_merge($opts, [
                'url' => $url,
                'alt' => $data->altTag ?? '',
                'width' => $width,
                'height' => $height,
                'source.src' => (string)$imgPath,
                'source.width' => $data->sourceImg->width,
                'source.height' => $data->sourceImg->height,
                'crop.width' => $data->crop->width,
                'crop.height' => $data->crop->height,
                'crop.x' => $data->crop->x,
                'crop.y' => $data->crop->y,
                'options' => $options,
                'crop.options' => $cropOptions,
                'caption' => $data->caption ?? '',
                'credits' => $data->credits ?? ''
            ]);
            return $this->modx->getChunk($outputChunk, $chunkParams);
        } else {
            // Otherwise return raw url
            return $url;
        }
    }

    /**
     * Get the phpThumb option strings of the options and crop.options placeholders, i.e. for a pthumb output filter in
     * the output chunk.
     *
     * @param object $data The crop engine value
     * @param string $imgPath The full path of the image on the server
     * @param string $phpThumbParams Additional phpThumb parameters
     * @return string[] The full option string and the crop option string
     */
    protected function getPhpThumbOptions($data, string $imgPath, string $phpThumbParams): array
    {
        $cropParams = [
            'sx' => $data->crop->x,
            'sy' => $data->crop->y,
            'sw' => $data->crop->width,
            'sh' => $data->crop->height,
        ];
        if ($imgPath && ImageOrientation::getFromFile($imgPath) !== 1) {
            // The crop area is set on the upright image, so phpThumb rotates the image by its EXIF orientation first
            $cropParams['ar'] = 'x';
        }
        $params = [];
        if ($data->targetWidth) {
            $params['w'] = $data->targetWidth;
        }
        if ($data->targetHeight) {
            $params['h'] = $data->targetHeight;
        }
        if ($data->targetWidth && $data->targetHeight) {
            $params['far'] = true;
        }

        // The additional phpThumb parameters replace the target size
        $optParams = [];
        if ($phpThumbParams) {
            parse_str($phpThumbParams, $optParams);
            foreach ($optParams as $key => $val) {
                if (empty($val)) {
                    unset($optParams[$key]);
                }
            }
        }
        $optParams = ($optParams) ? array_merge($cropParams, $optParams) : array_merge($cropParams, $params);
        return [
            rawurldecode(preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', http_build_query($optParams))),
            rawurldecode(preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', http_build_query($cropParams))),
        ];
    }

    /**
     * Check if the crop is the unchanged image, so that no crop file is needed.
     *
     * @param array $crop The normalized crop
     * @param int $width The width of the image
     * @param int $height The height of the image
     * @return bool
     */
    protected function isOriginal(array $crop, int $width, int $height): bool
    {
        if (!$crop['cropWidth'] || !$crop['cropHeight']) {
            return true;
        }
        return $crop['x'] === 0 && $crop['y'] === 0 && $crop['scaleX'] === 1 && $crop['scaleY'] === 1 &&
            $crop['cropWidth'] === $width && $crop['cropHeight'] === $height &&
            $crop['targetWidth'] === $width && $crop['targetHeight'] === $height;
    }

    /**
     * Get the crop file of an image crop and create it, if it does not exist.
     *
     * @param modMediaSource $source
     * @param string $src The path of the image in the media source
     * @param array $crop The normalized crop
     * @param string $cropImage The crop file in the value
     * @param int $resourceId
     * @param int $tvId
     * @return string The path of the crop file in the media source or an empty string, if it could not be created
     */
    protected function getCropImage($source, string $src, array $crop, string $cropImage, int $resourceId, int $tvId): string
    {
        $basePath = (string)$source->getBasePath($src);
        if ($cropImage !== '' && ($basePath === '' || is_file($basePath . $cropImage))) {
            // The crop file was created in the manager. The files of remote media sources are not checked.
            return $cropImage;
        }

        // The created crop files are remembered, because the crop path could contain date placeholders and the files
        // of remote media sources are not checked. The cache partition is not removed by clearing the MODX cache.
        // The modification time of a local image is part of the key, so that a changed image gets a new crop file.
        $version = ($basePath !== '' && is_file($basePath . $src)) ? filemtime($basePath . $src) : '';
        $cacheKey = 'cropfile/' . md5(json_encode([$source->get('id'), $src, $crop, $version]));
        $cacheOptions = [xPDO::OPT_CACHE_KEY => 'imageplus'];
        $cached = $this->modx->cacheManager->get($cacheKey, $cacheOptions);
        if (is_array($cached) && !empty($cached['error'])) {
            // The crop file could not be created recently, so the original image is used without trying it again
            return '';
        }
        if (is_string($cached) && $cached !== '' && ($basePath === '' || is_file($basePath . $cached))) {
            return $cached;
        }

        $imageCrop = new ImageCrop($this->modx, $this->imageplus);
        if ($version !== '') {
            // The crop file of a local image could exist, if the cache entry is lost
            $cropImage = $imageCrop->getCropFolder($src, $resourceId, $tvId) . ImageCrop::getCropName($src, $crop, (string)$version);
            if (is_file($basePath . $cropImage)) {
                $this->modx->cacheManager->set($cacheKey, $cropImage, 0, $cacheOptions);
                return $cropImage;
            }
        }
        $result = $imageCrop->create($source, $src, $crop, $resourceId, $tvId);
        if (isset($result['error'])) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The crop file of ' . $src . ' in the media source ' . $source->get('id') . ' could not be created: ' . $result['error'], '', 'Image+');
            $this->modx->cacheManager->set($cacheKey, ['error' => true], 3600, $cacheOptions);
            return '';
        }
        $this->modx->cacheManager->set($cacheKey, $result['image'], 0, $cacheOptions);
        return $result['image'];
    }
}
