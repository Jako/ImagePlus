<?php
/**
 * Image Memory classfile
 *
 * @package imageplus
 * @subpackage classfile
 */

namespace TreehillStudio\ImagePlus\Helper;

/**
 * Class ImageMemory
 *
 * Estimate and reserve the memory GD needs to process an image
 */
class ImageMemory
{
    /**
     * Estimate the peak memory GD needs to decode the image, to create the target image and to encode it
     *
     * @param array $imageSize The result of getimagesize/getimagesizefromstring
     * @param int $fileSize The size of the image file in bytes
     * @param int|null $pngColorType The PNG color type of the image (from the IHDR chunk), if it is a PNG
     * @param string $extension The extension of the target image, which sets its format
     * @param int $targetWidth
     * @param int $targetHeight
     * @param bool $fileInMemory True, if the file contents are held in memory while decoding
     * @return int
     */
    public static function estimate(array $imageSize, int $fileSize, ?int $pngColorType, string $extension, int $targetWidth, int $targetHeight, bool $fileInMemory = true): int
    {
        $sourcePixels = $imageSize[0] * $imageSize[1];
        $targetPixels = $targetWidth * $targetHeight;

        // Bytes per pixel while decoding and of the decoded GD image (measured with the bundled GD)
        switch ($imageSize['mime'] ?? '') {
            case 'image/png':
                // The PNG decoder keeps the whole decoded image in a buffer next to the GD image.
                // Grayscale (0) and palette (3) images are decoded to a palette image, all others to a true color image.
                $colorType = $pngColorType ?? 6;
                if ($colorType === 0 || $colorType === 3) {
                    $decodeBytes = 2;
                    $imageBytes = 1;
                } else {
                    $decodeBytes = ($colorType === 2) ? 7 : 8;
                    $imageBytes = 4;
                }
                break;
            case 'image/gif':
                $decodeBytes = 1;
                $imageBytes = 1;
                break;
            default:
                $decodeBytes = 4;
                $imageBytes = 4;
        }

        // Bytes per target pixel the encoder needs in addition to the target image
        switch (strtolower($extension)) {
            case 'png':
            case 'webp':
                $encodeBytes = 4;
                break;
            case 'gif':
                $encodeBytes = 1;
                break;
            default:
                $encodeBytes = 0;
        }
        // The encoded result is held in the output buffer and in the result string
        $outputSize = 2 * $fileSize * max(1, $targetPixels / max(1, $sourcePixels));

        $peak = max(
            // The file contents are released after decoding
            ($fileInMemory ? $fileSize : 0) + $sourcePixels * $decodeBytes,
            $sourcePixels * $imageBytes + $targetPixels * 4,
            $targetPixels * (4 + $encodeBytes) + $outputSize
        );
        // Add a margin for the row pointers and other overhead
        return (int)ceil($peak * 1.2);
    }

    /**
     * Get the PNG color type from the first bytes of a PNG file
     *
     * @param string $header At least the first 26 bytes of the file
     * @return int|null
     */
    public static function getPngColorType(string $header): ?int
    {
        return (strlen($header) > 25 && strpos($header, "\x89PNG") === 0) ? ord($header[25]) : null;
    }

    /**
     * Make sure that the needed memory is available and raise the memory limit if necessary
     *
     * @param int $needed
     * @return bool
     */
    public static function reserve(int $needed): bool
    {
        $limit = self::getBytes((string)ini_get('memory_limit'));
        if ($limit < 0) {
            return true;
        }
        $required = memory_get_usage() + $needed;
        if ($required <= $limit) {
            return true;
        }
        // Raise the limit with a safety margin, if the server allows that
        $newLimit = (int)ceil(($required + 16777216) / 1048576) . 'M';
        if (!function_exists('ini_set') || @ini_set('memory_limit', $newLimit) === false) {
            return false;
        }
        return self::getBytes((string)ini_get('memory_limit')) >= $required;
    }

    /**
     * Convert a php.ini size value to bytes
     *
     * @param string $value
     * @return int
     */
    public static function getBytes(string $value): int
    {
        $value = trim($value);
        if ($value === '' || $value === '-1') {
            return -1;
        }
        $bytes = (int)$value;
        switch (strtolower(substr($value, -1))) {
            case 'g':
                $bytes *= 1024;
            // no break
            case 'm':
                $bytes *= 1024;
            // no break
            case 'k':
                $bytes *= 1024;
        }
        return $bytes;
    }
}
