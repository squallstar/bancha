<?php
/**
 * CSV Adapter Class
 *
 * ...
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Adapter_csv implements Adapter
{
	public function __construct()
	{
		$this->mimes = array(
			'text/csv', 'text/comma-separated-values'	
		);
	}
	public function parse_stream($stream)
	{
		//debug($stream);
	}
}