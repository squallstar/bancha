<?php
/**
 * Settings Controller
 *
 * Generic Configurations
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Core_Settings extends Bancha_Controller
{

	public function __construct()
	{
	    parent::__construct();
	    $this->load->database();

	    $this->content->set_stage(TRUE);

	    $this->view->base = 'admin/';

	    $this->auth->needs_login();
	    $this->auth->check_permission('settings', 'manage');

	    $this->load->settings();
	}

	public function index()
	{
		//We get the Users scheme
		$scheme_name = 'Settings.xml';
		$tipo = $this->xml->parse_scheme($this->config->item('xml_folder') . $scheme_name);

		$data = $this->input->post();

		if (is_array($data) && count($data))
		{
			//Update
			foreach ($data as $key => $val)
			{
				if (is_array($val))
				{
					foreach ($val as $module => $value)
					{
						$this->settings->set($key, $value, $module);
					}
				}
			}
			$this->settings->build_cache();
			$this->view->message('success', _('The settings has been updated'));

			//The theme may have been changed
			unset($_SESSION['_website_theme']);
		}

		//Additional set-ups before the page rendering
        foreach ($tipo['fields'] as $field_name => $field_value)
        {
        	if (isset($field_value['extract']))
            {
                //We extract the custom options
    			$tipo['fields'][$field_name]['options'] = $this->records->get_field_options($field_value);
        	}
        }

		$this->view->set('tipo', $tipo);
		$this->view->set('scheme_name', $scheme_name);
		$this->view->render_layout('content/settings_edit');
	}
}