<?php
class User_model extends MY_Model{
	public $_db_table = 'users';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['user_id']['_Alias'] = 'ID';

		$this->_fields['username']['_Alias'] = 'Felhasználónév';

		$this->_fields['password']['_Alias'] = 'Jelszó';
		$this->_fields['password']['_On_list'] = false;
		
		$query = $this->db->get('roles')->result_array();

		$roles = array();
		foreach ($query as $row){
			$roles[$row['role_id']] = $row['name'];
		}

		$this->_fields['role_id']['Type'] = '_select';
		$this->_fields['role_id']['_Alias'] = 'Szerepkör';
		$this->_fields['role_id']['_Select_options'] = $roles;

		$this->_fields['remember_token']['_Alias'] = 'Cookie token';
		$this->_fields['remember_token']['_On_list'] = false;
		$this->_fields['remember_token']['_Editable'] = false;

		$this->_fields['last_login']['_Alias'] = 'Utolsó bejelentkezés';
		$this->_fields['last_login']['_Editable'] = false;

		$this->_fields['_settings']['Field'] = 'user_settings';
		$this->_fields['_settings']['_Alias'] = 'Felhasználó beállításai';
		$this->_fields['_settings']['_Function'] = 'user_field_settings';
		$this->_fields['_settings']['_On_new'] = false;

		parent::_post_actions();
	}

	public function user_field_settings($content, $field){
		$user_settings = $this->db->get_where('user_settings', array('user_id' => $content['user_id']))->row_array();

		return '<a class="btn btn-success" href="'.site_url('admin/user_settings/edit/'.$user_settings['user_setting_id']).'">Beállítások</a>';
	}

	public function get_user($user_id){
		$query = $this->db->get_where('users', array(
			'id' => $user_id
		));

		if($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}

	public function _create_table(){
		
	}
}