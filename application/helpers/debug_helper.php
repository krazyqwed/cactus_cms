<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function var_dump_pre($variable){
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
}
