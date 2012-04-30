<?php defined('BANCHA') or exit;

/*
|--------------------------------------------------------------------------
| Application Image Presets
|--------------------------------------------------------------------------
|
|   Here you can set all the image presets!
|   All the operations will be performed from the top to bottom of a preset
|   For more informations about the usage, read the Bancha documentation:
|
|   http://docs.getbancha.com/framework/core/imagepresets.html
|
*/

//I'm just a demo preset
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