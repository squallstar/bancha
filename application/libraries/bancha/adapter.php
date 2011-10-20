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
	/**
	 * @var array Returns all the accepted mimes of the adapter
	 */
	public function get_mimes();

	/**
	 * Parse some content and gives back an array of records
	 */
    public function parse_stream($stream, $to_record = TRUE);
}