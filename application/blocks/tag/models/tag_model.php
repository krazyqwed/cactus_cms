<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag_model extends MY_Model {
	public $_db_table = 'tags';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['tag_id']['_Alias'] = 'ID';

		$this->_fields['name']['_Alias'] = 'Név';
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` int(11) NOT NULL,
			  PRIMARY KEY (`tag_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	}
}