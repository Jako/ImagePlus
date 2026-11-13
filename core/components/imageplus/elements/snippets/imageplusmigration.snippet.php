<?php
/**
 * ImagePlusMigration Snippet to migrate the Image+ 2.x template variables and settings
 *
 * @package imageplus
 * @subpackage snippet
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

use TreehillStudio\ImagePlus\Snippets\ImagePlusMigration;

$corePath = $modx->getOption('imageplus.core_path', null, $modx->getOption('core_path') . 'components/imageplus/');
/** @var ImagePlus $imageplus */
$imageplus = $modx->getService('imageplus', 'ImagePlus', $corePath . 'model/imageplus/', [
    'core_path' => $corePath
]);

$snippet = new ImagePlusMigration($modx, $scriptProperties);
if ($snippet instanceof TreehillStudio\ImagePlus\Snippets\ImagePlusMigration) {
    return $snippet->execute();
}
return 'TreehillStudio\ImagePlus\Snippets\ImagePlusMigration class not found';
