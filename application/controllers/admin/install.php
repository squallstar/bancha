<?php
/**
 * Install Controller
 *
 * This controller let you install the website+cms.
 * After the installation, feel free to remove this file or add a die(); at the start!
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Install extends Bancha_Controller
{
	public function __construct()
	{
	    parent::__construct();
	    $this->view->base = 'admin/';
	}

	public function index()
	{
		$this->load->frlibrary('installer');

		if ($this->input->post('install'))
		{
			if ($this->input->post('create_tables'))
			{
				//First of all, let's create some tables
				$this->installer->create_tables();

				//Then, let's add some indexes
				$this->installer->create_indexes();

				//We create a defaut user
				$username = 'admin';
				$password = 'admin';
				$this->installer->create_groups();
				$this->installer->create_user($username, $password, 'Alessandro', 'Maroldi');
				$this->auth->login($username, $password);
				$this->view->set('username', $username);
				$this->view->set('password', $password);
			}

			if ($this->input->post('create_directories'))
			{
				//We create the directories
				$this->installer->create_directories();
			}

			if ($this->input->post('create_types'))
			{
				//We create the default types
				$this->installer->create_types();
			}

			if ($this->input->post('create_types') || $this->input->post('clear_cache'))
			{
				//We clear the content types cache
				$this->tree->clear_cache();
			}

			if ($this->input->post('log_events'))
			{
				//Let's log the first event!
				$this->load->events();
				$this->events->log('install', null, CMS);
			}

			$premade = $this->input->post('premade');
			if ($premade && $premade != '')
			{
				$this->installer->create_premade($premade);
			}

			//We clear the previous database cache
			$this->db->cache_delete_all();

			$this->view->set('message', $this->lang->_trans('%n has been installed!', array('n' => CMS)));
			$this->view->render_layout('installer/success', FALSE);
			return;

		} else {
			$this->view->set('already_installed', $this->installer->is_already_installed());
			$this->view->render_layout('installer/request', FALSE);
		}





	}
}