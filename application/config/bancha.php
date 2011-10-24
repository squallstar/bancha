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

/* ADMIN LANGUAGES
 * Here goes the admin languages
 */
$config['admin_languages'] = array(
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
 * Framework version
 */
define('BANCHA_VERSION', '0.8.2');

/*
 * Framework name
 */
define('CMS', 'BANCHA');

/*
 * OS directory separator
 * - On windows systems, will be "\"
 * - On unix-like systems, will be "/"
 * This variabile will be used just in this file
 */
$sep = DIRECTORY_SEPARATOR;

/*
 * WEBSITE INSTALLED THEMES
 * Here you can set the themes to use.
 * To activate them, go to the settings section under "Manage".
 */
$config['installed_themes'] = array(
	'default' => 'Default theme',
	'minimal' => 'A minimal theme'
);

/*
* CACHE - CATEGORIES AND HIERARCHIES
* Defines if the categories and hierarchies queries can be cached on the filesystem.
* Feel free to disable this feature if your disk makes a lot of file read/write operations.
*/
define('CACHE', TRUE);

/*
* RECORDS PER PAGE
* The number of record extracted per page in the administration
*/
$config['records_per_page'] = 10;

/* FRAMEWORK PATH
 * The library path where Bancha classes are stored
 */
define('FRPATH', APPPATH . 'libraries' . $sep . FRNAME . $sep);

/*
* XML FOLDER
* This directory contains the general XML schemes
*/
$config['xml_folder'] = APPPATH . 'xml' . $sep;

/*
 * XML TYPES FOLDER
 * This directory contains the XML schemes of the content types
 */
$config['xml_typefolder'] = APPPATH . 'xml' . $sep . 'types' . $sep;

/*
 * MODULES FOLDER
 */
$config['modules_folder'] = APPPATH . 'modules' . $sep;

/*
 * CACHE FOLDER
 */
$config['fr_cache_folder'] = APPPATH . 'cache' . $sep . '_' . FRNAME. $sep;

/*
 * TYPES CACHE FILE
 * This file contains all the XML schemes parsed and cached into a serialized php array
 */
$config['types_cache_folder'] = $config['fr_cache_folder'] . 'content.tmp';

/*
* TYPES CACHE FILE
* Every page tree (one per content type) will be cached here
*/
$config['tree_cache_folder'] = $config['fr_cache_folder'] . 'tree-{name}.tmp';

/*
 * TEMPLATES DIRECTORY
 * The templates that Bancha uses to generate many kind of things
 */
$config['templates_folder'] = FRPATH . 'templates' . $sep;

/*
* CUSTOM CONTROLLERS DIRECTORY
* The custom controllers (such as the triggers controller) are placed here
*/
$config['custom_controllers_folder'] = APPPATH . 'controllers' . $sep . 'custom' . $sep;

/*
 * ATTACH MAIN DIRECTORY
 * Absolute path (internal) to the attachments directory
 */
$config['attach_folder'] = FCPATH . 'attach' . $sep;

/*
 * ATTACH MAIN DIRECTORY
 * The public path of the attachments directory
 */
$config['attach_out_folder'] = 'attach/';

/*
 * STRIP WEBSITE URL
 * When set to TRUE, the website URL will be removed from the textarea fields.
 * Could be useful to not store any reference to the absolute path of the website.
 */
$config['strip_website_url'] = TRUE;

/*
 * FEED URI
 * The segments that can be added to the URL to reach the feed of a page
 */
$config['feed_uri'] = array('feed.xml', 'feed.json', 'print.pdf');

/*
 * VIEWS TEMPLATES FOLDER
 * This directory contains the views of the content types (detail, list, etc..)
 */
$config['views_templates_folder'] = 'type_templates/';

/*
 * DEFAULT VIEW TEMPLATE
 * When not specified, pages will use this as their default template.
 * The file must be placed into the templates directory of a theme, named
 * with .php extension.
 */
$config['default_view_template'] = 'default';

/*
 * VIEW TEMPLATES TO COPY
 * The .php templates that will be copied from Bancha templates to the website theme.
 */
$config['view_templates_to_copy'] = array('detail', 'list', 'feed');

/*
 * VIEWS ABSOLUTE TEMPLATES FOLDER
 * Absolute path to the type templates (views).
 */
$config['views_absolute_templates_folder'] = APPPATH . 'views' . $sep . $config['views_templates_folder'];

/*
 * XML TRANSLATIONS PATH
 * The XML parser will save the translations here, as PHP labels
 */
$config['xml_translations_path'] = APPPATH . $sep . 'views' . $sep . 'admin' . $sep . 'content' . $sep . 'translations.php';

/*
 * RESTRICTED FIELD NAMES
 * The restriced names for the XML fields. They are not available due to internal use.
 */
$config['restricted_field_names'] = array(
	'xml', 'categories', 'hierarchies'
);

/*
 * DEFAULT TREE TYPE
 * The content types that will be used as the main tree of the website.
 */
$config['default_tree_types'] = array('Menu');

/*
 * DELETE DEAD RECORDS
 * When set to TRUE, the records linked to a content type will be deleted when
 * their content type will be removed.
 * After the installation, is raccomended to set it to TRUE
 */
$config['delete_dead_records'] = FALSE;

/*
 * RECORD COLUMNS
 * The physical columns of the records table.
 * The website router uses it when extracts the records when it doesn't know the type.
 * Keep it updated when you add columns that you want to extract without knowing the content type.
 */
$config['record_columns'] = array(
	'id_record',
	'id_type',
	'lang',
	'uri',
	'title',
	'date_update',
	'date_insert',
	'date_publish',
	'xml',
	'id_parent',
	'show_in_menu',
	'published',
	'priority',
	'child_count'
);

/*
 * DOCUMENTS SELECT FIELDS
 * The columns that will be extracted from the documents table.
 */
$config['documents_select_fields'] = array(
	'id_document',
	'name',
	'path',
	'size',
	'width',
	'height',
	'resized_path',
	'thumb_path',
	'alt_text',
	'bind_id',
	'bind_table',
	'bind_field',
	'mime',
	'priority'
);

/*
 * RECORD NOT LIVE COLUMNS
 * The columns that doesn't exists in the production tables.
 * Production: <table>
 * Stage: <table>_stage
 */
$config['record_not_live_columns'] = array(
	'published'
);

/*
 * PAGE EXTRACT COLUMNS
 * The columns that will be extracted for the pages table.
 */
$config['page_extract_columns'] = array(
	'id_record', 'uri', 'full_uri', 'title', 'id_parent', 'show_in_menu', 'priority'
);

/*
 * TREE LINEAR FIELDS
 * The columns that will be extracted when building the website menu
 */
$config['tree_linear_fields'] = array('id_record', 'title', 'uri');

/*
 * RECORD SELECT (TREE) FIELDS
 * When extracting a content type with a page structure, these columns
 * will be also extracted.
 */
$config['record_select_tree_fields'] = array('id_parent');

/*
 * ARRAY FIELD TYPES
 * These kind of fields will be store as arrays values
 */
$config['array_field_types'] = array('multiselect', 'hierarchy');

/*
 * CONTENT TYPE CUSTOM FEED
 * When is set to TRUE, type template file "feed.php" will be used to render the feeds
 */
$config['type_custom_feeds'] = TRUE;


/* End of file bancha.php */
/* Location: ./application/config/bancha.php */