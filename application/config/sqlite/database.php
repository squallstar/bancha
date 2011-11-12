<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE LOCAL SETTINGS
| -------------------------------------------------------------------
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = '';
$db['default']['username'] = '';
$db['default']['password'] = '';
$db['default']['database'] = 'sqlite:'.USERPATH.'config/sqlite/sqlite.db';
$db['default']['dbdriver'] = 'pdo';
$db['default']['dbprefix'] = 'bancha_';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = APPPATH . 'cache' . DIRECTORY_SEPARATOR . '_db' . DIRECTORY_SEPARATOR;
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */