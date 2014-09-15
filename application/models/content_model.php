<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_model extends MY_Model {
	public $_db_table = 'contents';
	public $_db_table_lang = true;

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['content_id']['_Alias'] = 'ID';

		$this->_fields['title']['_Alias'] = 'Cím';

		$this->_fields['content_type']['_On_edit'] = false;
		$this->_fields['content_type']['_On_list'] = false;

		$file_list = glob(APPPATH.'/views/**/__*.php');

		foreach ($file_list as $key => $f) {
			$file_list[$key] = str_replace(APPPATH.'/views/', '', $f);
		}

		$this->_fields['content']['_Alias'] = 'Tartalom';
		$this->_fields['content']['Type'] = '_variable';
		$this->_fields['content']['_On_list'] = false;
		$this->_fields['content']['_Description'] = '<a href="javascript:void(0);" class="show-on-tr-markdown markdown_full">Teljes képernyő</a>';
		$this->_fields['content']['_Content_type_name'] = 'content_type';
		$this->_fields['content']['_Select_options'] = array('WYSIWYG', 'Markdown', 'Nyers', 'Fájl');
		$this->_fields['content']['_File_list'] = $file_list;

		$this->_fields['name']['_Alias'] = 'Név';
		$this->_fields['name']['_Description'] = 'Ez a név jelenik meg az elrendezésnél';

		$options = array();

		if (block_is_exists('category')){
			$result = $this->db->get('categories')->result_array();
			foreach ($result as $option)
				$options[$option['category_id']] = $option['name'];
		}

		$this->_fields['categories']['_Alias'] = 'Kategóriák';
		$this->_fields['categories']['Type'] = '_multiselect';
		$this->_fields['categories']['_Select_options'] = $options;
		$this->_fields['categories']['_On_list'] = false;
		$this->_fields['categories']['_Block_dependencity'] = block_is_exists('category');

		$this->_fields['description']['_Alias'] = 'Leírás';

		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `content_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Tartalom ID',
			  `title` varchar(128) NOT NULL,
			  `content_type` tinyint(4) NOT NULL,
			  `content` text NOT NULL,
			  `name` varchar(64) NOT NULL,
			  `description` text NOT NULL,
			  `categories` text NOT NULL,
			  PRIMARY KEY (`content_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
}