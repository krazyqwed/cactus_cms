<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_model extends MY_Model {
	public $_db_table = 'images';

	public function __construct(){
		parent::__construct($this->_db_table);
		
		parent::_post_actions();
	}
}
