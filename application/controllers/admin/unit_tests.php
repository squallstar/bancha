<?php
/**
 * Unit Tests Controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright		Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Unit_tests extends Bancha_Controller
{

	public function __construct()
	{
	    parent::__construct();

	    $this->content->set_stage(TRUE);

	    //All actions needs user login
	    $this->auth->needs_login();

	    $this->view->base = 'admin/';


	    return;
	}

	public function index() {
		$this->load->library('unit_test');
		
		$record = new Record('Blog');
		$msg = 'A sample text';
		$record->set('title', $msg);
		$id = $this->records->save($record);

		$this->unit->run($id, 'is_numeric', 'Insert new record');

		$record = $this->records->get($id);
		$this->unit->run($record, 'is_object', 'Search for a single record');

		$this->unit->run($record->get('title'), $msg, 'Tests a single field');

		$this->unit->run($this->records->delete_by_id($id), TRUE, 'Delete a record');

		$this->view->set('tests', $this->unit->report());
		$this->view->render_layout('unit_tests/results');
	}

}
