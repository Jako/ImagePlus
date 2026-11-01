<?php
/**
 * ImagePlusCleanup Snippet to remove the unused image crop files
 *
 * @package imageplus
 * @subpackage snippet
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

use TreehillStudio\ImagePlus\Snippets\ImagePlusCleanup;

$corePath = $modx->getOption('imageplus.core_path', null, $modx->getOption('core_path') . 'components/imageplus/');
/** @var ImagePlus $imageplus */
$imageplus = $modx->getService('imageplus', 'ImagePlus', $corePath . 'model/imageplus/', [
    'core_path' => $corePath
]);

$snippet = new ImagePlusCleanup($modx, $scriptProperties);
if ($snippet instanceof TreehillStudio\ImagePlus\Snippets\ImagePlusCleanup) {
    return $snippet->execute();
}
return 'TreehillStudio\ImagePlus\Snippets\ImagePlusCleanup class not found';
