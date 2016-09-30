<?php
class User_setting_model extends MY_Model{
	public $_db_table = 'user_settings';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['user_setting_id']['_On_edit'] = false;

		$this->_fields['user_id']['_On_edit'] = false;

		$this->_fields['full_name']['_Alias'] = 'Teljes név';

		$this->_fields['email']['_Alias'] = 'Email cím';
		
		$this->_fields['profile_image']['Type'] = '_image';
		$this->_fields['profile_image']['_Alias'] = 'Profil kép';
		$this->_fields['profile_image']['_Image_ratio'] = image_ratio(150, 150);
		$this->_fields['profile_image']['_Image_manual_crop'] = true;
		$this->_fields['profile_image']['_Description'] = 'Ezt a képet látja a többi felhasználó';

		$this->_fields['lockscreen_enable']['Type'] = '_checkbox';
		$this->_fields['lockscreen_enable']['_Alias'] = 'Automatikus lezárás';

		$this->_fields['lockscreen_timeout']['Type'] = '_spinner';
		$this->_fields['lockscreen_timeout']['_Alias'] = 'Automatikus lezárás ideje';
		$this->_fields['lockscreen_timeout']['_Description'] = 'Percekben kifejezve';

		$this->_fields['lockscreen_image']['Type'] = '_image';
		$this->_fields['lockscreen_image']['_Alias'] = 'Automatikus lezárás háttere';
		$this->_fields['lockscreen_image']['_Description'] = 'Érdemes minimum a monitorod felbontásának megfelelő mértű képet feltölteni';
		$this->_fields['lockscreen_image']['_Image_ratio'] = image_ratio(1920, 1080);
		$this->_fields['lockscreen_image']['_Image_manual_crop'] = false;

		parent::_post_actions();
	}
}
