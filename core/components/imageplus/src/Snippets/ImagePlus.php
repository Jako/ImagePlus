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
            'docid::int' => (isset($this->modx->resource)) ? $this->modx->resource->get('id') : 0,
            'type' => '',
            'options' => '',
            'tpl' => 'ImagePlus.image',
            'input::bool' => $this->imageplus->getOption('debug')
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
        $type = $this->getProperty('type');
        $options = $this->getProperty('options');
        $tpl = $this->getProperty('tpl');
        $debug = $this->getProperty('debug');

        if ($value) {
            // Value is set by snippet property
            $data = json_decode($value);
            if (!$data) {
                if ($debug) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Unable to decode JSON in snippet property', '', 'Image+');
                    return 'Unable to decode JSON in snippet property';
                }
            }
            // No TV is used
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
                $tvOutputProperties = $tv->get('output_properties');
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

        // Render output
        switch ($type) {
            case 'check':
                $data = json_decode($value, true);
                $output = ($data && (!empty($data['image']) || !empty($data['sourceImg']['src']))) ? 'image' : 'noimage';
                break;
            case 'tpl':
                $data = json_decode($value);
                $output = ($value) ? $this->imageplus->getImageURL($value, array_merge($tvOutputProperties, $this->getProperties(), [
                    'docid' => $docid,
                    'phpThumbParams' => $options,
                    'outputChunk' => $tpl,
                    'caption' => ($data && isset($data->caption)) ? $data->caption : '',
                    'credits' => ($data && isset($data->credits)) ? $data->credits : ''
                ]), $tv) : '';
                break;
            case 'thumb':
                $output = ($value) ? $this->imageplus->getImageURL($value, array_merge($tvOutputProperties, $this->getProperties(), [
                    'docid' => $docid,
                    'phpThumbParams' => $options,
                    'outputChunk' => '',
                ]), $tv) : '';
                break;
            default:
                $output = ($value) ? $this->imageplus->getImageURL($value, array_merge($tvOutputProperties, $this->getProperties(), [
                    'docid' => $docid,
                    'phpThumbParams' => $options,
                ]), $tv) : '';
                break;
        }
        return $output;
    }
}
