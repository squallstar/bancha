<?php
/**
 * Import/Export Controller
 *
 * Importazione ed esportazioni di dati nel CMS
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Import extends Milk_Controller
{

	public function __construct()
	{
	    parent::__construct();

	    $this->content->set_stage(TRUE);

	    $this->view->base = 'admin/';

	    $this->auth->needs_login();

	}

	public function index()
	{
		$types = $this->content->types();
		$tipi = array();
		$tipi[''] = _('Choose one...');
		foreach ($types as $type)
		{
			$tipi[$type['id']] = $type['name'];
		}
		
		$this->view->set('tipi', $tipi);
		
		$this->view->render_layout('import/records');
	}

}