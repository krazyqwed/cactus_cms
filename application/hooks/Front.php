<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Front_hook{
	function res(){
		$CI =& get_instance();

		$CI->load->config('front');

		if ($CI->router->fetch_class() != 'admin' && $CI->router->fetch_module() != "" && strpos('_', $CI->router->fetch_module()) != 0){
			$CI->front->add_script($CI->config->item('main_default_scripts'));
			$CI->front->add_style($CI->config->item('main_default_styles'));
		}else{
			$CI->front->add_script($CI->config->item('admin_default_scripts'));
			$CI->front->add_style($CI->config->item('admin_default_styles'));
		}
	}
}