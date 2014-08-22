<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout_part_model extends MY_Model {
	public $_db_table = 'layout_parts';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['layout_part_id']['_Alias'] = 'ID';

		$this->_fields['layout_id']['_Alias'] = 'Elrendezés ID';
		$this->_fields['layout_id']['_On_list'] = false;
		$this->_fields['layout_id']['_Editable'] = false;

		$this->_fields['position']['_Alias'] = 'Pozíció';
		$this->_fields['position']['Type'] = '_select';
		$this->_fields['position']['_Select_inner_relation'] = array(
			'inner_field' => 'layout_id',
			'relation_table' => 'layout_positions',
			'relation_field' => 'layout_id'
		);

		$this->_fields['url']['_Alias'] = 'URL';

		$this->_fields['css_id']['_Alias'] = 'CSS ID';

		$this->_fields['css_class']['_Alias'] = 'CSS osztály';

		$this->_fields['part_type']['_Alias'] = 'Részecske típus';
		$this->_fields['part_type']['_Editable'] = false;

		$this->_fields['part_id']['_Alias'] = 'Részecske ID';
		$this->_fields['part_id']['_Editable'] = false;

		$this->_fields['weight']['_Alias'] = 'Súly';
		$this->_fields['weight']['Type'] = '_spinner';

		$this->_fields['name']['_Alias'] = 'Név';

		$this->_fields['description']['_Alias'] = 'Leírás';

		$this->_fields['active']['_Alias'] = 'Aktív';
		$this->_fields['active']['Type'] = '_checkbox';

		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `layout_part_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `layout_id` int(10) unsigned NOT NULL,
			  `position` varchar(64) NOT NULL,
			  `url` varchar(64) NOT NULL,
			  `css_id` varchar(64) NOT NULL,
			  `css_class` varchar(128) NOT NULL,
			  `part_type` varchar(64) NOT NULL,
			  `part_id` int(10) unsigned NOT NULL,
			  `weight` int(10) unsigned NOT NULL,
			  `name` varchar(64) NOT NULL,
			  `description` text NOT NULL,
			  `active` int(11) NOT NULL,
			  PRIMARY KEY (`layout_part_id`),
			  KEY `layout` (`layout_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}