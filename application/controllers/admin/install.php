<?php
/**
 * Install Controller
 *
 * Installazione sito
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Install extends Milk_Controller
{
	public function __construct()
	{
	    parent::__construct();
	    $this->view->base = 'admin/';
	}

	public function index()
	{
		$this->load->library('milk/installer');

		if ($this->input->post('install'))
		{
			if ($this->input->post('create_tables'))
			{
				//Creo le tabelle
				$this->installer->create_tables();

				//Creo gli indici
				$this->installer->create_indexes();

				//Creo un utente ed i relativi permessi
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
				//Creo le directory
				$this->installer->create_directories();
			}

			if ($this->input->post('create_types'))
			{
				//Creo i tipi predefiniti
				$this->installer->create_types();
			}

			if ($this->input->post('create_types') || $this->input->post('clear_cache'))
			{
				//Svuoto la cache
				$this->tree->clear_cache();
			}

			if ($this->input->post('log_events'))
			{
				//Loggo il primo evento
				$this->load->events();
				$this->events->log('install', null, CMS);
			}

			$premade = $this->input->post('premade');
			if ($premade && $premade != '')
			{
				$this->installer->create_premade($premade);
			}

			$this->view->set('message', $this->lang->_trans('%n has been installed!', array('n' => CMS)));
			$this->view->render_layout('installer/success', FALSE);
			return;

		} else {
			$this->view->render_layout('installer/request', FALSE);
		}





	}
}