<?php
if ( ! defined('CUSTOM_ACTION')) exit('This controller should be called only from the website controller');

/**
 * Website Custom Actions Controller
 *
 * This controller manage the custom actions of the website
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Actions
{
	public $CI;

	public function __construct()
	{
		$this->CI = & get_instance();
	}

	/**
	 * Dummy action
	 */
	function helloworld($who_calls)
	{
		if ($who_calls == 'dispatcher')
		{
			//We just render the default template
			$this->CI->view->render_template('default');
		}
		else if ($who_calls == 'content_render')
		{
			//This will be rendered by the content_render
			echo 'Hello world by the custom action';
		}

		
	}
}