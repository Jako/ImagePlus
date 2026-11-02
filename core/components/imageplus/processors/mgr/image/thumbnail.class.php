<?php
/**
 * Image thumbnail processor
 *
 * Creates small thumbnails for the template variable preview with as little memory as possible:
 * - Generated thumbnails are cached and revalidated with an ETag
 * - Imagick is used if available, JPEG images are decoded at a reduced size
 * - GD is only used if the needed memory is available, the image is read from the file and not from a string
 * - The embedded EXIF thumbnail of a JPEG image is used if GD has not enough memory
 * - A placeholder is shown if no thumbnail can be created
 *
 * @package imageplus
 * @subpackage processors
 */

use TreehillStudio\ImagePlus\Helper\ImageMemory;
use TreehillStudio\ImagePlus\Helper\ImageOrientation;
use TreehillStudio\ImagePlus\Processors\Processor;

class ImagePlusImageThumbnailProcessor extends Processor
{
    /** @var string Changing the version invalidates all cached thumbnails */
    const CACHE_VERSION = '3';

    /** @var int The maximum width/height of a thumbnail */
    const MAX_SIZE = 2000;

    /** @var int The memory Imagick may use before it uses the disk cache */
    const IMAGICK_MEMORY = 134217728;

    /**
     * {@inheritDoc}
     */
    public function process()
    {
        // Don't block the parallel thumbnail requests of the preview with the session lock
        @session_write_close();

        $width = min(self::MAX_SIZE, max(0, (int)$this->getProperty('w', 0)));
        $height = min(self::MAX_SIZE, max(0, (int)$this->getProperty('h', 0)));
        if (!$width && !$height) {
            $width = 100;
        }

        $src = $this->prepareSrc((string)$this->getProperty('src'));
        $extension = strtolower(pathinfo($src, PATHINFO_EXTENSION));
        if ($src === '' || !in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $this->outputPlaceholder();
        }

        if (!$this->getSource() || !$this->source->checkPolicy('view')) {
            $this->outputPlaceholder();
        }

        // Prefer the local file, so that the file contents don't have to be held in memory
        $path = $this->source->getBasePath($src) . $src;
        $content = null;
        if ($path !== $src && is_file($path) && is_readable($path)) {
            $modified = filemtime($path);
            $fileSize = filesize($path);
        } else {
            $path = null;
            /** @var bool|array $object */
            $object = $this->source->getObjectContents($src);
            $content = (is_array($object) && isset($object['content'])) ? (string)$object['content'] : '';
            if ($content === '') {
                $this->outputPlaceholder();
            }
            $modified = $object['last_modified'] ?? '';
            $fileSize = strlen($content);
        }

        // JPEG images stay JPEG, all others become PNG to keep the transparency
        $outputExtension = in_array($extension, ['jpg', 'jpeg'], true) ? 'jpg' : 'png';
        $cacheKey = md5(implode('|', [self::CACHE_VERSION, $this->source->get('id'), $src, $modified, $fileSize, $width, $height]));
        $etag = '"' . $cacheKey . '"';
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag) {
            http_response_code(304);
            header('ETag: ' . $etag);
            exit;
        }

        $cacheFile = $this->modx->getCachePath() . 'imageplus/thumbnails/' . substr($cacheKey, 0, 2) . '/' . $cacheKey . '.' . $outputExtension;
        if (is_file($cacheFile)) {
            $this->output(file_get_contents($cacheFile), $outputExtension, $etag);
        }

        $imageSize = $path ? @getimagesize($path) : @getimagesizefromstring($content);
        if (!$imageSize || empty($imageSize[0]) || empty($imageSize[1])) {
            $this->outputPlaceholder();
        }
        // The thumbnail shows the upright image, the width and the height of a rotated image are swapped
        $orientation = 1;
        if ($imageSize['mime'] === 'image/jpeg') {
            $orientation = ImageOrientation::get($path ? (string)file_get_contents($path, false, null, 0, ImageOrientation::HEADER_LENGTH) : substr($content, 0, ImageOrientation::HEADER_LENGTH));
        }
        [$sourceWidth, $sourceHeight] = ImageOrientation::isRotated($orientation) ? [$imageSize[1], $imageSize[0]] : [$imageSize[0], $imageSize[1]];
        [$targetWidth, $targetHeight] = $this->getTargetSize($sourceWidth, $sourceHeight, $width, $height);

        $thumbnail = null;
        // Imagick without autoOrient can't create the upright thumbnail of a rotated image, GD rotates it instead
        if (extension_loaded('imagick') && ($orientation === 1 || method_exists('Imagick', 'autoOrient'))) {
            $thumbnail = $this->createWithImagick($path, $content, $targetWidth, $targetHeight, $outputExtension);
        }
        if ($thumbnail === null) {
            $thumbnail = $this->createWithGd($path, $content, $imageSize, $fileSize, $targetWidth, $targetHeight, $outputExtension, $orientation);
        }
        if ($thumbnail === null && $imageSize['mime'] === 'image/jpeg') {
            $thumbnail = $this->createFromExif($path, $content, $orientation);
            if ($thumbnail !== null) {
                // The embedded EXIF thumbnail is only a fallback, so it is not cached and has no ETag
                $this->output($thumbnail, 'jpg');
            }
        }
        if ($thumbnail === null) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Not enough memory to create a thumbnail of ' . $src . ' (' . $imageSize[0] . 'x' . $imageSize[1] . ')', '', 'ImagePlusImageThumbnailProcessor');
            $this->outputPlaceholder();
        }

        $this->modx->getCacheManager()->writeFile($cacheFile, $thumbnail);
        $this->output($thumbnail, $outputExtension, $etag);
    }

    /**
     * Get the media source of the image. Without a media source in the request, the media source is searched, because
     * the grid column renderer doesn't know the template variable of the image.
     *
     * @return modMediaSource|null
     */
    protected function getSource()
    {
        if (!$this->source && (string)$this->getProperty('source', '') === '') {
            $this->setProperty('source', $this->findSourceId(rawurldecode((string)$this->getProperty('src'))));
        }
        return parent::getSource();
    }

    /**
     * Find the media source, that contains the image: The media sources of the Image+ template variables and the
     * default media source are searched. Remote media sources can't be searched, so the media source of the Image+
     * template variables is used, if they have only one.
     *
     * @param string $src
     * @return int
     */
    private function findSourceId(string $src): int
    {
        $defaultSourceId = (int)$this->modx->getOption('default_media_source');
        $src = ltrim($src, '/');
        if ($src === '' || str_contains($src, "\0") || preg_match('#(^|[/\\\\])\.\.([/\\\\]|$)#', $src)) {
            return $defaultSourceId;
        }

        $tvSourceIds = array_values(array_diff($this->imageplus->getTvSourceIds(), [$defaultSourceId]));

        $this->modx->loadClass('sources.modMediaSource');
        foreach (array_merge($tvSourceIds, [$defaultSourceId]) as $sourceId) {
            $source = modMediaSource::getDefaultSource($this->modx, $sourceId, false);
            if (!$source || !$source->getWorkingContext()) {
                continue;
            }
            $source->initialize();
            $path = $source->getBasePath($src) . $src;
            if ($path !== $src && is_file($path)) {
                return $sourceId;
            }
        }
        return (count($tvSourceIds) === 1) ? $tvSourceIds[0] : $defaultSourceId;
    }

    /**
     * Calculate the thumbnail size, that fits into the requested size without upscaling
     *
     * @param int $sourceWidth
     * @param int $sourceHeight
     * @param int $width
     * @param int $height
     * @return int[]
     */
    private function getTargetSize(int $sourceWidth, int $sourceHeight, int $width, int $height): array
    {
        $scales = [1];
        if ($width) {
            $scales[] = $width / $sourceWidth;
        }
        if ($height) {
            $scales[] = $height / $sourceHeight;
        }
        $scale = min($scales);
        return [max(1, (int)round($sourceWidth * $scale)), max(1, (int)round($sourceHeight * $scale))];
    }

    /**
     * Create the thumbnail with Imagick. The memory of Imagick is not counted in the PHP memory_limit,
     * and JPEG images are decoded at a reduced size.
     *
     * @param string|null $path
     * @param string|null $content
     * @param int $targetWidth
     * @param int $targetHeight
     * @param string $outputExtension
     * @return string|null
     */
    private function createWithImagick(?string $path, ?string $content, int $targetWidth, int $targetHeight, string $outputExtension): ?string
    {
        try {
            // Use the disk cache of ImageMagick instead of using too much memory
            Imagick::setResourceLimit(Imagick::RESOURCETYPE_MEMORY, self::IMAGICK_MEMORY);
            $image = new Imagick();
            // Let libjpeg decode the image at the smallest scale that is still large enough. The image could be
            // rotated by autoOrient, so the larger side is used for both sides.
            $decodeSize = max($targetWidth, $targetHeight) * 2;
            $image->setOption('jpeg:size', $decodeSize . 'x' . $decodeSize);
            if ($path) {
                // Read only the first frame of animated images
                $image->readImage($path . '[0]');
            } else {
                $image->readImageBlob($content);
                $image->setFirstIterator();
                $image = $image->getImage();
            }
            if (method_exists($image, 'autoOrient')) {
                $image->autoOrient();
            }
            // resizeImage is about four times faster than thumbnailImage and keeps the transparency of the image,
            // thumbnailImage makes transparent areas more opaque. The metadata is removed with stripImage below.
            $image->resizeImage($targetWidth, $targetHeight, Imagick::FILTER_LANCZOS, 1);
            if ($outputExtension === 'jpg') {
                $image->setImageFormat('jpeg');
                $image->setImageCompressionQuality(85);
            } else {
                $image->setImageFormat('png');
            }
            $image->stripImage();
            $thumbnail = $image->getImageBlob();
            $image->clear();
            return $thumbnail ?: null;
        } catch (Throwable $e) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Imagick could not create the thumbnail: ' . $e->getMessage(), '', 'ImagePlusImageThumbnailProcessor');
            return null;
        }
    }

    /**
     * Create the thumbnail with GD, if the needed memory is available
     *
     * @param string|null $path
     * @param string|null $content
     * @param array $imageSize
     * @param int $fileSize
     * @param int $targetWidth
     * @param int $targetHeight
     * @param string $outputExtension
     * @param int $orientation The EXIF orientation of the image
     * @return string|null
     */
    private function createWithGd(?string $path, ?string $content, array $imageSize, int $fileSize, int $targetWidth, int $targetHeight, string $outputExtension, int $orientation = 1): ?string
    {
        $header = $path ? (string)file_get_contents($path, false, null, 0, 32) : substr($content, 0, 32);
        $memoryNeeded = ImageMemory::estimate($imageSize, $fileSize, ImageMemory::getPngColorType($header), $outputExtension, $targetWidth, $targetHeight, $content !== null);
        if (ImageOrientation::isRotated($orientation)) {
            // The rotated image is a copy of the image
            $memoryNeeded += $imageSize[0] * $imageSize[1] * 4;
        }
        if (!ImageMemory::reserve($memoryNeeded)) {
            return null;
        }

        $sourceImage = $path ? $this->createGdImageFromFile($path, $imageSize['mime']) : @imagecreatefromstring($content);
        if (!$sourceImage) {
            return null;
        }
        $sourceImage = ImageOrientation::apply($sourceImage, $orientation);
        $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);
        if ($outputExtension === 'png') {
            imagealphablending($targetImage, false);
            imagesavealpha($targetImage, true);
            imagefill($targetImage, 0, 0, imagecolorallocatealpha($targetImage, 0, 0, 0, 127));
        }
        imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, imagesx($sourceImage), imagesy($sourceImage));
        // The image memory is released with unset (imagedestroy has no effect since PHP 8 and is deprecated since PHP 8.5)
        unset($sourceImage);

        ob_start();
        if ($outputExtension === 'jpg') {
            imagejpeg($targetImage, null, 85);
        } else {
            imagepng($targetImage);
        }
        $thumbnail = ob_get_clean();
        unset($targetImage);
        return $thumbnail ?: null;
    }

    /**
     * Create a GD image directly from a file, without reading the file contents into a string
     *
     * @param string $path
     * @param string $mime
     * @return resource|GdImage|false
     */
    private function createGdImageFromFile(string $path, string $mime)
    {
        return match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    /**
     * Get the thumbnail that is embedded in the EXIF data of a JPEG image
     *
     * @param string|null $path
     * @param string|null $content
     * @param int $orientation The EXIF orientation of the image, the embedded thumbnail has the same orientation
     * @return string|null
     */
    private function createFromExif(?string $path, ?string $content, int $orientation = 1): ?string
    {
        if (!function_exists('exif_thumbnail')) {
            return null;
        }
        if ($path) {
            $thumbnail = @exif_thumbnail($path);
        } else {
            $stream = fopen('php://memory', 'r+b');
            fwrite($stream, $content);
            rewind($stream);
            $thumbnail = @exif_thumbnail($stream);
            fclose($stream);
        }
        if ($thumbnail && $orientation !== 1) {
            $image = @imagecreatefromstring($thumbnail);
            if ($image) {
                $image = ImageOrientation::apply($image, $orientation);
                ob_start();
                imagejpeg($image, null, 85);
                $thumbnail = ob_get_clean();
                unset($image);
            }
        }
        return $thumbnail ?: null;
    }

    /**
     * Output the thumbnail and stop the request
     *
     * @param string $thumbnail
     * @param string $extension
     * @param string|null $etag Without an ETag, the thumbnail is not cached by the browser
     */
    private function output(string $thumbnail, string $extension, ?string $etag = null)
    {
        header('Content-Type: ' . ($extension === 'jpg' ? 'image/jpeg' : 'image/png'));
        header('Content-Length: ' . strlen($thumbnail));
        if ($etag !== null) {
            header('Cache-Control: private, no-cache');
            header('ETag: ' . $etag);
        } else {
            header('Cache-Control: no-store');
        }
        echo $thumbnail;
        exit;
    }

    /**
     * Output a placeholder image and stop the request
     */
    private function outputPlaceholder()
    {
        $placeholder = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="75" viewBox="0 0 100 75">' .
            '<rect width="100" height="75" fill="#e4e4e4"/>' .
            '<path d="M30 55l15-18 10 12 7-8 10 14z" fill="#b4b4b4"/>' .
            '<circle cx="62" cy="27" r="5" fill="#b4b4b4"/>' .
            '</svg>';
        header('Content-Type: image/svg+xml');
        header('Content-Length: ' . strlen($placeholder));
        header('Cache-Control: no-store');
        echo $placeholder;
        exit;
    }
}

return 'ImagePlusImageThumbnailProcessor';
