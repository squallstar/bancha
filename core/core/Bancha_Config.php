<?php
/**
 * Bancha Config Class
 *
 * An extension of the original Code Igniter config class
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

class Bancha_Config extends CI_Config
{
	public $prepend_language = FALSE;

	public function __construct()
	{
		$this->_config_paths = array(USERPATH, APPPATH);
		parent::__construct();
	}

	/**
	 * Site URL
	 * Returns base_url . index_page [. uri_string]
	 *
	 * @access	public
	 * @param	string	the URI string
	 * @param   string|bool $prepend_language
	 * @return	string
	 */
	function site_url($uri = '', $prepend_language = TRUE)
	{
		$base = $this->slash_item('base_url');

		if (is_string($prepend_language))
		{
			$base.= $prepend_language . '/';
		} else {
			if ($prepend_language && $this->prepend_language)
			{
				$base.= $this->prepend_language . '/';
			}
		}

		if ($uri == '')
		{
			return $base.$this->item('index_page');
		}

		if ($this->item('enable_query_strings') == FALSE)
		{
			$suffix = ($this->item('url_suffix') == FALSE) ? '' : $this->item('url_suffix');
			return $base.$this->slash_item('index_page').$this->_uri_string($uri).$suffix;
		}
		else
		{
			return $base.$this->item('index_page').'?'.$this->_uri_string($uri);
		}
	}

}