<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['display_override'][] = array(
	'class'    => 'Output',
	'function' => 'seo',
	'filename' => 'Output.php',
	'filepath' => 'hooks'
);

$hook['display_override'][] = array(
	'class'    => 'Output',
	'function' => 'display',
	'filename' => 'Output.php',
	'filepath' => 'hooks'
);

$hook['pre_controller'][] = array(
    'class'    => 'Front_hook',
    'function' => 'res',
    'filename' => 'Front.php',
    'filepath' => 'hooks'
);

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */