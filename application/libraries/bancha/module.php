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

	public $module_filespath;
	public $module_name;

	public function __construct()
	{
		$CI = & get_instance();
		$this->view = & $CI->view;
	}

	/**
	 * Renderizza un modulo
	 * @param string $view Nome della view da utilizzare (default = 'view')
	 */
	public function render($view = 'view')
	{
		$CI = & get_instance();
		$module_name = strtolower(str_replace('_Module', '', get_class($this)));
		$CI->load->add_view_path($CI->config->item('modules_folder'));
		return $CI->load->view($module_name.DIRECTORY_SEPARATOR.$module_name.'_'.$view, $CI->view->get_data(), TRUE);
	}

	public function load($module_file)
	{
		require_once $this->module_filespath . strtolower($module_file) . '.php';
		$class_name = $this->module_name . '_' . ucfirst($module_file);
		return new $class_name();
	}
}