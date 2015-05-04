<?php
/**
 * Copyright 2013 by Alan Pich <alan.pich@gmail.com>
 *
 * This file is part of ImagePlus
 *
 * ImagePlus is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * ImagePlus is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ImagePlus; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package imageplus
 * @author Alan Pich <alan.pich@gmail.com>
 * @copyright Alan Pich 2013
 */

class ImagePlusInputRender extends modTemplateVarInputRender {
    
    /* @var ImagePlus $helper */
    private $helper;
    
    public function getTemplate() {
    	  return dirname(__FILE__).'/tpl/imageplus.inputrender.tpl';
    }//
    
    
    public function getLexiconTopics(){
        return array('imageplus:default');
    }//
    
    
    public function process($value,array $params = array()) {
        $this->modx->lexicon->load('imageplus:default');
        
        // Load helper class
        if(!class_exists('ImagePlus')){
            require $this->modx->getOption('imageplus.core_path',null,$this->modx->getOption('core_path').'components/imageplus/').'imageplus.class.php'; };
        $this->helper = new ImagePlus($this->modx);

        // Load required javascripts & register global config
        $this->helper->includeScriptAssets();

        // Prepare tv config for jsonification
        $tvConfig = $this->helper->loadTvConfig($this,$value,$params);
        $this->setPlaceholder('imageplusconfig',json_encode($tvConfig));
        $this->setPlaceholder('tvValue',$value);


        $this->setPlaceholder('mediasource',$this->tv->get('source'));
        $this->setPlaceholder('tvparams',json_encode($this->getInputOptions()));

        $this->setPlaceholder('imgData',$this->getImageDataJSON($value,$params));

        $this->setPlaceholder('config',json_encode($this->helper->config));
    	
    }
    
    
private function getImageDataJSON($value,$params){
		$I = json_decode($value);
		$Opts = $this->getInputOptions();
		
		$data = new stdClass;
		
		// Grab MediaSource info
		
        $source = $this->modx->getObject('modMediaSource', $this->tv->get('source'));
        $properties = $source->getProperties();
        $data->mediasource = new stdClass;
        $data->mediasource->id = $source->get('id');
        $data->mediasource->path = (isset($properties['basePath'])) ? $properties['basePath']['value'] : $this->modx->getOption('base_path');
        $data->mediasource->url = (isset($properties['baseUrl'])) ? $properties['baseUrl']['value'] : $this->modx->getOption('base_url');
		// Grab constraint info
		$data->constraint = new stdClass;
		$data->constraint->width =  empty($params['targetWidth']) ? 0 : (int) $params['targetWidth'];
		$data->constraint->height = empty($params['targetHeight'])? 0 : (int) $params['targetHeight'];
		
		// Generate ratio value
		if( $data->constraint->width >0 && $data->constraint->height >0 ){
			// If both width/height constraints set, use that for ratio calc
			$data->constraint->ratio = $data->constraint->width/$data->constraint->height;
		} else 
		if( isset($I->source->width) && isset($I->source->height) ){
			// Use source image size for ratio
			$data->constraint->ratio = $I->source->width / $I->source->height;
		} else {
			// Fail safe (and square)
			$data->constraint->ratio = false;
		};
		
		// Grab source image info (if it exists yet)
		if( isset($I->source) ){
			$data->source = new stdClass;
			$data->source->height = $I->source->height;
			$data->source->width = $I->source->width;
			$data->source->path = $I->source->path;
			$data->source->filename = $I->source->filename;
			$data->source->size = $I->source->size;
		} else {
			$data->source = false;
		};
		
		// Grab crop params (if they exist yet)
		if( isset($I->crop)){
			$data->crop = new stdClass;
			$data->crop->x = $I->crop->x;
			$data->crop->y = $I->crop->y;
			$data->crop->width = $I->crop->width;
			$data->crop->height = $I->crop->height;
		};
			
		return json_encode($data);
    }//

}
return 'ImagePlusInputRender';
