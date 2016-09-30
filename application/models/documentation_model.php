<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Documentation_model extends MY_Model {
	public $_db_table = 'documentation';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['entry_id']['_Alias'] = 'ID';

		$this->_fields['title']['_Alias'] = 'Cím';

		$this->_fields['content']['Type'] = '_markdown';
		$this->_fields['content']['_Alias'] = 'Tartalom';
		$this->_fields['content']['_On_list'] = false;
		$this->_fields['content']['_Function'] = 'blog_field';
		$this->_fields['content']['_Description'] = '<a href="javascript:void(0);" class="markdown_full">Teljes képernyő</a>';

		$this->_fields['date']['_Alias'] = 'Dátum';

		$this->_fields['active']['_Alias'] = 'Aktív';
		$this->_fields['active']['Type'] = '_checkbox';

		parent::_post_actions();
	}

	public function blog_field($content, $field){
		$data = array(
			'field_name' => $field,
			'content' => isset($content['content'])?$content['content']:'',
			'db_fields' => $this->_fields,
			'db_primary' => $this->_primary
		);

		$data['data'] = $data;

		$this->load->view('blog/admin/blog_field', $data);
	}
}
