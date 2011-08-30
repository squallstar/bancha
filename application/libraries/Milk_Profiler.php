<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Milk_Profiler extends CI_Profiler {

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
	 * Compile Rendered Views
	 *
	 * @return	string
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
	* Run the Profiler
	*
	* @return	string
	*/
	public function run()
	{
		$output = link_tag(site_url('css/admin/profiler.css')).
			'<script type="text/javascript" src="'.site_url('js/admin/profiler.js').'"></script>'.
			'<a id="milk_profiler_preview" onclick="_show_profiler();" href="#">'._('Preview').'</a>'.
			'<div id="milk_profiler"><div id="milk_profiler_content">'.
			'MILK&nbsp; &nbsp; <a href="'.admin_url().'">'._('Back to admin').'</a> - '.
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