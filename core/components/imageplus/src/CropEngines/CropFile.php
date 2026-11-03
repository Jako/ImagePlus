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
 * Uses the image crop files in the media source without phpThumb. The url, width and height are the ones of the crop
 * file of the image crop in the crop option or of the first image crop. A missing crop file (i.e. of a value created
 * before Image+ 3.0) is created on the first output. With a format option (i.e. the format property of the ImagePlus
 * snippet), a crop file in another image format is created on the first output. A missing crop file gets the image format
 * input option of the template variable. The phpThumb parameters are only used in the options placeholders of the
 * output chunk, i.e. for a pthumb output filter. The output chunk gets the url, width and height of every image crop in
 * the crops.<key>.url, crops.<key>.width and crops.<key>.height placeholders.
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
        $resourceId = (int)$this->modx->getOption('docid', $opts, 0);
        $tvId = ($tv) ? (int)$tv->get('id') : 0;
        $format = ImageCrop::normalizeFormat((string)$this->modx->getOption('format', $opts, ''));
        // A missing crop file gets the image format of the template variable or the default format
        $defaultFormat = ImageCrop::getTVFormat(($tv) ? $tv->get('input_properties') : []);
        [$url, $width, $height] = $this->getOutputImage($data, $source, $resourceId, $tvId, $format, $defaultFormat);

        // If an output chunk is selected, parse that
        $outputChunk = $this->modx->getOption('outputChunk', $opts, '');
        if ($outputChunk) {
            // The crop engine values of all image crops are only used for the crops placeholders
            $crops = [];
            foreach ((array)($opts['crops.values'] ?? []) as $key => $cropJson) {
                $cropData = json_decode((string)$cropJson);
                if ($cropData) {
                    [$crops['crops.' . $key . '.url'], $crops['crops.' . $key . '.width'], $crops['crops.' . $key . '.height']] = $this->getOutputImage($cropData, $source, $resourceId, $tvId, $format, $defaultFormat);
                }
            }
            unset($opts['crops.values']);
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
            ], $crops);
            return $this->modx->getChunk($outputChunk, $chunkParams);
        } else {
            // Otherwise return raw url
            return $url;
        }
    }

    /**
     * Get the url, width and height of the crop file of an image crop. The original image is used, if the image crop
     * is the unchanged image in the image format or if its crop file could not be created.
     *
     * @param object $data The crop engine value
     * @param modMediaSource $source The initialized media source
     * @param int $resourceId
     * @param int $tvId
     * @param string $format The image format of the crop file, the format of the stored crop file is used by default
     * @param string $defaultFormat The image format of a missing crop file without a format
     * @return array The url, width and height
     */
    protected function getOutputImage($data, $source, int $resourceId, int $tvId, string $format = '', string $defaultFormat = ''): array
    {
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

        // The unchanged image needs no crop file, unless it was created in the manager or has another image format
        $cropImage = (string)($data->crop->image ?? '');
        if ($cropImage !== '' && $format !== '' && ImageCrop::normalizeFormat(pathinfo($cropImage, PATHINFO_EXTENSION)) !== $format) {
            // The stored crop file has another image format
            $cropImage = '';
            $convert = true;
        } else {
            $convert = $format !== '' && ImageCrop::getCropExtension($src, $format) !== pathinfo($src, PATHINFO_EXTENSION);
        }
        if ($cropImage !== '' || ($convert && $crop['cropWidth'] && $crop['cropHeight']) || !$this->isOriginal($crop, $width, $height)) {
            $cropImage = $this->getCropImage($source, $src, $crop, $cropImage, $resourceId, $tvId, ($format !== '') ? $format : $defaultFormat);
            if ($cropImage !== '') {
                $url = $this->imageplus->getSourceObjectUrl($source, $cropImage);
                $width = $crop['targetWidth'];
                $height = $crop['targetHeight'];
            }
        }
        return [$url, $width, $height];
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
     * @param string $format The image format of the crop file
     * @return string The path of the crop file in the media source or an empty string, if it could not be created
     */
    protected function getCropImage($source, string $src, array $crop, string $cropImage, int $resourceId, int $tvId, string $format = ''): string
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
        $cacheKey = 'cropfile/' . md5(json_encode(array_merge([$source->get('id'), $src, $crop, $version], ($format !== '') ? [$format] : [])));
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
            $cropImage = $imageCrop->getCropFolder($src, $resourceId, $tvId) . ImageCrop::getCropName($src, $crop, (string)$version, $format);
            if (is_file($basePath . $cropImage)) {
                $this->modx->cacheManager->set($cacheKey, $cropImage, 0, $cacheOptions);
                return $cropImage;
            }
        }
        $result = $imageCrop->create($source, $src, $crop, $resourceId, $tvId, $format);
        if (isset($result['error'])) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The crop file of ' . $src . ' in the media source ' . $source->get('id') . ' could not be created: ' . $result['error'], '', 'Image+');
            $this->modx->cacheManager->set($cacheKey, ['error' => true], 3600, $cacheOptions);
            return '';
        }
        $this->modx->cacheManager->set($cacheKey, $result['image'], 0, $cacheOptions);
        return $result['image'];
    }
}
