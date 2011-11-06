<?php
/**
 * Dashboard Controller
 *
 * Panoramica (amministrazione)
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Core_Dashboard extends Bancha_Controller {

	public function __construct() {
	    parent::__construct();
	    $this->view->base = 'admin/';

	    $this->auth->needs_login();
	    $this->load->events();
	}

	public function index()
	{
		$this->welcome();
	}

	public function welcome($lang = '')
	{
		if ($lang != '')
		{
			$this->lang->set_lang($lang);
			$this->lang->set_cookie();
		}
		$this->view->render_layout('dashboard/intro');	
	}

	public function events($limit = 30)
	{
		if ($limit == 'all')
		{
			$limit = 999999;
		}
		$this->view->set('events', $this->events->get_last($limit));

		$this->view->render_layout('dashboard/events');
	}

}