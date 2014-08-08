<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog_model extends MY_Model {
	public $_db_table = 'blog';

	public function __construct(){
		if (!$this->cache->get('table_cache__'.$this->_db_table)){
			$this->_create_table();
		}

		parent::__construct($this->_db_table);

		$this->_fields['entry_id']['_Alias'] = 'ID';

		$this->_fields['title']['_Alias'] = 'Cím';

		$this->_fields['short_content']['_Alias'] = 'Bevezető';
		$this->_fields['short_content']['_List'] = false;

		$this->_fields['content']['Type'] = '_markdown';
		$this->_fields['content']['_Alias'] = 'Tartalom';
		$this->_fields['content']['_On_list'] = false;
		//$this->_fields['content']['_Function'] = 'blog_field';
		$this->_fields['content']['_Description'] = '<a href="javascript:void(0);" class="markdown_full">Teljes képernyő</a>';

		$this->_fields['date']['_Alias'] = 'Dátum';

		$this->_fields['url']['_Alias'] = 'URL';
		$this->_fields['url']['_Description'] = 'blog/';

		if (block_is_exists('tag')){
			$result = $this->db->get('tags')->result_array();
			foreach ($result as $option)
				$options[$option['tag_id']] = $option['name'];
		}

		$this->_fields['tags']['_Alias'] = 'Tagek';
		$this->_fields['tags']['Type'] = '_multiselect';
		$this->_fields['tags']['_Select_options'] = $options;
		$this->_fields['tags']['_On_list'] = false;
		$this->_fields['tags']['_Block_dependencity'] = block_is_exists('tag');

		$this->_fields['seo_title']['_Alias'] = 'SEO cím';

		$this->_fields['seo_description']['_Alias'] = 'SEO leírás';

		$this->_fields['active']['_Alias'] = 'Aktív';
		$this->_fields['active']['Type'] = '_checkbox';
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `entry_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(256) NOT NULL,
			  `short_content` text NOT NULL,
			  `content` text NOT NULL,
			  `date` date NOT NULL,
			  `url` varchar(64) NOT NULL,
			  `seo_title` varchar(60) NOT NULL,
			  `seo_description` varchar(150) NOT NULL,
			  `active` tinyint(4) NOT NULL,
			  PRIMARY KEY (`entry_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}
/*
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
*/
}