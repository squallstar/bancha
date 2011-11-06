<?php
/**
 * Bancha configuration
 *
 * The Bancha main configuration file
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include(APPPATH . 'config/website.php');


/* WEBSITE LANGUAGES
 * Here goes the website languages
 */
$config['website_languages'] = array(
	'en' => array(
		'name'			=> 'english',
		'locale'		=> 'en_US',
		'description'	=> 'English',
		'date_format'	=> 'Y-m-d'
	),
	'it' => array(
		'name'			=> 'italian',
		'locale'		=> 'it_IT',
		'description'	=> 'Italiano',
		'date_format'	=> 'd/m/Y'
	)
);

/*
 * PREPEND URI LANGUAGE
 * When set to true, the current language will be prepended all URIs.
 *
 * Example when is on:  www.example.org/it/path/to/page
 * Example when is off: www.example.org/path/to/page
 */
 $config['prepend_uri_language'] = TRUE;

/*
 * WEBSITE INSTALLED THEMES
 * Here you can set the themes to use.
 * To activate a theme, go to the settings section under "Manage".
 */
$config['installed_themes'] = array(
	'default' => 'Default theme',
	'minimal' => 'A minimal theme'
);

/*
* SHARED API TOKEN
* Defines whether a single username can handle multiple tokens or not.
* Set to TRUE to enable multiple tokens.
*/
$config['shared_api_token'] = FALSE;

/*
* RECORDS PER PAGE
* The number of record extracted per page in the administration
*/
$config['records_per_page'] = 15;

/*
 * STRIP WEBSITE URL
 * When set to TRUE, the website URL will be removed from the textarea fields.
 * Could be useful to not store any reference to the absolute path of the website.
 */
$config['strip_website_url'] = TRUE;

/*
 * DEFAULT TREE TYPE
 * The content types that will be used as the main tree of the website.
 */
$config['default_tree_types'] = array('Menu');


/* End of file bancha.php */
/* Location: ./application/config/website.php */