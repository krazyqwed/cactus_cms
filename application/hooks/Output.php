<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Output_hook{
	function seo(){
		$CI =& get_instance();

		$output = $CI->output->get_output();

		$output = str_replace('{seo_title}', $CI->seo->display_title(), $output);
		$output = str_replace('{seo_description}', $CI->seo->display_description(), $output);
		
		$CI->output->set_output($output);
	}

	function res(){
		$CI =& get_instance();

		$output = $CI->output->get_output();

		if (false){
			$styles = $CI->front->display_styles(true);
			file_put_contents(FCPATH.'res/style.min.css', $CI->front->combine_css($styles));
			$link = '<link rel="stylesheet" type="text/css" href="'.base_url('res/style.min.css').'" />';
			$output = str_replace('{display_styles}', $link, $output);
		
			$scripts = $CI->front->display_scripts(true);
			file_put_contents(FCPATH.'res/script.min.js', $CI->front->combine_js($scripts));
			$link = '<script type="text/javascript" src="'.base_url('res/script.min.js').'"></script>';
			$output = str_replace('{display_scripts}', $link, $output);
		}else{
			$output = str_replace('{display_styles}', $CI->front->display_styles(), $output);
			$output = str_replace('{display_scripts}', $CI->front->display_scripts(), $output);
		}



		$CI->output->set_output($output);
	}

	function display(){
		$CI =& get_instance();
		$CI->output->_display();
	}
}