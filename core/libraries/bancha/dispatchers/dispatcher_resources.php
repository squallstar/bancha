<?php
/**
 * Resources Dispatcher (Library)
 *
 * The minifier dispatcher of the website resources
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Dispatcher_resources
{
	/**
	 * @var int The number of caching days to be set
	 */
	public $days = 20;

	/**
	 * Starts the minification process of a single Content-Type resources
	 * Used by the minify() function inside website_helper
	 */
	public function minify($theme, $src)
	{
		$B = & get_instance();
		
		//Cache headers
		$B->output->set_header('Cache-Control: public,max-age=31536000')
				  ->set_header('Age: 5881')
				  ->set_header('Pragma:')
				  ->set_header('Keep-Alive: 999')
				  ->set_header('Expires: ' . date('D, d M Y H:i:s', time()+(86400*$this->days)))
				  ->enable_profiler(FALSE);

		$cache_folder = $B->config->item('fr_cache_folder');
		$cache_filename = md5($theme . $src);

		$tmp = explode('.', $src);
		$ext = $tmp[count($tmp)-1];

		switch ($ext)
		{
			case 'js':
				$mime = 'application/x-javascript';
				break;
			case 'css':
				$mime = 'text/css';
				break;
			default:
				$mime = 'text/html'; //change to text/html, i'm using "text/bancha" for debug
		}

		$full_filename = 'res.'.$cache_filename . '.'.$ext;

		if (CACHE && file_exists($cache_folder . $full_filename))
		{
			//The cached file is sent to the client
			$B->output->set_content_type($mime)
				         ->set_output(file_get_contents($cache_folder . $full_filename));
			return;
		}

		$path = urldecode($theme);
		$files = explode(',',$src);
		if (!count($files)) return;
		$full_path = THEMESPATH . $path . '/';

		$minified = '';

		$ext_libs = str_replace(array('\\','/'), DIRECTORY_SEPARATOR, APPPATH . 'libraries' . DIRECTORY_SEPARATOR . 
			  	   'externals' . DIRECTORY_SEPARATOR);
  	   $B->load->helper('file');

		if ($ext == 'js')
		{
			//JSMin lib
			$path =  $ext_libs . 'JSMin.php';
			require_once($path);
			
			foreach ($files as $file)
			{
				$content = read_file($full_path . $file);
				$minified .= JSMin::minify($content);
			}
		} else if ($ext == 'css')
		{
			//CSSMin lib
			$path =  $ext_libs . 'CSSMin.php';
			require_once($path);

			foreach ($files as $file)
			{
				$content = read_file($full_path . $file);
				$minified .= Minify_CSS_Compressor::process($content);
			}
		} else
		{
			//Dummy resource... text?
			foreach ($files as $file)
			{
				$minified .= read_file($full_path . $file);
			}
		}

		if (!$minified)
		{
			show_404('Resources dispatcher: ' . $src);
		}

		if (CACHE)
		{
			write_file($cache_folder . $full_filename, $minified);
		}

		//The generated minified file is sent to the client
		$B->output->set_content_type($mime)
				  ->set_output($minified);
	}
}