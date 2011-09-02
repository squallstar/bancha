<?php
/**
 * View Library Class
 *
 * Classe per la gestione e rendering di una view
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
	 * @var mixed Istanza di CodeIgniter
	 */
	private $_CI;

	/**
	 * @var array Dati da passare alle view
	 */
	private $_data = array();

	/**
	 * @var string Directory da prependere ai render
	 */
	private $_prepend_dir;

	/**
	 * @var string Directory dei templates di un tema
	 */
	private $_template_dir = 'templates/';

	/**
	 * @var string Path del layout di amministrazione
	 */
	private $_layout_dir = 'layout/layout';

	/**
	 * @var array Elenco delle viste renderizzate nella request attuale
	 */
	public $rendered_views = array();

	/**
	 * @var string base-path delle view
	 */
	public $base = '';

	/**
	 * @var string Contenuto del title tag (head)
	 */
	public $title = '';

	/**
	 * @var string Keywords meta tag
	 */
	public $keywords = '';

	/**
	 * @var string Description Meta tag
	 */
	public $description = '';

	/**
	 * @var array Risorse javascript
	 */
	public $javascript = array();

	/**
	 * @var mixed array Fogli di stile
	 */
	public $css = array();

	/**
	 * @var array Temi disponibili nel front-end
	 */
	public $themes = array();

	/**
	 * @var string Tema attuale
	 */
	public $theme = '';

	/**
	 * @var string Directory dei temi
	 */
	public $theme_path = '';

	/**
	 * @var bool Imposta se questa view ha un feed associato
	 */
	public $has_feed = FALSE;

	/**
	 * @var bool Imposta se questa view e' un feed xml/json
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
	 * Carica il tema attuale
	 * Se non in sessione, viene caricato il tema di default (sia desktop che mobile)
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
	 * Imposta un nuovo tema
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
	 * Aggiorna il cookie relativo al tema corrente
	 */
	public function store_theme()
	{
		$this->_CI->session->set_userdata('_website_theme', $this->theme);
		$this->update_ci_path();
	}

	/**
	 * Aggiorna i path di rendering delle view di CodeIgniter per includere il tema attuale
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
	 * Imposta un valore nella view
	 * @param string $key
	 * @param mixed $val
	 */
	public function set($key, $val)
	{
		$this->_data[$key] = $val;
	}

	/**
	 * Ottiene un valore pre impostato nella view
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
	 * Rimuove un valore dalla view
	 * @param string $key
	 */
	public function remove($key)
	{
		unset($this->_data[$key]);
	}

	/**
	 * Renderizza un layout utilizzando la struttura base.
	 * Viene usato prevalentemente dall'amministrazione
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
	 * Renderizza un template presente nella directory relativa
	 * Passa per il layout se il secondo parametro e' TRUE
	 * @param string $template_file
	 * @param bool $layout
	 */
	public function render_template($template_file, $layout = TRUE)
	{
		if ($layout)
		{
			$this->set('_template_file', $this->_template_dir.$template_file);
			$this->_CI->load->view('layout', $this->_data);
			//$this->_CI->load->view($this->_prepend_dir.$this->theme.'/layout', $this->_data);
		} else {
			$this->_CI->load->view($this->_template_dir.$template_file, $this->_data);
		}
	}

	/**
	 * Metodo per renderizzare un template specifico di un tipo scelto
	 * @param string $type_name
	 * @param string $view_file
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
	 * Renderizza una singola view del theme in uso
	 * @param string $view_file
	 */
	public function render($view_file)
	{
		$this->_CI->load->view($view_file);
	}

}