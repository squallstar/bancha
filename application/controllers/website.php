<?php
/**
 * Website Main Controller
 *
 * The base front-end controller of the website
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'controllers/website.php');

Class Website extends Core_Website
{
	public function __construct()
	{
		parent::__construct();
	}
}