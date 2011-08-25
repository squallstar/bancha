<?php
/**
 * Directory helper
 *
 * Funzioni di utilità per lavorare con le directory
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */


/**
 * Elimina una directory ed i file contenuti ricorsivamente
 * @param string $dir directory da eliminare
 */
function delete_directory($dir)
{
    if (!file_exists($dir)) return true;
    if (!is_dir($dir) || is_link($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
    	if ($item == '.' || $item == '..') continue;
    	if (!delete_directory($dir . "/" . $item)) {
    		chmod($dir . "/" . $item, 0777);
        	if (!delete_directory($dir . "/" . $item)) return false;
    	};
    }
    return rmdir($dir);
}