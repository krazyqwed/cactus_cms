<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MX_Controller {
	public function index($id = null){
		$model = $this->load->model('blog/blog_model');

		if ($id === null){
			$entries = $this->db->where('active', 1)->get($model->_db_table)->result_array();

			/* Language */
			$entries = part_get_lang_table($entries, $id, $model);

			$data = array( 'entries' => $entries );

			$this->load->view('index', $data);
		}else{
			$this->load->library('Markdown');

			$this->front->add_style(array(
				'res/css/main/highlight/monokai_sublime.css',
				'res/css/main/blog.css'
			));
			$this->front->add_script('res/js/main/highlight.min.js');

			/* Join lang table because of the URL */
			if (isset($model->db_table_lang) && $model->db_table_lang)
				$entry = $this->db->join($model->_db_table.'_lang', $model->_db_table.'_lang.'.$model->_primary.' = '.$model->_db_table.'.'.$model->_primary, 'left')->where($model->_db_table.'.entry_id', $id)->or_where($model->_db_table.'.url', $id)->or_where($model->_db_table.'_lang.url', $id)->get($model->_db_table)->row_array();
			else
				$entry = $this->db->where('active', 1)->where('entry_id', $id)->get($model->_db_table)->row_array();

			if ($entry){
				/* Language */
				$entry = part_get_lang_table($entry, $id, $model);

				/* SEO */
				$this->seo->set_title($entry['seo_title']);
				$this->seo->set_description($entry['seo_description']);

				$data = array( 'entry' => $entry );

				$this->load->view('blog', $data);
			}else{
				$this->load->view('main/page_404');
			}
		}
	}
}