<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *   Image presets
 *
 *   Here you can set all the image presets!
 *   All the operations will be performed from the top to bottom of a preset
 *   For more informations about the usage, read the Bancha documentation
 *
 * - Base path of an image
 *   http://localhost/attach/blog/immagini/123/pic-1.jpg
 *
 * - Preset path with the preset named "user_profile"
 *   http://localhost/attach/cache/blog/immagini/123/user_profile/pic-1.jpg
 *
 *   To reset the preset cache, delete the directory /attach/cache
 *
 **/


$config['presets']['user_profile'] = array(
	array(
		'operation' => 'resize',
		'size' => '150x150',
		'fixed' => TRUE,
		'quality' => 100,
		'ratio' => TRUE
	),
	array(
		'operation' => 'crop',
		'size' => '125x125',
		'quality' => 80,
		'x' => 25,
		'y' => 25
	)
);

$config['presets']['standard'] = array(
	array(
		'operation' => 'resize',
		'size' => '640x?',
		'ratio' => TRUE,
		'quality' => 70
	)
);