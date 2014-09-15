<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_model extends MY_Model {
	public $_db_table = 'images';

	public function __construct(){
		parent::__construct($this->_db_table);
		
		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `filename` varchar(64) NOT NULL,
			  `ext` varchar(5) NOT NULL,
			  `filesize` int(11) NOT NULL,
			  `visible_name` varchar(128) NOT NULL,
			  `width` int(11) NOT NULL,
			  `height` int(11) NOT NULL,
			  `cropped` tinyint(1) NOT NULL,
			  `crop_x1` int(11) NOT NULL,
			  `crop_y1` int(11) NOT NULL,
			  `crop_x2` int(11) NOT NULL,
			  `crop_y2` int(11) NOT NULL,
			  PRIMARY KEY (`image_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}