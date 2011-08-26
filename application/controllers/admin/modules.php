<?php
/**
 * Modules Controller
 *
 * Lista e operazioni sui moduli installati
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Modules extends Milk_Controller
{

	public function __construct()
	{
	    parent::__construct();

	    $this->content->set_stage(TRUE);

	    $this->view->base = 'admin/';

	    $this->auth->needs_login();

	}

	public function index()
	{
		$modules = get_filenames($this->config->item('modules_folder'));
		$this->view->set('modules', $modules);
		$this->view->render_layout('modules/list');
	}

	public function docs($module)
	{
		$folder = $this->config->item('modules_folder') . $module;
		if (file_exists($folder . '/' . $module . '_docs'.EXT))
		{
			//Render docs
		} else {
			show_error($this->lang->_trans('The module %m has no documentation or not exists.', array('m' => $module)));
		}
	}

}