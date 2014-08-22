<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Route_model extends MY_Model {
	public $_db_table = 'routes';

	public function __construct(){
		parent::__construct($this->_db_table);
		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `route_id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `slug` varchar(64) NOT NULL,
			  `controller` varchar(64) NOT NULL,
			  PRIMARY KEY (`route_id`),
			  KEY `slug` (`slug`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."_lang` (
			  `route_id` bigint(20) NOT NULL,
			  `lang` varchar(2) NOT NULL,
			  `slug` varchar(64) NOT NULL,
			  `controller` varchar(64) NOT NULL,
			  KEY `slug` (`slug`),
			  KEY `route_id` (`route_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	}
}