<?php
/**
 * Bancha Profiler
 *
 * A Code Igniter Profiler extension
 * We changed the theme, the position and we added a new rendering section
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Bancha_Profiler extends CI_Profiler {

	/**
	 * @var array Sections to print
	 */
	protected $_available_sections = array(
		'benchmarks',
		'get',
		'memory_usage',
		'post',
		'uri_string',
		'controller_info',
		'queries',
		'renders',
		'http_headers',
		'session_data',
		'config'
	);

	/**
	 * Compiles the view rendering section
	 * @return string
	 */
	protected function _compile_renders()
	{
		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_get" style="border:1px solid #cd6e00;padding:6px 10px 10px 10px;margin:20px 0 20px 0;background-color:#eee">';
		$output .= "\n";
		$output .= '<legend style="color:#cd6e00;">&nbsp;&nbsp;RENDERED VIEWS&nbsp;&nbsp;</legend>';
		$output .= "\n";

		$rendered = $this->CI->view->rendered_views;

		if (count($rendered) == 0)
		{
			$output .= "<div style='color:#cd6e00;font-weight:normal;padding:4px 0 4px 0'>No views have been rendered</div>";
		}
		else
		{
			$output .= "\n\n<table style='width:100%; border:none'>\n";

			foreach (array_reverse($rendered) as $val)
			{
				$output .= "<tr><td style='width:100%;color:#000;background-color:#ddd;padding:5px'>".$val."&nbsp;&nbsp; </td></tr>\n";
			}

			$output .= "</table>\n";
		}

		$output .= "</fieldset>";

		return $output;
	}

	/**
	* Renders the profiler
	* @return string
	*/
	public function run()
	{
		//We can try to place a "Edit this page" link, so let's check if a page or a record exists
		$record = $this->CI->view->get('record');
		if ($record && $record instanceof Record)
		{
			$tipo = $this->CI->content->type($record->_tipo);
			$edit_link = 'contents/edit_record/' . $tipo['name'] . '/' . $record->id;
			$edit_name = _('Edit this content');
		} else {
			$page = $this->CI->view->get('page');
			if ($page && $page instanceof Record && $page->is_page())
			{
				$tipo = $this->CI->content->type($page->_tipo);
				$edit_link = 'pages/edit_record/' . $tipo['name'] . '/' . $page->id;
				$edit_name = _('Edit this page');
			}
		}

		$output = link_tag(site_url(THEMESPATH . 'admin/css/profiler.css', FALSE)).
			'<script type="text/javascript" src="'.site_url(THEMESPATH . 'admin/js/profiler.js', FALSE).'"></script>'.
			'<a id="bancha_profiler_preview" onclick="_show_profiler();" href="#">'._('Preview').'</a>'.
			'<div id="bancha_profiler"><div id="bancha_profiler_content">'.
			'BANCHA&nbsp; &nbsp; <a href="'.admin_url().'">'._('Back to admin').'</a> - '.
			(isset($edit_link) ? '<a href="'.admin_url($edit_link).'">'.$edit_name.'</a> - ' : '').
			'<a href="#" onclick="_show_ciprofiler();">'._('Open profiler').'</a>'
			.'<div id="bancha_profiler_ci">'
		;

		foreach ($this->_available_sections as $section)
		{
			if ($this->_compile_{$section} !== FALSE)
			{
				$func = "_compile_{$section}";
				$output .= $this->{$func}();
			}
		}

		$output .= '</div></div></div>';

		return $output;
	}

}