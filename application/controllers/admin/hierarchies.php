<?php
/**
 * Hierarchies Controller
 *
 * This controller manage, creates and deletes the hierarchies
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Hierarchies extends Bancha_Controller
{

	public function __construct()
  	{
	    parent::__construct();

	    //We are always in staging here
	    $this->content->set_stage(TRUE);

	    //Views base path
	    $this->view->base = 'admin/';

	    //All actions needs user login
	    $this->auth->needs_login();

	    //Loads the hierarchies model
	    $this->load->hierarchies();
  	}

	public function index()
	{	
		if ($this->input->post('new'))
		{
			//New hierarchy
			$name = $this->input->post('name');
			$done = $this->hierarchies->add($name, $this->input->post('id_parent'));
			if ($done)
			{
				$this->view->message('success', $this->lang->_trans('The hierarchy %n has been added.', array('n' => '['.$name.']')));
			}
		}

		//We delete this hierarchies
		if ($data = $this->input->post('hierarchies'))
		{
			debug($data);
			$elements = $this->hierarchies->parse_data($data);
			$done = $this->hierarchies->delete($elements);
			if ($done)
			{
				$this->view->message('success', _('The selected hierarchies have been deleted.'));
			}
		}

		//We add a first blank element to the select
		$dropdown = $this->hierarchies->dropdown();
		$tmp = array('' => '');
		foreach ($dropdown as $key => $val)
		{
			$tmp[$key] = $val;
		}
		$dropdown = $tmp;

		$list = $this->hierarchies->get();
		$this->view->set('hierarchies', $list);
		$this->view->set('dropdown', $dropdown);
		$this->view->set('tree', $this->hierarchies->get_tree());

		$this->view->render_layout('hierarchies/list');
	}


}