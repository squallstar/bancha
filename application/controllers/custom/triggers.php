<?php
require_once(APPPATH . 'controllers/custom/triggers.php');

/**
 * Website custom Triggers
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Triggers extends Core_Triggers
{
	public $CI;

	public function __construct()
	{
		$this->CI = & get_instance();
	}

	/**
	 * A dummy trigger
	 */
	function demotrigger($record)
	{
		log_message('error', 'Someone called me with the record named ' . $record->get('title'));
	}
}