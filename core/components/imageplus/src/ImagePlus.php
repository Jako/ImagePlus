<?php
/**
 * Image+
 *
 * Copyright 2013-2015 by Alan Pich <alan.pich@gmail.com>
 * Copyright 2015-2026 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package imageplus
 * @subpackage classfile
 */

namespace TreehillStudio\ImagePlus;

use modTemplateVar;
use TreehillStudio\ImagePlus\CropEngines\AbstractCropEngine;
use TreehillStudio\ImagePlus\Helper\ImageOrientation;
use modMediaSource;
use modX;
use PDO;
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
    public $version = '3.0.0-b1';

    /**
     * The class options
     * @var array $options
     */
    public $options = [];

    /**
     * The cached context keys of resources
     * @var array $resourceContextKeys
     */
    private $resourceContextKeys = [];

    /**
     * The cached map of the media source urls
     * @var array|null $sourceMap
     */
    private $sourceMap = null;

    /**
     * ImagePlus constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = [])
    {
        $this->modx =& $modx;

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
        $engineClass = (string)$this->getOption('cropEngineClass');
        if ($engineClass !== '' && (!class_exists($engineClass) || !is_subclass_of($engineClass, AbstractCropEngine::class) || !$engineClass::engineRequirementsMet($this->modx))) {
            // The crop engine could not be used, i.e. a phpThumb crop engine of Image+ 2.x, so the image crop files are used
            if ($this->getOption('debug')) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The crop engine ' . $engineClass . ' could not be used. The CropFile crop engine is used instead.', '', 'Image+');
            }
            $engineClass = '';
        }
        if ($engineClass === '') {
            $engineClass = '\\' . CropEngines\CropFile::class;
        }
        $this->options['cropEngineClass'] = $engineClass;
        if (!$engineClass::engineRequirementsMet($this->modx)) {
            // Handle unmet dependencies
            $this->options['hasUnmetDependencies'] = true;
            return;
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
        // The map is loaded once per request, the scripts are included on every manager page and by each template variable
        if ($this->sourceMap !== null) {
            return $this->sourceMap;
        }
        $sources = $this->modx->getCollection('sources.modMediaSource');
        $sourceMap = [];
        foreach ($sources as $source) {
            /** @var modMediaSource $source */
            $source->initialize();
            $sourceMap[$source->get('id')] = new stdClass();
            $sourceMap[$source->get('id')]->url = $source->getBaseUrl();
        }
        $this->sourceMap = $sourceMap;
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
        $cssUrl = $this->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';
        $nodeUrl = $assetsUrl . '../../../node_modules/';

        if ($this->getOption('debug') && $assetsUrl != MODX_ASSETS_URL . 'components/imageplus/') {
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.util.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.uploadbrowser.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.uploadcropbrowser.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.panel.input.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($jsSourceUrl . 'imageplus.columnrenderer.js?v=v' . $this->version);
            $this->modx->controller->addJavascript($nodeUrl . 'cropperjs/dist/cropper.js?v=v' . $this->version);
            $this->modx->controller->addCss($cssSourceUrl . 'imageplus.css?v=v' . $this->version);
        } else {
            $this->modx->controller->addJavascript($jsUrl . 'imageplus.min.js?v=v' . $this->version);
            $this->modx->controller->addCss($cssUrl . 'imageplus.min.css?v=v' . $this->version);
        }
        $this->modx->controller->addHtml('<script type="text/javascript">' .
            'ImagePlus.config = ' . json_encode(array_merge($this->options, [
                // The urls of the media sources are only needed by the manager scripts
                'sources' => $this->loadSourceMap()
            ]), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) . ';' .
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
        $engineClass = $this->getOption('cropEngineClass');

        // Check crop engine is usable
        if ($this->getOption('hasUnmetDependencies') || !$engineClass) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Requirements not met for crop engine.', '', 'Image+');
            return 'Image+ error - requirements not met for crop engine.';
        }

        /**
         * @var AbstractCropEngine $cropEngine
         */
        $cropEngine = new $engineClass($this->modx, [
            'core_path' => $this->getOption('corePath')
        ]);

        $json = $this->prepareTvValue($json, $opts, $tv);
        $source = $this->getTvSource($tv, $opts['docid'] ?? 0, $opts['source'] ?? 0);
        if ($source) {
            $source->initialize();
        }
        $source = $source ?: $this->modx->getOption('default_media_source');
        if (!empty($opts['outputChunk'])) {
            // The output chunk gets the crops placeholders of all image crops
            $data = json_decode($json, true);
            $opts['crops.values'] = [];
            foreach ((is_array($data) && isset($data['sizes']) && is_array($data['sizes'])) ? array_keys($data['sizes']) : [] as $key) {
                $cropOpts = ['crop' => (string)$key];
                $opts['crops.values'][$key] = $this->getCropEngineValue($json, $cropOpts, $source);
            }
        }
        $json = $this->getCropEngineValue($json, $opts, $source);
        return $cropEngine->getImageUrl($json, $opts, $tv);
    }

    /**
     * Get the value of the selected image crop in the format of the crop engines
     *
     * @param string $json JSON value prepared by prepareTvValue
     * @param array $opts The crop option selects the image crop, the first one is used by default. The url of the
     * crop file is added as crop.url option.
     * @param modMediaSource|int $source The (initialized) media source of the template variable or its id
     * @return string
     */
    public function getCropEngineValue($json, &$opts = [], $source = 0)
    {
        $sourceId = (is_object($source)) ? (int)$source->get('id') : (int)$source;
        $data = json_decode($json, true);
        if (!is_array($data) || empty($data['image'])) {
            return '';
        }
        $sizes = (isset($data['sizes']) && is_array($data['sizes'])) ? $data['sizes'] : [];
        $key = $opts['crop'] ?? '';
        $size = ($key !== '' && isset($sizes[$key])) ? $sizes[$key] : (reset($sizes) ?: []);
        // The stored url is outdated after a site move, so the url is created with the media source, if possible
        if (!empty($size['image']) && is_object($source)) {
            $opts['crop.url'] = $this->getSourceObjectUrl($source, $size['image']);
        } else {
            $opts['crop.url'] = $size['baseUrl'] ?? '';
        }

        return json_encode([
            'sourceImg' => [
                'src' => $data['image'],
                'source' => $sourceId ?: $this->modx->getOption('default_media_source'),
                'width' => (int)($size['originalWidth'] ?? 0),
                'height' => (int)($size['originalHeight'] ?? 0),
            ],
            'crop' => [
                'x' => (int)($size['x'] ?? 0),
                'y' => (int)($size['y'] ?? 0),
                'width' => (int)($size['cropWidth'] ?? 0),
                'height' => (int)($size['cropHeight'] ?? 0),
                'scaleX' => ((int)($size['scaleX'] ?? 1) === -1) ? -1 : 1,
                'scaleY' => ((int)($size['scaleY'] ?? 1) === -1) ? -1 : 1,
                // The crop file of the image crop in the media source
                'image' => (string)($size['image'] ?? ''),
            ],
            'targetWidth' => (int)($size['targetWidth'] ?? 0),
            'targetHeight' => (int)($size['targetHeight'] ?? 0),
            'altTag' => $data['altTag'] ?? '',
            'caption' => $data['caption'] ?? '',
            'credits' => $data['credits'] ?? '',
        ], JSON_UNESCAPED_SLASHES);
    }

    /**
     * Prepare a value and return a valid JSON encoded Image+ object
     *
     * The Image+ object contains the image path, the image crops and the alt/caption/credits text:
     * {"image": "", "baseUrl": "", "sizes": {"<key>": {"x": 0, "y": 0, "cropWidth": 0, "cropHeight": 0, ...}}, "altTag": "", "caption": "", "credits": ""}
     * An image path and an Image+ object before 3.0 is converted to that format. The crop is used as image crop
     * with the key in the crop option. The media source is not part of the value, the media source of the template
     * variable is used.
     *
     * @param string $json JSON value to prepare
     * @param array $opts
     * @param modTemplateVar $tv
     * @return string
     */
    public function prepareTvValue($json, $opts = [], $tv = null)
    {
        if ($json == '') {
            return '';
        }
        $key = !empty($opts['crop']) ? $opts['crop'] : 'crop';
        $data = json_decode($json, true);
        if (!is_array($data)) {
            // The variable does not contain an Image+ image object and has to be converted to an Image+ image object
            $source = $this->getTvSource($tv, $opts['docid'] ?? 0, $opts['source'] ?? 0);
            if (!$source) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Invalid Media Source', '', 'Image+');
                return '';
            }
            $source->setRequestProperties($_REQUEST);
            $source->initialize();

            $imgPath = $source->getBasePath() . $json;
            if (file_exists($imgPath)) {
                // The crop refers to the upright image, like the crops of the crop window
                $size = ImageOrientation::getImageSize($imgPath);
            } else {
                if ($this->getOption('debug')) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'The template variabe value does not contain an existing image', '', 'Image+');
                }
                $size = false;
            }
            $data = [
                'image' => $json,
                'sizes' => ($size) ? [
                    $key => $this->getCropSize(0, 0, $size[0], $size[1], $size[0], $size[1], $opts['targetWidth'] ?? 0, $opts['targetHeight'] ?? 0)
                ] : [],
            ];
        } elseif (isset($data['sourceImg'])) {
            // The variable contains an Image+ image object before 3.0
            $sourceImg = (array)$data['sourceImg'];
            $crop = (isset($data['crop'])) ? (array)$data['crop'] : [];
            $data = [
                'image' => $sourceImg['src'] ?? '',
                'sizes' => (!empty($crop['width']) && !empty($crop['height'])) ? [
                    $key => $this->getCropSize($crop['x'] ?? 0, $crop['y'] ?? 0, $crop['width'], $crop['height'], $sourceImg['width'] ?? 0, $sourceImg['height'] ?? 0, $data['targetWidth'] ?? 0, $data['targetHeight'] ?? 0)
                ] : [],
                'altTag' => $data['altTag'] ?? '',
                'caption' => $data['caption'] ?? '',
                'credits' => $data['credits'] ?? '',
            ];
        }
        if (empty($data['image'])) {
            return '';
        }
        unset($data['source']);
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get the media source of a template variable or the default media source
     *
     * The media source of the template variable depends on the context of the resource. Without a resource id, the
     * context of the current resource is used. Without a template variable (i.e. for a MIGX value), the media source
     * with the source id or the default media source is used.
     *
     * @param modTemplateVar|null $tv
     * @param int $docid The id of the resource, that contains the template variable value
     * @param int $sourceId The id of the media source, that is used without a template variable
     * @return modMediaSource|null
     */
    public function getTvSource($tv = null, $docid = 0, $sourceId = 0)
    {
        if ($tv) {
            return $tv->getSource($this->getResourceContextKey($docid));
        }
        return $this->modx->getObject('sources.modMediaSource', (int)$sourceId ?: $this->modx->getOption('default_media_source'));
    }

    /**
     * Get the ids of the media sources of all Image+ template variables in all contexts
     *
     * @return int[]
     */
    public function getTvSourceIds()
    {
        $c = $this->modx->newQuery('modTemplateVar', ['type' => 'imageplus']);
        $c->select('id');
        $tvIds = ($c->prepare() && $c->stmt->execute()) ? $c->stmt->fetchAll(PDO::FETCH_COLUMN) : [];
        if (!$tvIds) {
            return [];
        }
        $c = $this->modx->newQuery('sources.modMediaSourceElement', [
            'object:IN' => $tvIds,
            'object_class:IN' => ['modTemplateVar', 'MODX\\Revolution\\modTemplateVar'],
        ]);
        $c->select('source');
        $sourceIds = ($c->prepare() && $c->stmt->execute()) ? $c->stmt->fetchAll(PDO::FETCH_COLUMN) : [];
        return array_values(array_unique(array_map('intval', $sourceIds)));
    }

    /**
     * Get the context key of a resource, the context key of the current resource or mgr
     *
     * @param int $docid
     * @return string
     */
    private function getResourceContextKey($docid = 0)
    {
        $docid = (int)$docid;
        if ($this->modx->resource && (!$docid || (int)$this->modx->resource->get('id') === $docid)) {
            return $this->modx->resource->get('context_key');
        }
        if ($docid) {
            if (!isset($this->resourceContextKeys[$docid])) {
                $c = $this->modx->newQuery('modResource', ['id' => $docid]);
                $c->select('context_key');
                $this->resourceContextKeys[$docid] = (string)$this->modx->getValue($c->prepare());
            }
            if ($this->resourceContextKeys[$docid] !== '') {
                return $this->resourceContextKeys[$docid];
            }
        }
        return 'mgr';
    }

    /**
     * Get the data of an image crop
     *
     * @param int $x
     * @param int $y
     * @param int $cropWidth
     * @param int $cropHeight
     * @param int $originalWidth
     * @param int $originalHeight
     * @param int $targetWidth
     * @param int $targetHeight
     * @return array
     */
    private function getCropSize($x, $y, $cropWidth, $cropHeight, $originalWidth, $originalHeight, $targetWidth, $targetHeight)
    {
        return [
            'x' => (int)$x,
            'y' => (int)$y,
            'cropWidth' => (int)$cropWidth,
            'cropHeight' => (int)$cropHeight,
            'originalWidth' => (int)$originalWidth,
            'originalHeight' => (int)$originalHeight,
            'targetWidth' => (int)$targetWidth ?: null,
            'targetHeight' => (int)$targetHeight ?: null,
            'scaleX' => 1,
            'scaleY' => 1,
        ];
    }

    /**
     * Get the image crop configuration, that is used without the image crops option: one image crop without constraints
     *
     * @return array
     */
    public function getDefaultCropSizes()
    {
        return ['crop' => ['name' => $this->modx->lexicon('imageplus.cropimage.crop')]];
    }

    /**
     * Update the urls of the image and its crops in a value with the urls of the media source. The urls are stored in
     * the value, but they are outdated after a site move or a changed media source url.
     *
     * @param array $data The decoded value
     * @param modMediaSource $source The initialized media source of the template variable
     * @return array
     */
    public function updateValueUrls(array $data, $source)
    {
        if (!empty($data['image'])) {
            $data['baseUrl'] = $this->getSourceObjectUrl($source, $data['image']);
        }
        if (isset($data['sizes']) && is_array($data['sizes'])) {
            foreach ($data['sizes'] as $key => $size) {
                if (is_array($size) && !empty($size['image'])) {
                    $data['sizes'][$key]['baseUrl'] = $this->getSourceObjectUrl($source, $size['image']);
                }
            }
        }
        return $data;
    }

    /**
     * Get the url of an object in a media source, that is usable in the manager
     *
     * @param modMediaSource $source
     * @param string $path
     * @return string
     */
    public function getSourceObjectUrl($source, $path)
    {
        $url = $source->getObjectUrl($path);
        if (!preg_match('#^(https?:)?//#', $url) && strpos($url, '/') !== 0) {
            $url = $this->modx->getOption('base_url') . $url;
        }
        return $url;
    }

    /**
     * Replace the placeholders in an upload/crop path
     *
     * @param string $path
     * @param int $resourceId
     * @param int $tvId
     * @return string
     */
    public function parsePathPlaceholders($path, $resourceId = 0, $tvId = 0)
    {
        $user = $this->modx->getUser();
        $placeholders = [
            '[[+year]]' => date('Y'),
            '[[+month]]' => date('m'),
            '[[+day]]' => date('d'),
            '[[+user]]' => $user ? $user->get('id') : 0,
            '[[+username]]' => $user ? $user->get('username') : '',
            '[[+resource_id]]' => (int)$resourceId,
            '[[+tv_id]]' => (int)$tvId,
        ];
        return str_replace(array_keys($placeholders), array_values($placeholders), (string)$path);
    }
}

define('imageplus', true);
