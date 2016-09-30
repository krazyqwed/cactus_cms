<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require dirname(__FILE__).'/front_controller.php';

class Main extends Front_Controller {
  public function index(){
    $data = array();

    $data['main_author'] = $this->db->query("
      SELECT t1.*, t2.* FROM `users` t1
      INNER JOIN `user_settings` t2 ON t1.user_id = t2.user_id
      WHERE t1.`username` = 'krazyqwed'
    ")->row_array();

    parent::index($data);
  }
}
