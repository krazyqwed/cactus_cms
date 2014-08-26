<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function image_save($image){
	$id = false;

	foreach ($image as $key => $i){
		if (!isset($i['imageid']) && $key != 'delete-image'){
			$id[] = image_save_final($i);
		}elseif (isset($i['imageid'])){
			$id[] = $i['imageid'];
			image_edit_crop($i['imageid'], $i);
		}

		if ($key == 'delete-image'){
			$id = image_delete($i, $id);
		}
	}

	if (!empty($id))
		return implode('|', $id);
	else
		return false;
}

function image_delete($image_ids, $current_ids){
	$CI =& get_instance();

	foreach ($image_ids as $image_id){
		unset($current_ids[$image_id]);

		$image = $CI->db->get_where('images', array('image_id' => $image_id))->row_array();

		foreach (glob('./upload/public/img/'.$image['filename'].'*') as $filename)
			unlink($filename);

		$CI->db->where('image_id', $image_id)->delete('images');
	}

	return $current_ids;
}

function image_edit_crop($image_id, $data){
	$CI =& get_instance();
	$CI->config->load('image');

	$image = $CI->db->get_where('images', array('image_id' => $image_id))->row_array();
	
	if (!isset($data['x1'])){
		$final_data['cropped'] = 0;
	}else{
		$final_data['cropped'] = 1;
		$final_data['crop_x1'] = round($data['x1']);
		$final_data['crop_y1'] = round($data['y1']);
		$final_data['crop_x2'] = round($data['x2']);
		$final_data['crop_y2'] = round($data['y2']);

		if ($final_data['crop_x1'] > $image['width'])
			$final_data['crop_x1'] =  $image['width'];
		if ($final_data['crop_x2'] >  $image['width'])
			$final_data['crop_x2'] =  $image['width'];
		if ($final_data['crop_y1'] > $image['height'])
			$final_data['crop_y1'] = $image['height'];
		if ($final_data['crop_y2'] > $image['height'])
			$final_data['crop_y2'] = $image['height'];
		
		$final_path_original = './upload/public/img/'.$image['filename'].'_original.'.$image['ext'];
		$final_path_crop = './upload/public/img/'.$image['filename'].'_cropped.'.$image['ext'];
		
		foreach (glob('./upload/public/img/'.$image['filename'].'_cropped*') as $filename)
			unlink($filename);

		$CI->image_moo->load($final_path_original)->crop($final_data['crop_x1'], $final_data['crop_y1'], $final_data['crop_x2'], $final_data['crop_y2'])->save($final_path_crop, true);
	}

	$CI->db->where('image_id', $image_id)->update('images', $final_data);
}

function image_save_final($image){
	$CI =& get_instance();
	$CI->config->load('image');

	$filename = md5(microtime());

	$path = './upload/cache/'.$image['filename'];
	$ext = end(explode('.', $image['filename']));
	$final_path = './upload/public/img/'.$filename.'_original.'.$ext;

	copy($path, $final_path);
	unlink($path);

	$dim = getimagesize($final_path);
	$filesize = filesize($final_path);

	$data = array(
		'filename' => $filename,
		'ext' => $ext,
		'filesize' => $filesize,
		'width' => $dim[0],
		'height' => $dim[1],
		'cropped' => 0
	);

	if (isset($image['x1'])){
		$image['x1'] = round($image['x1']);
		$image['y1'] = round($image['y1']);
		$image['x2'] = round($image['x2']);
		$image['y2'] = round($image['y2']);

		if ($image['x1'] >  $dim[0])
			$image['x1'] =  $dim[0];
		if ($image['x2'] >  $dim[0])
			$image['x2'] =  $dim[0];
		if ($image['y1'] > $dim[1])
			$image['y1'] = $dim[1];
		if ($image['y2'] > $dim[1])
			$image['y2'] = $dim[1];
		
		$final_path_crop = './upload/public/img/'.$filename.'_cropped.'.end(explode('.', $image['filename']));
		$CI->image_moo->load($final_path)->crop($image['x1'], $image['y1'], $image['x2'], $image['y2'])->save($final_path_crop);

		$data['cropped'] = 1;
		$data['crop_x1'] = $image['x1'];
		$data['crop_y1'] = $image['y1'];
		$data['crop_x2'] = $image['x2'];
		$data['crop_y2'] = $image['y2'];
	}

	$CI->db->insert('images', $data);

	return $CI->db->insert_id();
}

function image_display($image, $dim = null, $show_cropped = null, $custom_placeholder = null){
	$CI =& get_instance();	

	if (!is_array($image) && file_exists('./'.urldecode($image)) && $image != ''){
		$original_path = './'.urldecode($image);
		$new_url = 'upload/public/img/'.md5($original_path).'_'.$dim[0].'_'.$dim[1].'.'.end(explode('.', $image));
		$new_path = './'.$new_url;

		$image_dim = getimagesize($original_path);

		if ($image_dim[0] != $dim[0] || $image_dim[1] != $dim[1])
			$CI->image_moo->load($original_path)->resize($dim[0], $dim[1])->save($new_path);

		return base_url($new_url);
	}elseif (!file_exists('./'.urldecode($image)) && $image != ''){
		$original_path = './'.urldecode($image);
		$original_filename = md5($original_path);
		
		foreach (glob('./upload/public/img/'.$original_filename.'*') as $filename)
			unlink($filename);
	}

	if (!is_array($image) && is_numeric($image))
		$image = $CI->db->get_where('images', array('image_id' => $image))->row_array();
	elseif (!is_array($image) && $image && $image != '')
		$image = $CI->db->get_where('images', array('filename' => $image))->row_array();

	if (!empty($image)){
		if (is_null($dim)){
			$filename = $image['filename'].'_original.'.$image['ext'];
			$url = 'upload/public/img/'.$filename;

			return base_url($url);
		}

		if ((is_null($show_cropped) && $image['cropped'] == 1) || (!is_null($show_cropped) && $show_cropped)){
			$original_filename = $image['filename'].'_cropped.'.$image['ext'];

			if ($image['width'] != $dim[0] || $image['height'] != $dim[1])
				$filename = $image['filename'].'_cropped_'.$dim[0].'_'.$dim[1].'.'.$image['ext'];
			else
				$filename = $image['filename'].'_cropped.'.$image['ext'];
		}else{
			$original_filename = $image['filename'].'_original.'.$image['ext'];

			if ($image['width'] != $dim[0] || $image['height'] != $dim[1])
				$filename = $image['filename'].'_'.$dim[0].'_'.$dim[1].'.'.$image['ext'];
			else
				$filename = $image['filename'].'_original.'.$image['ext'];
		}

		$original_path = './upload/public/img/'.$original_filename;

		$url = 'upload/public/img/'.$filename;
		$new_path = './'.$url;

		if ($image['width'] != $dim[0] || $image['height'] != $dim[1])
			$CI->image_moo->load($original_path)->resize_crop($dim[0], $dim[1])->save($new_path);

		return base_url($url);
	}else{
		if ($custom_placeholder == null){
			if (is_array($dim))
				return 'http://placehold.it/'.$dim[0].'x'.$dim[1];
			else
				return 'http://placehold.it/150x75';
		}else{
			return $custom_placeholder;
		}
	}
}