<?php
/**
 * View Library Class
 *
 * The library that renders the website views
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class View extends Core
{

	/**
	 * @var array The variables that will be passed to the view
	 */
	private $_data = array();

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
	 * @var string The previous rendered view
	 */
	public $previous_view = '';

	/**
	 * @var string The current view (changes during rendering)
	 */
	public $current_view = '';

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

	/**
	 * @var array Will be used as placemarker of the current URI context
	 * @see semantic_url() in website_helper
	 */
	public $semantic_url = array();

	/**
	 * @var array The flashmessages to show
	 */
	public $messages = array();

	public function __construct()
	{
		$this->load_theme();
	}

	/**
	 * Loads the current theme
	 * If it not exists in the session, will be loaded the default one (desktop or mobile)
	 */
	public function load_theme() {
		$this->theme = isset($_SESSION['_website_theme']) ? $_SESSION['_website_theme'] : FALSE;
		if (!$this->theme && !defined('DISABLE_SETTINGS'))
		{
			$this->load->library('user_agent');
			$this->load->settings();

			if (!$this->agent->is_mobile())
			{
				$this->theme = $this->settings->get('website_desktop_theme');
			} else {
				$this->theme = $this->settings->get('website_mobile_theme');
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
		$this->load->settings();

		switch ($new_theme)
		{
			case 'desktop':
			case 'tablet':
				$this->theme = $this->settings->get('website_desktop_theme');
				break;
			case 'mobile':
				$this->theme = $this->settings->get('website_mobile_theme');
				break;
		}

		return $this->store_theme();
	}

	/**
	 * Updates the cookie that stores the website current theme
	 */
	public function store_theme()
	{
		//We set a single cookie to help the Output class to send cached pages
		$_SESSION['_website_theme'] = $this->theme;
		
		return $this->update_ci_path();
	}

	/**
	 * Updates che CodeIgniter rendering view paths to include the current theme
	 */
	public function update_ci_path()
	{
		$theme_path = THEMESPATH . $this->theme . '/';
		$this->theme_path = site_url(null, FALSE) . $theme_path;
		$this->load->add_view_path($theme_path . 'views/');

		if (!defined('THEME_PUB_PATH'))
		{
			define('THEME_PUB_PATH', $this->theme_path);
		}
		return TRUE;
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

	/**
	 * Gets all the data of the view
	 * @return array
	 */
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
		return $this->load->view($this->base.$this->_layout_dir, array(
			'base'		=> $this->base,
			'content'	=> & $this->_data,
			'view'		=> $this->base.$view_file,
			'header'	=> $header,
			'title'		=> $this->title
		));
	}

	/**
	 * Renders a theme template
	 * If the second param is set to FALSE, the layout will not be rendered
	 * @param string $template_file
	 * @param bool $layout
	 * @param int $code HTTP code
	 * @param bool $return Whether the view needs to be returned or "echoed"
	 */
	public function render_template($template_file, $layout = TRUE, $code = '', $return = FALSE)
	{
		if ($template_file == '')
		{
			$template_file = 'default';
		}
		if (is_numeric($code))
		{
			$this->output->set_status_header($code);
			if ($code == 404)
			{
				log_message('error', 'Page not found: '.current_url());
			}
		}
		if ($layout)
		{
			$this->set('_template_file', $this->_template_dir.$template_file);
			return $this->load->view('layout', $this->_data, $return);
		} else {
			return $this->load->view($this->_template_dir.$template_file, $this->_data, $return);
		}
	}

	/**
	 * Renders the template of a content type
	 * @param string $type_name The name of the type
	 * @param string $view_file (detail, list, etc...)
	 * @param bool $propagate_data Whether to pass the local data to the view
	 */
	public function render_type_template($type_name='', $view_file='', $propagate_data = FALSE)
	{
		if ($type_name == '' || $view_file == '')
		{
			show_error('Content type or view name not set (view/render_type_template');
		}

		$type_templates = THEMESPATH . $this->theme . '/views/' . $this->config->item('views_templates_folder');


		if (file_exists($type_templates . $type_name . '/' . $view_file . '.php'))
		{
			$view_path = $this->config->item('views_templates_folder') . $type_name . '/' . $view_file;
		} else {
			$type = $this->content->type($type_name);
			$view_path = $this->config->item('views_templates_folder') . 'Default-' . ($type['tree'] ? 'Page' : 'Content') . '/' . $view_file;
		}
			
		$this->load->view($view_path, $propagate_data ? $this->_data : '');
	}

	/**
	 * Renders a single view file in the current theme
	 * @param string $view_file
	 */
	public function render($view_file, $data = array())
	{
		$this->load->view($view_file, $data);
	}

	/**
	 * Adds a flash message to the view
	 * @param success|warning|info|error $type
	 * @param string $message
	 */
	public function message($type, $message = '')
	{
		if ($message != '')
		{
			$this->messages[$type] = $message;
		}
	}

	/**
	 * Returns all the flash messages
	 * @return XHTML
	 */
	public function get_messages()
	{
		$tmp = '';
		if (count($this->messages))
		{
			foreach ($this->messages as $type => $message)
			{
				$tmp.= '<div class="message '.$type.'"><p>'.$message.'</p></div>';
			}
		}
		return $tmp;
	}

	/**
	 * Adds the live tags
	 * @return XHTML
	 */
	public function live_tags($field, $record)
	{
 		if ($this->output->has_profiler()
 			&& $this->auth->has_permission('content', $record->tipo))
 		{
 			return ' data-mode="edit" data-field="'.$field.'" data-type="'
 				   . $record->tipo
 				   . '" data-key="'.$record->id.'" data-fieldtype="'
 				   . $this->content->content_types[$record->_tipo]['fields'][$field]['type'].'"';
 		} else {
 			return '';
 		}
 	}

}