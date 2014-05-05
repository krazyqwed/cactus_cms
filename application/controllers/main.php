<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MX_Controller {
	private $parts = null;
	private $active_layout = null;

	public function __construct(){
		parent::__construct();

		/* Alapértelmezett nyelv */
		if (!$this->config->item('multi_language_enabled')){
			$this->load->model('system_setting_model');
			$settings = $this->db->where('system_setting_id', 1)->get($this->system_setting_model->_db_table)->row_array();
			$this->session->set_userdata('krazy_language', $settings['site_default_language']);
		}else{
			if (!$this->session->userdata('krazy_language'))
				$this->session->set_userdata('krazy_language', $this->config->item('default_language'));
		}

		$this->load->model('layout_model');
		$model = $this->load->model('layout_part_model');

		$this->active_layout = layout_get_active();

		$layout = $this->db->select('layout_id')->get_where($this->layout_model->_db_table, array( 'layout_id' => $this->active_layout['layout_id'] ))->row_array();
		$parts = $this->db->get_where($model->_db_table, array( 'layout_id' => $layout['layout_id'] ))->result_array();
		$parts = part_get_lang_table($parts, null, $model);

		$this->parts = layout_order_parts($parts);

		$this->front->add_script($this->config->item('main_default_scripts'));
		$this->front->add_style($this->config->item('main_default_styles'));
	}

	public function index(){
		$settings_model = $this->load->model('setting_model');
		$settings = $this->db->get($this->setting_model->_db_table)->row_array();
		
		$data['settings'] = $settings;
		$data['settings'] = part_get_lang_table($data['settings'], 1, $settings_model);

		$data['parts'] = $this->parts;

		$this->load->view($this->active_layout['folder'].'/_layout', $data);
	}

	public function language($lang){
		$this->session->set_userdata('krazy_language', $lang);

		if ($this->config->item('multi_language_enabled'))
			$this->index();
		else
			$this->page_404();
	}

	public function page_404(){
		$this->load->model('setting_model');
		$settings = $this->db->get($this->setting_model->_db_table)->row_array();
		$data['settings'] = $settings;
		
		$data['parts'] = $this->parts;
		$data['parts']['content'][] = array('view' => 'main/page_404');
		$this->load->view('main/_layout', $data);
	}
}