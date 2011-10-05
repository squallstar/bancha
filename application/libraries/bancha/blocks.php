<?php
/**
 * View Blocks Library Class (Experimental)
 *
 * This library manage the view blocks.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Blocks
{
	/**
	 * @var mixed Code Igniter instance
	 */
	private $CI;

	public function __construct()
	{
		$this->_CI = & get_instance();
	}

	public function load($block_id = '')
	{
		$page = & $this->_CI->view->get('page');
		if ($page instanceof Record)
		{
			
		}
		return '';
	}

	public function search_blocks($string)
	{
		$pattern = '/\$this->blocks->load\(\'([A-Za-z0-9]+)\'\)/';
		$matches = array();
		$found = preg_match_all($pattern, $string, $matches);
		return $found && isset($matches[1]) ? $matches[1] : FALSE;
	}

	public function fill_blocks($blocks, $theme, $template = 'default')
	{
		if (is_array($blocks) && count($blocks))
		{
			$tmp = array();
			foreach ($blocks as $block_name)
			{
				$tmp[$block_name] = $this->_CI->settings->get($block_name, $theme, $template);
			}
			return $tmp;
		}
		return FALSE;
	}
}