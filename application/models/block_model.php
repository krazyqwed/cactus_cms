<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Block_model extends MY_Model {
	public $_db_table = 'blocks';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['block_id']['_Alias'] = 'ID';

		$this->_fields['block_folder']['_Alias'] = 'Blokk mappa';

		$this->_fields['controller']['_Alias'] = 'Controller';

		$this->_fields['method']['_Alias'] = 'Method';

		$this->_fields['name']['_Alias'] = 'Név';

		$this->_fields['description']['_Alias'] = 'Leírás';

		$blocks = array();

		foreach(glob(APPPATH . 'blocks/*') as $block){
			$block = basename($block, EXT);
			$blocks[$block] = $block;
		}

		$this->_fields['block_folder']['Type'] = '_select';
		$this->_fields['block_folder']['_Select_options'] = $blocks;

		$this->_fields['controller']['Type'] = '_select';
		$this->_fields['controller']['_Select_options'] = null;
		$this->_fields['controller']['_Select_ajax'] = true;
		$this->_fields['controller']['_Select_ajax_method'] = site_url('admin/block_get_controllers');
		$this->_fields['controller']['_Select_ajax_field'] = 'block_folder';

		$this->_fields['method']['Type'] = '_select';
		$this->_fields['method']['_Select_options'] = null;
		$this->_fields['method']['_Select_ajax'] = true;
		$this->_fields['method']['_Select_ajax_method'] = site_url('admin/block_get_methods');
		$this->_fields['method']['_Select_ajax_field'] = 'controller';

		parent::_post_actions();
	}

	public function get_block_controllers($block = null, $return = false){
		if ($block === null){
			$this->_fields['controller']['_Select_disabled'] = true;
		}else{
			$controllers = array();

			foreach(glob(APPPATH.'blocks/'.$block.'/controllers/*'.EXT) as $controller){
				$controller = basename($controller, EXT);
				
				if ($controller[0] != '_'){
					$controllers[$controller] = $controller;
				}
			}

			if ($return)
				return $controllers;
			else
				$this->_fields['controller']['_Select_options'] = $controllers;
		}
	}

	public function get_controller_methods($block, $controller = null, $return = false){
		if ($controller === null){
			$this->_fields['method']['_Select_disabled'] = true;
		}else{
			include_once(APPPATH.'blocks/'.$block.'/controllers/'.$controller.EXT);
			
			$rClass = new ReflectionClass($controller);
			$all_methods = $rClass->getMethods();
			$methods = array();

			foreach ($all_methods as $method){
				if (strtolower($method->class) === $controller && strpos($method->name, '__') === false)
					$methods[$method->name] = $method->name;
			}

			if ($return)
				return $methods;
			else
				$this->_fields['method']['_Select_options'] = $methods;
		}
	}
}