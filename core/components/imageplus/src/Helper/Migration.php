<?php
/**
 * Migration
 *
 * @package imageplus
 * @subpackage helper
 */

namespace TreehillStudio\ImagePlus\Helper;

use modContext;
use modContextSetting;
use modSystemSetting;
use modTemplateVar;
use modTemplateVarResource;
use modX;
use TreehillStudio\ImagePlus\ImagePlus;
use xPDO;

/**
 * Migrate the Image+ 2.x template variables and settings to Image+ 3.x
 *
 * Convert the Image+ template variable values before 3.0 to the Image+ 3.x value format and remove the media source
 * from the values, if it is the media source of the template variable. Otherwise, log an error for each affected
 * value in core/cache/logs/imageplus_migration.log
 *
 * Convert the target width and target height input options, system and context settings of the Image+ template variables to
 * the minimal image width/height input options and their aspect ratio or the target ratio to the image crops input
 * option. Remove these and the predefined crop sizes/aspect ratios system and context settings.
 *
 * In dry run mode, nothing is saved or removed and the changes are only reported.
 */
class Migration
{
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
     * Report the changes without saving them
     * @var bool $dryRun
     */
    protected $dryRun;

    /**
     * The collected log messages
     * @var array $messages
     */
    protected $messages = [];

    /**
     * Migration constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param bool $dryRun Report the changes without saving them.
     */
    public function __construct(modX &$modx, bool $dryRun = false)
    {
        $this->modx =& $modx;
        $this->dryRun = $dryRun;

        $corePath = $this->modx->getOption('imageplus.core_path', null, $this->modx->getOption('core_path') . 'components/imageplus/');
        $this->imageplus = $this->modx->getService('imageplus', 'ImagePlus', $corePath . 'model/imageplus/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * Run the complete migration.
     *
     * @return void
     */
    public function run()
    {
        $this->migrateValues();
        $this->migrateTargets();
    }

    /**
     * Get the collected log messages.
     *
     * @return array Each message as array with the keys level and message.
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * Log a message and collect it.
     *
     * The message is logged in the default log target, i.e. the package installation console, and collected for the
     * snippet output.
     *
     * @param int $level
     * @param string $message
     * @param bool $toFile Log the message additionally in core/cache/logs/imageplus_migration.log
     * @return void
     */
    protected function log(int $level, string $message, bool $toFile = false)
    {
        if ($this->dryRun) {
            $message = 'Dry run: ' . $message;
        }
        $this->messages[] = [
            'level' => $level,
            'message' => $message,
        ];
        $this->modx->log($level, $message);
        if ($toFile) {
            $this->modx->log($level, $message, [
                'target' => 'FILE',
                'options' => [
                    'filename' => 'imageplus_migration.log',
                    'filepath' => $this->modx->getOption(xPDO::OPT_CACHE_PATH, null, MODX_CORE_PATH . 'cache/') . 'logs/',
                ]
            ]);
        }
    }

    /**
     * Log a detailed change in dry run mode.
     *
     * @param string $message
     * @return void
     */
    protected function logChange(string $message)
    {
        if ($this->dryRun) {
            $this->log(xPDO::LOG_LEVEL_INFO, $message);
        }
    }

    /**
     * Get the past or conditional form of a verb, depending on the dry run mode.
     *
     * @param string $past
     * @param string $infinitive
     * @return string
     */
    protected function verb(string $past, string $infinitive): string
    {
        return ($this->dryRun) ? 'Would ' . $infinitive : $past;
    }

    /**
     * Convert the Image+ template variable values before 3.0 to the Image+ 3.x value format and remove the media source
     * from the values, if it is the media source of the template variable.
     *
     * The crop of a value before 3.0 is converted to the first image crop of the template variable. Without an image
     * crops input option, this is the image crop with the key crop, that is also used for the converted target
     * options in migrateTargets.
     *
     * @return void
     */
    public function migrateValues()
    {
        $countMigrated = 0;
        $countErrors = 0;
        $tvSources = [];

        /** @var modTemplateVar[] $tvs */
        $tvs = $this->modx->getIterator('modTemplateVar', ['type' => 'imageplus']);
        foreach ($tvs as $tv) {
            // The crop is converted to the first image crop of the template variable like in the template variable input
            $properties = $tv->get('input_properties');
            $sizes = json_decode((is_array($properties)) ? ($properties['sizes'] ?? '') : '', true);
            if (!is_array($sizes) || empty($sizes)) {
                $sizes = $this->imageplus->getDefaultCropSizes();
            }
            $cropKey = (string)array_keys($sizes)[0];

            $c = $this->modx->newQuery('modTemplateVarResource');
            $c->leftJoin('modResource', 'Resource', ['Resource.id = modTemplateVarResource.contentid']);
            $c->select($this->modx->getSelectColumns('modTemplateVarResource', 'modTemplateVarResource'));
            $c->select(['context_key' => 'Resource.context_key']);
            $c->where(['tmplvarid' => $tv->get('id')]);
            /** @var modTemplateVarResource[] $tvResources */
            $tvResources = $this->modx->getIterator('modTemplateVarResource', $c);
            foreach ($tvResources as $tvResource) {
                $value = json_decode($tvResource->get('value'), true);
                if (!is_array($value)) {
                    // Plain image paths are converted on the fly by ImagePlus::prepareTvValue
                    continue;
                }
                $legacy = isset($value['sourceImg']) && is_array($value['sourceImg']);
                $valueSource = ($legacy) ? ($value['sourceImg']['source'] ?? null) : ($value['source'] ?? null);
                if (!$legacy && $valueSource === null) {
                    // Image+ 3.x value
                    continue;
                }
                $tvName = 'the template variable "' . $tv->get('name') . '" (' . $tv->get('id') . ') in the resource ' . $tvResource->get('contentid');

                if ($valueSource !== null) {
                    // The media source of the template variable depends on the context of the resource
                    $contextKey = $tvResource->get('context_key') ?: 'web';
                    $sourceKey = $tv->get('id') . '-' . $contextKey;
                    if (!isset($tvSources[$sourceKey])) {
                        $source = $tv->getSource($contextKey);
                        $tvSources[$sourceKey] = ($source) ? (int)$source->get('id') : 0;
                    }
                    if ((int)$valueSource !== $tvSources[$sourceKey]) {
                        $this->log(xPDO::LOG_LEVEL_ERROR, 'The media source ' . $valueSource . ' in the value of ' . $tvName . ' is not the media source ' . $tvSources[$sourceKey] . ' of the template variable in the context "' . $contextKey . '". Value: ' . $tvResource->get('value'), true);
                        $countErrors++;
                        continue;
                    }
                }

                // A value without an image is cleared
                $oldValue = $tvResource->get('value');
                $newValue = $this->imageplus->prepareTvValue($oldValue, ['crop' => $cropKey], $tv);
                $tvResource->set('value', $newValue);
                if ($this->dryRun || $tvResource->save()) {
                    $this->logChange(($newValue !== '') ? 'Would convert the value of ' . $tvName . ' to: ' . json_encode(json_decode($newValue, true), JSON_UNESCAPED_SLASHES) : 'Would clear the value of ' . $tvName . ', it does not contain an image. Value: ' . $oldValue);
                    $countMigrated++;
                } else {
                    $this->log(xPDO::LOG_LEVEL_ERROR, 'Could not save the value of ' . $tvName . '.', true);
                    $countErrors++;
                }
            }
        }

        if ($countMigrated) {
            $this->log(xPDO::LOG_LEVEL_INFO, $this->verb('Converted', 'convert') . ' ' . $countMigrated . ' Image+ template variable values to the Image+ 3.x format.');
        }
        if ($countErrors) {
            $this->log(xPDO::LOG_LEVEL_WARN, $countErrors . ' Image+ template variable values ' . (($this->dryRun) ? 'would not be' : 'were not') . ' migrated. Please check core/cache/logs/imageplus_migration.log');
        }
    }

    /**
     * Convert the target options and settings of the Image+ template variables to the minimal image size and image crops
     * input options and remove the target and predefined crop sizes/aspect ratios system and context settings.
     *
     * @return void
     */
    public function migrateTargets()
    {
        // Collect the target system and context settings, the context settings are grouped by the context key
        $settings = ['' => []];
        foreach (['modSystemSetting', 'modContextSetting'] as $class) {
            $c = $this->modx->newQuery($class);
            $c->where(['key:LIKE' => 'imageplus.%target\\_%']);
            /** @var modSystemSetting[]|modContextSetting[] $objects */
            $objects = $this->modx->getIterator($class, $c);
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
        $contexts = $this->modx->getIterator('modContext', ['key:!=' => 'mgr']);
        foreach ($contexts as $context) {
            $contextKeys[] = $context->get('key');
        }
        $this->modx->lexicon->load('imageplus:default');
        $countMigrated = 0;
        $countErrors = 0;

        /** @var modTemplateVar[] $tvs */
        $tvs = $this->modx->getIterator('modTemplateVar', ['type' => 'imageplus']);
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
                        $this->log(xPDO::LOG_LEVEL_WARN, 'The template variable "' . $tv->get('name') . '" had different targets in the contexts. The lowest target width/height is used as minimal image size and the aspect ratio of the first context is used for the image crop. Please check the template variable options.');
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
                        'name' => $this->modx->lexicon('imageplus.cropimage.crop'),
                        'proportion' => $proportion
                    ]], JSON_UNESCAPED_SLASHES);
                    $changed = true;
                }
            }
            if ($changed) {
                $properties = array_diff_key($properties, array_flip($legacyKeys));
                $tv->set('input_properties', $properties);
                if ($this->dryRun || $tv->save()) {
                    $this->logChange('Would change the input options of the template variable "' . $tv->get('name') . '" (' . $tv->get('id') . ') to: minWidth: ' . ($properties['minWidth'] ?? '') . ', minHeight: ' . ($properties['minHeight'] ?? '') . ', sizes: ' . ($properties['sizes'] ?? ''));
                    $countMigrated++;
                } else {
                    $this->log(xPDO::LOG_LEVEL_ERROR, 'Could not save the converted target options of the template variable "' . $tv->get('name') . '" (' . $tv->get('id') . '). Input options: ' . json_encode($properties, JSON_UNESCAPED_SLASHES), true);
                    $countErrors++;
                }
            }
        }
        if ($countMigrated) {
            $this->log(xPDO::LOG_LEVEL_INFO, $this->verb('Converted', 'convert') . ' the target options of ' . $countMigrated . ' Image+ template variables to the minimal image size and image crops options.');
        }

        // Remove the target and the predefined crop sizes/aspect ratios system and context settings
        foreach (['modSystemSetting' => 'system', 'modContextSetting' => 'context'] as $class => $type) {
            $c = $this->modx->newQuery($class);
            $c->where([
                'key:IN' => ['imageplus.target_width', 'imageplus.target_height', 'imageplus.target_ratio', 'imageplus.select_config', 'imageplus.force_config'],
            ]);
            $c->orCondition(['key:LIKE' => 'imageplus.%.target_width']);
            $c->orCondition(['key:LIKE' => 'imageplus.%.target_height']);
            $c->orCondition(['key:LIKE' => 'imageplus.%.target_ratio']);
            $countRemoved = 0;
            /** @var modSystemSetting[]|modContextSetting[] $legacySettings */
            $legacySettings = $this->modx->getIterator($class, $c);
            foreach ($legacySettings as $setting) {
                $settingName = 'the ' . $type . ' setting "' . $setting->get('key') . '"' . (($type === 'context') ? ' in the context "' . $setting->get('context_key') . '"' : '');
                if ($this->dryRun || $setting->remove()) {
                    $this->logChange('Would remove ' . $settingName . '. Value: ' . $setting->get('value'));
                    $countRemoved++;
                } else {
                    $this->log(xPDO::LOG_LEVEL_ERROR, 'Could not remove ' . $settingName . '. Value: ' . $setting->get('value'), true);
                    $countErrors++;
                }
            }
            if ($countRemoved) {
                $this->log(xPDO::LOG_LEVEL_INFO, $this->verb('Removed', 'remove') . ' ' . $countRemoved . ' Image+ target and predefined crop sizes ' . $type . ' settings.');
            }
        }
        if ($countErrors) {
            $this->log(xPDO::LOG_LEVEL_WARN, $countErrors . ' Image+ template variables or settings ' . (($this->dryRun) ? 'would not be' : 'were not') . ' migrated. Please check core/cache/logs/imageplus_migration.log');
        }
    }
}
