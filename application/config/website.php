<?php include(APPPATH . 'config/website.php');

/* ------------------------------------------------------------------------ */
/* ------------------------ BANCHA MAIN SETTINGS -------------------------- */
/* ------------------------------------------------------------------------ */

/*
|--------------------------------------------------------------------------
| 1. Installed themes
|--------------------------------------------------------------------------
|
| Here you can set the themes that you want to use.
| The key must be the exact name of the folder under the themes directory.
| To activate a theme, go to the "Theme" or "Settings" page on the admin.
|
*/
$config['installed_themes'] = array(
	'sandbox' => 'Sandbox'
);


/*
|--------------------------------------------------------------------------
| 2. Website languages
|--------------------------------------------------------------------------
|
| Here goes all the website available languages
|
*/
$config['website_languages'] = array(
	'en' => array(
		'name'			=> 'english',
		'locale'		=> 'en_US',
		'description'	=> 'English',
		'date_format'	=> 'Y-m-d'
	)
);

/*
|--------------------------------------------------------------------------
| 3. URI language prefix
|--------------------------------------------------------------------------
|
| When set to true, the current language will be prefixed all URIs.
|
| Example when is on:  www.example.org/it/path/to/page
| Example when is off: www.example.org/path/to/page
|
*/
$config['prepend_uri_language'] = FALSE;


/* ------------------------------------------------------------------------ */
/* --------------------- SECONDARY SETTINGS ------------------------------- */
/* ------------------------------------------------------------------------ */


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