<?php
/**
 * Website Helper
 *
 * Some utilities for the website (back and front end)
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

/**
 * Dumps an object (or a variable)
 * @param mixed $obj
 * @param string $title
 * @param bool $kill
 */
function debug($obj, $title='*DEBUG*', $kill = FALSE)
{
	echo "<pre>-------------------\r\n";
	if ($title != '') echo strtoupper($title)."\r\n";
	if (is_string($obj)) $obj = htmlentities($obj);
	var_dump($obj);
	echo "-------------------</pre>";
	if ($kill) die($kill);
}

/**
 * Makes a simple GET cURL call to a webservice
 * @param string $url
 */
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
 * Returns the url of the administration
 * @param string $str path to append
 */
function admin_url($str='')
{
	return site_url('admin/'.$str);
}

/**
 * Returns the path of the current theme
 * @param string $str path to append
 */
function theme_url($str = '')
{
	return THEME_PUB_PATH . $str;
}

/**
 * Returns the public url of an attachment
 * @param string $str relative path
 */
function attach_url($str='')
{
	return site_url(config_item('attach_out_folder') . str_replace('\\', '/', $str));
}

/**
 * Returns the path of an image preset, given the path and the preset name to apply
 * @param $path image path
 * @param $preset preset name
 * @param $append_siteurl whether to prepend or not the website url
 */
function preset_url($path, $preset, $append_siteurl = TRUE) {
	if ($path && $preset)
	{
		//Prototype: attach/cache/type/field/id/preset/name.ext
		$tmp = explode(array('/', '\\'), trim($obj, '/'));
		$i = count($tmp)-1;
		$path = config_item('attach_out_folder') . '/cache/' . $tmp[$i-3] . '/' . $tmp[$i-2] . '/' . $tmp[$i-1] . '/' . $preset . '/' . $tmp[$i];
		return $append_siteurl ? site_url($path) : $path;
	}
	return '';
}