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
							$tmp = explode($name . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR, $file);

							$tmp[1] = str_replace('\\', '/', $tmp[1]);

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

					$delete_section = $this->input->get('delete_section');
					if ($delete_section !== FALSE)
					{
						$block_name = $this->input->get('block');
						if (isset($filled_blocks[$block_name]))
						{
							unset($filled_blocks[$block_name][$delete_section]);
							if (!count($filled_blocks[$block_name]))
							{
								$filled_blocks[$block_name] = array();
							}
							$this->settings->set_block($block_name, $filled_blocks, $name, $filename);
							$this->settings->clear_cache();

							//Force the redirect to prevent another the URI with the GET parameters
							redirect(current_url());
						}
					}



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

	/**
	 * Method to add a new section (via an ajax request)
	 */
	public function add_section()
	{
		if ($this->input->is_ajax_request())
		{
			$this->load->frlibrary('blocks');
			$block_name = $this->input->post('block');
			$theme = $this->input->post('theme');
			$template = $this->input->post('template');

			$block = $this->settings->get_block($block_name, $theme, $template);
			if (!is_array($block))
			{
				$block = array();
				$pos = 0;
			} else {
				$pos = count($block);
			}

			$response = '';
			switch ($this->input->post('section_type'))
			{
				case 'html':
					$html = $this->input->post('html');
					if (strlen($html))
					{
						$block[$pos] = array(
							'type'	=> 'html',
							'data'	=> $html,
							'block'	=> $block_name
						);
						$response = $this->blocks->get_section_preview($block[$pos], $pos);
					}
					break;
				case 'code':
					$code = $this->input->post('code');
					if (strlen($code))
					{
						$block[$pos] = array(
							'type'	=> 'code',
							'data'	=> $code,
							'block'	=> $block_name
						);
						$response = $this->blocks->get_section_preview($block[$pos], $pos);
					}
					break;
			}
			$done = $this->settings->set_block($block_name, $block, $theme, $template);
			$this->settings->clear_cache();
			if ($done) echo $response;
			return;
		}
	}

	public function reorder_block()
	{
		if ($this->input->is_ajax_request())
		{
			$this->load->frlibrary('blocks');
			$block_name = $this->input->post('block');
			$theme = $this->input->post('theme');
			$template = $this->input->post('template');

			$block = $this->settings->get_block($block_name, $theme, $template);
			$new_block = array();
			$reordered_blocks = array();
			foreach ($block as $pos => $section)
			{
				$new_pos = $this->input->post($pos);
				$new_block[$new_pos] = $section;
			}

			for ($i=0; $i < count($new_block); $i++) { 
				$reordered_blocks[$i] = $new_block[$i];
			}

			$done = $this->settings->set_block($block_name, $reordered_blocks, $theme, $template);
			$this->settings->clear_cache();
			if ($done) echo 1;
			return;
			
		}

	}
}



