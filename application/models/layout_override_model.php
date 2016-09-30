<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout_override_model extends MY_Model {
	public $_db_table = 'layout_overrides';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['layout_override_id']['_Alias'] = 'ID';

		$layouts = $this->db->get('layouts')->result_array();
		$select_layout = array();

		foreach ($layouts as $layout)
			$select_layout[$layout['layout_id']] = $layout['name'];

		$this->_fields['layout_id']['_Alias'] = 'Elrendezés';
		$this->_fields['layout_id']['Type'] = '_select';
		$this->_fields['layout_id']['_Override_list_values'] = $select_layout;
		$this->_fields['layout_id']['_Select_options'] = $select_layout;

		$this->_fields['url_pattern']['_Alias'] = 'URL';

		parent::_post_actions();
	}
}
