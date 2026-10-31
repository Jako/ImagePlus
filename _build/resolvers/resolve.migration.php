<?php
/**
 * Resolve migration
 *
 * Migrate the Image+ 2.x template variable values and settings to Image+ 3.x. See
 * core/components/imageplus/src/Helper/Migration.php for details. The migration can be tested before with the
 * ImagePlusMigration snippet and the dryRun property.
 *
 * The migration runs only, if the migrate_legacy setup option is enabled. It is enabled by default, i.e. if the package
 * is installed without setup options.
 *
 * @package imageplus
 * @subpackage build
 *
 * @var array $options
 * @var xPDOTransport $transport
 */

use TreehillStudio\ImagePlus\Helper\Migration;

/** @var modX $modx */
$modx = $transport->xpdo;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        if (isset($options['migrate_legacy']) && !$options['migrate_legacy']) {
            $modx->log(xPDO::LOG_LEVEL_INFO, 'Skipped the migration of the Image+ 2.x template variables and settings.');
            break;
        }
        $corePath = $modx->getOption('imageplus.core_path', null, $modx->getOption('core_path') . 'components/imageplus/');
        require_once $corePath . 'vendor/autoload.php';

        $migration = new Migration($modx);
        $migration->run();
        break;
}
return true;
