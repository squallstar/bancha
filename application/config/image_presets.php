<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['presets']['user_pic'] = array(
	array('operation' => 'resize', 'size' => '600x?', 'quality' => 50, 'ratio' => TRUE)
);

$config['presets']['demo'] = array(
	array('operation' => 'crop', 'size' => '500x500', 'quality' => 50, 'ratio' => FALSE, 'x' => 50, 'y' => 50),
	array('operation' => 'resize', 'size' => '150x150', 'quality' => 50, 'ratio' => FALSE)
);