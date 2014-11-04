<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function file_save($file, $field){
	$id = false;

	foreach ($file as $key => $f){
		if (!isset($f['fileid']) && $key != 'delete-file'){
			$id[] = file_save_final($f);
		}elseif (isset($f['fileid'])){
			$id[] = $f['fileid'];

			if (isset($f['filename']) && $f['filename'] != ''){
				file_update($f);
			}
		}

		if ($key == 'delete-file'){
			$id = file_delete($f, $id);
		}
	}

	if (!empty($id))
		return implode('|', $id);
	else
		return false;
}

function file_delete($file_ids, $current_ids){
	$CI =& get_instance();

	foreach ($file_ids as $file_id){
		unset($current_ids[$file_id]);

		$file = $CI->db->get_where('files', array('file_id' => $file_id))->row_array();

		foreach (glob('./upload/public/file/'.$file['filename'].'*') as $filename)
			unlink($filename);

		$CI->db->where('file_id', $file_id)->delete('files');
	}

	return $current_ids;
}

function file_save_final($file){
	$CI =& get_instance();

	$filename = md5(microtime());

	$path = './upload/cache/'.$file['filename'];
	$ext = end(explode('.', $file['filename']));
	$final_path = './upload/public/file/'.$filename.'.'.$ext;

	copy($path, $final_path);
	unlink($path);

	
	$filesize = filesize($final_path);

	$data = array(
		'filename' => $filename,
		'ext' => $ext,
		'mime' => file_get_mime($final_path),
		'filesize' => $filesize,
		'visible_name' => $file['filename']
	);

	$CI->db->insert('files', $data);

	return $CI->db->insert_id();
}

function file_update($file){
	$CI =& get_instance();

	$data = array(
		'visible_name' => $file['filename']
	);

	$CI->db->where('file_id', $file['fileid'])->update('files', $data);
}

function file_get_mime($path){
	if (function_exists('finfo_open')){
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$info = finfo_file($finfo, $path);
		finfo_close($finfo);
	}else{
		$info = 'unknown';
	}

	return $info;
}

function file_url($file, $visible_name = false){
	$CI =& get_instance();

	if (file_exists('./'.urldecode($file)) && $file != ''){
		$original_path = './'.urldecode($file);
		$new_url = 'upload/public/file/'.md5($original_path).end(explode('.', $file));

		return base_url($new_url);
	}

	if (!is_array($file) && is_numeric($file))
		$file = $CI->db->get_where('files', array('file_id' => $file))->row_array();
	elseif (!is_array($file) && $file != '')
		$file = $CI->db->get_where('files', array('filename' => $file))->row_array();

	if ($file){
		$new_url = 'file_download/'.$file['file_id']; // 'upload/public/file/'.$file['filename'].'.'.$file['ext'];

		if ($visible_name){
			return array(base_url($new_url), $file['visible_name'] != ''?$file['visible_name']:$file['filename'].'.'.$file['ext']);
		}else{
			return base_url($new_url);
		}
	}else{
		return false;
	}
}