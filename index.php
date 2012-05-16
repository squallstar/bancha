<?php
/**
 * Bancha Requests Collector (Index file)
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

/*
 *---------------------------------------------------------------
 * BANCHA APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * development	: the default environment (DB: application/config/database.php)
 * sqlite		: uses the sqlite internal database	(DB: application/config/sqlite/database.php)
 * production	: errors will not be displayed
 *
 */
	define('ENVIRONMENT', 'development');
/*
 */

if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'sqlite':
		case 'development':
			error_reporting(-1);
		break;

		case 'testing':
		case 'production':
			error_reporting(0);
		break;

		default:
			exit('Bancha application environment is not set correctly.');
	}
}

/*
 *---------------------------------------------------------------
 * CORE FOLDER NAME
 *---------------------------------------------------------------
 *
 * NO TRAILING SLASH!
 *
 */
	$core_folder = 'core';

/*
 *---------------------------------------------------------------
 * USER APPLICATION FOLDER NAME
 *---------------------------------------------------------------
 *
 * NO TRAILING SLASH!
 *
 */
	$user_path = 'application';

/*
 *---------------------------------------------------------------
 * CODEIGNITER SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 *
 */
	$system_path = $core_folder . '/libraries/codeigniter';

/*
 *---------------------------------------------------------------
 * THEMES FOLDER NAME
 *---------------------------------------------------------------
 *
 * NO TRAILING SLASH!
 *
 */
	$themes_folder = 'themes';


/*
 *---------------------------------------------------------------
 * ADMIN PUBLIC PATH
 *---------------------------------------------------------------
 *
 */
	$admin_path = 'admin/';


// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

	// Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}

	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}

	// ensure there's a trailing slash
	$system_path = rtrim($system_path, '/').'/';

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	define('EXT', '.php');

	// Path to the system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// Path to the front controller (this file)
	define('FCPATH', str_replace(SELF, '', __FILE__));

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

	//The administration public path
	define('ADMIN_PUB_PATH', $admin_path);

	// The path to the core folder
	if (is_dir($core_folder))
	{
		define('APPPATH', $core_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$core_folder.'/'))
		{
			exit("Your core folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$core_folder.'/');
	}

	// The path to the "application" folder
	if (is_dir($themes_folder))
	{
		define('THEMESPATH', $themes_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$themes_folder.'/'))
		{
			exit("Your themes folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('THEMESPATH', BASEPATH.$themes_folder.'/');
	}

	define('USERPATH', $user_path.'/');

	//New constant added in CI 2.1
	define ('VIEWPATH', APPPATH.'views/' );

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */

session_start();
require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */