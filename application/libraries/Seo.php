<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo{
	protected $CI;
	protected $title = '';
	protected $description = '';

	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->model('seo_model');

		$this->set_by_db();
	}

	public function set_by_db(){
		if ($this->title == '' && $this->description == ''){
			$db_uris = $this->CI->db->get($this->CI->seo_model->_db_table)->result_array();
			$actual_uri = preg_replace('/[0-9]+/', '[num]', uri_string());

			$result = false;

			foreach ($db_uris as $db_uri){
				$uri = '~^'.str_replace('/[any]', '(.*)', $db_uri['url_pattern']).'$~';
				$uri = str_replace('[num]', '\[num\]', $uri);

				if (preg_match($uri, $actual_uri))
					$result = $db_uri;
			}

			$this->title = $result['title'];
			$this->description = $result['description'];
		}
	}

	public function set_title($title){
		$this->title = $title;
	}

	public function set_description($description){
		$this->description = $description;
	}

	public function display_title(){
		return $this->title;
	}
	
	public function display_description(){
		return $this->description;
	}
}
