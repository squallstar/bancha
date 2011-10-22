<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Modules Controller
 *
 * Lista e operazioni sui moduli installati
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Modules extends Bancha_Controller
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
		$this->load->helper('directory');
		$modules = directory_map($this->config->item('modules_folder'), 1);
		$this->view->set('modules', $modules);
		$this->view->render_layout('modules/list');
	}

	public function install()
	{
		if (count($_FILES) && isset($_FILES['zip_module']))
		{
			$this->load->extlibrary('unzip');
			$this->unzip->allow(array('php'));

			$zip_file = $_FILES['zip_module'];
			$modules_folder = $this->config->item('modules_folder');
			$tmp_dirname = date('YmdHis');

			if ($this->unzip->extract($zip_file['tmp_name'], $modules_folder . $tmp_dirname))
			{

			} else {
				//Errors here
			}
		}
	}

	public function docs($module = '')
	{
		if ($module == '')
		{
			$this->index();
			return;
		}

		$folder = $this->config->item('modules_folder') . $module;
		$doc_file = $folder . '/' . $module . '_docs'.EXT;

		if (file_exists($doc_file))
		{
			//Render docs
			$this->view->set('documentation', read_file($doc_file));
			$this->view->set('module', $module);
			$this->view->render_layout('modules/docs');
		} else {
			show_error($this->lang->_trans('The module %m has no documentation or not exists.', array('m' => $module)));
		}
	}

}