<?php
/**
 * Breadcrumbs helper
 *
 * Funzioni di utilità per le briciole di pane
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */


/**
 * Helper per stampare delle breadcrumbs
 * @param array
 * @return xhtml
 */
if (!function_exists('breadcrumbs'))
{
	function breadcrumbs($breadcrumbs_array = array())
	{
		$tmp = '';
		$current_uri = uri_string().'/';
		$site_url = site_url();

		if (count($breadcrumbs_array))
		{
			$i = 0;
			foreach ($breadcrumbs_array as $key => $breadcrumb)
			{
				if ($i > 0)
				{
					$tmp.= ' &raquo; ';
				}

				if ($current_uri == $breadcrumb['link'])
				{
					$tmp.= $breadcrumb['title'];
					break;
				} else {
					$tmp.= '<a href="'.$site_url.$breadcrumb['link'].'">'.$breadcrumb['title'].'</a>';
				}
				$i++;
			}
		}
		return '<div class="breadcrumbs">'.$tmp.'</div>';
	}
}