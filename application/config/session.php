<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Session namespace
$uri = explode('/', $this->uri->uri_string());

if ($uri[0] == $this->config->item('admin_path')){
    $config['sess_namespace'] = 'cactus_admin';
}else{
    $config['sess_namespace'] = 'cactus_front';
}




/* End of file session.php */
/* Location: ./application/config/session.php */
