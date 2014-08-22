<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout_position_model extends MY_Model {
	public $_db_table = 'layout_positions';

	public function __construct(){
		parent::__construct($this->_db_table);
		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `layout_position_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `layout_id` int(10) unsigned NOT NULL,
			  `key` varchar(32) NOT NULL,
			  `name` varchar(32) NOT NULL,
			  `width` int(11) NOT NULL,
			  PRIMARY KEY (`layout_position_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}