<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends MY_Model {
	public $_db_table = 'settings';
	public $_db_table_lang = true;

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['setting_id']['_On_edit'] = false;

		$this->_fields['slogen']['_Alias'] = 'Szlogen';

		$this->_fields['footer']['_Alias'] = 'Lábléc';
		$this->_fields['footer']['_Description'] = 'Lábléc szöveg';

		$this->_fields['banner_image']['Type'] = '_image';
		$this->_fields['banner_image']['_Image_ratio'] = image_ratio(700, 250);
		$this->_fields['banner_image']['_Image_multiple'] = true;
		$this->_fields['banner_image']['_Image_manual_crop'] = true;
		$this->_fields['banner_image']['_Alias'] = 'Banner kép';
		$this->_fields['banner_image']['_Description'] = 'Több kép feltöltése';

		$this->_fields['logo_image']['Type'] = '_image';
		$this->_fields['logo_image']['_Image_ratio'] = image_ratio(200, 100);
		$this->_fields['logo_image']['_Image_manual_crop'] = true;
		$this->_fields['logo_image']['_Alias'] = 'Logo kép';
		$this->_fields['logo_image']['_Description'] = 'Egyedi logo feltöltése';

		$this->_fields['file_sandbox']['Type'] = '_file';
		$this->_fields['file_sandbox']['_File_multiple'] = true;
		$this->_fields['file_sandbox']['_Alias'] = 'Fájl teszt';
		$this->_fields['file_sandbox']['_Description'] = 'Fájlok feltöltése';

		$this->_fields['content_image']['Type'] = '_elfinder';
		$this->_fields['content_image']['_Image_ratio'] = image_ratio(200, 150);
		$this->_fields['content_image']['_Alias'] = 'Tartalom kép';
		$this->_fields['content_image']['_Description'] = 'Egyedi kép kiválasztása a médiatárból';

		parent::_post_actions();
	}
}
