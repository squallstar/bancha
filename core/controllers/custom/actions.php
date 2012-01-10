<?php
if ( ! defined('CUSTOM_ACTION')) exit('This controller should be called only from the website controller');

/**
 * Website Custom Actions Controller
 *
 * This controller manage the custom actions of the website
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Core_Actions
{
	public $CI;

	public function __construct()
	{
		$this->CI = & get_instance();
	}

	/**
	 * Demo action. Feel free to remove it!
	 */
	public function helloworld($who_calls)
	{
		if ($who_calls == 'dispatcher')
		{
			//We just render the default template
			$this->CI->view->render_template('default');
		}
		else if ($who_calls == 'content_render')
		{
			//This will be rendered inside the content_render
			echo 'Hello world by the custom action';
		}		
	}

	/**
	 * Renders a contact form using the "contact_form" module.
	 * Remember to use the "content_render" action mode when calling this action.
	 */
	public function contact_form()
	{
		$config = array(
			'action'	=> 'email',
			'from'		=> 'noreply@example.org',
			'to'		=> 'support@example.org',
			'subject'	=> 'New request received'
		);

		echo $this->CI->load->module('contact_form', $config)->render();
	}
}