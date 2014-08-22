<?php
class User_setting_model extends MY_Model{
	public $_db_table = 'user_settings';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['user_setting_id']['_On_edit'] = false;

		$this->_fields['user_id']['_On_edit'] = false;

		$this->_fields['full_name']['_Alias'] = 'Teljes név';

		$this->_fields['email']['_Alias'] = 'Email cím';

		$this->_fields['profile_image']['_Alias'] = 'Profil kép';
		$this->_fields['profile_image']['Type'] = '_image';
		$this->_fields['profile_image']['_Image_size'] = array(150, 150);
		$this->_fields['profile_image']['_Image_manual_crop'] = true;
		$this->_fields['profile_image']['_Description'] = 'Ezt a képet látja a többi felhasználó';

		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `user_settings` (
			  `user_setting_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(10) unsigned NOT NULL,
			  `full_name` varchar(128) NOT NULL,
			  `email` varchar(128) NOT NULL,
			  `profile_image` text NOT NULL,
			  PRIMARY KEY (`user_setting_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}