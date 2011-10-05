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

	/**
	* Loads and returns the rendering of a block
	* The current template and theme will be automatically recognized
	* @param string $block_id
	* @return xhtml
	*/
	public function load($block_id = '')
	{
    	$page = & $this->_CI->view->get('page');
    	if ($page instanceof Record && $block_id)
    	{
      		$view_template = $this->_CI->view->current_view;
      		$theme = $this->_CI->view->theme;
			if ($view && $theme)
      		{
        		$block = $this->fill_block($block_id, $theme, $view_template);
				if (is_array($block) && count($block))
				{
					foreach ($block as $single_widget)
          			{
						//Renders a block
					}
				}
			}
		}
		return '';
	}

	/**
	 * Search for block closures into a string
	 * @param string $content
	 * @return array
	 */
	public function search_blocks($content)
	{
    	$pattern = '/\$this->blocks->load\(\'([A-Za-z0-9]+)\'\)/';
    	$matches = array();
    	$found = preg_match_all($pattern, $content, $matches);
    	return $found && isset($matches[1]) ? $matches[1] : FALSE;
  	}

  	/**
  	 * Fills the informations of an array of blocks
  	 * @param $blocks
  	 * @param $theme
  	 * @param $template
  	 * @return array
  	 */
  	public function fill_blocks($blocks, $theme, $template = 'default')
  	{
    	if (is_array($blocks) && count($blocks))
    	{
	      	$tmp = array();
	      	foreach ($blocks as $block_name)
	      	{
	        	$tmp[$block_name] = $this->fill_block($block_name, $theme, $template);
	      	}
	      	return $tmp;
    	}
    	return FALSE;
  	}

  	/**
  	 * Fills the informations of a single block
  	 * @param $block
  	 * @param $theme
  	 * @param $template
  	 * @return array
  	 */
	public function fill_block($block, $theme, $template = 'default')
	{
		return $this->_CI->settings->get($block, $theme, $template);
	}
}
