<?php
/**
 * Milk_Utf8
 *
 * Evita che gli input vengano puliti da CodeIgniter
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

class Milk_Utf8 extends CI_Utf8 {

	function clean_string($str)
	{
		return $str;
	}
}