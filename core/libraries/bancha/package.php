<?php
/**
 * Package Interface Class
 *
 * You must implement this class to make a package
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

interface Bancha_Package
{
	/**
	 * @var string Return the package version
	 */
	public function title();

	/**
	 * @var string Return the package version
	 */
	public function version();

	/**
	 * @var string Return the package author
	 */
	public function author();

	/**
	 * Additional operations to perform after the install
	 * @optional public function install();
	 */

	/**
	 * Additional operations to perform before the uninstall
	 * @optional public function uninstall();
	 */
}