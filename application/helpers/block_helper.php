<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function block_display($part){
	$CI =& get_instance();
	$CI->load->model('block_model');

	$result = $CI->db->get_where($CI->block_model->_db_table, array('block_id' => $part['part_id']))->row_array();

	$params = array($result['block_folder'].'/'.$result['method']);

	if ($part['url'] != '*'){
		$part_url = str_replace('/[any]', '', $part['url']);
		$part_url = preg_replace('~'.$part_url.'~', '', uri_string(), 1);
		$part_url = preg_replace('~^/~', '', $part_url);

		if ($part_url != '')
			$params = array_merge($params, explode('/', $part_url));
	}

	echo call_user_func_array(array(new Modules, 'run'), $params);
}

function block_get_config($block_folder = null){
	if ($block_folder !== null)
		$blocks = glob(APPPATH . 'blocks/'.$block_folder);
	else
		$blocks = glob(APPPATH . 'blocks/*');

	$blocks_array = array();

	if ($blocks){
		foreach($blocks as $block){
			$block = basename($block, EXT);

			foreach(glob(APPPATH.'blocks/'.$block.'/controllers/_'.$block.EXT) as $controller){
				include_once(APPPATH.'blocks/'.$block.'/controllers/_'.$block.EXT);

				$rClass = new ReflectionClass('_'.$block);
				$all_vars = $rClass->getDefaultProperties();
				$vars = array();

				foreach ($all_vars as $key => $value){
					if ($key == '_config'){
						$vars = $value;
					}
				}

				if ($block_folder !== null)
					$blocks_array = $vars;
				else
					$blocks_array[$block] = $vars;
			}
		}
	}

	return $blocks_array;
}

function block_is_exists($block_folder){
	$block = glob(APPPATH . 'blocks/'.$block_folder);

	return !empty($block);
}