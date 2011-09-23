<?php
/**
 * Auth Controller
 *
 * Login/Logout (amministrazione)
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Auth extends Bancha_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->view->base = 'admin/';
	}

	function index()
	{
		$this->login();
	}

	function login()
	{
		if ($this->auth->is_logged())
		{
			redirect('admin/dashboard');
		}

		if ($this->input->post('username'))
		{
			$logged = $this->auth->login(
				$this->input->post('username'),
				$this->input->post('password')
			);

			if ($logged)
			{
				$this->load->events();
				$this->events->log('login');
				redirect('admin/dashboard');
			} else {
				$this->view->set('message', _('Username/password wrong.'));
			}
		}

		$this->view->render_layout('auth/login', FALSE);
	}

	function logout()
	{
		$this->auth->logout();

		$this->load->events();
		$this->events->log('logout');

		redirect('admin/auth/login');
	}
}