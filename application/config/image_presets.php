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

//Ridimensiona una immagine, tenendone le proporzioni e ogni lato di minimo 100px, poi la ritaglia a quadrato
$config['presets']['user_profile'] = array(
	array('operation' => 'resize', 'size' => '150x150', 'fixed' => TRUE, 'quality' => 100, 'ratio' => TRUE),
	array('operation' => 'crop', 'size' => '125x125', 'quality' => 80, 'x' => 25, 'y' => 25)
);

//Ridimensiona una immagine tenendo come larghezza massima 640px mantenendo le proporzioni.
$config['presets']['standard'] = array(
	array('operation' => 'resize', 'size' => '640x?', 'ratio' => TRUE, 'quality' => 70)
);