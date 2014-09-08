<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front{
	public $scripts = array();
	public $styles = array();

	public function add_script($url){
		if (is_array($url))
			$this->scripts = array_merge($this->scripts, $url);
		else
			$this->scripts = array_merge($this->scripts, array($url));
	}

	public function add_style($url){
		if (is_array($url))
			$this->styles = array_merge($this->styles, $url);
		else
			$this->styles = array_merge($this->styles, array($url));
	}

	private function make_unique($input){
		$res = array();
		$res_z = array();

		foreach ($input as $key => $value){

			if (is_array($value)){
				$res_z['z_'.$value[1].'_'.md5(serialize($value[0]))] = $value;
			}else{
				$res[md5(serialize($value))] = $value;
			}
		}

		uksort($res_z, "strnatcmp");

		return array_merge($res, $res_z);
	}

	public function display_scripts($as_array = false){
		$this->scripts = $this->make_unique($this->scripts);
		$scripts = ($as_array)?array():'';

		if (!empty($this->scripts)){
			foreach ($this->scripts as $key => $script) {
				if (is_array($script)){
					unset($this->scripts[$key]);
					$this->scripts['z_'.$script[1].'_'.$key] = $script[0];
				}
			}

			foreach ($this->scripts as $script){
				if ($as_array)
					$scripts[] = $script;
				else
					$scripts .= '<script type="text/javascript" src="'.base_url($script).'"></script>';
			}
		}

		return $scripts;
	}
	
	public function display_styles($as_array = false){
		$this->styles = $this->make_unique($this->styles);
		$styles = ($as_array)?array():'';

		if (!empty($this->styles)){
			foreach ($this->styles as $key => $style) {
				if (is_array($style)){
					unset($this->styles[$key]);
					$this->styles['z_'.$style[1].'_'.$key] = $style[0];
				}
			}

			foreach ($this->styles as $style){
				if ($as_array)
					$styles[] = $style;
				else
					$styles .= '<link rel="stylesheet" type="text/css" href="'.base_url($style).'" />';
			}
		}

		return $styles;
	}

	public function autoload_by_field($fields){
		foreach ($fields as $field){
			if (isset($field['Type'])){
				switch ($field['Type']){
					case '_select':
						$this->add_script('res/js/admin/select/bootstrap-select.min.js');
						$this->add_style('res/js/admin/select/bootstrap-select.min.css');
						break;
					case '_multiselect':
						$this->add_script('res/js/admin/chosen/chosen.jquery.min.js');
						$this->add_style('res/js/admin/chosen/chosen.min.css');
						break;
					case '_wysiwyg':
						$this->add_script(array(
							'res/js/admin/tinymce/tinymce.min.js',
							'res/js/admin/tinymce/plugins/codemirror/plugin.min.js',
							'res/js/admin/tinymce/plugins/youtube/plugin.min.js',
						));

						break;
					case '_variable':
						$this->add_style(array(
							'res/js/admin/codemirror/codemirror.css',
							'res/js/admin/select/bootstrap-select.min.css',
							'res/js/admin/markdown/ghostdown.css',
							'res/js/admin/prettify/prettify.css'
						));
						$this->add_script(array(
							'res/js/admin/codemirror/codemirror.js',
							'res/js/admin/select/bootstrap-select.min.js',
							'res/js/admin/tinymce/tinymce.min.js',
							'res/js/admin/tinymce/plugins/codemirror/plugin.min.js',
							'res/js/admin/tinymce/plugins/youtube/plugin.min.js',
							'res/js/admin/markdown/ghostdown.js',
							'res/js/admin/markdown/jquery.ghostdown.js',
							'res/js/admin/prettify/prettify.js',
							'res/js/admin/prettify/lang-php.js'
						));
						break;
					case '_image':
						$this->add_script(array('res/js/admin/fineuploader/jquery.fineuploader-4.1.0-13.min.js', 'res/js/admin/jcrop/jquery.Jcrop.min.js'));
						$this->add_style(array('res/js/admin/fineuploader/fineuploader-4.1.0-13.min.css', 'res/js/admin/jcrop/jquery.Jcrop.min.css'));
						break;
					case '_file':
						$this->add_script(array('res/js/admin/fineuploader/jquery.fineuploader-4.1.0-13.min.js', 'res/js/admin/jcrop/jquery.Jcrop.min.js'));
						$this->add_style(array('res/js/admin/fineuploader/fineuploader-4.1.0-13.min.css', 'res/js/admin/jcrop/jquery.Jcrop.min.css'));
						break;
					case '_markdown':
						$this->add_style(array('res/js/admin/codemirror/codemirror.css', 'res/js/admin/markdown/ghostdown.css', 'res/js/admin/prettify/prettify.css'));
						$this->add_script(array('res/js/admin/codemirror/codemirror.js', 'res/js/admin/markdown/ghostdown.js', 'res/js/admin/markdown/jquery.ghostdown.js', 'res/js/admin/prettify/prettify.js', 'res/js/admin/prettify/lang-php.js'));
						break;
				}
			}
		}
	}

	public function combine_css($styles){
		$full_css = '';

		foreach ($styles as $s){
			$css = file_get_contents($s);

			$path = str_replace('\\', '/', str_replace(FCPATH, '', realpath($s)));
			$path = dirname($path);

			preg_match_all('/(?:url\([\'"]*((?!\/\/)[a-z0-9\/._\-\\?#=]+)[\'"]*\))/i', $css, $matches);

			$matches[1] = array_unique($matches[1]);

			$matches_original = $matches[1];

			foreach ($matches[1] as $key => $m){
				if (strpos($m, '../') !== false){
					$path_parts = explode('/', $path);

					while (stripos($m, '../') !== false){
						unset($path_parts[count($path_parts) - 1]);
						$m = preg_replace('/\.\.\//', '', $m, 1);
					}

					$new_path = implode('/', $path_parts);
					$matches[1][$key] = base_url($new_path.'/'.$m);
				}else{
					$matches[1][$key] = base_url($path.'/'.$m);
				}
			}

			$css = str_replace($matches_original, $matches[1], $css);

			$full_css .= $css;
		}

		return $full_css;
	}

	public function combine_js($scripts){
		$full_js = '';

		foreach ($scripts as $s){
			$js = file_get_contents($s);

			$full_js .= $js . ';';
		}

		return $full_js;
	}

	public function handle_cache($resources, $type){
		$CI =& get_instance();
		
		$md5 = md5(implode(',', $resources));

		// If cache folder not exists
		if (!file_exists($CI->config->item('minify_cache_path'))) {
			mkdir($CI->config->item('minify_cache_path'), 0777, true);
			file_put_contents($CI->config->item('minify_cache_path').'.gitignore', '*'.PHP_EOL.'!.gitignore');
		}

		if (!file_exists($CI->config->item('minify_cache_path').$md5.'.'.$type)){
			if ($type == 'css'){
				$CI->load->library('minify/cssmin');

				$content = $CI->front->combine_css($resources);

				$CI->cssmin->set_memory_limit('256M');
				$CI->cssmin->set_max_execution_time(120);

				$content = $CI->cssmin->run($content);
			}elseif ($type == 'js'){
				$CI->load->library('minify/jsmin');

				$content = $CI->front->combine_js($resources);
				$content = $CI->jsmin->minify($content);
			}

			file_put_contents($CI->config->item('minify_cache_path').$md5.'.'.$type, $content);
		}

		return str_replace(FCPATH, '', $CI->config->item('minify_cache_path')).$md5.'.'.$type;
	}
}
