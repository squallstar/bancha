<?php
/**
 * Docs Controller
 *
 * Documentazione di Bancha (amministrazione)
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Core_Docs extends Bancha_Controller {

	public function __construct() {
	    parent::__construct();
	    $this->load->database();
	    $this->view->base = 'admin/';

	    $this->auth->needs_login();

	}

	public function index() {
		$this->view->render_layout('docs/general');
	}

}