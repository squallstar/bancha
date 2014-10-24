<?php
/**
 * Form Renderer Class
 *
 * A helper for the administration forms
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Form_renderer
{
  	/**
  	 * Prints the sidebar of a content type (fieldsets list)
  	 * @param array $tipo Content type
  	 * @return xhtml
  	 */
  	public function get_sidebar($tipo)
  	{
  		$xhtml = '';
  		foreach ($tipo['fieldsets'] as $fieldset) {
			$xhtml.= '<li><a href="#sb-' . url_title($fieldset['name']) . '">';

			if (isset($fieldset['icon'])) {
				$xhtml.= '<img src="' . site_url(THEMESPATH.'admin/widgets/schemes_icons/' . $fieldset['icon'] . '.png') . '" /> ';
			}
			$xhtml.= _($fieldset['name']) . '</a></li>';
		}
		if ($tipo['has_categories']) {
			$xhtml.= '<li><a href="#sb_category"><img src="' . site_url(THEMESPATH.'admin/widgets/schemes_icons/legend.png') . '" /> '. _('Categories') . '</a>';
		}
		if ($tipo['has_hierarchies']) {
			$xhtml.= '<li><a href="#sb_hierarchies"><img src="' . site_url(THEMESPATH.'admin/widgets/schemes_icons/folders.png') . '" /> '. _('Hierarchies') . '</a>';
		}
		if (isset($tipo['relations'])) {
			$xhtml.= '<li><a href="#sb_relations"><img src="' . site_url(THEMESPATH.'admin/widgets/schemes_icons/link.png') . '" /> '. _('Relations') . '</a>';
		}
		return $xhtml;
  	}
}