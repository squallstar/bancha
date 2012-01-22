<?php
/**
 * Preivew Controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

defined('FRNAME') or die;

Class Core_Preview extends Bancha_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

		//We are always in staging here
		$this->content->set_stage(TRUE);

		$this->auth->needs_login();
	}

	public function content($type_name, $id)
	{
		$type = $this->content->type($type_name);

        //ACL Check
        $this->auth->check_permission('content', $type['name']);

        $record = $this->records->type($type_name)->get($id);
       	
       	if ($record instanceof Record)
       	{
       		$url = semantic_url($record);
       		if ($url != '#') {
       			redirect($url);
       		} else {
       			show_error(_('The are no pages that are listing this record.'));
       		}
       	}
	}
	
}