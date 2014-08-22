<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_setting_model extends MY_Model {
	public $_db_table = 'system_settings';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['system_setting_id']['_On_edit'] = false;

		if (count($this->config->item('languages')) <= 1)
			$this->_fields['multi_language_enabled']['_On_edit'] = false;

		$this->_fields['multi_language_enabled']['Type'] = '_checkbox';
		$this->_fields['multi_language_enabled']['_Alias'] = 'Többnyelvűség';
		$this->_fields['multi_language_enabled']['_Description'] = 'A használható nyelvek listája a "config/language.php" fájlban található';

		$this->_fields['site_default_language']['Type'] = '_select';
		$this->_fields['site_default_language']['_Select_options'] = $this->config->item('languages');
		$this->_fields['site_default_language']['_Alias'] = 'Oldal alapértelmezett nyelve';
		$this->_fields['site_default_language']['_Description'] = 'Csak akkor érvényes, ha ki van kapcsolva a többnyelvűség';
	
		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `system_setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `multi_language_enabled` tinyint(1) NOT NULL,
			  `site_default_language` varchar(2) NOT NULL,
			  PRIMARY KEY (`system_setting_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}