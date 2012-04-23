<?php define('WEBSITE_CONTROLLER_EXISTS', TRUE);
/**
 * Website Main Controller
 *
 * The base front-end controller of the website
 * Check the core/controllers/website.php for available methods
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

require_once(APPPATH . 'controllers/website.php');

Class Website extends Core_Website
{
	public function __construct()
	{
		parent::__construct();
	}
}