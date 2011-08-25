<?php
/**
 * Auth Controller
 *
 * Login/Logout (amministrazione)
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Auth extends Milk_Controller {

	public function __construct() {
	    parent::__construct();
	    $this->view->base = 'admin/';
	}

	function index() {
		$this->login();
	}

	function login() {

		if ($this->auth->is_logged()) {
			redirect('admin/dashboard');
		}

		if ($this->input->post('username')) {

			$logged = $this->auth->login(
				$this->input->post('username'),
				$this->input->post('password')
			);

			if ($logged) {
				redirect('admin/dashboard');
			} else {
				$this->view->set('message', 'Username e/o password errati.');
			}
		}

		$this->view->render_layout('auth/login', FALSE);
	}

	function logout() {
		$this->auth->logout();
		redirect('admin/auth/login');
	}

}