<?php
/**
 * Schemes Controller
 *
 * -- development only --
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Core_Schemes extends Bancha_Controller
{

	public function __construct()
	{
	    parent::__construct();
	    $this->load->database();

	    $this->content->set_stage(TRUE);

	    $this->view->base = 'admin/';
	}

	public function index()
	{
		
	}

	public function rebuild($type_name)
	{
		$this->load->dbforge();
		$this->load->frlibrary('schemeforge');

		$type = $this->content->type($type_name);

		debug( $this->schemeforge->recreate_by_scheme($type) );
		return;
	}
}