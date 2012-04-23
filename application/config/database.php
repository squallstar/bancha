<?php  if ( ! defined('BASEPATH')) die;
/*
| -------------------------------------------------------------------
| BANCHA DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
*/

$db['default']['hostname'] = '127.0.0.1';
$db['default']['username'] = 'root';
$db['default']['password'] = 'root';
$db['default']['database'] = 'demo';

/* Database Driver
 * Available drivers are: mssql, mysql, mysqli, oci8, odbc, pdo, postgre, sqlite, sqlsrv.
 */
$db['default']['dbdriver'] = 'mysql';

/* Table prefix of the Bancha generated tables */
$db['default']['dbprefix'] = 'bancha_';

/*
 * DB Debug
 * - TRUE: query errors will be displayed on screen
 * - FALSE: query errors will be logged - the user will see a blank page
 * As you can see, by default, is disabled while in production environment
 */
$db['default']['db_debug'] = ENVIRONMENT == 'production' ? FALSE : TRUE;

/* Queries cache directory */
$db['default']['cachedir'] = USERPATH . 'cache' . DIRECTORY_SEPARATOR . '_db' . DIRECTORY_SEPARATOR;

/* Charset */
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';

/* Other settings (tipically you don't have to change these) */
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;
$db['default']['pconnect'] = TRUE;
$db['default']['cache_on'] = FALSE;
$active_group = 'default';
$active_record = TRUE;


/* End of file database.php */
/* Location: ./application/config/database.php */