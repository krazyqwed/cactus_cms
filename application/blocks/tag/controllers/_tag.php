<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _Tag extends MY_Controller {
	private $_config = array(
		'_in_menu' => true,
		'_in_menu_path' => 'tags',
		'_in_menu_name' => 'Címkék',
		'_in_menu_icon' => 'tag'
	);

	public function __construct(){
		parent::__construct();

		$this->front->add_script($this->config->item('admin_default_scripts'));
		$this->front->add_style($this->config->item('admin_default_styles'));
	}

	public function index($action = null, $id = null){
		$this->load->model('tag/tag_model');

		$this->_action($this->_config['_in_menu_path'], $this->tag_model, $action, $id);
	}
}