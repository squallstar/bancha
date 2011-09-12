<?php
/**
 * Hierarchies Controller
 *
 * This controller manage, creates and deletes the hierarchies
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Hierarchies extends Milk_Controller
{

	public function __construct()
  	{
	    parent::__construct();

	    //We are always in staging here
	    $this->content->set_stage(TRUE);

	    //Views base path
	    $this->view->base = 'admin/';

	    //All actions needs user login
	    $this->auth->needs_login();

	    //Loads the hierarchies model
	    $this->load->hierarchies();
  	}

	public function index()
	{
		$list = $this->hierarchies->get();
		$this->view->set('hierarchies', $list);
		$this->view->set('dropdown', $this->hierarchies->dropdown());

		$this->view->render_layout('hierarchies/list');
	}


}