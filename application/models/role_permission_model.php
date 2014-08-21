<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_permission_model extends MY_Model {
	public $_db_table = 'role_permissions';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['role_permission_id']['_Alias'] = 'ID';
		$this->_fields['role_permission_id']['_On_edit'] = false;

		$query = $this->db->get('roles')->result_array();

		$roles = array();
		foreach ($query as $row){
			$roles[$row['role_id']] = $row['name'];
		}

		$this->_fields['role_id']['_Editable'] = false;
		$this->_fields['role_id']['_Override_list_values'] = $roles;
		$this->_fields['role_id']['_Alias'] = 'Szerepkör ID';

		$this->_fields['permission_id']['_On_new'] = false;
		$this->_fields['permission_id']['_On_edit'] = false;

		$this->_fields['enabled']['_On_new'] = false;
		$this->_fields['enabled']['_On_edit'] = false;

		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$_db_table."` (
			  `role_permission_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `role_id` int(10) unsigned NOT NULL,
			  `permission_id` int(10) unsigned NOT NULL,
			  `enabled` tinyint(1) NOT NULL,
			  PRIMARY KEY (`role_permission_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}