<?php
/**
 * Packages Class
 *
 * This library let you install the modules
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Packages extends Core
{
	private $_modules_dir;

	private $_loaded_modules_packages;

	public function __construct()
	{
		$this->load->helper('file');

		$this->_loaded_modules_packages = array();

		//Package interface
	    require_once(APPPATH . 'libraries/bancha/package.php');

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
	public function install_file($file)
	{
		if (!file_exists($file)) return FALSE;

		$tmp_name = 'tmp' . date('YmdHis');
		$module_dir = USERPATH . 'modules' . DIRECTORY_SEPARATOR;
		$tmp_dir = USERPATH . 'modules' . DIRECTORY_SEPARATOR . $tmp_name;

		$this->_check_moduledir($tmp_name);

		$this->load->extlibrary('unzip');
		$this->unzip->extract($file, $tmp_dir);
		@unlink($file);

		$package = $this->get_module_package($tmp_name, TRUE);

		$module_dir .= $package->name() . DIRECTORY_SEPARATOR;

		if ( !rename($tmp_dir, $module_dir) ) {
			show_error("Cannot rename $tmp_dir to $module_dir");
		}
		return $this->_install($package->name());
	}

	/**
	 * Install a package - must be called from install_data() or install_file()
	 * @param string $name
	 * @param stream $file
	 * @return bool
	 */
	private function _install($name)
	{
		$package = $this->get_module_package($name);
		if ($package) {
			if (method_exists($package, 'install')) {
				$package->install();
			}
		}
		return TRUE;
	}

	/**
	 * Uninstall a package
	 * @param string $name
	 * @param stream $file
	 * @return bool
	 */
	public function uninstall($name)
	{
		$package = $this->get_module_package($name);
		if ($package) {
			if (method_exists($package, 'uninstall')) {
				$package->uninstall();
			}
		}

		$this->load->helper('directories');
		return delete_directory($this->_modules_dir . DIRECTORY_SEPARATOR . $name);
	}

	/**
	 * Gets the package class of a module
	 * @param string $name
	 * @return Package Object
	 */
	public function get_module_package($name, $first_found = FALSE)
	{
		if (isset($this->_loaded_modules_packages[$name])) {
			return $this->_loaded_modules_packages[$name];
		}

		$module_dir = USERPATH . 'modules' . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
		$installer = $module_dir . 'package.php';

		if (file_exists($installer)) {
			if (!class_exists(ucfirst($name) . '_Package')) {
				require_once($installer);
			}

			if ($first_found) {
				$classes = get_declared_classes();
				foreach ($classes as $class) {
					if (strpos($class, '_Package') !== FALSE) {
						$classname = $class;
						break;
					}
				}
			} else {
				$classname = ucfirst($name) . '_Package';
			}

			if (!isset($classname)) {
				show_error("Package class not found inside $name");
			}

			$package = new $classname();
			$this->_loaded_modules_packages[$name] = $package;
			return $package;
		} else {
			show_error("The module $name does not implement the Package class.");
		}
		return FALSE;
	}

}