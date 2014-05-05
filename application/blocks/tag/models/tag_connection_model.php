<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag_connection_model extends MY_Model {
	public $_db_table = 'tag_connections';

	public function __construct(){
		parent::__construct($this->_db_table);
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$_db_table."` (
			  `tag_connection_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `tag_id` int(10) unsigned NOT NULL,
			  `table` varchar(64) NOT NULL,
			  `key` int(64) NOT NULL,
			  PRIMARY KEY (`tag_connection_id`),
			  KEY `tag_id` (`tag_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	}
}