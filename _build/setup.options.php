<?php
/**
 * Setup options
 *
 * @package imageplus
 * @subpackage build
 *
 * @var array $options
 */

// Defaults
$defaults = [
    'migrate_legacy' => true,
];

$migrationOption = '<div style="position: relative">
    <input type="hidden" name="migrate_legacy" value="0">
    <input type="checkbox" name="migrate_legacy" id="migrate_legacy" ' . (($defaults['migrate_legacy']) ? 'checked' : '') . ' value="1">
    <label for="migrate_legacy" style="display: inline;">Migrate the Image+ 2.x template variables and settings</label>
    <p class="red">The template variable values are converted to the new value format without the media source, if
    it is the media source of the template variable. The target width/height input options and settings are converted to the minimal
    image size input options and to the size of the image crop, the target ratio is converted to the ratio of the image
    crop and the legacy settings are removed. The crop files of the image crops
    are created. Errors are logged in
    core/cache/logs/imageplus_migration.log</p>
    <p>Disable this option to check the migration first with the ImagePlusMigration snippet and its dryRun
    property. Run the snippet with &dryRun=`0` afterwards to migrate.</p>
</div>';

$output = '<style type="text/css">
    #modx-setupoptions-panel { display: none; }
    #modx-setupoptions-form p { margin-bottom: 10px; }
    #modx-setupoptions-form h2 { margin-bottom: 15px; }
</style>';

$values = [];
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        $output .= '<h2>Install Image+</h2>

        <p>Thanks for installing Image+. This open source extra was
        developed by Treehill Studio - MODX development in Münsterland.</p>

        <p>During the installation, we will collect some statistical data (the
        hostname, the MODX UUID, the PHP version and the MODX version of your
        MODX installation). Your data will be kept confidential and under no
        circumstances be used for promotional purposes or disclosed to third
        parties. We only like to know the usage count of this package.</p>
        
        <p>If you install this package, you are giving us your permission to
        collect, process and use that data for statistical purposes.</p>

        <p>Please review the installation options carefully.</p>';
        $output .= $migrationOption;
        break;
    case xPDOTransport::ACTION_UPGRADE:
        $output .= '<h2>Upgrade Image+</h2>

        <p>Image+ will be upgraded. This open source extra was developed by
        Treehill Studio - MODX development in Münsterland.</p>

        <p>During the installation, we will collect some statistical data (the
        hostname, the MODX UUID, the PHP version and the MODX version of your
        MODX installation). Your data will be kept confidential and under no
        circumstances be used for promotional purposes or disclosed to third
        parties. We only like to know the usage count of this package.</p>

        <p>If you upgrade this package, you are giving us your permission to
        collect, process and use that data for statistical purposes.</p>

        <p>Please review the installation options carefully.</p>';
        $output .= $migrationOption;
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return $output;
