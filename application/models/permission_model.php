<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission_model extends MY_Model {
	public $_db_table = 'permissions';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['permission_id']['_Alias'] = 'ID';

		$this->_fields['table']['_Alias'] = 'Table';
		$this->_fields['table']['_Editable'] = false;

		$this->_fields['field']['_Alias'] = 'Field';
		$this->_fields['field']['_Editable'] = false;

		$this->_fields['controller']['_Alias'] = 'Controller';
		$this->_fields['controller']['_Editable'] = false;

		$this->_fields['action']['_Alias'] = 'Action';
		$this->_fields['action']['_Editable'] = false;

		$this->_fields['enabled']['Type'] = '_checkbox';
		$this->_fields['enabled']['_Alias'] = 'Engedélyezve';

		parent::_post_actions();
	}
}
