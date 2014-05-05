<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['admin/blog'] = 'blog/_blog/index';
$route['admin/blog/(:any)'] = 'blog/_blog/index/$1';