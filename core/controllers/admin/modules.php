<?php defined('BANCHA') or exit;
/**
 * Modules Controller
 *
 * See: http://docs.getbancha.com/modules
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Core_Modules extends Bancha_Controller
{
	public function __construct()
	{
	    parent::__construct();
	    $this->load->database();
	    $this->content->set_stage(TRUE);
	    $this->view->base = 'admin/';

	    $this->auth->needs_login();
	}

	public function index()
	{
		$this->load->helper('directory');
		$this->load->frlibrary('packages');

		$modules_dir = USERPATH . 'modules';

		$package = $this->input->post('package');
		$slug = $this->input->post('slug');

		$done = -1;

		if ($package && $slug)
		{
			$data = getter($package);
			$done = $this->packages->install_data($slug, $data);
		} else if (count($_FILES) && isset($_FILES['package']['tmp_name'])) {
			$slug = str_replace('.zip', '', $_FILES['package']['name']);
			$done = $this->packages->install_file($slug, $_FILES['package']['tmp_name']);
		}

		//Alerts
		if ($done !== -1) {
			if ($done) {
				$this->view->message('success', _('The module has been installed.'));
			} else {
				$this->view->message('warning', _('The module can not be installed right now.'));
			}
		}


		$modules = directory_map($modules_dir, 1);	

		//Filter non-directories
		$compiled_modules = array();
		foreach ($modules as $pos => $module) {
			if (is_dir($modules_dir . DIRECTORY_SEPARATOR . $module)) {
				$package = $this->packages->get_module_package($module);
				$compiled_modules[$module] = $package;
			}
		}
		$this->view->set('modules', $compiled_modules);
		$this->view->render_layout('modules/list');
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