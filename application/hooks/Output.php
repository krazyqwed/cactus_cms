<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Output{
	function seo(){
		$CI =& get_instance();

		$output = $CI->output->get_output();

		$output = str_replace('{seo_title}', $CI->seo->display_title(), $output);
		$output = str_replace('{seo_description}', $CI->seo->display_description(), $output);

		$CI->output->set_output($output);
	}

	function display(){
		$CI =& get_instance();
		$CI->output->_display();
	}
}