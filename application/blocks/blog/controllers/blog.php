<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends MX_Controller {
	public function index($id = null){
		$this->load->library('markdown');
		$model = $this->load->model('blog/blog_model');

		$this->front->add_style(array(
			'res/css/main/highlight/monokai_sublime.css',
			'res/css/main/blog.css'
		));
		$this->front->add_script('res/js/main/highlight.min.js');

		if ($id === null){
			$entries = $this->db->query("
				SELECT t1.*, t2.full_name FROM `".$model->_db_table."` t1
				LEFT JOIN `user_settings` t2 ON t1.`author` = t2.`user_id`
				WHERE t1.`active` = 1
				ORDER BY t1.`date` DESC
			")->result_array();

			/* Language */
			$entries = part_get_lang_table($entries, $id, $model);

			/* Tags */
			foreach ($entries as $key => $entry) {
				$tags = json_decode($entry['tags']);

				$tag_list = array();

				foreach ($tags as $tag){
					$tag = $this->db->query("SELECT name FROM tags WHERE tag_id = ".$tag)->row_array();
					$tag_selector = str_replace(array(' ', ',', '.', '-'), '_',strtolower($tag['name']));
					$tag_list[$tag_selector] = $tag['name'];
				}

				$entries[$key]['tags'] = $tag_list;
			}

			$data = array( 'entries' => $entries );

			$this->load->view('index', $data);
		}else{
			$entry = $this->db->query("
				SELECT t1.*, t2.full_name FROM `".$model->_db_table."` t1
				LEFT JOIN `user_settings` t2 ON t1.`author` = t2.`user_id`
				WHERE t1.`entry_id` = ".$id."
			")->row_array();

			if ($entry){
				/* Files */
				$files = explode('|', $entry['files']);

				$file_list = array();

				foreach ($files as $file){
					$file_list[] = file_url($file, true);
				}

				/* Tags */
				$tags = json_decode($entry['tags']);

				$tag_list = array();

				foreach ($tags as $tag){
					$tag = $this->db->query("SELECT name FROM tags WHERE tag_id = ".$tag)->row_array();
					$tag_selector = str_replace(array(' ', ',', '.', '-'), '_', strtolower($tag['name']));
					$tag_list[$tag_selector] = $tag['name'];
				}

				/* Language */
				$entry = part_get_lang_table($entry, $id, $model);

				/* SEO */
				$this->seo->set_title($entry['seo_title']);
				$this->seo->set_description($entry['seo_description']);

				$data = array(
					'entry' => $entry,
					'files' => $file_list,
					'tags' => $tag_list
				);

				$this->load->view('blog', $data);
			}else{
				$this->load->view('main/page_404');
			}
		}
	}
}