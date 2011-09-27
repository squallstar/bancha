<?php
/**
 * Livemode Controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Livemode extends Bancha_Controller
{
	public function __construct()
	{
	    parent::__construct();
	    $this->view->base = 'admin/';
	}


}