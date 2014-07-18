<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _Blog extends MY_Controller {
	private $_config = array(
		'_in_menu' => true,
		'_in_menu_path' => 'blog',
		'_in_menu_name' => 'Blog bejegyzések',
		'_in_menu_icon' => 'book'
	);

	public function __construct(){
		parent::__construct();
	}

	public function index($action = null, $id = null){
		$this->load->model('blog/blog_model');
		$model = $this->blog_model;

		if ($action == 'edit'){
			$model->load_field_libs();
		}

		$this->_action($this->_config['_in_menu_path'], $model, $action, $id);
	}
}