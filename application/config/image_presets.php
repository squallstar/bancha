<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Image presets
 *
 * Questo file definisce tutti i preset utilizzabili per le immagini
 * Per utilizzare un preset dopo averlo definito:
 *
 * - Path normale di una immagine
 *   http://localhost/attach/blog/immagini/123/pic-1.jpg
 *
 * - Path con il preset chiamato "user_profile"
 *   http://localhost/attach/cache/blog/immagini/123/user_profike/pic-1.jpg
 *
 * Per eliminare la cache generata dai preset, eliminare la directory /attach/cache
 *
 **/

//Ridimensiona una immagine, tenendone le proporzioni
$config['presets']['user_profile'] = array(
	array('operation' => 'resize', 'size' => '100x100', 'quality' => 85, 'ratio' => TRUE)
);

//Ritaglia una immagine e poi la ridimensiona senza tenere conto delle proporzioni (stretch)
$config['presets']['demo_preset'] = array(
	array('operation' => 'crop', 'size' => '320x?', 'quality' => 100, 'ratio' => FALSE, 'x' => 50, 'y' => 50),
	array('operation' => 'resize', 'size' => '150x150', 'quality' => 85, 'ratio' => FALSE)
);