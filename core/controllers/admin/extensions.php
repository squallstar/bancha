<?php defined('BANCHA') or exit;
/**
 * Extensions Controller
 *
 * See: http://docs.getbancha.com/modules
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Core_Extensions extends Bancha_Controller
{
	public function __construct()
	{
	    parent::__construct();
	    $this->load->database();
	    $this->content->set_stage(TRUE);
	    $this->view->base = 'admin/';

	    $this->auth->needs_login();
	}

	public function package($name = '', $controller = '', $action = 'index')
	{
		$package_path = USERPATH . 'modules' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'extend' . DIRECTORY_SEPARATOR;

		if ($controller == '') {
			$controller = $name;
		}

		$path = $package_path . 'controllers/' . $controller . '.php';
		if (!file_exists($path)) {
			show_error("The controller has not been found here: $path");
		}
		require_once($path);

		$classname = ucfirst($controller) . '_Controller';
		if (!class_exists($classname)) {
			show_error("The class $classname has not been implemented inside the file $path");
		}

		$c = new $classname();
		if (!method_exists($c, $action)) {
			show_error("The controller $classname does not implement the $action method.");
		}
		$c->$action();
	}
}