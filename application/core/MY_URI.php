<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_URI class */
require APPPATH."third_party/MX/URI.php";

class MY_URI extends MX_URI {
	function uri_string() {
		if ($this->config->item('multi_language_enabled')){
			$langs = $this->config->item('languages');

			$uri = explode('/', $this->uri_string);

			if ($uri[0] != $this->config->item('admin_path')){
				foreach ($langs as $key => $lang){
					if (isset($uri[0]) && $uri[0] == $key) unset($uri[0]);
				}
			}

			$uri_string = implode('/', $uri);

			return $uri_string;
		}else{
			return $this->uri_string;
		}
	}
}