<?php
/**
 * Image+
 *
 * Copyright 2013-2015 by Alan Pich <alan.pich@gmail.com>
 * Copyright 2015-2023 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package imageplus
 * @subpackage classfile
 *
 * @author Alan Pich <alan.pich@gmail.com>
 * @author Thomas Jakobi <office@treehillstudio.com>
 * @copyright Alan Pich 2013-2015
 * @copyright Thomas Jakobi 2015-2023
 */

namespace TreehillStudio\ImagePlus;

use modTemplateVar;
use TreehillStudio\ImagePlus\CropEngines\AbstractCropEngine;
use modMediaSource;
use modX;
use stdClass;
use xPDO;

/**
 * Class ImagePlus
 */
class ImagePlus
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'imageplus';

    /**
     * The package name
     * @var string $packageName
     */
    public $packageName = 'ImagePlus';

    /**
     * The version
     * @var string $version
     */
    public $version = '2.9.5';

    /**
     * The class options
     * @var array $options
     */
    public $options = [];

    /**
     * ImagePlus constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = [])
    {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, $this->namespace);

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/' . $this->namespace . '/');
        $modxversion = $this->modx->getVersionData();

        // Load some default paths for easier management
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ], $options);

        // Add default options
        $this->options = array_merge($this->options, [
            'debug' => (bool)$this->modx->getOption($this->namespace . '.debug', null, '0') == 1,
            'modxversion' => $modxversion['version'],
            'sources' => $this->loadSourceMap()
        ]);

        $this->checkDependencies();

        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("$this->namespace.$key", $this->modx->config)) {
                $option = $this->modx->getOption("$this->namespace.$key");
            }
        }
        return $option;
    }

    /**
     * Check dependencies and raise warnings if not met
     */
    private function checkDependencies()
    {
        // Do some basic intelligent sniffing
        if (!$this->getOption('cropEngineClass')) {
            if (CropEngines\PhpThumbsUp::engineRequirementsMet($this->modx)) {
                $this->options['cropEngineClass'] = '\TreehillStudio\ImagePlus\CropEngines\PhpThumbsUp';
            } elseif (CropEngines\PhpThumbOf::engineRequirementsMet($this->modx)) {
                $this->options['cropEngineClass'] = '\TreehillStudio\ImagePlus\CropEngines\PhpThumbOf';
            } elseif (CropEngines\PhpThumbOn::engineRequirementsMet($this->modx)) {
                $this->options['cropEngineClass'] = '\TreehillStudio\ImagePlus\CropEngines\PhpThumbOn';
            } else {
                $this->options['cropEngineClass'] = '';
            }
            if (!$this->getOption('cropEngineClass')) {
                // Handle unmet dependencies
                $this->options['hasUnmetDependencies'] = true;
                return;
            }
        }
        $this->options['hasUnmetDependencies'] = false;
    }

    /**
     * Get a map of MediaSource id => baseUrl
     *
     * @return array
     */
    private function loadSourceMap()
    {
        $sources = $this->modx->getCollection('sources.modMediaSource');
        $sourceMap = [];
        foreach ($sources as $source) {
            /** @var modMediaSource $source */
            $source->initialize();
            $sourceMap[$source->get('id')] = new stdClass();
            $sourceMap[$source->get('id')]->url = $source->getBaseUrl();
        }
        return $sourceMap;
    }

    /**
     * Register javascripts in the controller
     */
    public function includeScriptAssets()
    {
        $assetsUrl = $this->getOption('assetsUrl');
        $jsUrl = $this->getOption('jsUrl') . 'mgr/';
        $jsSourceUrl = $assetsUrl . '../../../source/js/mgr/';
        $jsVendorUrl = $assetsUrl . '../../../source/vendor/';
        $cssUrl = $this->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';
        $nodeUrl = $assetsUrl . '../../../node_modules/';

        if ($this->getOption('debug') && $assetsUrl != MODX_ASSETS_URL . 'components/imageplus/') {
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.panel.input.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.window.editor.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.migx_renderer.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'tools/JSON2.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($nodeUrl . 'jquery/dist/jquery.slim.min.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsVendorUrl . 'jcrop/js/jquery.Jcrop.min.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.jquery.imagecrop.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.grid.js?v=v' . $this->version);
            $this->modx->controller->addCss($cssSourceUrl . 'imageplus.css?v=v' . $this->version);
        } else {
            $this->modx->controller->addJavascript($jsUrl . 'imageplus.min.js?v=v' . $this->version);
            $this->modx->controller->addCss($cssUrl . 'imageplus.min.css?v=v' . $this->version);
        }
        $this->modx->controller->addHtml('<script type="text/javascript">' .
            'ImagePlus.config = ' . json_encode($this->options, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . ';' .
            '</script>');
    }

    /**
     * Return a scaled, cached version of the source image for front-end use
     *
     * @param string $json
     * @param array $opts
     * @param modTemplateVar $tv
     * @return string
     * @internal param array $params
     */
    public function getImageURL($json, $opts = [], $tv = null)
    {
        // Check system settings for crop engine override
        $engineClass = $this->getOption('cropEngineClass');

        /**
         * @var AbstractCropEngine $cropEngine
         */
        $cropEngine = new $engineClass($this->modx, [
            'core_path' => $this->getOption('corePath')
        ]);

        // Check crop engine is usable
        if ($this->getOption('hasUnmetDependencies')) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Requirements not met for crop engine.', '', 'Image+');
            return 'Image+ error - requirements not met for crop engine.';
        }

        $json = $this->prepareTvValue($json, $opts, $tv);
        return $cropEngine->getImageUrl($json, $opts, $tv);
    }

    /**
     * Prepare a JSON encoded object and return a valid JSON encoded Image+ object
     *
     * @param string $json JSON value to prepare
     * @param array $opts
     * @param modTemplateVar $tv
     * @return string
     */
    public function prepareTvValue($json, $opts = [], $tv = null)
    {
        // Prepare value
        $decoded = json_decode($json);
        if (!$decoded) {
            // The variable does not contain an Image+ image object
            if ($json != '') {
                // Get Media Source
                /** @var modMediaSource $source */
                if ($tv) {
                    $source = $tv->getSource(($this->modx->resource) ? $this->modx->resource->get('context_key') : 'mgr');
                } else {
                    $source = $this->modx->getObject('modMediaSource', $this->modx->getOption('default_media_source'));
                }
                if (!$source) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Invalid Media Source', '', 'Image+');
                    return '';
                }
                $source->setRequestProperties($_REQUEST);
                $source->initialize();

                // The variable contains a value and has to be converted to an Image+ image object
                $imgPath = $source->getBasePath() . $json;
                if (file_exists($imgPath)) {
                    $size = getimagesize($imgPath);
                } else {
                    if ($this->getOption('debug')) {
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The template variabe value does not contain an existing image', '', 'Image+');
                    }
                    $size = false;
                }
                $json = json_encode([
                    'altTag' => '',
                    'crop' => [
                        'height' => ($size) ? $size[1] : 0,
                        'width' => ($size) ? $size[0] : 0,
                        'x' => 0,
                        'y' => 0
                    ],
                    'sourceImg' => [
                        'height' => ($size) ? $size[1] : 0,
                        'width' => ($size) ? $size[0] : 0,
                        'source' => $source->get('id'),
                        'src' => $json
                    ],
                    'targetHeight' => (int)($opts['targetHeight'] ?? 0),
                    'targetWidth' => (int)($opts['targetWidth'] ?? 0)
                ]);
            }
        }
        return $json;
    }
}

define('imageplus', true);
