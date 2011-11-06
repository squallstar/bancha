<?php
/**
 * Menu helper
 *
 * The website menu helpers
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */


/**
 * Prints a tree as an XHTML (UL>LI>A tags)
 * @param array $tree The tree that we print
 * @param int $max_depth Max depth
 * @param int $level Starting level
 * @param string $show_in_menu The only value of the column "show_in_menu" to accept
 * @param string $str A private string for internal use
 * @return xhtml
 */
if (!function_exists('menu'))
{
	function menu(&$tree, $max_depth=99, $level=1, $show_in_menu='T', &$str='')
	{
		if (is_array($tree) && count($tree) && $level <= $max_depth ) {
			if ($level == 1) {
				$str .= '<ul class="menu">';
			}
			foreach ($tree as $page) {
				if ($page['show_in_menu'] == $show_in_menu)
				{
					$str .= '<li class="'.($page['open'] ? 'open' : '').($page['selected'] ? ' selected' : '').'">';
					$str .= '<a href="'.site_url($page['link']).'">'.$page['title'].'</a>';
					if (isset($page['sons']))
					{
						$str .= '<ul class="level_'.$level.'">';
						$str .= menu($page['sons'], $max_depth, $level+1, $show_in_menu);
						$str .= '</ul>';
					}
					$str .= '</li>';
				}
			}
			if ($level == 1) {
				$str .= '</ul>';
			}
		}
		return $str;
	}
}