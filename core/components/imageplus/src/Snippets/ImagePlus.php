<?php
/**
 * ImagePlus Snippet as alternative to Image+ TV Output Type
 *
 * @package imageplus
 * @subpackage snippet
 */

namespace TreehillStudio\ImagePlus\Snippets;

use modTemplateVar;
use xPDO;

class ImagePlus extends Snippet
{
    /**
     * Get default snippet properties.
     *
     * @return array
     */
    public function getDefaultProperties()
    {
        return [
            'value' => '',
            'tvname' => '',
            'source::int' => 0,
            'docid::int' => (isset($this->modx->resource)) ? $this->modx->resource->get('id') : 0,
            'options' => '',
            'format' => '',
            'tpl::allowEmpty' => 'ImagePlus.image',
            'debug::bool' => $this->imageplus->getOption('debug')
        ];
    }

    /**
     * Execute the snippet and return the result.
     *
     * @return string
     */
    public function execute()
    {
        $value = $this->getProperty('value');
        $tvname = $this->getProperty('tvname');
        $docid = $this->getProperty('docid');
        $options = $this->getProperty('options');
        $tpl = $this->getProperty('tpl');
        $debug = $this->getProperty('debug');
        $source = $this->getProperty('source');

        if ($value) {
            // Value is set by snippet property
            $data = json_decode($value);
            if (!$data) {
                if ($debug) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Unable to decode JSON in snippet property', '', 'Image+');
                    return 'Unable to decode JSON in snippet property';
                }
            }
            // No TV is used, the media source is set by the snippet property or by the value before Image+ 3.0
            if (!$source && isset($data->sourceImg->source)) {
                $source = (int)$data->sourceImg->source;
            }
            $tv = null;
            $tvOutputProperties = [];
        } else {
            // Value is retreived from template variable
            /** @var modTemplateVar $tv */
            $tv = $this->modx->getObject('modTemplateVar', ['name' => $tvname]);
            if ($tv) {
                // Get the raw content of the TV
                $value = $tv->getValue($docid);
                $value = $tv->processBindings($value, $docid);
                $tvOutputProperties = (array)$tv->get('output_properties');
                foreach ($tvOutputProperties as &$tvOutputProperty) {
                    switch ($tvOutputProperty) {
                        case 'true' :
                            $tvOutputProperty = true;
                            break;
                        case 'false' :
                            $tvOutputProperty = false;
                            break;
                    }
                }
            } else {
                if ($debug) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Template Variable '$tvname' not found.", '', 'Image+');
                    return "Template Variable '$tvname' not found.";
                }
                $tvOutputProperties = [];
            }
        }

        // Render the template chunk or return the url of the image crop file without a template chunk
        return ($value) ? $this->imageplus->getImageURL($value, array_merge($tvOutputProperties, $this->getProperties(), [
            'docid' => $docid,
            'source' => $source,
            'phpThumbParams' => $options,
            'outputChunk' => $tpl,
        ]), $tv) : '';
    }
}
