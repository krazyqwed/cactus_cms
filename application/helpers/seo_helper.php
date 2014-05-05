<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function seo_display(){
	$CI =& get_instance();
	$CI->load->model('seo_model');

	$uri = preg_replace('/\/[0-9]+/', '[num]', uri_string());

	$result = $CI->db->select('*')
		->from($CI->seo_model->_db_table)
		->where('url_pattern', $uri)
		->get()
		->row_array();

	var_dump($result);
}