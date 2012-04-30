<?php
/**
 * Packages Class
 *
 * This library let you install the modules
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Packages extends Core
{
	private $_modules_dir;

	public function __construct()
	{
		$this->load->helper('file');

		$this->_modules_dir = USERPATH . 'modules';
		$this->_check_moduledir();
	}

	private function _check_moduledir($module = null)
	{
		$dir = $module ? $this->_modules_dir . DIRECTORY_SEPARATOR . $module : $this->_modules_dir;
		if (!is_dir($dir)) {
			@mkdir($dir, DIR_WRITE_MODE, TRUE);
		}
	}

	/**
	 * Stream data extraction
	 * @param string $name
	 * @param stream $data
	 * @return bool
	 */
	public function install_data($name, $data)
	{
		$name = (string)$name;
		$this->_check_moduledir($name);
		$module_dir = USERPATH . 'modules' . DIRECTORY_SEPARATOR . $name;
		$package_file = $module_dir . DIRECTORY_SEPARATOR . 'package.zip';

		if (write_file($package_file, $data)) {
			$this->load->extlibrary('unzip');
			$this->unzip->extract($package_file, $module_dir);
			@unlink($package_file);

			return $this->_install($name);
		} else {
			//Error
			$this->view->message('warning', 'Cannot write package to ' . $module_dir);
			return FALSE;
		}
	}

	/**
	 * Local file extraction
	 * @param string $name
	 * @param stream $file
	 * @return bool
	 */
	public function install_file($name, $file)
	{
		$name = (string)$name;
		$this->_check_moduledir($name);
		$module_dir = USERPATH . 'modules' . DIRECTORY_SEPARATOR . $name;

		$this->load->extlibrary('unzip');
		$this->unzip->extract($file, $module_dir);
		@unlink($file);

		return $this->_install($name);
	}

	/**
	 * Install a package - must be called from install_data() or install_file()
	 * @param string $name
	 * @param stream $file
	 * @return bool
	 */
	private function _install($name)
	{
		$module_dir = USERPATH . 'modules' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;

		$installer = $module_dir . $name . '_package.php';
		if (file_exists($installer)) {
			require_once($installer);
			$classname = ucfirst($name) . '_Package';
			$package = new $classname();
			if (method_exists($package, 'install')) {
				$package->install();
			}
		}
		return TRUE;
	}

}