<?php
/**
 * Image+ Input Render
 *
 * @package imageplus
 * @subpackage input_render
 */

class ImagePlusInputRender extends modTemplateVarInputRender
{
    /**
     * Return the template path to load
     *
     * @return string
     */
    public function getTemplate()
    {
        $corePath = $this->modx->getOption('imageplus.core_path', null, $this->modx->getOption('core_path') . 'components/imageplus/');
        return $corePath . 'elements/tv/input/tpl/imageplus.render.tpl';
    }

    /**
     * Get lexicon topics
     *
     * @return array
     */
    public function getLexiconTopics()
    {
        return ['imageplus:default'];
    }

    /**
     * Process Input Render
     *
     * @param string $value
     * @param array $params
     * @return void
     */
    public function process($value, array $params = [])
    {
        $corePath = $this->modx->getOption('imageplus.core_path', null, $this->modx->getOption('core_path') . 'components/imageplus/');
        /** @var ImagePlus $imageplus */
        $imageplus = $this->modx->getService('imageplus', 'ImagePlus', $corePath . 'model/imageplus/', [
            'core_path' => $corePath
        ]);
        $imageplus->includeScriptAssets();

        // Get Media Source
        /** @var modMediaSource $source */
        $source = $this->tv->getSource(($this->modx->resource) ? $this->modx->resource->get('context_key') : 'mgr');
        if (!$source || !$source->getWorkingContext()) {
            return;
        }
        $source->setRequestProperties($_REQUEST);
        $source->initialize();

        // For migxResourceMediaPath snippet
        $this->setPlaceholder('mediasource', $source->get('id'));

        $context = (isset ($_GET['wctx']) && $_GET['wctx']) ? $_GET['wctx'] : '';
        $obj = $this->modx->getContext($context);
        $contextSettings = ($obj) ? $obj->config : [];
        $contextSettings = array_merge($this->modx->config, $contextSettings);
        $tvName = $this->tv->get('name');

        // Prepare TV config
        $tvConfig = new stdClass();
        $allowBlank = $params['allowBlank'] ?? true;
        $tvConfig->allowBlank = !($allowBlank === 'false' || $allowBlank === 0 || $allowBlank === false);
        $tvConfig->minWidth = (int)($params['minWidth'] ?? 0);
        $tvConfig->minHeight = (int)($params['minHeight'] ?? 0);
        $tvConfig->thumbnailHeight = (int)$this->getTVConfig('thumbnail_height', $tvName, $contextSettings, (!empty($params['thumbnailHeight'])) ? $params['thumbnailHeight'] : null) ?: 250;
        $tvConfig->inlinePreview = !(isset($params['inlinePreview']) && in_array($params['inlinePreview'], ['false', '0', 0, false], true));
        $tvConfig->altTagOn = (bool)$this->getTVConfig('allow_alt_tag', $tvName, $contextSettings, $params['allowAltTag'] ?? null);
        $tvConfig->captionOn = (bool)$this->getTVConfig('allow_caption', $tvName, $contextSettings, $params['allowCaption'] ?? null);
        $tvConfig->creditsOn = (bool)$this->getTVConfig('allow_credits', $tvName, $contextSettings, $params['allowCredits'] ?? null);
        // The image crops are set by the crop sizes option
        $sizes = json_decode($params['sizes'] ?? '', true);
        if (!is_array($sizes) || empty($sizes)) {
            $sizes = $imageplus->getDefaultCropSizes();
        }
        $tvConfig->sizes = $sizes;
        $tvConfig->mediaSource = $source->get('id');
        $tvConfig->tvId = $this->tv->get('id');
        $tvConfig->tvParams = $this->getInputOptions();

        $this->setPlaceholder('tvConfig', json_encode($tvConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG));

        // Prepare value, a converted crop is used for the first image crop
        $value = $imageplus->prepareTvValue($this->tv->value, array_merge($params, [
            'crop' => array_keys($sizes)[0]
        ]), $this->tv);
        $data = json_decode($value, true);
        if (is_array($data)) {
            // The stored urls are replaced, because they are outdated after a site move
            $value = json_encode($imageplus->updateValueUrls($data, $source), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        $this->tv->value = $value;
    }

    /**
     * @param string $key
     * @param string $name
     * @param array $config
     * @param string $default
     * @return string
     */
    private function getTVConfig($key, $name, $config, $default)
    {
        if (is_null($default)) {
            // Use $config if defined and not empty
            $value = $this->modx->getOption('imageplus.' . $key, $config, $default, true);

            // TV name based
            if ($name) {
                // Use $config if defined and not empty
                $value = $this->modx->getOption('imageplus.' . $name . '.' . $key, $config, $value, true);
            }
        } else {
            $value = $default;
        }

        return $value;
    }
}

return 'ImagePlusInputRender';
