<?php
/**
 * Breadcrumbs helper
 *
 * Helper functions for the breadcrumbs
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if (!function_exists('breadcrumbs'))
{
	/**
	 * Prints the view breadcrumbs
	 * @param array $breadcrumbs_array
	 * @param string $separator
	 */
	function breadcrumbs($breadcrumbs_array = array(), $separator = ' &raquo; ')
	{
		$tmp = '';
		$current_uri = uri_string().'/';
		$site_url = site_url();

		if (count($breadcrumbs_array))
		{
			$i = 0;
			foreach ($breadcrumbs_array as $key => $breadcrumb)
			{
				if ($i > 0) $tmp.= $separator;

				if ($current_uri == $breadcrumb['link'])
				{
					$tmp.= '<span>'.$breadcrumb['title'].'</span>';
					break;
				} else {
					$tmp.= '<span><a href="'.$site_url.$breadcrumb['link'].'">'.$breadcrumb['title'].'</a></span>';
				}
				$i++;
			}
		}
		return '<div class="breadcrumbs">'.$tmp.'</div>';
	}
}