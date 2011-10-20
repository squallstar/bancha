<?php
/**
 * Adapter Interface Class
 *
 * This class helps you to implement different adapters
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

interface Adapter
{
	public $mimes;
    public function parse_stream($stream);
}