<?php
/**
 * Bancha_Controller
 *
 * Controller ereditato da tutti gli altri controllers
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Bancha_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//Sets the default content type
		$this->output->set_header('Content-Type: text/html; charset=UTF-8');

		//Loads the current language and .mo files
		$section = $this->uri->segment(1);
		$this->lang->check($section == rtrim(ADMIN_PUB_PATH, '/') ? 'admin' : 'website');

		//Loads the framework :)
		$this->load->bancha();
	}

	public function __destruct()
	{
		if (THEME_HOOKS && function_exists('hook_ondestruct')) {
			hook_ondestruct();
		}
	}
}