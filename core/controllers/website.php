<?php
/**
 * Website Main Controller
 *
 * The base front-end controller of the website
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Core_Website extends Bancha_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->settings();

		//If the user is logged in, we set the stage to true
		//so he/she can surf on the stage pages and records
		if ($this->auth->is_logged())
		{
			$this->content->set_stage(TRUE);

			//We add also the preview bar
			$this->output->enable_profiler();
		} else {
			$this->content->set_stage(FALSE);

			//If is not logged in, let's check if the website is under maintenance
			$maintenance = $this->settings->get('website_maintenance');
			if ($maintenance)
			{
				switch ($maintenance)
				{
					case 'T':
						show_error(_('The website is currently under maintenance. Please try later.'));
						break;
					case 'L':
						redirect(admin_url('?continue='.urlencode(site_url())));
				}
			}
		}

		$this->load->frlibrary('blocks');
		$this->load->helper('menu');
	}

	/**
	 * Website homepage routing
	 */
	function home()
	{
		$home = $this->settings->get('website_homepage_' . $this->lang->current_language);
		if ($home)
		{
			if ($this->config->item('prepend_uri_language'))
			{
				$this->config->prepend_language = $this->lang->current_language;
			}
			redirect(site_url($home), 'location', 301);
		} else {
			if (!$this->settings->get('is_installed'))
			{
				redirect(admin_url('install'));
			} else {
				show_error(_('The default homepage has not been set. Please go to the settings and update the website homepage.'));
			}
		}
	}

	/**
	 * Changes the website theme
	 * Called by: /go-{theme}
	 * @param string $new_language
	 */
	function change_theme($new_theme)
	{
		$this->view->set_theme($new_theme);
		redirect('/');
	}

	/**
	 * Changes the website language
	 * Called by: /change-language/{lang}
	 * @param string $new_language
	 */
	function change_language($new_language)
	{
		$this->lang->set_lang($new_language);
		$this->lang->set_cookie();
		$this->home();
	}

	/**
	 * Website routing
	 * The default requests go here!
	 */
	function router()
	{
		if (!(count($this->uri->segments)))
		{
			$this->home();
			return;
		}
		$this->load->dispatcher('default');
		$this->dispatcher->start();
	}

	/**
	 * Image routing
	 */
	function image_router($type, $field, $id, $preset, $filename, $ext)
	{
		$this->load->dispatcher('images');

		$data = array(
			'type'		=> $type,
			'field'		=> $field,
			'id'		=> $id,
			'preset'	=> $preset,
			'filename'	=> $filename,
			'ext'		=> $ext
		);

		$this->dispatcher->retrieve($data);
	}

	/**
	 * Resources minification routing
	 */
	function minify()
	{
		$resources = $this->input->get('f');
		$theme = $this->input->get('t');
		$version = $this->input->get('v');

		$this->load->dispatcher('resources');
		$this->dispatcher->minify($theme, $resources, $version);
	}
}

if (!class_exists('Website'))
{
	show_404();
}