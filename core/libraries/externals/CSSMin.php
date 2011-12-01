<?php
/**
 * Bancha CSS Minifier
 *
 * Simply compress a CSS File
 * Usage: CSSMin::compress($content)
 *
 * @package     Bancha
 * @author      Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright   Copyright (c) 2011, Squallstar
 * @license     GNU/GPL (General Public License)
 * @link        http://squallstar.it
 *
 */
class CSSMin
{
    public static function compress($buffer, $resources_root='')
    {
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);

        if ($resources_root != '')
        {
        	//The resources paths needs to be normalized using the new resources root
        	$buffer = preg_replace(
            "/url\('*([^)']+)'*+\)/e", "str_replace('../', '', '$resources_root$1')", $buffer
            );

        }

        return str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    }    
}
