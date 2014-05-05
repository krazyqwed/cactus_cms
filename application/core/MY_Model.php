<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_model extends CI_Model {
	public $_fields = array();
	public $_indexes = false;
	public $_primary = false;
	public $_db_table = false;

	public function __construct($db_table = null){
		parent::__construct();

		if (!is_null($db_table)){
			$this->_db_table = $db_table;

			$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
			$table_cache = $this->cache->get('table_cache__'.$db_table);

			if (!$table_cache){
				$query = $this->db->query("SHOW COLUMNS FROM `".$db_table."`");
				$fields_temp = $query->result_array();

				foreach ($fields_temp as $field)
					$this->_fields[$field['Field']] = $field;

				$primary = $this->db->query("SHOW INDEX FROM `".$db_table."` WHERE `Key_name` = 'PRIMARY'")->row_array();
				$this->_primary = $primary['Column_name'];

				$query = $this->db->query("SHOW INDEX FROM `".$db_table."` WHERE `Key_name` != 'PRIMARY'");
				$indexes = $query->result_array();

				foreach ($indexes as $index)
					$this->_indexes[] = $index['Column_name'];

				$this->cache->save('table_cache__'.$db_table, array(
					'fields' => $this->_fields,
					'primary' => $this->_primary,
					'indexes' => $this->_indexes
				), 0);
			}else{
				$this->_fields = $table_cache['fields'];
				$this->_primary = $table_cache['primary'];
				$this->_indexes = $table_cache['indexes'];
			}
		}
	}

	protected function _post_actions(){
		// You can do anything here with eg.: model fields
	}

	public function load_field_libs(){
		$this->front->autoload_by_field($this->_fields);
	}

	/* Example:
		$this->block_instance_model->getWithRelation(array($this->block_model->_db_table => 'block_id' ));
	*/
	public function getWithRelation($relations, $query_string = null){
		if (is_null($query_string))
			$query = $this->db->query("SELECT * FROM `".$this->_db_table."`");
		else
			$query = $this->db->query($query_string);

		$result = $query->result_array();
		
		foreach ($result as $row => $value){
			foreach ($relations as $relation => $field){
				if ($value[$field] != NULL){
					$query = $this->db->query("SELECT * FROM `".$relation."` WHERE `".$field."` = ".$value[$field]);
					$row_result = $query->row_array();
					$result[$row][$field.'.relation'] = $row_result;
				}
			}
		}

		return $result;
	}

	public function save($id = null){
		$data = $this->input->post();

		foreach ($this->_fields as $field){
			if (isset($field['Type']) && $field['Type'] == '_image' && isset($data[$field['Field']])){
				$image_id = image_save($data[$field['Field']]);

				if ($image_id)
					$data[$field['Field']] = $image_id;
				else
					$data[$field['Field']] = '';
			}

			if (isset($field['Type']) && $field['Type'] == '_file' && isset($data[$field['Field']])){
				$file_id = file_save($data[$field['Field']]);

				if ($file_id)
					$data[$field['Field']] = $file_id;
				else
					$data[$field['Field']] = '';
			}

			if (isset($field['Type']) && $field['Type'] == '_multiselect' && isset($data[$field['Field']]) && is_array($data[$field['Field']])){
				$data[$field['Field']] = json_encode($data[$field['Field']]);
			}
		}

		if ($id === null){
			$this->db->insert($this->_db_table, $data);
			$id = $this->db->insert_id();
		}else{
			$this->db->where($this->_primary, $id)->update($this->_db_table, $data);
		}

		return $id;
	}

	public function save_lang($id = null, $data = null, $lang = null){
		if (is_null($data)){
			$data = $this->input->post();

			if ($lang)
				$this->db->where(array($this->_primary => $id, 'lang' => $lang))->update($this->_db_table.'_lang', $data);
		}else{
			$this->db->insert($this->_db_table.'_lang', $data);
			$id = $this->db->insert_id();
		}
		
		return $id;
	}

	public function delete($id = null){
		$data = $this->input->post();

		$this->db->where($this->_primary, $id)->delete($this->_db_table);
	}
}