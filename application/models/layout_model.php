<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout_model extends MY_Model {
	public $_db_table = 'layouts';

	public function __construct(){
		parent::__construct($this->_db_table);
		
		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `layout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `folder` varchar(64) NOT NULL,
			  `name` varchar(64) NOT NULL,
			  `description` text NOT NULL,
			  PRIMARY KEY (`layout_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}