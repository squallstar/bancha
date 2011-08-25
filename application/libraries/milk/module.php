<?php
abstract class Milk_Module
{
	public $view;

	public function __construct()
	{
		$CI = & get_instance();
		$this->view = & $CI->view;
	}

	public function render($view = 'view')
	{
		$CI = & get_instance();
		$module_name = strtolower(str_replace('_Module', '', get_class($this)));
		$old_path = $CI->load->_ci_view_path;

		$CI->load->_ci_view_path = $CI->config->item('modules_folder');
		$CI->load->view($module_name.'/'.$module_name.'_'.$view, $CI->view->get_data());

		$CI->load->_ci_view_path = $old_path;
	}
}