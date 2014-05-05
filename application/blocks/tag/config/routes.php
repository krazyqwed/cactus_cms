<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['admin/tags'] = 'tag/_tag/index';
$route['admin/tags/(:any)'] = 'tag/_tag/index/$1';