<?php
/**
 * Themes Controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Themes extends Bancha_Controller
{
	public $themes;

	public function __construct()
	{
	    parent::__construct();

	    $this->content->set_stage(TRUE);
	    $this->view->base = 'admin/';

	    $this->auth->needs_login();
	    //$this->auth->check_permission('themes', 'manage');

	    $this->load->settings();
	    $this->themes = $this->config->item('installed_themes');
	}

	/**
	 * Shows the themes list and some triggers to change the active theme
	 */
	public function index()
	{
		$current_desktop_theme = $this->settings->get('website_desktop_theme');
		$current_mobile_theme = $this->settings->get('website_mobile_theme');

		if ($this->input->post(NULL, FALSE))
		{
			$new_deskop_theme = $this->input->post('desktop_theme');
			if ($current_desktop_theme != $new_deskop_theme)
			{
				$this->settings->set('website_desktop_theme', $new_deskop_theme);
				$current_desktop_theme = $new_deskop_theme;
			}

			$new_deskop_theme = $this->input->post('mobile_theme');
			if ($current_mobile_theme != $new_deskop_theme)
			{
				$this->settings->set('website_mobile_theme', $new_deskop_theme);
				$current_mobile_theme = $new_deskop_theme;
			}
			$this->view->message('success', _('The settings have been updated.'));
			$this->settings->clear_cache();
		}

		$this->view->set('themes', $this->themes);
		$this->view->set('desktop_theme', $current_desktop_theme);
		$this->view->set('mobile_theme', $current_mobile_theme);
		$this->view->render_layout('themes/list');
	}

	/**
	 * Shows a single theme, or a file of a theme
	 * @param string $name
	 */
	public function theme($name, $filename='')
	{
		if (isset($this->themes[$name]))
		{
			$theme_path = THEMESPATH . $name;
			$theme_templates_path = $theme_path . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
			if (file_exists($theme_templates_path))
			{
				$this->load->helper(array('file', 'text'));
				$files = get_filenames($theme_templates_path, TRUE, FALSE);

				$this->view->set('theme', $name);
				$this->view->set('theme_description', $this->themes[$name]);

				if ($filename == '')
				{
					$templates = array();
					$other_files = array();
					foreach ($files as $file) {
						if (strpos($file, '.php') !== FALSE)
						{
							$tmp = explode($theme_templates_path, $file);

							if (strpos($tmp[1], 'templates/') === 0)
							{
								$templates[] = $tmp[1];
							} else {
								$other_files[] = $tmp[1];
							}
						}
					}

					//Theme view
					$this->view->set('templates', $templates);
					$this->view->set('files', $other_files);
					$this->view->render_layout('themes/theme');
					return;
				} else {
					//Edit a single template
					$filename = str_replace('|', '/', urldecode($filename)) . '.php';
					$content = file_get_contents($theme_templates_path . $filename);

					$this->load->frlibrary('blocks');
					$blocks = $this->blocks->search_blocks($content);

					$filled_blocks = $this->blocks->fill_blocks($blocks, $name, $filename);

					$this->view->set('blocks', $filled_blocks);
					$this->view->set('template', $filename);
					$this->view->set('content', $content);
					$this->view->render_layout('themes/template_edit');
					return;
				}

			} else {
				show_error($this->lang->_trans('The folder %d has not been found on the filesystem.',
					array('d' => $theme_templates_path)
				));
			}
		} else {
			show_error($this->lang->_trans('The theme %n does not exists.', array('n' => $name)));
		}
	}

	public function add_section()
	{
		$block = $this->input->post('block');

	}
}