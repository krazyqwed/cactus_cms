<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function part_check_visibility($part){
	$CI =& get_instance();

	if (isset($part['url'])){
		$actual_uri = preg_replace('/\/[0-9]+/', '[num]', uri_string());

		$uri = '~^'.str_replace('/[any]', '(.*)', $part['url']).'$~';
		$uri = str_replace('[num]', '\[num\]', $uri);
	}

	if ((isset($part['view']) || ($part['url'] == '*' || preg_match($uri, $actual_uri)) && $part['active'] == 1))
		return true;

	return false;
}

function part_render($part){
	$CI =& get_instance();

	if (!isset($part['view'])){
		echo '<div id="'.$part['css_id'].'">';
		
		if ($part['part_type'] == 'content')
			content_display($part['part_id']);
		elseif ($part['part_type'] == 'menu')
			menu_display($part['part_id']);
		elseif ($part['part_type'] == 'block')
			block_display($part);
		
		echo '</div>';
	}else{
		$CI->load->view($part['view']);
	}
}

function part_get_lang_table($result, $part_id = null, $model){
	$CI =& get_instance();

	if (isset($model->_db_table_lang) && $model->_db_table_lang){
		if ($CI->session->userdata('krazy_language') != $CI->config->item('default_language')){
			if($CI->db->query("SHOW TABLES LIKE '".$model->_db_table."_lang'")->num_rows() == 1){
				if ($part_id === null){
					$result_lang = $CI->db->where('lang', $CI->session->userdata('krazy_language'))->get($model->_db_table.'_lang')->result_array();

					foreach ($result as $key => $row){
						foreach ($result_lang as $key2 => $row2){
							if ($row[$model->_primary] == $row2[$model->_primary])
								$result[$key] = array_merge($row, $row2);
						}
					}
				}else{
					$result_lang = $CI->db->where($model->_primary, $part_id)->where('lang', $CI->session->userdata('krazy_language'))->get($model->_db_table.'_lang')->row_array();
					$result = array_merge($result, $result_lang);
				}
			}
		}
	}

	return $result;
}