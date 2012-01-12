<?php
/**
 * Module Class
 *
 * Libreria per gestire un modulo
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Bancha_Module extends Core
{
	/**
	 * @var string The path of the files for this module
	 */
	public $module_filespath;

	/**
	 * @var string The name of the current module
	 */
	public $module_name;

	private $_view_path_added = FALSE;

	public function __construct()
	{
		
	}

	/**
	 * Sets a variable as a class property
	 * @param string $key
	 * @param mixed $val
	 */
	public function _set_var($key, $val)
	{
		$this->$key = $val;
	}

	/**
	 * Renders a module, using the default view
	 * @param string $view The view template to use (default = 'view')
	 */
	public function render($view = 'view')
	{
		$module_name = strtolower(str_replace('_Module', '', get_class($this)));
		if (!$this->_view_path_added)
		{
			$this->_view_path_added = TRUE;
			$this->load->add_view_path($this->config->item('modules_folder'));
		}
		return $this->load->view($module_name.DIRECTORY_SEPARATOR.$module_name.'_'.$view, $this->view->get_data(), TRUE);
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