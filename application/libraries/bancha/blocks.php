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

	public function load($block_id)
	{
		if (!isset($this->CI))
		{
			$this->CI = & get_instance();
		}

		$page = & $this->CI->view->get('page');
		if ($page instanceof Record)
		{
			
		}
		return '';
	}

	public function parse_blocks($string)
	{
		$pattern = '\$this->blocks->load\(\'([A-Za-z0-9]+)\'\)';
		$matches = array();
		preg_match($pattern, $string, $matches);
		return $matches;
	}
}