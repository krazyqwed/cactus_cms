<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function layout_order_parts($parts){
	$new_parts = array();

	foreach ($parts as $part)
		$new_parts[$part['position']][] = $part;

	return $new_parts;
}

function layout_render_parts($position, $parts){
	if (isset($parts[$position])){
		$parts = $parts[$position];

		$weight = null;

		foreach ($parts as $key => $part) {
			if (part_check_visibility($part)){
				if (isset($part['weight']))
					$weight[$key] = $part['weight'];
				else
					$weight[$key] = 9999;
			}else{
				unset($parts[$key]);
			}
		}
		
		if (is_array($parts) && is_array($weight))
			array_multisort($weight, SORT_ASC, $parts);

		foreach ($parts as $part){
			echo part_render($part);
		}
	}
}

function layout_parts_table_display($layout_id){
	$CI =& get_instance();

	$CI->load->model('layout_part_model');
	$parts_list = $CI->db->where('layout_id', $layout_id)->get($CI->layout_part_model->_db_table)->result_array();

	$data = array(
		'layout_id' => $layout_id,
		'parts_list' => $parts_list,
		'db_fields' => $CI->layout_part_model->_fields,
		'db_primary' => $CI->layout_part_model->_primary
	);

	$view = $CI->load->view('admin/layout/layout_part_table', $data, true);

	return $view;
}

function layout_get_active(){
	$CI =& get_instance();

	$model = $CI->load->model('layout_override_model');
	$layout_model = $CI->load->model('layout_model');

	$actual_uri = preg_replace('/\/[0-9]+/', '[num]', uri_string());

	$overrides = $CI->db->get($model->_db_table)->result_array();
	$overrides = part_get_lang_table($overrides, null, $model);

	foreach ($overrides as $override){
		$uri = '~^'.str_replace('/[any]', '(.*)', $override['url_pattern']).'$~';
		$uri = str_replace('[num]', '\[num\]', $uri);

		if (preg_match($uri, $actual_uri))
			return $CI->db->get_where($layout_model->_db_table, array('layout_id' => $override['layout_id']))->row_array();
	}

	return $CI->db->get_where($layout_model->_db_table, array('folder' => 'main'))->row_array();
}