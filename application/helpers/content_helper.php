<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function content_display($part_id){
	$CI =& get_instance();
	$model = $CI->load->model('content_model');

	$result = $CI->db->get_where($CI->content_model->_db_table, array($CI->content_model->_primary => $part_id))->row_array();

	$result = part_get_lang_table($result, $part_id, $model);

	echo $result['content'];
}