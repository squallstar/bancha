<?php
abstract class Milk_Module
{
	/**
	 * @var mixed Reference alla View Library
	 */
	public $view;

	public function __construct()
	{
		$CI = & get_instance();
		$this->view = & $CI->view;
	}

	/**
	 * Renderizza un modulo
	 * @param string $view Nome della view da utilizzare (default = 'view')
	 */
	public function render($view = 'view')
	{
		$CI = & get_instance();
		$module_name = strtolower(str_replace('_Module', '', get_class($this)));
		$CI->load->add_view_path($CI->config->item('modules_folder'));
		$CI->load->view($module_name.DIRECTORY_SEPARATOR.$module_name.'_'.$view, $CI->view->get_data());
	}
}