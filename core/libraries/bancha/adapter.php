<?php
/**
 * Adapter Interface Class
 *
 * This class helps you to implement different adapters
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

interface Adapter extends Core
{
	/**
	 * @var array Returns all the accepted mimes of the adapter
	 */
	public function get_mimes();

	/**
	 * Parse some content and gives back an array of records (or saves it)
	 * @param mixed $stream The stream to parse
	 * @param bool $to_record Whether each records need to be return as a "Record" object or just an array
	 * @param string $type The default content type (used to create and save records)
	 * @param bool $autosave When set to TRUE, records will also be saved into the database
	 */
    public function parse_stream($stream, $to_record = TRUE, $type = '', $autosave = FALSE);
}