<?php
if ( ! defined('CUSTOM_TRIGGER')) exit('You cannot call the triggers directly');

/**
 * Website custom Triggers
 *
 * Attivatori chiamabili dai tipi di contenuto
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Core_Triggers
{
	public $CI;

	public function __construct()
	{
		$this->CI = & get_instance();
	}

	/**
	 * Trigger/attivatore dimostrativo
	 */
	function demotrigger($record)
	{
		log_message('error', 'Someone called me with the record named ' . $record->get('title'));
	}
}