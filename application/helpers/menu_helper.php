<?php
/**
 * Menu helper
 *
 * Funzioni di utilità per i menu
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */


/**
 * Helper per stampare l'XHTML un ramo di menu
 * @param array $tree L'albero da stampare
 * @param int $max_depth Profondità massima
 * @param int $level Livello di partenza
 * @param string $show_in_menu Menu da stampare (Default: T)
 * @param string $str Stringa interna riservata
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