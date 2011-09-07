<?php
/**
 * Website Main Controller
 *
 * Controller base del front-end del sito internet
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
Class Website extends Milk_Controller {

	public function __construct() {
		parent::__construct();

		//Se l'utente è loggato, imposto lo stage come attivo
		if ($this->auth->is_logged()) {
			$this->content->set_stage(TRUE);
			$this->output->enable_profiler();
		} else {
			$this->content->set_stage(FALSE);
		}

		$this->load->helper('menu');
	}

	/**
	 * Website homepage
	 */
	function home() {
		//Estraggo il menu di default
		$this->view->set('tree', $this->tree->get_default());

		$this->view->javascript = array('jquery.js', 'application.js');
		$this->view->css = array('style.css');

		//Renderizzo il template home
		$this->view->render_template('home');
	}

	/**
	 * Cambia il theme del sito
	 * Invocata da: /go-{theme}
	 * @param string $new_language
	 */
	function change_theme($new_theme) {
		$this->view->set_theme($new_theme);
		redirect('/');
	}

	/**
	 * Cambia la lingua del sito
	 * Invocata da: /change-language/{lang}
	 * @param string $new_language
	 */
	function change_language($new_language) {
		$this->lang->set_lang($new_language);
		$this->lang->set_cookie();
		redirect('/');
	}

	/**
	 * Website Routing
	 * Metodo per il routing generale del front-end
	 */
	function router() {

		$this->view->javascript = array('jquery.js', 'application.js');
		$this->view->css = array('style.css');
		
		$this->load->dispatcher('default');
		$this->dispatcher->start();
	}
}