<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller {
	public function image_load($imageid){
		echo json_encode(
			array(
				'src' => image_display($imageid, null, false)
			)
		);
	}
}