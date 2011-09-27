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

Class Website extends Bancha_Controller
{
	public function __construct()
	{
		parent::__construct();

		//If the user is logged in, we set the stage to true
		//so he/she can surf on the stage pages and records
		if ($this->auth->is_logged()) {
			$this->content->set_stage(TRUE);
			//We add also the preview bar
			$this->output->enable_profiler();
		} else {
			$this->content->set_stage(FALSE);
		}

		$this->load->helper('menu');
	}

	/**
	 * Website homepage
	 */
	function home()
	{
		//We need the default tree
		$this->view->set('tree', $this->tree->get_default());

		$this->view->javascript = array('jquery.js', 'application.js');
		$this->view->css = array('style.css');

		$this->view->render_template('home');
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
		redirect('/');
	}

	/**
	 * Website routing
	 * The default requests go here!
	 */
	function router()
	{
		$this->view->javascript = array('jquery.js', 'application.js');
		$this->view->css = array('style.css');

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
}