<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_model extends MY_Model {
	public $_db_table = 'roles';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['role_id']['_Alias'] = 'ID';

		$this->_fields['key']['_Alias'] = 'Azonosító';

		$this->_fields['name']['_Alias'] = 'Név';

		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$_db_table."` (
			  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `key` varchar(32) NOT NULL,
			  `name` varchar(64) NOT NULL,
			  PRIMARY KEY (`role_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}