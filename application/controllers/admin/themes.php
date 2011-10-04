<?php
/**
 * Themes Controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Themes extends Bancha_Controller
{

	public function __construct()
	{
	    parent::__construct();

	    $this->content->set_stage(TRUE);

	    $this->view->base = 'admin/';

	    $this->auth->needs_login();
	    //$this->auth->check_permission('themes', 'manage');

	    $this->load->settings();
	}

	public function index()
	{
		//$this->load->helper(array('file', 'text'));
		//$themes = get_filenames(THEMESPATH . 'default', TRUE, FALSE);
		//debug($themes);

		$this->view->set('themes', $this->config->item('installed_themes'));
		$this->view->render_layout('themes/list');
	}
}