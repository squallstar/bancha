<?php
/**
 * View Library Class
 *
 * The library that renders the website views
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class View
{

	/**
	 * @var mixed CodeIgniter instance
	 */
	private $_CI;

	/**
	 * @var array The variables that will be passed to the view
	 */
	private $_data = array();

	/**
	 * @var string The directory appended to rendering path
	 */
	private $_prepend_dir;

	/**
	 * @var string The theme templates directory
	 */
	private $_template_dir = 'templates/';

	/**
	 * @var string This path contains the layouts of the admin
	 */
	private $_layout_dir = 'layout/layout';

	/**
	 * @var array List of the rendered views
	 */
	public $rendered_views = array();

	/**
	 * @var string The views base_path
	 */
	public $base = '';

	/**
	 * @var string Title tag (head)
	 */
	public $title = '';

	/**
	 * @var string Keywords (meta tag)
	 */
	public $keywords = '';

	/**
	 * @var string Description (meta tag
	 */
	public $description = '';

	/**
	 * @var array Javascript resources
	 */
	public $javascript = array();

	/**
	 * @var mixed array List of CSS
	 */
	public $css = array();

	/**
	 * @var string Author of the page (meta tag)
	 */
	public $author = '';

	/**
	 * @var array Available themes on the front-end
	 */
	public $themes = array();

	/**
	 * @var string Current theme
	 */
	public $theme = '';

	/**
	 * @var string Themes directory
	 */
	public $theme_path = '';

	/**
	 * @var bool Choose if the view has a linked feed
	 */
	public $has_feed = FALSE;

	/**
	 * @var bool Choose if this view is a feed (xml/json)
	 */
	public $is_feed = FALSE;

	public function __construct()
	{
		$this->_CI = & get_instance();
		$this->themes = $this->_CI->config->item('website_themes');
		$this->_prepend_dir = $this->_CI->config->item('website_views_folder');
		$this->load_theme();
	}

	/**
	 * Loads the current theme
	 * If it not exists in the session, will be loaded the default one (desktop or mobile)
	 */
	public function load_theme() {
		$this->theme = $this->_CI->session->userdata('_website_theme');
		if (!$this->theme)
		{
			$this->_CI->load->library('user_agent');
			if ($this->_CI->agent->is_mobile() && isset($this->themes['mobile']))
			{
				$this->theme = $this->themes['mobile'];
			} else {
				$this->theme = $this->themes['desktop'];
			}
			$this->store_theme();
		}
		$this->update_ci_path();
	}

	/**
	 * Sets a new theme as active
	 * @param string $new_theme
	 */
	public function set_theme($new_theme)
	{
		if (isset($this->themes[$new_theme]))
		{
			$this->theme = $this->themes[$new_theme];
			$this->store_theme();
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Updates the cookie that stores the website current theme
	 */
	public function store_theme()
	{
		$this->_CI->session->set_userdata('_website_theme', $this->theme);
		$this->update_ci_path();
	}

	/**
	 * Updates che CodeIgniter rendering view paths to include the current theme
	 */
	public function update_ci_path()
	{
		$theme_path = THEMESPATH . $this->theme . '/';
		$this->theme_path = site_url() . $theme_path;
		$this->_CI->load->add_view_path($theme_path . 'views/');

		if (!defined('THEME_PUB_PATH'))
		{
			define('THEME_PUB_PATH', $this->theme_path);
		}
	}

	/**
	 * Sets a value to be passed to the views during their rendering
	 * @param string $key
	 * @param mixed $val
	 */
	public function set($key, $val)
	{
		$this->_data[$key] = $val;
	}

	/**
	 * Returns a pre-setted value from the view
	 * @param string $key
	 */
	public function get($key)
	{
		if (isset($this->_data[$key]))
		{
			return $this->_data[$key];
		} else return FALSE;
	}

	public function get_data()
	{
		return $this->_data;
	}

	/**
	 * Removes a value from the view
	 * @param string $key
	 */
	public function remove($key)
	{
		unset($this->_data[$key]);
	}

	/**
	 * Renders a layout using the base structure.
	 * This function is used only by the administration!!!
	 * @param string $view_file
	*/
	public function render_layout($view_file, $header=true)
	{
		return $this->_CI->load->view($this->base.$this->_layout_dir, array(
			'base' => $this->base,
			'content'	=> & $this->_data,
			'view' => $this->base.$view_file,
			'header' => $header,
			'title'	=> $this->title
		));
	}

	/**
	 * Renders a theme template
	 * If the second param is set to FALSE, the layout will not be rendered
	 * @param string $template_file
	 * @param bool $layout
	 * @param int $code HTTP code
	 */
	public function render_template($template_file, $layout = TRUE, $code = '')
	{
		if (is_numeric($code))
		{
			$this->_CI->output->set_status_header($code);
			if ($code == 404)
			{
				log_message('error', 'Page not found: '.current_url());
			}
		}
		if ($layout)
		{
			$this->set('_template_file', $this->_template_dir.$template_file);
			$this->_CI->load->view('layout', $this->_data);
		} else {
			$this->_CI->load->view($this->_template_dir.$template_file, $this->_data);
		}
	}

	/**
	 * Renders the template of a content type
	 * @param string $type_name The name of the type
	 * @param string $view_file (detail, list, etc...)
	 */
	public function render_type_template($type_name='', $view_file='')
	{
		if ($type_name == '' || $view_file == '')
		{
			show_error('Tipo o nome view non settato (view/render_type_template');
		}
		$view_path = $this->_CI->config->item('views_templates_folder') . $type_name . '/' . $view_file;

		$this->_CI->load->view($view_path);
	}

	/**
	 * Renders a single view file in the current theme
	 * @param string $view_file
	 */
	public function render($view_file)
	{
		$this->_CI->load->view($view_file);
	}

}