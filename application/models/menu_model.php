<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends MY_Model {
	public $_db_table = 'menus';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['menu_id']['_Alias'] = 'ID';

		$this->_fields['css_class']['_Alias'] = 'CSS osztály';

		$this->_fields['template']['_Alias'] = 'Sablon';
		$this->_fields['template']['_Description'] = '{rel_url}, {full_url}, {label}, {sub:</tag>}';
		$this->_fields['template']['_On_list'] = false;

		$this->_fields['name']['_Alias'] = 'Név';

		$this->_fields['description']['_Alias'] = 'Leírás';

		$this->_fields['_sortable']['Field'] = 'menu_items';
		$this->_fields['_sortable']['_Description'] = 'Menüpontok felvitele/kezelése';
		$this->_fields['_sortable']['_Alias'] = 'Menüpontok';
		$this->_fields['_sortable']['_Function'] = 'menu_item_list';
		$this->_fields['_sortable']['_On_new'] = false;

		parent::_post_actions();
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `css_class` varchar(128) NOT NULL,
			  `template` text NOT NULL,
			  `name` varchar(64) NOT NULL,
			  `description` text NOT NULL,
			  PRIMARY KEY (`menu_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");
	}

	public function menu_item_list($content, $field){
		$this->load->model('menu_item_model');
		
		$menu_items = $this->db->where('menu_id', $content['menu_id'])->get($this->menu_item_model->_db_table)->result_array();

		$data = array(
			'menu_id' => $content['menu_id'],
			'menu_items' => $menu_items,
			'db_fields' => $this->menu_item_model->_fields,
			'db_primary' => $this->menu_item_model->_primary
		);

		$data['data'] = $data;

		$this->front->add_script(array(
			'res/js/admin/sortable/jquery.mjs.nestedSortable.js',
			'res/js/admin/menu.js'
		));

		$this->load->view('admin/menus/menu_item_list', $data);
	}
}