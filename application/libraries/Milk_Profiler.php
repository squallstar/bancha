<?php
/**
 * Bancha Profiler
 *
 * Estensione del profiler di Code Igniter
 * E' stata aggiunta la sezione "render" ed una nuova grafica
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
	 * @var array Sezioni da stampare
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
	 * Compila la sezione del rendering delle view
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
			$output .= "<div style='color:#cd6e00;font-weight:normal;padding:4px 0 4px 0'>Nessuna vista renderizzata</div>";
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
	* Renderizza il profiler
	* @return string
	*/
	public function run()
	{
		$output = link_tag(site_url(THEMESPATH . 'admin/css/profiler.css')).
			'<script type="text/javascript" src="'.site_url(THEMESPATH . 'admin/js/profiler.js').'"></script>'.
			'<a id="milk_profiler_preview" onclick="_show_profiler();" href="#">'._('Preview').'</a>'.
			'<div id="milk_profiler"><div id="milk_profiler_content">'.
			'BANCHA&nbsp; &nbsp; <a href="'.admin_url().'">'._('Back to admin').'</a> - '.
			'<a href="#" onclick="_show_ciprofiler();">'._('Open profiler').'</a>'
			.'<div id="milk_profiler_ci">'
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