<?php
/**
 * Image Crop classfile
 *
 * @package imageplus
 * @subpackage classfile
 */

namespace TreehillStudio\ImagePlus\Helper;

use modMediaSource;
use modX;
use TreehillStudio\ImagePlus\ImagePlus;

/**
 * Class ImageCrop
 *
 * Create the image crop files with GD. The crop processor and the CropFile crop engine use it.
 */
class ImageCrop
{
    /** The extensions of the images, that can be cropped */
    const EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

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
     * ImageCrop constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param ImagePlus $imageplus A reference to the ImagePlus instance.
     */
    public function __construct(modX &$modx, $imageplus)
    {
        $this->modx =& $modx;
        $this->imageplus = $imageplus;
    }

    /**
     * Get the size of the crop file. A missing target width or height is calculated with the aspect ratio of the crop.
     *
     * @param int $cropWidth
     * @param int $cropHeight
     * @param int $targetWidth
     * @param int $targetHeight
     * @return int[]
     */
    public static function getTargetSize(int $cropWidth, int $cropHeight, int $targetWidth, int $targetHeight): array
    {
        if ($cropWidth <= 0 || $cropHeight <= 0) {
            return [$targetWidth, $targetHeight];
        }
        if (!$targetWidth && !$targetHeight) {
            return [$cropWidth, $cropHeight];
        } elseif (!$targetWidth) {
            $targetWidth = (int)floor($targetHeight / $cropHeight * $cropWidth);
        } elseif (!$targetHeight) {
            $targetHeight = (int)floor($targetWidth / $cropWidth * $cropHeight);
        }
        return [max(1, $targetWidth), max(1, $targetHeight)];
    }

    /**
     * Get the folder of the crop files of an image in the media source.
     *
     * @param string $filename The path of the image in the media source
     * @param int $resourceId
     * @param int $tvId
     * @return string
     */
    public function getCropFolder(string $filename, int $resourceId = 0, int $tvId = 0): string
    {
        $cropPath = $this->imageplus->parsePathPlaceholders($this->imageplus->getOption('crop_path', null, ''), $resourceId, $tvId);
        $cropPath = trim(preg_replace('/[.]{2,}/', '', $cropPath), '/');
        $dirname = pathinfo($filename, PATHINFO_DIRNAME);
        return (($cropPath === '') ? 'crop' : $cropPath) . '/' . (($dirname !== '.' && $dirname !== '') ? $dirname . '/' : '');
    }

    /**
     * Get the name of the crop file. It contains a hash of the crop and of the modification time of the image (or of
     * its contents, if the media source is not local), so that changed images and crops get a new file.
     *
     * @param string $filename The path of the image in the media source
     * @param array $crop
     * @param string $version The modification time or the md5 hash of the image
     * @return string
     */
    public static function getCropName(string $filename, array $crop, string $version): string
    {
        $pathinfo = pathinfo($filename);
        $imageHash = implode('-', [
            $crop['cropWidth'], $crop['cropHeight'], $crop['x'], $crop['y'], $crop['scaleX'], $crop['scaleY'],
            $crop['targetWidth'], $crop['targetHeight'], $version
        ]);
        return $pathinfo['filename'] . '-' . md5($imageHash) . '.' . ($pathinfo['extension'] ?? '');
    }

    /**
     * Normalize the crop data: x, y, cropWidth, cropHeight, scaleX, scaleY, targetWidth and targetHeight.
     *
     * @param array $crop
     * @return array
     */
    public static function normalizeCrop(array $crop): array
    {
        $crop = [
            'x' => (int)($crop['x'] ?? 0),
            'y' => (int)($crop['y'] ?? 0),
            'cropWidth' => (int)($crop['cropWidth'] ?? 0),
            'cropHeight' => (int)($crop['cropHeight'] ?? 0),
            'scaleX' => ((int)($crop['scaleX'] ?? 1) === -1) ? -1 : 1,
            'scaleY' => ((int)($crop['scaleY'] ?? 1) === -1) ? -1 : 1,
            'targetWidth' => (int)($crop['targetWidth'] ?? 0),
            'targetHeight' => (int)($crop['targetHeight'] ?? 0),
        ];
        [$crop['targetWidth'], $crop['targetHeight']] = self::getTargetSize($crop['cropWidth'], $crop['cropHeight'], $crop['targetWidth'], $crop['targetHeight']);
        return $crop;
    }

    /**
     * Get the version of an image, that is part of the crop file name.
     *
     * @param modMediaSource $source
     * @param string $filename
     * @param string|null $contents The contents of the image, if they are loaded already
     * @return string
     */
    public static function getVersion($source, string $filename, ?string $contents = null): string
    {
        $basePath = (string)$source->getBasePath($filename);
        $filepath = $basePath . $filename;
        if ($basePath !== '' && is_file($filepath)) {
            return (string)filemtime($filepath);
        }
        if ($contents === null) {
            $file = $source->getObjectContents($filename);
            $contents = (is_array($file)) ? (string)($file['content'] ?? '') : '';
        }
        return md5($contents);
    }

    /**
     * Create the crop file of an image in the media source.
     *
     * @param modMediaSource $source The initialized media source
     * @param string $filename The path of the image in the media source
     * @param array $crop The crop data
     * @param int $resourceId The resource for the placeholders of the crop path
     * @param int $tvId The template variable for the placeholders of the crop path
     * @return array The path of the crop file in image, its width and height or the error message in error
     */
    public function create($source, string $filename, array $crop, int $resourceId = 0, int $tvId = 0): array
    {
        $crop = self::normalizeCrop($crop);
        if (!$crop['cropWidth'] || !$crop['cropHeight']) {
            return ['error' => $this->modx->lexicon('imageplus.cropimage.error_cropheight_cropwidth')];
        }
        $imageExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($imageExtension, self::EXTENSIONS, true)) {
            return ['error' => $this->modx->lexicon('imageplus.cropimage.error_image_not_valid')];
        }

        // Try to load the image
        $file = $source->getObjectContents($filename);
        $file = (is_array($file)) ? (string)($file['content'] ?? '') : '';
        if ($file === '') {
            return ['error' => $this->modx->lexicon('imageplus.cropimage.error_image_not_exists')];
        }

        $imagePath = $this->getCropFolder($filename, $resourceId, $tvId);
        $cropName = self::getCropName($filename, $crop, self::getVersion($source, $filename, $file));

        $imageSize = getimagesizefromstring($file);
        if (!$imageSize) {
            return ['error' => $this->modx->lexicon('imageplus.cropimage.error_image_not_valid')];
        }
        // The crop area is set on the upright image, that is shown in the browser
        $orientation = ($imageSize['mime'] === 'image/jpeg') ? ImageOrientation::get(substr($file, 0, ImageOrientation::HEADER_LENGTH)) : 1;
        $memoryNeeded = ImageMemory::estimate($imageSize, strlen($file), ImageMemory::getPngColorType($file), $imageExtension, $crop['targetWidth'], $crop['targetHeight']);
        if (ImageOrientation::isRotated($orientation)) {
            // The rotated image is a copy of the image
            $memoryNeeded += $imageSize[0] * $imageSize[1] * 4;
        }
        if (!ImageMemory::reserve($memoryNeeded)) {
            return ['error' => $this->modx->lexicon('imageplus.cropimage.error_memory', [
                'width' => $imageSize[0],
                'height' => $imageSize[1],
                'memory' => ceil($memoryNeeded / 1048576) . 'M',
                'limit' => ini_get('memory_limit'),
            ])];
        }

        $sourceImage = imagecreatefromstring($file);
        unset($file);
        if (!$sourceImage) {
            return ['error' => $this->modx->lexicon('imageplus.cropimage.error_image_not_valid')];
        }
        $sourceImage = ImageOrientation::apply($sourceImage, $orientation);
        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
        $targetImage = imagecreatetruecolor($crop['targetWidth'], $crop['targetHeight']);

        if ($crop['scaleX'] === -1) {
            imageflip($sourceImage, IMG_FLIP_HORIZONTAL);
        }
        if ($crop['scaleY'] === -1) {
            imageflip($sourceImage, IMG_FLIP_VERTICAL);
        }
        imagealphablending($sourceImage, true);

        imagealphablending($targetImage, false);
        imagesavealpha($targetImage, true);
        imagefill($targetImage, 0, 0, imagecolorallocatealpha($targetImage, 0, 0, 0, 127));

        // Copy the part of the crop area that is inside the source image directly into the target image.
        // Parts of the crop area outside the source image stay transparent.
        $ratioX = $crop['targetWidth'] / $crop['cropWidth'];
        $ratioY = $crop['targetHeight'] / $crop['cropHeight'];
        $srcX = max(0, $crop['x']);
        $srcY = max(0, $crop['y']);
        $srcWidth = min($sourceWidth, $crop['x'] + $crop['cropWidth']) - $srcX;
        $srcHeight = min($sourceHeight, $crop['y'] + $crop['cropHeight']) - $srcY;
        if ($srcWidth > 0 && $srcHeight > 0) {
            imagecopyresampled($targetImage, $sourceImage,
                (int)round(($srcX - $crop['x']) * $ratioX), (int)round(($srcY - $crop['y']) * $ratioY), $srcX, $srcY,
                (int)round($srcWidth * $ratioX), (int)round($srcHeight * $ratioY), $srcWidth, $srcHeight
            );
        }
        // The image memory is released with unset (imagedestroy has no effect since PHP 8 and is deprecated since PHP 8.5)
        unset($sourceImage);

        ob_start();
        switch ($imageExtension) {
            case 'png':
                imagepng($targetImage);
                break;
            case 'gif':
                // GIF has no alpha channel, so the fully transparent pixels get the transparent color of the GIF
                imagecolortransparent($targetImage, imagecolorallocatealpha($targetImage, 0, 0, 0, 127));
                imagegif($targetImage);
                break;
            case 'webp':
                imagewebp($targetImage);
                break;
            default:
                imagejpeg($targetImage);
        }
        $result = ob_get_clean();

        unset($targetImage);

        if (!$result) {
            return ['error' => $this->modx->lexicon('imageplus.cropimage.error_image')];
        }
        $source->createContainer($imagePath, '/');
        // Unset errors to prevent issues if the container already exists.
        $source->errors = [];
        if (!$source->createObject($imagePath, $cropName, $result) &&
            !$source->updateObject($imagePath . $cropName, $result)
        ) {
            return ['error' => $this->modx->lexicon('imageplus.cropimage.error_crop')];
        }
        return [
            'image' => $imagePath . $cropName,
            'width' => $crop['targetWidth'],
            'height' => $crop['targetHeight'],
        ];
    }
}
