<?php
/**
 * View Blocks Library Class (Experimental)
 *
 * This library manage the view blocks.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
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

      		//We remove the un-necessary path
      		$view_template = str_replace(THEMESPATH . $theme . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR, '', $view_template);

      		
			if ($view_template && $theme)
      		{
      			
        		$block = $this->fill_block($block_id, $theme, $view_template);
				if (is_array($block) && count($block))
				{
					$tmp = '';
					foreach ($block as $single_section)
          			{
						//Renders a block
						$tmp.= $this->render_section($single_section);
					}
					return $tmp;
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
    	$pattern = '/\$this->blocks->load\(\'([A-Za-z0-9_]+)\'\)/';
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
    	return array();
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
		return $this->_CI->settings->get_block($block, $theme, $template);
	}

	public function get_section_preview($section, $pos = 0)
	{
		if (!isset($section['type'])) return '';
		switch ($section['type'])
		{
			case 'html':
				$response = '<div class="section html" data-pos="' . $pos . '"><h4>HTML</h4><div class="content_section">' . $section['data'] . '</div>';
				break;
			case 'code':
				ob_start();
				echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $section['data'])));
				$buffer = ob_get_contents();
				@ob_end_clean();
				$response = '<div class="section code" data-pos="' . $pos . '"><h4>PHP</h4><div class="content_section">' . $buffer . '</div>';
				break;
		}
		$response .= '<a href="#" onclick="bancha.blocks.delete_section(this);return false;">' . _('Delete section') . '</a></div>';
		return $response;
	}

	public function render_section($section)
	{
		if (!isset($section['type'])) return '';

		$tmp = '';
		switch ($section['type'])
		{
			case 'html':
				$tmp .= '<section class="block html">' . "\r\n" . $section['data'] . "\r\n</section>";
				break;
			case 'code':
				ob_start();
				echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', $section['data'])));
				$buffer = ob_get_contents();
				@ob_end_clean();
				$tmp .= '<section class="block code">' . $buffer . "</section>";
				break;
		}
		return $tmp;
	}

}
