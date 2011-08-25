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

Class View {

	private $_CI;
	private $_data = array();

	private $_prepend_dir;
	private $_template_dir = 'templates/';
	private $_layout_dir = 'layout/layout';

	public $rendered_views = array();
	public $base = '';
	public $title = '';
	public $keywords = '';
	public $description = '';
	public $javascript = array();
	public $css = array();
	public $skin = '';

	public function __construct()
	{
		$this->_CI = & get_instance();
		$this->_prepend_dir = $this->_CI->config->item('website_views_folder');
		$this->load_skin();
	}

	/**
	 * Loads a website skin
	 */
	public function load_skin() {
		$this->skin = $this->_CI->session->userdata('_website_skin');
		if (!$this->skin)
		{
			$skins = $this->_CI->config->item('website_skins');
			$this->_CI->load->library('user_agent');
			if ($this->_CI->agent->is_mobile() && isset($skins[1]))
			{
				$this->skin = $skins[1];
			} else {
				$this->skin = $skins[0];
			}
			$this->store_skin();
		}
	}

	/**
	 * Force and set a new skin
	 * @param string $to
	 */
	public function set_skin($to)
	{
		$skins = $this->_CI->config->item('website_skins');
		if (in_array($to, $skins))
		{
			$this->skin = $to;
			$this->store_skin();
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Store the current skin in session
	 */
	public function store_skin()
	{
		$this->_CI->session->set_userdata('_website_skin', $this->skin);
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
	 * Passa sempre per il layout!
	 * @param string $template_file
	 */
	public function render_template($template_file)
	{
		$this->set('_template_file', $this->_template_dir.$template_file);
		$this->_CI->load->view($this->_prepend_dir.$this->skin.'/layout', $this->_data);
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
	 * Renderizza una singola view nello skin in uso
	 * @param string $view_file
	 */
	public function render($view_file)
	{
		$this->_CI->load->view($this->_prepend_dir.$this->skin.'/'.$view_file);
	}

}