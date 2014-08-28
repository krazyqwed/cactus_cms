<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends MX_Controller {
	public $_user;

	protected $_public_actions = array(
		'login',
		'registration'
	);

	public function __construct(){
		parent::__construct();

		if ($this->is_admin()){
			$this->_user = $this->session->userdata('user');

			/* Store current url if not lockscreen */
			if ($this->router->fetch_method() != 'lockscreen' && !$this->input->is_ajax_request()){
				if ($this->session->userdata('lockscreen') && $this->router->fetch_method() != 'logout'){
					redirect('admin/lockscreen');
				}

				$url = site_url($this->uri->uri_string());
	    		$url = $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;

				$this->session->set_userdata('last_page_url', $url);
			}

			$this->load->helper('permission_helper');

			if (!$this->session->userdata('user') && !in_array($this->uri->segment(2), $this->_public_actions)){
				if ($this->login_check_stored_session()){
					redirect('admin/lockscreen');
				}else{
					$data['v'] = 'admin/login';
					$this->load->view('admin/_layout', $data);

					$hook =& load_class('Hooks', 'core');
					$hook->_call_hook('display_override');
					exit();
				}
			}elseif ($this->session->userdata('user') && in_array($this->uri->segment(2), $this->_public_actions)){
				redirect('admin');
			}
		}
	}

	private function login_check_stored_session(){
		if ($this->input->cookie('cactus_remember_token')){
			$remember_token = $this->input->cookie('cactus_remember_token');

			$data = $this->db->join('user_settings', 'user_settings.user_id = users.user_id', 'inner')->get_where('users', array( 'remember_token' => $remember_token ));

			if ($data->num_rows() > 0){
				$this->session->set_userdata('user', $data->row());

				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	private function is_admin(){
		$uri = explode('/', $this->uri->uri_string());
		
		return $uri[0] == $this->config->item('admin_path');
	}

	protected function _action($module, $model, $action, $content_id = null, $options = null){
		/* Set permissions for actions */
		permission_check('action', $module, '_list');
		permission_check('action', $module, '_edit');
		permission_check('action', $module, '_save');
		permission_check('action', $module, '_delete');

		if (is_array($content_id)){
			$content = $this->db->get_where($model->_db_table, array($content_id[0] => $content_id[1]))->row_array();
			$content_id = $content[$model->_primary];
		}

		if (is_null($action))
			$this->_list($module, $model, $options);
		elseif ($action === 'edit')
			$this->_edit($module, $model, $content_id, $options);
		elseif ($action === 'save' && $this->input->is_ajax_request())
			$this->_save($module, $model, $content_id, $options);
		elseif ($action === 'delete')
			$this->_delete($module, $model, $content_id, $options);
	}

	private function _list($module, $model, $options = null){
		if (!permission_check('action', $module, '_list')) redirect('admin');

		$contents = $this->db->get($model->_db_table)->result_array();

		$data = array(
			'contents' => $contents,
			'v' => 'admin/_list',
			'title' => 'Lista',
			'db_table' => $model->_db_table,
			'db_fields' => $model->_fields,
			'db_primary' => $model->_primary,
			'db_table_lang' => isset($model->_db_table_lang) ? $model->_db_table_lang : false,
			'module' => $module,
			'options' => $options
		);

		$this->front->add_style('res/js/admin/datatables/jquery.dataTables.min.css');
		$this->front->add_script(array(
			'res/js/admin/datatables/jquery.dataTables.min.js',
			'res/js/admin/datatables/dataTables.bootstrap.min.js'
		));

		$this->load->view('admin/_layout', $data);
	}

	private function _edit($module, $model, $content_id = null, $options = null){
		if (!permission_check('action', $module, '_edit')) redirect('admin');

		$this->front->autoload_by_field($model->_fields);

		$uri_lang = end($this->uri->segment_array());

		if (array_key_exists($uri_lang, $this->config->item('languages'))){
			$content = $this->db->where($model->_primary, $content_id)->get($model->_db_table)->row_array();
			$content_lang = $this->db->where($model->_primary, $content_id)->where('lang', $uri_lang)->get($model->_db_table.'_lang')->row_array();

			if (empty($content_lang)){
				$model->save_lang($content_id, array($model->_primary => $content_id, 'lang' => $uri_lang));
				$content_lang = $this->db->where($model->_primary, $content_id)->where('lang', $uri_lang)->get($model->_db_table.'_lang')->row_array();
			}

			$content = array_merge($content, $content_lang);

			function array_unique_diff ($array1, $array2){
				return array_merge(array_diff_key($array1, $array2), array_diff_key($array2, $array1));
			}

			$diffs = array_unique_diff($content, $content_lang);

			foreach ($diffs as $key => $diff)
				$model->_fields[$key]['_On_edit'] = false;
		}else{
			$content = $this->db->where($model->_primary, $content_id)->get($model->_db_table)->row_array();
			
			foreach ($content as $key => $col){
				if ($model->_fields[$key]['Type'] == '_image'){
					if ($col != ''){
						$content[$key] = $this->db->get_where('images', 'image_id IN ('.implode(',', explode('|', $col)).')')->result_array();
					}
				}

				if ($model->_fields[$key]['Type'] == '_file'){
					if ($col != ''){
						$content[$key] = $this->db->get_where('files', 'file_id IN ('.implode(',', explode('|', $col)).')')->result_array();
					}
				}
			}
		}

		$data = array(
			'content' => $content,
			'v' => 'admin/_edit',
			'title' => 'Tartalom szerkesztése',
			'db_table' => $model->_db_table,
			'db_fields' => $model->_fields,
			'db_primary' => $model->_primary,
			'db_table_lang' => isset($model->_db_table_lang) ? $model->_db_table_lang : false,
			'module' => $module,
			'model' => $model,
			'options' => $options
		);

		if (is_null($content_id))
			$data['title'] = 'Új tartalom';

		$this->load->view('admin/_layout', $data);
	}

	private function _save($module, $model, $content_id = null, $options = null){
		if (!permission_check('action', $module, '_save')){
			$json = array(
				'success' => 0,
				'message' => '<strong>Nincs megfelelő jogosultsága</strong>',
				'message_color' => 'red'
			);

			$this->load->json($json); exit();
		}

		function preg_array_key_exists($pattern, $array){
			$keys = array_keys($array);    
			return (int) preg_grep($pattern,$keys);
		}

		$uri_lang = end($this->uri->segment_array());

		if (array_key_exists($uri_lang, $this->config->item('languages')))
			$id = $model->save_lang($content_id, null, $uri_lang);
		else
			$id = $model->save($content_id);

		$data = $this->input->post();

		$json = array(
			'success' => 1,
			'message' => '<strong>Sikeres mentés</strong>'
		);

		foreach ($model->_fields as $key => $value){
			if (isset($data[$key]) && $value['Type'] == '_image' && preg_array_key_exists('/^new/' ,$data[$key])){
				$json['refresh'] = true;
			}

			if (isset($data[$key]) && $value['Type'] == '_file' && preg_array_key_exists('/^new/' ,$data[$key])){
				$json['refresh'] = true;
			}
		}

		if (is_null($content_id))
			$json['reload_url'] = site_url('admin/'.$module.'/edit/'.$id);

		$this->load->json($json);

		if (!isset($options['no_exit']) || (isset($options['no_exit']) && !$options['no_exit']))
			exit();
	}

	private function _delete($module, $model, $content_id = null, $options = null){
		if (!permission_check('action', $module, '_delete')) redirect('admin');

		$id = $model->delete($content_id);
		
		if (isset($options['override_back_url']))
			redirect(site_url('admin/'.$options['override_back_url']));

		redirect(site_url('admin/'.$module));
	}
}