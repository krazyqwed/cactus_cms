<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends MY_Model {
	public $_db_table = 'files';

	public function __construct(){
		parent::__construct($this->_db_table);
		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `filename` varchar(64) NOT NULL,
			  `ext` varchar(5) NOT NULL,
			  `mime` varchar(32) NOT NULL,
			  `filesize` int(11) NOT NULL,
			  `visible_name` varchar(128) NOT NULL,
			  PRIMARY KEY (`file_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}