<?php
/**
 * Image orientation
 *
 * @package imageplus
 * @subpackage helper
 */

namespace TreehillStudio\ImagePlus\Helper;

/**
 * Read the EXIF orientation of a JPEG image and turn a GD image upright
 *
 * Cameras and phones often save portrait photos as landscape images with an EXIF orientation. Browsers show these
 * images upright, GD ignores the orientation. The orientation is read from the JPEG data, so that the exif extension
 * is not needed.
 */
class ImageOrientation
{
    /** @var int The EXIF data is in the first bytes of a JPEG file */
    const HEADER_LENGTH = 131072;

    /**
     * Get the EXIF orientation of a JPEG image
     *
     * @param string $data The beginning of the JPEG file, at least the first HEADER_LENGTH bytes
     * @return int The orientation 1-8, 1 if the image has no orientation
     */
    public static function get(string $data): int
    {
        $length = strlen($data);
        if ($length < 4 || substr($data, 0, 2) !== "\xFF\xD8") {
            return 1;
        }
        $offset = 2;
        while ($offset + 4 <= $length) {
            if ($data[$offset] !== "\xFF") {
                return 1;
            }
            $marker = ord($data[$offset + 1]);
            if ($marker === 0xFF) {
                // Fill byte
                $offset++;
                continue;
            }
            if ($marker === 0xDA || $marker === 0xD9) {
                // The image data starts without EXIF data
                return 1;
            }
            $segmentLength = unpack('n', substr($data, $offset + 2, 2))[1];
            if ($marker === 0xE1 && substr($data, $offset + 4, 6) === "Exif\0\0") {
                return self::getTiffOrientation(substr($data, $offset + 10, $segmentLength - 8));
            }
            $offset += 2 + $segmentLength;
        }
        return 1;
    }

    /**
     * Get the orientation of a JPEG file
     *
     * @param string $path
     * @return int
     */
    public static function getFromFile(string $path): int
    {
        return self::get((string)@file_get_contents($path, false, null, 0, self::HEADER_LENGTH));
    }

    /**
     * Get the size of an image file like getimagesize, the width and the height of the upright image
     *
     * @param string $path
     * @return array|false
     */
    public static function getImageSize(string $path)
    {
        $imageSize = @getimagesize($path);
        if ($imageSize && $imageSize['mime'] === 'image/jpeg' && self::isRotated(self::getFromFile($path))) {
            [$imageSize[0], $imageSize[1]] = [$imageSize[1], $imageSize[0]];
        }
        return $imageSize;
    }

    /**
     * Check if the image is rotated by 90 degrees, so that its width and height are swapped
     *
     * @param int $orientation
     * @return bool
     */
    public static function isRotated(int $orientation): bool
    {
        return $orientation >= 5 && $orientation <= 8;
    }

    /**
     * Turn a GD image upright. A rotated image is a new image, so that it needs the memory of the image again.
     *
     * @param resource|\GdImage $image
     * @param int $orientation
     * @return resource|\GdImage The upright image
     */
    public static function apply($image, int $orientation)
    {
        switch ($orientation) {
            case 2:
                imageflip($image, IMG_FLIP_HORIZONTAL);
                break;
            case 3:
                imageflip($image, IMG_FLIP_BOTH);
                break;
            case 4:
                imageflip($image, IMG_FLIP_VERTICAL);
                break;
            case 5:
                imageflip($image, IMG_FLIP_HORIZONTAL);
                return self::rotate($image, 90);
            case 6:
                return self::rotate($image, -90);
            case 7:
                imageflip($image, IMG_FLIP_HORIZONTAL);
                return self::rotate($image, -90);
            case 8:
                return self::rotate($image, 90);
        }
        return $image;
    }

    /**
     * Rotate a GD image counterclockwise
     *
     * @param resource|\GdImage $image
     * @param int $angle
     * @return resource|\GdImage
     */
    private static function rotate($image, int $angle)
    {
        $rotated = imagerotate($image, $angle, 0);
        if (!$rotated) {
            return $image;
        }
        unset($image);
        return $rotated;
    }

    /**
     * Get the orientation from the TIFF data of the EXIF segment
     *
     * @param string $tiff
     * @return int
     */
    private static function getTiffOrientation(string $tiff): int
    {
        $length = strlen($tiff);
        if ($length < 8) {
            return 1;
        }
        $byteOrder = substr($tiff, 0, 2);
        if ($byteOrder === 'II') {
            [$short, $long] = ['v', 'V'];
        } elseif ($byteOrder === 'MM') {
            [$short, $long] = ['n', 'N'];
        } else {
            return 1;
        }
        $ifdOffset = unpack($long, substr($tiff, 4, 4))[1];
        if ($ifdOffset + 2 > $length) {
            return 1;
        }
        $count = unpack($short, substr($tiff, $ifdOffset, 2))[1];
        for ($i = 0; $i < $count; $i++) {
            $entry = $ifdOffset + 2 + $i * 12;
            if ($entry + 12 > $length) {
                break;
            }
            if (unpack($short, substr($tiff, $entry, 2))[1] === 0x0112) {
                $orientation = unpack($short, substr($tiff, $entry + 8, 2))[1];
                return ($orientation >= 1 && $orientation <= 8) ? $orientation : 1;
            }
        }
        return 1;
    }
}
