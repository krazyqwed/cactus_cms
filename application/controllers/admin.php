<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('user_model');
	}

	public function lockscreen(){
		// Set lock
		$this->session->set_userdata('lockscreen', true);

		if ($this->input->post('password')){
			$data = $this->db->get_where('users', array(
				'user_id' => $this->_user->user_id,
				'password' => md5($this->input->post('password'))
			));

			if ($data->num_rows() > 0){
				$this->session->set_userdata('lockscreen', false);
				redirect($this->session->userdata('last_page_url'));
			}else{
				$this->session->set_userdata('lockscreen_error', true);
			}
		}

		$this->front->add_script('res/js/admin/lockscreen.js');

		$this->load->view('admin/lockscreen');
	}

	public function login(){
		if ($this->input->post('username')){
			$username = $this->input->post('username');
			$password = $this->input->post('password');
		}

		$data = $this->db->join('user_settings', 'users.user_id = user_settings.user_id', 'inner')->get_where('users', array(
			'username' => $username,
			'password' => md5($password)
		));

		if ($data->num_rows() > 0){
			$this->session->set_userdata('user', $data->row());

			if ($this->input->post('remember')){
				$random = md5($username.date('Y-m-d H:i:s').$password.rand(1, 10000));

				$cookie = array(
					'name'   => 'cactus_remember_token',
					'value'  => $random,
					'expire' => '1209600', //Two weeks
					'domain' => '',
					'path'   => '/'
				);

				$this->input->set_cookie($cookie);

				$this->db->where('username', $username)->update('users', array(
					'remember_token' => $random,
					'last_login' => date('Y-m-d H:i:s')
				));
			}
		}else{
			$this->session->set_flashdata('user_failed_login', true);
		}

		redirect('admin');
	}

	public function logout(){
		$cookie = array(
			'name'   => 'cactus_remember_token',
			'value'  => '',
			'expire' => '0',
			'domain' => '',
			'path'   => '/'
		);

		$this->input->set_cookie($cookie);

		$this->db->update('users', array(
			'remember_token' => ''
		));

		$this->session->sess_destroy();

		redirect('admin');
	}

	public function registration(){
		$post = $this->input->post();

		if ($post){
			if (strlen($post['username']) && strlen($post['password']) && strlen($post['password2']) && $post['password'] == $post['password2']){
				$exists = (boolean)$this->db->get_where('users', array('username' => $post['username']))->num_rows();

				if (!$exists){
					$this->db->insert('users', array(
						'username' => $post['username'],
						'password' => md5($post['password']),
						'role_id' => 2
					));

					$this->session->set_flashdata('registration_successful', true);

					redirect('admin');
				}else{
					$data['v'] = 'admin/registration';
					$data['post'] = $post;
				
					$data['errors']['username'] = 'A felhasználónév már foglalt';
				}
			}else{
				$data['v'] = 'admin/registration';
				$data['post'] = $post;
				
				if (!strlen($post['username'])) $data['errors']['username'] = 'Nincs megadva a felhasználónév';
				if (!strlen($post['password'])) $data['errors']['password'] = 'Nincs megadva a jelszó';
				if (strlen($post['password']) && !strlen($post['password2'])) $data['errors']['password2'] = 'Nincs megadva a jelszó';
				if ($post['password'] != $post['password2']) $data['errors']['password2'] = 'A jelszavak nem egyeznek';
			}
		}else{
			$data['v'] = 'admin/registration';
		}

		$this->load->view('admin/_layout', $data);
	}

	public function index(){
		$data['v'] = 'admin/index';

		$data['change_note'] = file_get_contents('cactus_notes.txt');

		$session_files = glob(session_save_path()."/sess_*");
		$session_count = 0;

		foreach ($session_files as $key => $value) {
			if (filemtime($value) > strtotime('-5 minutes')) {
				$session_count++;
			}
		}

		$data['active_users'] = $session_count;

		$this->load->view('admin/_layout', $data);
	}

	public function users($action = null, $id = null){
		$this->load->model('user_model');
		$model = $this->user_model;

		$this->_action('users', $model, $action, $id);
	}

	public function permissions($action = null, $id = null){
		$this->load->model('permission_model');
		$model = $this->permission_model;

		$this->_action('permissions', $model, $action, $id, array(
			'disable_add' => true,
			'top_notification' => 'Figyelem! Az egyes bejegyzések a különböző admin oldalak meglátogatásával, maguktól kerlülnek ide!'
		));
	}

	public function roles($action = null, $id = null){
		$this->load->model('role_model');
		$model = $this->role_model;

		if ($action == 'save'){
			$data = $this->input->post();

			if (isset($data['enabled'])){
				$permissions_res = $this->db->get('permissions')->result_array();
				$permissions = array();

				foreach ($permissions_res as $key => $val){
					$permissions[$val['permission_id']] = $val;
				}

				$role_permissions_res = $this->db->get_where('role_permissions', array('role_id' => $id))->result_array();
				$role_permissions = array();

				foreach ($role_permissions_res as $key => $val){
					$role_permissions[$val['permission_id']] = $val;
				}

				foreach ($data['enabled'] as $key => $val){
					if (isset($role_permissions[$key]) && $role_permissions[$key]['enabled'] != $val){ // Checking if there's a role with this key
						if ($permissions[$key]['enabled'] != $val){ // Checking differences with defaults
							$insert_data = array(
								'role_id' => $id,
								'permission_id' => $key,
								'enabled' => $val
							);

							$this->db->insert('role_permissions', $insert_data);
						}else{
							$this->db->delete('role_permissions', array('role_permission_id' => $role_permissions[$key]['role_permission_id']));
						}
					}elseif (isset($role_permissions[$key]) && $permissions[$key]['enabled'] == $val){
						$this->db->delete('role_permissions', array('role_permission_id' => $role_permissions[$key]['role_permission_id']));
					}elseif (!isset($role_permissions[$key]) && $permissions[$key]['enabled'] != $val){
						$insert_data = array(
							'role_id' => $id,
							'permission_id' => $key,
							'enabled' => $val
						);

						$this->db->insert('role_permissions', $insert_data);
					}
				}

				foreach($_POST as $key => $val){
					if ($key != 'key' && $key != 'name'){
						unset($_POST[$key]);
					}
				}
			}
			$this->_action('roles', $model, $action, $id);
		}else{
			$this->_action('roles', $model, $action, $id);
		}
	}

	public function role_permissions($action = null, $id = null){
		$this->load->model('role_permission_model');
		$model = $this->role_permission_model;

		$this->_action('role_permissions', $model, $action, $id);
	}

	public function user_settings($action = null, $id = null){
		$this->load->model('user_setting_model');
		$model = $this->user_setting_model;

		$this->_action('user_settings', $model, $action, $id);
	}

	public function profile_settings($action = null, $id = null){
		$model = $this->load->model('user_setting_model');

		if ($action == 'save'){
			$this->_action('profile_settings', $model, 'save', array('user_id', $this->session->userdata('user')->user_id), array('no_exit' => true));

			$data = $this->db->join('user_settings', 'users.user_id = user_settings.user_id', 'left')->get_where('users', array(
				'users.user_id' => $this->session->userdata('user')->user_id
			));
			
			$this->session->set_userdata('user', $data->row());
			
			exit();
		}else{
			$this->_action('profile_settings', $model, 'edit', array('user_id', $this->session->userdata('user')->user_id), array('override_back_url' => '', 'disable_delete' => true));
		}
	}

	public function blocks($action = null, $id = null){
		$model = $this->load->model('block_model');

		if ($action === 'edit' && $id !== null){
			$block = $this->db->where($model->_primary, $id)->get($model->_db_table)->row_array();

			$model->get_block_controllers($block['block_folder']);

			if ($block['block_folder'] !== null){
				$model->get_controller_methods($block['block_folder'], $block['controller']);
			}
		}
		
		$this->_action('blocks', $model, $action, $id);
	}

	public function block_get_controllers(){
		$block = $this->input->post();
		$model = $this->load->model('block_model');
		$json = $model->get_block_controllers($block['block_folder'], true);

		$this->load->json($json); exit;
	}

	public function block_get_methods(){
		$block = $this->input->post();

		if (isset($block['controller'])){
			$model = $this->load->model('block_model');
			$json = $model->get_controller_methods($block['block_folder'], $block['controller'], true);

			$this->load->json($json); exit;
		}
	}

	public function menus($action = null, $id = null){
		$model = $this->load->model('menu_model');
		$model_item = $this->load->model('menu_item_model');

		if ($action == 'order'){
			$items = json_decode($this->input->post('data'), true);
			$update_items = array();
			$insert_items = array();
			$item_ids = array();

			$weight = 0;

			foreach ($items as $item){
				if ($item['item_id'] !== null){
					$item['menu_item_id'] = $item['item_id'];
					$item['weight'] = $weight;

					unset($item['item_id']);
					unset($item['depth']);
					unset($item['left']);
					unset($item['right']);
					
					if ($item['menu_item_id'] == 'new'){
						unset($item['menu_item_id']);

						$item['menu_id'] = $id;

						if ($item['parent_id'] === null)
							$item['parent_id'] = 0;

						$item['active'] = 0;
						$item['label'] = 'Új menüpont';
						$item['url'] = '!';

						$insert_items[] = $item;
					}else{
						$item['active'] = $item['active'];
						$update_items[] = $item;
					}

					$weight++;
				}
			}

			foreach ($update_items as $item)
				$item_ids[] = $item['menu_item_id'];

			if (!empty($item_ids))
				$this->db->where('menu_id', $id)->where_not_in('menu_item_id', $item_ids)->delete($this->menu_item_model->_db_table);
			else
				$this->db->where('menu_id', $id)->delete($this->menu_item_model->_db_table);

			if (!empty($insert_items))
				$this->db->insert_batch($this->menu_item_model->_db_table, $insert_items);

			if (!empty($update_items))
				$this->db->update_batch($this->menu_item_model->_db_table, $update_items, 'menu_item_id');

			$json = array(
				'success' => 1,
				'message' => '<strong>Sikeres mentés</strong>',
				'html' => menu_display($id, true, false),
				'html_table' => menu_items_table_display($id)
			);

			$this->load->json($json); exit;
		}elseif ($action == 'edit_item'){
			$menu = $this->db->select('menu_id')->where('menu_item_id', $id)->get($model_item->_db_table)->row_array();

			$this->_action('menu_items', $model_item, 'edit', $id, array('override_back_url' => 'menus/edit/'.$menu['menu_id']));
		}else{
			$this->_action('menus', $model, $action, $id);
		}
	}

	public function menu_items($action = null, $id = null){
		$model = $this->load->model('menu_item_model');

		if (is_null($action)){
			$this->_action('menu_items', $model, $action, $id);
		}elseif ($action == 'delete'){
			$menu = $this->db->select('menu_id')->where('menu_item_id', $id)->get($model->_db_table)->row_array();

			$this->_action('menu_items', $model, $action, $id, array('override_back_url' => 'menus/edit/'.$menu['menu_id']));
		}else{
			$this->_action('menu_items', $model, $action, $id);
		}
	}

	public function contents($action = null, $id = null){
		$this->load->model('content_model');
		$model = $this->content_model;

		$this->_action('contents', $model, $action, $id);
	}

	public function layout_parts($action = null, $id = null){
		$this->load->model('layout_part_model');

		if ($action == 'save' && $this->input->post('url') != '*' && $this->input->post('url') != ''){
			$model = $this->load->model('route_model');

			$url = explode('|', $this->input->post('url'));

			foreach ($url as $url_part){
				$exists = $this->db->where('slug', str_replace(array('[num]', '[any]'), array('(:num)', '(:any)'), $url_part))->get($this->route_model->_db_table)->row_array();

				if (empty($exists)){
					$data = array(
						'slug' => str_replace(array('[num]', '[any]'), array('(:num)', '(:any)'), $url_part),
						'controller' => 'main/index'
					);

					$this->db->insert($this->route_model->_db_table, $data);
				}

				$exists = $this->db->where('slug', str_replace(array('/[num]', '/[any]'), '', $url_part))->get($this->route_model->_db_table)->row_array();

				if (empty($exists)){
					$data = array(
						'slug' => str_replace(array('/[num]', '/[any]'), '', $url_part),
						'controller' => 'main/index'
					);

					$this->db->insert($this->route_model->_db_table, $data);
				}
			}
		}elseif ($action == 'delete'){
			$this->load->model('route_model');
			$part = $this->db->where('layout_part_id', $id)->get($this->layout_part_model->_db_table)->row_array();
			
			if ($part['url'] != '*' && $part['url'] != ''){
				$url = explode('|', $part['url']);

				foreach ($url as $url_part){
					$exists = $this->db->where('url', $url_part)->get($this->layout_part_model->_db_table)->num_rows();

					if ($exists == 1)
						$this->db->where('slug', $url_part)->delete($this->route_model->_db_table);
				}
			}
		}

		$model = $this->layout_part_model;

		$this->_action('layout_parts', $model, $action, $id, array('override_back_url' => 'layout'));
	}

	public function layout($action = null, $id = null){
		$model = $this->load->model('layout_part_model');

		if ($this->session->userdata('layout') === false)
			$this->session->set_userdata('layout', 1);

		if (is_null($action)){
			$this->load->model('menu_model');
			$this->load->model('content_model');
			$this->load->model('block_model');

			$layouts = $this->db->get('layouts')->result_array();
			$layout = $this->db->get_where('layouts', array('layout_id' => $this->session->userdata('layout')))->row_array();
			
			$positions = $this->db->get_where('layout_positions', array('layout_id' => $this->session->userdata('layout')))->result_array();

			$parts = $this->db->where('layout_id', $this->session->userdata('layout'))->order_by('position ASC, weight ASC')->get($this->layout_part_model->_db_table)->result_array();

			$menus = $this->db->get($this->menu_model->_db_table)->result_array();
			$contents = $this->db->get($this->content_model->_db_table)->result_array();
			$blocks = $this->db->get($this->block_model->_db_table)->result_array();

			$data = array(
				'layouts' => $layouts,
				'layout' => $layout,
				'positions' => $positions,
				'menus' => $menus,
				'contents' => $contents,
				'blocks' => $blocks,
				'parts' => $parts,
				'db_table' => $model->_db_table,
				'db_fields' => $model->_fields,
				'db_primary' => $model->_primary,
				'db_table_lang' => isset($model->_db_table_lang) ? $model->_db_table_lang : false
			);
			
			$data['v'] = 'admin/layout/layout';

			$this->front->add_script('res/js/admin/layout.js');
			
			$this->load->view('admin/_layout', $data);
		}elseif ($action == 'switch'){
			$id = $this->db->get_where('layouts', array('folder' => $id))->row_array();
			$this->session->set_userdata('layout', $id['layout_id']);
			redirect('admin/layout');
		}elseif ($action == 'edit'){
			$this->_action('layout_parts', $model, $action, $id, array('override_back_url' => 'layout'));
		}elseif ($action == 'save'){
			$this->_action('layout_parts', $model, $action, $id);
		}elseif ($action == 'save_layout'){
			$data = $this->input->post('data');

			$posted_layout = json_decode($data, true);

			$update_layout = array();
			$insert_layout = array();
			$part_ids = array();

			foreach ($posted_layout as $key => $region){
				foreach ($region as $inner_key => $element){
					$temp = array();

					$temp['layout_id'] = $id;
					$temp['position'] = $key;
					$temp['part_type'] = $element['part-type'];
					$temp['part_id'] = $element['part-id'];
					$temp['active'] = $element['active'];
					$temp['weight'] = $inner_key;

					if (isset($element['layout-part-id'])){
						$temp['layout_part_id'] = $element['layout-part-id'];

						$update_layout[] = $temp;
					}else{
						$temp['name'] = $element['part-name'];

						$insert_layout[] = $temp;
					}
				}
			}

			foreach ($update_layout as $part_id)
				$part_ids[] = $part_id['layout_part_id'];

			if (!empty($part_ids))
				$this->db->where('layout_id', $id)->where_not_in('layout_part_id', $part_ids)->delete($this->layout_part_model->_db_table);
			else
				$this->db->where('layout_id', $id)->delete($this->layout_part_model->_db_table);

			if (!empty($insert_layout))
				$this->db->where('layout_id', $id)->insert_batch($this->layout_part_model->_db_table, $insert_layout);
			
			if (!empty($update_layout))
				$this->db->where('layout_id', $id)->update_batch($this->layout_part_model->_db_table, $update_layout, 'layout_part_id');

			$json = array(
				'success' => 1,
				'message' => '<strong>Sikeres mentés</strong>',
				'html_table' => layout_parts_table_display($id)
			);

			$this->load->json($json); exit();
		}
	}

	public function layout_overrides($action = null, $id = null){
		$this->load->model('layout_override_model');
		$model = $this->layout_override_model;

		$this->_action('layout_overrides', $model, $action, $id);
	}

	public function system_settings($action = null, $id = null){
		$model = $this->load->model('system_setting_model');

		if ($action == 'save')
			$this->_action('system_settings', $model, 'save', 1);
		else
			$this->_action('system_settings', $model, 'edit', 1, array('override_back_url' => '', 'disable_delete' => true));
	}

	public function settings($action = null, $id = null){
		$model = $this->load->model('setting_model');

		if ($action == 'save')
			$this->_action('settings', $model, 'save', 1);
		else
			$this->_action('settings', $model, 'edit', 1, array('override_back_url' => '', 'disable_delete' => true));
	}

	public function seo($action = null, $id = null){
		$this->load->model('seo_model');
		$model = $this->seo_model;

		$this->_action('seo', $model, $action, $id);
	}

	public function routes($action = null, $id = null){
		$this->load->model('route_model');
		$model = $this->route_model;

		$this->_action('routes', $model, $action, $id);
	}

	public function documentation($action = null, $id = null){
		$model = $this->load->model('documentation_model');

		if ($action == 'view'){
			$this->load->library('Markdown');
			
			$this->front->add_style(array(
				'res/css/admin/highlight/monokai_sublime.css',
				'res/css/admin/documentation.css'
			));
			$this->front->add_script('res/js/admin/highlight/highlight.min.js');

			$data['entries'] = $this->db->where('active', 1)->get($model->_db_table)->result_array();

			$data['v'] = 'admin/documentation';
			$this->load->view('admin/_layout', $data);
		}else{
			$this->_action('documentation', $model, $action, $id);
		}


	}

	public function elfinder_html($type = 'file'){
		if ($type == 'mce'){
			$this->load->view('admin/others/elfinder_mce');
		}elseif ($type == 'file'){
			$this->load->view('admin/others/elfinder_file');
		}
	}

	public function elfinder_init(){
		$this->load->helper('path');

		$opts = array(
			'roots' => array(
				array( 
					'driver'		=> 'LocalFileSystem', 
					'path'			=> set_realpath('upload/private'),
					'startPath'		=> set_realpath('upload/private/img'),
					'URL'			=> base_url('upload/private') . '/',
					'alias' 		=> 'Fájlok',
					'uploadAllow'	=> array('image'),
					'uploadDeny'	=> array('all'),
					'uploadOrder'	=> 'deny,allow',
					'mimeDetect'	=> 'internal',
					'tmbSize'		=> 96,
					'attributes'	=> array(
						array(
							'pattern'	=> '!^/private!',
							'read'		=> true,
							'write'		=> true,
							'locked'	=> true
						),
						array(
							'pattern'	=> '!^/.quarantine!',
							'hidden'	=> true
						),
						array(
							'pattern'	=> '!^/.tmb!',
							'hidden'	=> true
						)
					)
				) 
			)
		);

		$this->load->library('elfinder_library', $opts);
	}

	public function upload_pre(){
		$this->load->library('upload');

		// Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$this->upload->allowedExtensions = array(); // all files types allowed by default

		// Specify max file size in bytes.
		$this->upload->sizeLimit = 10 * 1024 * 1024; // default is 10 MiB

		$method = $_SERVER["REQUEST_METHOD"];
		if ($method == "POST") {
		    header("Content-Type: text/plain");

		    // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		    $result = $this->upload->handleUpload("upload/cache");

		    // To return a name used for uploaded file you can use the following line.
		    $result["uploadName"] = $this->upload->getUploadName();
		    $result["uploadMime"] = $this->upload->getUploadMime(true);

		    echo json_encode($result);
		}else{
		    header("HTTP/1.0 405 Method Not Allowed");
		}
	}

	public function youtube_init(){
		$this->load->view('admin/others/youtube');
	}
}