<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout_position_model extends MY_Model {
	public $_db_table = 'layout_positions';

	public function __construct(){
		parent::__construct($this->_db_table);
		parent::_post_actions();
	}
}
