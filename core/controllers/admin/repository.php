<?php
/**
 * Repository Controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Core_Repository extends Bancha_Controller
{
	public function __construct() {
	    parent::__construct();
	    $this->load->database();
	    $this->view->base = 'admin/';

	    //We are always in staging here
	    $this->content->set_stage(TRUE);

	    $this->auth->needs_login();
	}

	public function index()
	{
		$this->load->documents();

		if (count($_FILES))
		{
			$this->documents->upload_to_repository($_FILES);
		}

		$repository = $this->documents->table('repository')
									  ->field('document')
									  ->limit(30)
									  ->order_by('id_document', 'DESC')
									  ->get();
		//Image presets
		$this->load->config('image_presets');
		$presets = $this->config->item('presets');

		$tmp = array('' => '');
		foreach ($presets as $key => $val)
		{
			$tmp[$key] = ucfirst($key);
		}

		$this->view->set('presets', $tmp);

		$this->view->set('repository_files', $repository);

		$this->view->render_layout('repository/list');
	}
}