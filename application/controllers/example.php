<?php
/**
 * Example Controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Example extends Bancha_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo 'Hello world';
	}
}