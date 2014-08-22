<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo_model extends MY_Model {
	public $_db_table = 'seo';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['seo_id']['_Alias'] = 'ID';

		$this->_fields['url_pattern']['_Alias'] = 'URL Minta';
		$this->_fields['url_pattern']['_Description'] = '[num] -> Bármilyen szám<br/>[any] -> Bármi, utána nem állhat semmi';
	
		$this->_fields['title']['_Alias'] = 'Cím';
		$this->_fields['title']['_Description'] = 'Title tag';

		$this->_fields['description']['_Alias'] = 'Leírás';
		$this->_fields['description']['_Description'] = 'Meta tag';

		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `seo_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `url_pattern` varchar(128) NOT NULL,
			  `title` varchar(60) NOT NULL,
			  `description` varchar(150) NOT NULL,
			  PRIMARY KEY (`seo_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		");
	}
}