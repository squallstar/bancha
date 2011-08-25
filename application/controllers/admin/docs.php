<?php
/**
 * Docs Controller
 *
 * Documentazione di Milk (amministrazione)
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Docs extends Milk_Controller {

	public function __construct() {
	    parent::__construct();
	    $this->view->base = 'admin/';

	    $this->auth->needs_login();

	}

	public function index() {
		$this->view->render_layout('docs/general');
	}

}