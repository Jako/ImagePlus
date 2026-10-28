<?php
/**
 * Resolve migration
 *
 * Remove the media source from the Image+ template variable values, if it is the media source of the template
 * variable. Otherwise, log an error for each affected value in core/cache/logs/imageplus_migration.log
 *
 * Convert the target width and target height input options, system and context settings of the Image+ template variables to
 * the minimal image width/height input options and their aspect ratio or the target ratio to the image crops input
 * option. Remove these and the predefined crop sizes/aspect ratios system and context settings.
 *
 * @package imageplus
 * @subpackage build
 *
 * @var array $options
 * @var xPDOTransport $transport
 */

/** @var modX $modx */
$modx = $transport->xpdo;

if (!function_exists('getImagePlusMigrationLogTarget')) {
    function getImagePlusMigrationLogTarget($modx)
    {
        return [
            'target' => 'FILE',
            'options' => [
                'filename' => 'imageplus_migration.log',
                'filepath' => $modx->getOption(xPDO::OPT_CACHE_PATH, null, MODX_CORE_PATH . 'cache/') . 'logs/',
            ]
        ];
    }
}

if (!function_exists('migrateImagePlusSources')) {
    function migrateImagePlusSources($modx)
    {
        $logTarget = getImagePlusMigrationLogTarget($modx);
        $countMigrated = 0;
        $countErrors = 0;
        $tvSources = [];

        /** @var modTemplateVar[] $tvs */
        $tvs = $modx->getIterator('modTemplateVar', ['type' => 'imageplus']);
        foreach ($tvs as $tv) {
            $c = $modx->newQuery('modTemplateVarResource');
            $c->leftJoin('modResource', 'Resource', ['Resource.id = modTemplateVarResource.contentid']);
            $c->select($modx->getSelectColumns('modTemplateVarResource', 'modTemplateVarResource'));
            $c->select(['context_key' => 'Resource.context_key']);
            $c->where(['tmplvarid' => $tv->get('id')]);
            /** @var modTemplateVarResource[] $tvResources */
            $tvResources = $modx->getIterator('modTemplateVarResource', $c);
            foreach ($tvResources as $tvResource) {
                $value = json_decode($tvResource->get('value'), true);
                if (!is_array($value)) {
                    continue;
                }
                if (isset($value['sourceImg']) && is_array($value['sourceImg'])) {
                    // Image+ value before 3.0
                    $valueSource = $value['sourceImg']['source'] ?? null;
                } else {
                    $valueSource = $value['source'] ?? null;
                }
                if ($valueSource === null) {
                    continue;
                }

                // The media source of the template variable depends on the context of the resource
                $contextKey = $tvResource->get('context_key') ?: 'web';
                $sourceKey = $tv->get('id') . '-' . $contextKey;
                if (!isset($tvSources[$sourceKey])) {
                    $source = $tv->getSource($contextKey);
                    $tvSources[$sourceKey] = ($source) ? (int)$source->get('id') : 0;
                }

                if ((int)$valueSource === $tvSources[$sourceKey]) {
                    if (isset($value['sourceImg'])) {
                        unset($value['sourceImg']['source']);
                    } else {
                        unset($value['source']);
                    }
                    $tvResource->set('value', json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                    if ($tvResource->save()) {
                        $countMigrated++;
                    } else {
                        $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not save the value of the template variable "' . $tv->get('name') . '" (' . $tv->get('id') . ') in the resource ' . $tvResource->get('contentid') . '.', $logTarget);
                        $countErrors++;
                    }
                } else {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, 'The media source ' . $valueSource . ' in the value of the template variable "' . $tv->get('name') . '" (' . $tv->get('id') . ') in the resource ' . $tvResource->get('contentid') . ' is not the media source ' . $tvSources[$sourceKey] . ' of the template variable in the context "' . $contextKey . '". Value: ' . $tvResource->get('value'), $logTarget);
                    $countErrors++;
                }
            }
        }

        if ($countMigrated) {
            $modx->log(xPDO::LOG_LEVEL_INFO, 'Removed the media source from ' . $countMigrated . ' Image+ template variable values.');
        }
        if ($countErrors) {
            $modx->log(xPDO::LOG_LEVEL_WARN, $countErrors . ' Image+ template variable values were not migrated. Please check core/cache/logs/imageplus_migration.log');
        }
    }
}

if (!function_exists('migrateImagePlusTargets')) {
    function migrateImagePlusTargets($modx)
    {
        // Collect the target system and context settings, the context settings are grouped by the context key
        $settings = ['' => []];
        foreach (['modSystemSetting', 'modContextSetting'] as $class) {
            $c = $modx->newQuery($class);
            $c->where(['key:LIKE' => 'imageplus.%target\\_%']);
            /** @var modSystemSetting[]|modContextSetting[] $objects */
            $objects = $modx->getIterator($class, $c);
            foreach ($objects as $object) {
                $contextKey = ($class === 'modContextSetting') ? $object->get('context_key') : '';
                $settings[$contextKey][$object->get('key')] = trim((string)$object->get('value'));
            }
        }
        // The targets of a template variable in a context, context settings replace system settings and TV name based settings replace the global settings
        $getTargets = function ($tvName, $contextKey) use ($settings) {
            $getSetting = function ($key) use ($settings, $contextKey) {
                return (($contextKey !== '') ? ($settings[$contextKey][$key] ?? '') : '') ?: ($settings[''][$key] ?? '');
            };
            $targets = [];
            foreach (['targetWidth' => 'target_width', 'targetHeight' => 'target_height', 'targetRatio' => 'target_ratio'] as $option => $key) {
                $targets[$option] = $getSetting('imageplus.' . $tvName . '.' . $key) ?: $getSetting('imageplus.' . $key);
            }
            return $targets;
        };
        $contextKeys = [];
        /** @var modContext[] $contexts */
        $contexts = $modx->getIterator('modContext', ['key:!=' => 'mgr']);
        foreach ($contexts as $context) {
            $contextKeys[] = $context->get('key');
        }
        $modx->lexicon->load('imageplus:default');
        $logTarget = getImagePlusMigrationLogTarget($modx);
        $countMigrated = 0;
        $countErrors = 0;

        /** @var modTemplateVar[] $tvs */
        $tvs = $modx->getIterator('modTemplateVar', ['type' => 'imageplus']);
        foreach ($tvs as $tv) {
            $properties = $tv->get('input_properties');
            if (!is_array($properties)) {
                continue;
            }
            $targets = [
                'targetWidth' => trim((string)($properties['targetWidth'] ?? '')),
                'targetHeight' => trim((string)($properties['targetHeight'] ?? '')),
                'targetRatio' => trim((string)($properties['targetRatio'] ?? '')),
            ];
            // The system and context settings were used, if no target input option was set
            if (!array_filter($targets)) {
                $targets = $getTargets($tv->get('name'), '');
                $contextTargets = [];
                foreach ($contextKeys as $contextKey) {
                    $contextTargets[$contextKey] = $getTargets($tv->get('name'), $contextKey);
                }
                if ($contextTargets) {
                    // The minimal size is the lowest size of all contexts, so that no image is rejected, that was allowed before
                    $widths = array_map('intval', array_column($contextTargets, 'targetWidth'));
                    $heights = array_map('intval', array_column($contextTargets, 'targetHeight'));
                    // The aspect ratio of the first context with targets is used
                    $first = [];
                    foreach ($contextTargets as $contextTarget) {
                        if (array_filter($contextTarget)) {
                            $first = $contextTarget;
                            break;
                        }
                    }
                    $targets = [
                        'targetWidth' => (string)min($widths),
                        'targetHeight' => (string)min($heights),
                        'targetRatio' => $first['targetRatio'] ?? '',
                        'proportion' => ((int)($first['targetWidth'] ?? 0) && (int)($first['targetHeight'] ?? 0)) ? (int)$first['targetWidth'] . 'x' . (int)$first['targetHeight'] : '',
                    ];
                    if (count(array_unique(array_map('serialize', $contextTargets))) > 1) {
                        $modx->log(xPDO::LOG_LEVEL_WARN, 'The template variable "' . $tv->get('name') . '" had different targets in the contexts. The lowest target width/height is used as minimal image size and the aspect ratio of the first context is used for the image crop. Please check the template variable options.');
                    }
                }
            }
            $legacyKeys = ['targetWidth', 'targetHeight', 'targetRatio', 'selectConfig'];
            $changed = (bool)array_intersect_key($properties, array_flip($legacyKeys));
            // The target width and height were the minimal size of the uploaded image
            if ((int)$targets['targetWidth'] && empty($properties['minWidth'])) {
                $properties['minWidth'] = (string)(int)$targets['targetWidth'];
                $changed = true;
            }
            if ((int)$targets['targetHeight'] && empty($properties['minHeight'])) {
                $properties['minHeight'] = (string)(int)$targets['targetHeight'];
                $changed = true;
            }
            // The aspect ratio of the target width and height or the target ratio is used for the image crop
            $sizes = json_decode($properties['sizes'] ?? '', true);
            if (!is_array($sizes) || empty($sizes)) {
                $proportion = '';
                if (isset($targets['proportion'])) {
                    $proportion = $targets['proportion'];
                } elseif ((int)$targets['targetWidth'] && (int)$targets['targetHeight']) {
                    $proportion = (int)$targets['targetWidth'] . 'x' . (int)$targets['targetHeight'];
                }
                if (!$proportion && (float)$targets['targetRatio']) {
                    $proportion = round((float)$targets['targetRatio'] * 1000) . 'x1000';
                }
                if ($proportion) {
                    // The key of the image crop is the key, that was used for the target options
                    $properties['sizes'] = json_encode(['crop' => [
                        'name' => $modx->lexicon('imageplus.cropimage.crop'),
                        'proportion' => $proportion
                    ]], JSON_UNESCAPED_SLASHES);
                    $changed = true;
                }
            }
            if ($changed) {
                $properties = array_diff_key($properties, array_flip($legacyKeys));
                $tv->set('input_properties', $properties);
                if ($tv->save()) {
                    $countMigrated++;
                } else {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not save the converted target options of the template variable "' . $tv->get('name') . '" (' . $tv->get('id') . '). Input options: ' . json_encode($properties, JSON_UNESCAPED_SLASHES), $logTarget);
                    $countErrors++;
                }
            }
        }
        if ($countMigrated) {
            $modx->log(xPDO::LOG_LEVEL_INFO, 'Converted the target options of ' . $countMigrated . ' Image+ template variables to the minimal image size and image crops options.');
        }

        // Remove the target and the predefined crop sizes/aspect ratios system and context settings
        foreach (['modSystemSetting' => 'system', 'modContextSetting' => 'context'] as $class => $type) {
            $c = $modx->newQuery($class);
            $c->where([
                'key:IN' => ['imageplus.target_width', 'imageplus.target_height', 'imageplus.target_ratio', 'imageplus.select_config', 'imageplus.force_config'],
            ]);
            $c->orCondition(['key:LIKE' => 'imageplus.%.target_width']);
            $c->orCondition(['key:LIKE' => 'imageplus.%.target_height']);
            $c->orCondition(['key:LIKE' => 'imageplus.%.target_ratio']);
            $countRemoved = 0;
            /** @var modSystemSetting[]|modContextSetting[] $settings */
            $settings = $modx->getIterator($class, $c);
            foreach ($settings as $setting) {
                if ($setting->remove()) {
                    $countRemoved++;
                } else {
                    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not remove the ' . $type . ' setting "' . $setting->get('key') . '"' . (($type === 'context') ? ' in the context "' . $setting->get('context_key') . '"' : '') . '. Value: ' . $setting->get('value'), $logTarget);
                    $countErrors++;
                }
            }
            if ($countRemoved) {
                $modx->log(xPDO::LOG_LEVEL_INFO, 'Removed ' . $countRemoved . ' Image+ target and predefined crop sizes ' . $type . ' settings.');
            }
        }
        if ($countErrors) {
            $modx->log(xPDO::LOG_LEVEL_WARN, $countErrors . ' Image+ template variables or settings were not migrated. Please check core/cache/logs/imageplus_migration.log');
        }
    }
}

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        migrateImagePlusSources($modx);
        migrateImagePlusTargets($modx);
        break;
}
return true;
