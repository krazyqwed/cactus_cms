<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Load routes from database */
require_once(BASEPATH .'database/DB'. EXT);
$db =& DB();
$settings = $db->get('system_settings')->row_array();

$config['multi_language_enabled'] = (boolean) $settings['multi_language_enabled'];

$config['languages'] = array(
	'hu' => 'Magyar',
	'en' => 'English',
	'es' => 'España'
);

$config['default_language'] = 'hu';
$config['default_language_admin'] = 'hu';