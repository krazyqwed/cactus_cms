<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['admin_default_scripts'] = array(
	'res/js/smoothscroll.js',
	'res/js/jquery-1.10.2.min.js',
	'res/js/jquery-ui-1.10.4.min.js',
	'res/js/admin/bootstrap/bootstrap.min.js',
	array('res/js/admin/admin.js', 9999)
);

$config['admin_default_styles'] = array(
	'res/css/admin/bootstrap.min.css',
	'res/css/admin/font-awesome.min.css',
	'res/css/admin/smoothness/jquery-ui-1.10.4.custom.min.css',
	'res/css/admin/admin.css',
	'res/css/admin/override.css'
);

$config['main_default_scripts'] = array(
	'res/js/jquery-1.10.2.min.js',
	'res/js/jquery-ui-1.10.4.min.js',
	'res/js/main/foundation/foundation.min.js',
	array('res/js/main/main.js', 9999)
);

$config['main_default_styles'] = array(
	'res/css/main/normalize.css',
	'res/js/main/foundation/foundation.min.css'
);