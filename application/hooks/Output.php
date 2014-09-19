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

		$system_settings = $CI->db->get('system_settings')->row_array();

		if (!$CI->input->is_ajax_request()){
			if ($system_settings['minify_css'] == 1){
				$styles = $CI->front->display_styles(true);

				$file_path = $CI->front->handle_cache($styles, 'css');

				$link = '<link rel="stylesheet" type="text/css" href="'.base_url($file_path).'" />';
				$output = str_replace('{display_styles}', $link, $output);
			}else{
				$output = str_replace('{display_styles}', $CI->front->display_styles(), $output);
			}

			$base_string = '<script type="text/javascript">base_url = "'.base_url().'";</script>';

			if ($system_settings['minify_js'] == 1){
				$scripts = $CI->front->display_scripts(true);

				$file_path = $CI->front->handle_cache($scripts, 'js');

				$base_string .= '<script type="text/javascript" src="'.base_url($file_path).'"></script>';
				$output = str_replace('{display_scripts}', $base_string, $output);
			}else{
				$base_string .= $CI->front->display_scripts();
				$output = str_replace('{display_scripts}', $base_string, $output);
			}
		}

		$CI->output->set_output($output);
	}

	function display(){
		$CI =& get_instance();
		$CI->output->_display();
	}
}