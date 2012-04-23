<?php
/**
 * Sharer Module View
 *
 * See the Sharer Module for documentation and an example of usage
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

switch ($_sharer['type'])
{
	case 'facebook':
		echo '<a href="' . $_sharer['link'] . '" rel="external">Share on facebook</a>';
		break;

	case 'twitter':
		echo '<a href="' . $_sharer['link'] . '" rel="external">Share on twitter</a>';
		break;
}