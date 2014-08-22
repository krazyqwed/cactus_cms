<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_model extends MY_Model {
	public $_db_table = 'roles';

	public function __construct(){
		parent::__construct($this->_db_table);

		$this->_fields['role_id']['_Alias'] = 'ID';

		$this->_fields['key']['_Alias'] = 'Azonosító';

		$this->_fields['name']['_Alias'] = 'Név';

		$this->_fields['_permissions']['Field'] = 'permission_ids';
		$this->_fields['_permissions']['_Description'] = 'Engedélyek kezelése';
		$this->_fields['_permissions']['_Alias'] = 'Engedélyek';
		$this->_fields['_permissions']['_Function'] = 'permission_list';
		$this->_fields['_permissions']['_On_new'] = false;
		$this->_fields['_permissions']['_Show_title'] = false;

		parent::_post_actions();
	}

	public function permission_list($content, $field){
		$this->load->model('permission_model');

		$permissions = $this->db->get('permissions')->result_array();
		$role_permissions_res = $this->db->where('role_id', $content['role_id'])->get('role_permissions')->result_array();

		$role_permissions = array();
		foreach ($role_permissions_res as $rp){
			$role_permissions[$rp['permission_id']] = $rp;
		}

		$data = array(
			'role_id' => $content['role_id'],
			'db_fields' => $this->permission_model->_fields,
			'db_primary' => $this->permission_model->_primary,
			'permissions' => $permissions,
			'role_permissions' => $role_permissions
		);

		$this->front->add_style('res/js/admin/datatables/jquery.dataTables.min.css');
		$this->front->add_script(array(
			'res/js/admin/datatables/jquery.dataTables.min.js',
			'res/js/admin/datatables/dataTables.bootstrap.min.js'
		));

		$this->load->view('admin/permissions/permission_list', $data);
	}

	public function _create_table(){
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".$this->_db_table."` (
			  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `key` varchar(32) NOT NULL,
			  `name` varchar(64) NOT NULL,
			  PRIMARY KEY (`role_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
		");

		$this->db->simple_query("
			DROP TRIGGER IF EXISTS `delete_role`;
			DELIMITER ;;
			CREATE TRIGGER `delete_role` AFTER DELETE ON `".$this->_db_table."` FOR EACH ROW BEGIN
				DELETE FROM role_permissions WHERE role_id = OLD.role_id;
			END;;
			DELIMITER ;
		");
	}
}