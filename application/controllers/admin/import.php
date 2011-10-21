<?php
/**
 * Import/Export Controller
 *
 * Importazione ed esportazioni di dati nel CMS
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Import extends Bancha_Controller
{
	public $adapters = array();

	public function __construct()
	{
	    parent::__construct();

	    $this->content->set_stage(TRUE);

	    $this->view->base = 'admin/';

	    $this->auth->needs_login();

	    $this->adapters = array(
	    	'csv'		=> 'CSV',
	    	'wordpress'	=> 'Wordpress'
		);

		$this->view->set('adapters', $this->adapters);

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

	public function step($which = 1) 
	{
		$adapter_type = $this->input->post('adapter_type');

		if (!in_array($adapter_type, array_keys($this->adapters)))
		{
			show_error(_('Adapter not found.'));
		}

		if (!isset($_FILES['records']) || !$_FILES['records']['tmp_name'])
		{
			$this->view->message('warning', _('You must upload a file.'));
			$this->index();
			return;
		} else {
			$file = $_FILES['records'];
		}

		$contents = file_get_contents($file['tmp_name']);
		$this->load->adapter($adapter_type);

		if (!in_array($file['type'], $this->adapter->mimes))
		{
			$this->view->message('warning', _('The mime-type of the file is not allowed by this adapter.'));
			$this->index();
			return;
		}

		$autosave = TRUE;

		$records = $this->adapter->parse_stream($contents, TRUE, $this->input->post('type_id'), $autosave);
		
		if (count($records) && !$autosave)
		{
			foreach ($records as $record)
			{
				if ($this->records->save($record))
				{
					$saved++;
				}
			}
		} else {
			$saved = count($records);
		}

		$this->view->set('records', $records);
		$this->view->message('success', $this->lang->_trans('%c records have been imported.', array('c' => $saved)));

		$this->view->render_layout('import/report');
	}


}