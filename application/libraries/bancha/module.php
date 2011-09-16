<?php
/**
 * Module Class
 *
 * Libreria per gestire un modulo
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Bancha_Module
{
	/**
	 * @var mixed Reference alla View Library
	 */
	public $view;

	/**
	 * @var string The path of the files for this module
	 */
	public $module_filespath;

	/**
	 * @var string The name of the current module
	 */
	public $module_name;

	public function __construct()
	{
		$CI = & get_instance();
		$this->view = & $CI->view;
	}

	/**
	 * Renders a module, using the default view
	 * @param string $view The view template to use (default = 'view')
	 */
	public function render($view = 'view')
	{
		$CI = & get_instance();
		$module_name = strtolower(str_replace('_Module', '', get_class($this)));
		$CI->load->add_view_path($CI->config->item('modules_folder'));
		return $CI->load->view($module_name.DIRECTORY_SEPARATOR.$module_name.'_'.$view, $CI->view->get_data(), TRUE);
	}

	/**
	 * Loads a single class inside this module
	 * @param string $module_file The name of the class to load: Modulename_$classname
	 */
	public function load($classname)
	{
		$lower_name = strtolower($classname);

		$file_name = strtolower($this->module_name) . '_' . $lower_name . '.php';
		require_once $this->module_filespath . $file_name;

		$compiled_name = $this->module_name . '_' . ucfirst($classname);
		$this->$lower_name = new $compiled_name();
	}
}