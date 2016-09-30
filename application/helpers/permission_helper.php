<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function permission_check($type, $tc, $fa){
	$CI =& get_instance();

	$permission = false;

	// Check if permission is exists, if it's not, then add to DB
	$permission_id = permission_get_id($type, $tc, $fa);

	// Check if role has the permission
	$permission_role = permission_role($permission_id);

	// When role failed, check if user has the permission
	$permission_user = permission_user($permission_id);

	if ($permission_user !== null){
		$permission = $permission_user;
	}elseif ($permission_role !== null){
		$permission = $permission_role;
	}else{
		$permission_row = permission_get_by_id($permission_id);
		$permission = (bool)$permission_row['enabled'];
	}

	return $permission;
}

function permission_get_id($type, $tc, $fa){
	$CI =& get_instance();

	if ($type == 'field'){
		$permission = $CI->db->get_where('permissions', array(
			'table' => $tc,
			'field' => $fa
		))->row_array();
	}else{
		$permission = $CI->db->get_where('permissions', array(
			'controller' => $tc,
			'action' => $fa
		))->row_array();
	}

	if (!$permission){
		$CI->db->insert('permissions', array(
			'table' => $type == 'field' ? $tc : '',
			'field' => $type == 'field' ? $fa : '',
			'controller' => $type == 'action' ? $tc : '',
			'action' => $type == 'action' ? $fa : ''
		));

		$permission = array('permission_id' => $CI->db->insert_id()); 
	}

	return $permission['permission_id'];
}


function permission_get_by_id($id){
	$CI =& get_instance();

	$permission = $CI->db->get_where('permissions', array(
		'permission_id' => $id
	))->row_array();

	return $permission;
}

function permission_role($permission_id, $role_id = null){
	$CI =& get_instance();

	$_user = $CI->session->userdata('user');

	if ($role_id === null) $role_id = $_user->role_id;

	$permission = $CI->db->get_where('role_permissions', array(
		'role_id' => $role_id,
		'permission_id' => $permission_id
	))->row_array();

	if ($permission){
		if ($permission['enabled'] == 1) return true;
		else return false;
	}else{
		return null;
	}
}

function permission_user($permission_id, $user_id = null){
	$CI =& get_instance();

	$_user = $CI->session->userdata('user');

	if ($user_id === null) $user_id = $_user->user_id;

	$permission = $CI->db->get_where('user_permissions', array(
		'user_id' => $user_id,
		'permission_id' => $permission_id
	))->row_array();

	if ($permission){
		if ($permission['enabled'] == 1) return true;
		else return false;
	}else{
		return null;
	}
}
