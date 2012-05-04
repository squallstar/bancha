<?php include(APPPATH . 'config/website.php');

/* ------------------------------------------------------------------------ */
/* ------------------------ BANCHA MAIN SETTINGS -------------------------- */
/* ------------------------------------------------------------------------ */

/*
|--------------------------------------------------------------------------
| 2. Website languages
|--------------------------------------------------------------------------
|
| Here goes all the website available languages.
| You can copy this array from the core/config/website.php file.
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

/* ENCRYPT PASSWORDS
 * Select if the user passwords needs to be encrypted
 */
$config['encrypt_password'] = TRUE;

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