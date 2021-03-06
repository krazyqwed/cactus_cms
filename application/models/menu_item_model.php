<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_item_model extends MY_Model {
	public $_db_table = 'menu_items';
	public $_db_table_lang = true;

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['menu_item_id']['_Alias'] = 'ID';
		$this->_fields['menu_item_id']['_On_list'] = false;

		$this->_fields['menu_id']['_Alias'] = 'Menü ID';
		$this->_fields['menu_id']['_On_list'] = false;
		$this->_fields['menu_id']['_Editable'] = false;

		$this->_fields['parent_id']['_Alias'] = 'Szülő';
		$this->_fields['parent_id']['_On_list'] = false;
		$this->_fields['parent_id']['_Editable'] = false;

		$this->_fields['url']['_Alias'] = 'URL';
		$this->_fields['url']['_Description'] = '! -> Nem linkel<br/>%= -> külső oldalra mutató URL';

		$this->_fields['weight']['_Alias'] = 'Súly';
		$this->_fields['weight']['_On_list'] = false;
		$this->_fields['weight']['Type'] = '_spinner';
	
		$this->_fields['label']['_Alias'] = 'Felirat';

		$this->_fields['active']['_Alias'] = 'Aktív';
		$this->_fields['active']['_On_list'] = false;
		$this->_fields['active']['Type'] = '_checkbox';

		$this->_fields['description']['_Alias'] = 'Leírás';
		
		parent::_post_actions();
	}
}
