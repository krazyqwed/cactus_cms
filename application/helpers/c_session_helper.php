<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function c_session_handle(){
	$CI =& get_instance();
	
	if (!file_exists(APPPATH.'cache/session')){
		mkdir(APPPATH.'cache/session', 0775);
	}

	file_put_contents(APPPATH.'cache/session/sess_'.session_id(), '');

	foreach (glob(APPPATH.'cache/session/*') as $file){
		if (filemtime($file) <= strtotime('-5 minutes')) {
			unlink($file);
		}
	}
}

function c_session_active_number(){
	$CI =& get_instance();

	$count = 0;
	foreach (glob(APPPATH.'cache/session/*') as $file){
		if (filemtime($file) <= strtotime('-5 minutes')) {
			unlink($file);
		}else{
			$count++;
		}
	}

	return $count;
}