<?php
/**
 * Website Helper
 *
 * Some utilities for the website
 * ------------------------------
 * Please do not change the functions below.
 * Instead, feel free to copy and rename them.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

/**
 * Many helper functions access this global variable
 * to improve performances instead of using the bancha()
 * function declared below.
 */
$GLOBALS['B'] = CI_Controller::get_instance();

/**
 * Returns the Bancha singleton super-object
 * @return CI_Controller
 */
function &bancha()
{
	global $B;
	return $B;
}

/**
 * Dumps an object (or a variable)
 * @param mixed $obj
 * @param string $title
 * @param bool $kill
 */
function debug($obj, $kill = FALSE)
{
	echo "<pre>-------------------\r\n";
	if (is_string($obj)) $obj = htmlentities($obj);
	var_dump($obj);
	echo "-------------------</pre>";
	if ($kill) die($kill);
}

function show_400()
{
	show_error(_('You have no rights to access this page.'), 400);
}

/**
 * Makes a simple GET cURL call to a webservice
 * @param string $url
 */
function getter($url)
{
	if (!function_exists('curl_init')) {
		show_error('cURL PHP extension not found');
	}
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
	return site_url(ADMIN_PUB_PATH.$str, FALSE);
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
	return site_url(config_item('attach_out_folder') . str_replace('\\', '/', $str), FALSE);
}

/**
 * Returns the path of an image preset, given the path and the preset name to apply
 * @param $path image path
 * @param $preset preset name
 * @param $append_siteurl whether to prepend or not the website url
 */
function preset_url($path, $preset, $append_siteurl = TRUE)
{
	if ($path && $preset)
	{
		//Prototype: attach/cache/type/field/id/preset/name.ext
		$tmp = explode('/', trim(str_replace('\\', '/', $path), '/'));
		$i = count($tmp)-1;
		$path = config_item('attach_out_folder') . 'cache/' . $tmp[$i-3] . '/' . $tmp[$i-2] . '/' . $tmp[$i-1] . '/' . $preset . '/' . $tmp[$i];
		return $append_siteurl ? site_url($path, FALSE) : $path;
	}
	return '';
}

/**
 * Returns the path of a minified file with the provided resources
 * @param array|string $files One or more resources
 * @param int $version
 * @return string
 */
function minify($files = array(), $version='')
{
	global $B;
	if (is_string($files))
	{
		$files = array($files);
	}
	$current_theme = $B->view->theme;

	$ext = explode('.', $files[0]);
	$ext = $ext[count($ext)-1];

	$folder = config_item('attach_out_folder') . 'cache/resources-'.$ext.'/' . md5($current_theme.implode(',', $files));

	$params = '?t=' . $current_theme . '&f=' . urlencode(implode(',', $files));
	if ($version != '')
	{
		$folder.= '.' . $version;
		$params.= '&v=' . $version;
	}

	$folder.= '.' . $ext;

	return site_url($folder . $params, FALSE);
}

/**
 * This function tries to generate the detail url of the given Record
 * @param Record|string $object A record object, or just the slug to append
 */
function semantic_url($object = '')
{
	global $B;
	$view =& $B->view;
	$record = $view->get('record');
	$page = $view->get('page');

	$current_uri = $B->uri->uri_string;

	if ($object instanceof Record)
	{
		$type =& $B->content->type($object->_tipo);
		if ($type['tree']) {
			//Is a page
			$tree = $B->tree->get_linear();
			foreach ($tree as $single_page) {
				if ($single_page->id_record == $object->id) {
					return site_url($single_page->full_uri);
				}
			}
		}
		$uri = $object->get('uri');
	} else {
		$uri = (string)$object;
	}

	if (!$page)
	{
		//When the page is not set, we have no informations about the record so we need to search on the DB
		return site_url($B->pages->get_semantic_url($object->_tipo) . '/' . $uri);
	}

	if ($record)
	{
		if ($page->get('action') == 'single')
		{
			//We are in the detail page of a record. Let's replace the current detail URI with the new one
			$parent_uri = rtrim($current_uri, $record->get('uri'));
			return site_url($parent_uri . $uri);
		}
	} else {
		//We are in a page
		if ($page->get('action') == 'list')
		{
			//If the page is listing some records, we just add the new URI to the current one
			return site_url(rtrim($current_uri, '/') . '/' . $uri);
		}
	}

	//If we are here, is a normal page. We have to try to find another page that is listing this record
	if ($page && $object instanceof Record)
	{
		return site_url($B->pages->get_semantic_url($object->_tipo) . '/' . $uri);
	} else {
		//No more things to try on
		return '#';
	}
}
