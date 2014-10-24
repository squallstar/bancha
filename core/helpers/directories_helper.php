<?php
/**
 * Directories helper
 *
 * Some functions to work with directories
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */


/**
 * Completely deletes a directory and its files
 * @param string $dir The directory to remove
 */
if (!function_exists('delete_directory'))
{
	function delete_directory($dir)
	{
	    $dir = str_replace('//', '/', $dir);
	    if (!file_exists($dir)) return true;
	    if (!is_dir($dir) || is_link($dir)) return @unlink($dir);
	    foreach (scandir($dir) as $item) {
	    	if ($item == '.' || $item == '..') continue;
	    	if (!delete_directory($dir . "/" . $item)) {
	    		@chmod($dir . "/" . $item, 0777);
	        	if (!delete_directory($dir . "/" . $item)) return false;
	    	};
	    }
	    return rmdir($dir);
	}
}