<?php
/**
 * Ajax Controller
 *
 * (amministrazione)
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Ajax extends Bancha_Controller
{

	public function __construct()
	{
	    parent::__construct();

	    $this->content->set_stage(TRUE);

	    $this->view->base = 'admin/';

	    $this->auth->needs_login();

	}

	public function can_use_uri()
	{

		$uri = $this->input->post('uri', '');
		$uri = $this->records->get_safe_uri($uri);
		$edit_record_id = $this->input->post('id_record', '');

		if ($uri && $uri != '')
		{
			$record = $this->records->uri_is_used($uri);
			if ($record)
			{
				$err = 'Questo URI &egrave; utilizzato dal contenuto [<a href="'.admin_url('contents/edit_record/'.$record->_tipo.'/'.$record->id).'">'.$record->get('title').'</a>]';
				if ($edit_record_id)
				{
					if ($edit_record_id != $record->id)
					{
						echo $err;
						return;
					}
				} else {
					echo $err;
					return;
				}
			}
		}
		return;
	}

	public function delete_document()
	{
		$id = $this->input->post('document_id');
		if ($id != '')
		{
			$this->load->documents();
			echo $this->documents->delete_by_id($id) ? 1 : 0;
		}
	}

	public function finder($id_record='undefined', $type='')
	{
		if ($id_record != 'undefined')
		{
			$this->load->documents();

			if ($type != '')
			{
				$tipo = $this->content->type($type);
				$table = $tipo['table'];
			} else {
				$table = 'records';
			}

			$documents = $this->documents->table($table)
										 ->id($id_record)
										 ->get();

			$this->view->set('documents', $documents);

		} else {
			show_error(_('This content has no attachments.'));
		}

		$this->view->render_layout('content/documents_finder');
	}

}