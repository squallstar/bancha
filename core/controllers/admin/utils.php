<?php
/**
 * Utilities Controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Core_Utils extends Bancha_Controller
{

	public function __construct()
	{
	    parent::__construct();
	}

	public function index()
	{
		//Nothing
	}

	public function minify($theme) {

		//JSMin
		$path =  str_replace(array('\\','/'), DIRECTORY_SEPARATOR,
					APPPATH . 'libraries' . DIRECTORY_SEPARATOR . 
		  		  'externals' . DIRECTORY_SEPARATOR . 
			  		'JSMin.php');
		require_once($path);

		$cache_folder = $this->config->item('fr_cache_folder');

		$src = $this->input->get('src');
		$cache_name = md5($theme . $src);

		$file_name = 'min.'.$cache_name . '.tmp';

		if (file_exists($cache_folder . $file_name)) {
			//The final output is sent to the client
			$this->output->set_content_type('js')
				   ->set_output(file_get_contents($cache_folder . $file_name));
			return;
		}

		$path = urldecode($theme);
		$files = explode(',',$src);
		if (!count($files)) return;
		$full_path = THEMESPATH . $path . '/';

		$minified = '';

		foreach ($files as $file) {
			$content = @file_get_contents($full_path . $file);
			if (rtrim($file, '.js') != $file) {
				$minified .= JSMin::minify($content);
			}
		}

		$this->load->helper('file');
		write_file($cache_folder . $file_name, $minified);

		echo $minified;
	}

}