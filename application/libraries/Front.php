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

	public function display_scripts(){
		$this->scripts = $this->make_unique($this->scripts);
		$scripts = '';

		if (!empty($this->scripts)){
			foreach ($this->scripts as $key => $script) {
				if (is_array($script)){
					unset($this->scripts[$key]);
					$this->scripts['z_'.$script[1].'_'.$key] = $script[0];
				}
			}

			foreach ($this->scripts as $script)
				$scripts .= '<script type="text/javascript" src="'.base_url($script).'"></script>';
		}

		return $scripts;
	}
	
	public function display_styles(){
		$this->styles = $this->make_unique($this->styles);
		$styles = '';

		if (!empty($this->styles)){
			foreach ($this->styles as $key => $style) {
				if (is_array($style)){
					unset($this->styles[$key]);
					$this->styles['z_'.$style[1].'_'.$key] = $style[0];
				}
			}

			foreach ($this->styles as $style)
				$styles .= '<link rel="stylesheet" type="text/css" href="'.base_url($style).'" />';
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
						$this->add_script(array('res/js/admin/tinymce/tinymce.min.js', 'res/js/admin/tinymce/jquery.tinymce.min.js'));
						break;
					case '_variable':
						$this->add_style(array('res/js/admin/codemirror/codemirror.css', 'res/js/admin/select/bootstrap-select.min.css', 'res/js/admin/markdown/ghostdown.css', 'res/js/admin/prettify/prettify.css'));
						$this->add_script(array('res/js/admin/codemirror/codemirror.js', 'res/js/admin/select/bootstrap-select.min.js', 'res/js/admin/tinymce/tinymce.min.js', 'res/js/admin/tinymce/jquery.tinymce.min.js', 'res/js/admin/markdown/ghostdown.js', 'res/js/admin/markdown/jquery.ghostdown.js', 'res/js/admin/prettify/prettify.js', 'res/js/admin/prettify/lang-php.js'));
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
}
