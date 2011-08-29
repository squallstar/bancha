<?php
/**
 * Website Helper
 *
 * Funzioni di utilità genera del sito (sia back-end che front-end)
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

function show_exception($e, $message, $status_code = 500, $heading = 'An Error Was Encountered')
{
	$_error =& load_class('Exceptions', 'core');
	echo $_error->show_exception($heading, $message, 'error_general', $status_code, $e);
	exit;
}

/**
 * Metodo che stampa a video in modo umano un oggetto
 * @param mixed $obj
 * @param string $title
 * @param bool $kill
 */
function debug($obj, $title='', $kill = FALSE) {
	echo "<pre>-------------------\r\n";
	if ($title != '') echo strtoupper($title)."\r\n";
	if (is_string($obj)) $obj = htmlentities($obj);
	var_dump($obj);
	echo "-------------------</pre>";
	if ($kill) die($kill);
}

function getter($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

/**
 * Wrapper della funzione site_url che aggiunge il path dell'amministrazione
 * @param string $str path da appendere
 */
function admin_url($str='') {
	return site_url('admin/'.$str);
}

/**
 * Ritorna l'url pubblico di un attachment
 * @param string $str path relativa
 */
function attach_url($str='') {
	return site_url(get_instance()->config->item('attach_out_folder') . str_replace('\\', '/', $str));
}