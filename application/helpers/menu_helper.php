<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function menu_display($part_id, $admin = false, $echo = true){
	$CI =& get_instance();
	$menu_model = $CI->load->model('menu_model');
	$model = $CI->load->model('menu_item_model');

	$menu = $CI->db->get_where($CI->menu_model->_db_table , array('menu_id' => $part_id))->row_array();

	$CI->db->select('*')->from($CI->menu_item_model->_db_table);

	if (!$admin)
		$CI->db->where(array('menu_id' => $part_id, 'active' => 1));
	else
		$CI->db->where(array('menu_id' => $part_id));

	$result = $CI->db->order_by('parent_id ASC, weight ASC')->get()->result_array();

	if (!$admin)
		$result = part_get_lang_table($result, null, $model);

	$menu = array(
		'template' => $menu['template'],
		'css_class' => $menu['css_class'],
		'items' => array(),
		'parents' => array()
	);

	foreach ($result as $items){
		$menu['items'][$items['menu_item_id']] = $items;
		$menu['parents'][$items['parent_id']][] = $items['menu_item_id'];
	}

	if ($echo){
		if ($admin)
			echo menu_build_admin(0, $menu);
		else
			echo menu_build(0, $menu);
	}else{
		if ($admin)
			return menu_build_admin(0, $menu);
		else
			return menu_build(0, $menu);
	}
}

function menu_build($parent, $menu){
	if ($menu['template'] == '')
		$template = '<li rel="{rel_url}">{{main:<a href="{full_url}"><span>{label}</span></a>}}{end:</li>}';
	else
		$template = $menu['template'];

	$html = '';

	if (isset($menu['parents'][$parent])){
		$html .= '<ul'.($menu['css_class'] != '' ? ' class="'.$menu['css_class'].'"' : '').'>';

		foreach ($menu['parents'][$parent] as $item_id){
			if(!isset($menu['parents'][$item_id])){
				if (preg_match('/^%=/', $menu['items'][$item_id]['url']) != 0){
					$html .= preg_replace(array('/\{\{main:(.*)\}\}/', '/\{rel_url\}/', '/\{full_url\}/', '/\{label\}/', '/\{end:(.*)\}/'), array('$1', '', preg_replace('/^%=/', '', $menu['items'][$item_id]['url']), $menu['items'][$item_id]['label'], '$1'), $template);
				}else{
					$html .= preg_replace(array('/\{\{main:(.*)\}\}/', '/\{rel_url\}/', '/\{full_url\}/', '/\{label\}/', '/\{end:(.*)\}/'), array('$1', $menu['items'][$item_id]['url'], site_url($menu['items'][$item_id]['url']), $menu['items'][$item_id]['label'], '$1'), $template);
				}
			}else{
				if (preg_match('/^%=/', $menu['items'][$item_id]['url']) != 0){
					$html .= preg_replace(array('/\{\{main:(.*)\}\}/', '/\{rel_url\}/', '/\{full_url\}/', '/\{label\}/', '/\{end:(.*)\}/'), array('$1', '', preg_replace('/^%=/', '', $menu['items'][$item_id]['url']), $menu['items'][$item_id]['label'], '$1'), $template);
				}elseif (($menu['items'][$item_id]['url'] != "" && $menu['items'][$item_id]['url'] != "!") || $menu['items'][$item_id]['url'] != "!"){
					$html .= preg_replace(array('/\{\{main:(.*)\}\}/', '/\{rel_url\}/', '/\{full_url\}/', '/\{label\}/', '/\{end:(.*)\}/'), array('$1', $menu['items'][$item_id]['url'], site_url($menu['items'][$item_id]['url']), $menu['items'][$item_id]['label'], '$1'), $template);
				}else{
					$html .= preg_replace(array('/\{\{main:(.*)\}\}/', '/\{rel_url\}/', '/\{full_url\}/', '/\{label\}/', '/\{end:(.*)\}/'), array('', '', 'javascript:void(0)', $menu['items'][$item_id]['label'], '$1'), $template);
				}
				
				$html .= menu_build($item_id, $menu).'</li>';
			}
		}
		
		$html .= "</ul>";
	}

	return $html;
}

function menu_build_admin($parent, $menu){
	$html = "";

	if (isset($menu['parents'][$parent])){
		$html .= '<ul>';

		foreach ($menu['parents'][$parent] as $item_id){
			$html .= '<li rel="item-'.$menu['items'][$item_id]['menu_item_id'].'" data-active="'.($menu['items'][$item_id]['active'] == 1 ? '1': '0').'"><div class="btn-group"><div class="btn btn-default btn-sm">'.$menu['items'][$item_id]['label'].'</div><div class="btn btn-default btn-sm js-remove"><i class="fa fa-times"></i></div><div class="btn btn-'.($menu['items'][$item_id]['active'] == 1 ? 'success': 'danger').' btn-sm js-active"><i class="fa fa-eye'.($menu['items'][$item_id]['active'] == 1 ? '': '-slash').'"></i></div></div>';
			$html .= menu_build_admin($item_id, $menu);
			$html .= '</li>';
		}
		
		$html .= "</ul>";
	}

	if ($html == '')
		$html = '<ul></ul>';

	return $html;
}

function menu_items_table_display($menu_id){
	$CI =& get_instance();

	$CI->load->model('menu_item_model');
	$menu_items = $CI->db->where('menu_id', $menu_id)->get($CI->menu_item_model->_db_table)->result_array();

	$data = array(
		'menu_id' => $menu_id,
		'menu_items' => $menu_items,
		'db_fields' => $CI->menu_item_model->_fields,
		'db_primary' => $CI->menu_item_model->_primary
	);

	$view = $CI->load->view('admin/menus/menu_item_table', $data, true);

	return $view;
}