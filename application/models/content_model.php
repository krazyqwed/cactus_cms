<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_model extends MY_Model {
	public $_db_table = 'contents';
	public $_db_table_lang = true;

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['content_id']['_Alias'] = 'ID';

		$this->_fields['title']['_Alias'] = 'Cím';

		$this->_fields['content']['_Alias'] = 'Tartalom';
		$this->_fields['content']['Type'] = '_wysiwyg';
		$this->_fields['content']['_On_list'] = false;

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
}