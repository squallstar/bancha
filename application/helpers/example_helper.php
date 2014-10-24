<?php
/**
 * Example helper
 *
 * This script is an example of how to implement and use an helper
 *
 * How to load an inside the MVC:
 * $this->load->helper('helper_name');
 *
 * And on a theme:
 * load_helper('helper_name');
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if (!function_exists('example'))
{
	function example()
	{
		echo 'hello world';
	}
}