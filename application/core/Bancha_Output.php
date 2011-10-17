<?php
/**
 * Bancha Output Core
 * Extended from CodeIgniter original Output Core class
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

class Bancha_Output extends CI_Output
{
	/**
	 * Returns true if the profiler will be shown on this request
	 * @return bool
	 */
	public function has_profiler()
	{
		return $this->enable_profiler;
	}

	/**
	 * Write a Cache File
	 * We added the query string to the cache file name to include GET req and the .tmp extension
	 * We also toggled off the caching system for stage contents
	 *
	 * @access	public
	 * @return	void
	 */
	public function _write_cache($output)
	{
		$CI =& get_instance();

		if (!isset($CI->content))
		{
			$CI->load->content();
		}

		if ($CI->content->is_stage || isset($_COOKIE['prevent_cache']))
		{
			//Stage contents cannot be saved to disk because they will
			//overwrite the production files
			return;
		}

		$path = $CI->config->item('cache_path');

		$cache_path = ($path == '') ? APPPATH.'cache/' : $path;

		if ( ! is_dir($cache_path) OR ! is_really_writable($cache_path))
		{
			log_message('error', "Unable to write cache file: ".$cache_path);
			return;
		}

		$uri =	$_SERVER['REQUEST_URI'];

		$cache_path .= md5($uri).'.tmp';

		if ( ! $fp = @fopen($cache_path, FOPEN_WRITE_CREATE_DESTRUCTIVE))
		{
			log_message('error', "Unable to write cache file: ".$cache_path);
			return;
		}

		$expire = time() + ($this->cache_expiration * 60);

		if (flock($fp, LOCK_EX))
		{
			fwrite($fp, $expire.'TS--->'.$output);
			flock($fp, LOCK_UN);
		}
		else
		{
			log_message('error', "Unable to secure a file lock for file at: ".$cache_path);
			return;
		}
		fclose($fp);
		@chmod($cache_path, FILE_WRITE_MODE);

		log_message('debug', "Cache file written: ".$cache_path);
	}

	// --------------------------------------------------------------------

	/**
	 * Update/serve a cached file
	 * We added the query string to the cache file name to include GET requests and the .tmp extension
	 * and we also check if the user is logged in.
	 *
	 * @access	public
	 * @return	void
	 */
	public function _display_cache(&$CFG, &$URI)
	{
		if (isset($_COOKIE['prevent_cache']))
		{
			//The user is logged in. Let's always output not-cached pages
			return FALSE;
		}

		$cache_path = ($CFG->item('cache_path') == '') ? APPPATH.'cache/' : $CFG->item('cache_path');

		// Build the file path.  The file name is an MD5 hash of the full URI
		$uri =	$_SERVER['REQUEST_URI'];

		$filepath = $cache_path.md5($uri).'.tmp';

		if ( ! @file_exists($filepath))
		{
			return FALSE;
		}

		if ( ! $fp = @fopen($filepath, FOPEN_READ))
		{
			return FALSE;
		}

		flock($fp, LOCK_SH);

		$cache = '';
		if (filesize($filepath) > 0)
		{
			$cache = fread($fp, filesize($filepath));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		// Strip out the embedded timestamp
		if ( ! preg_match("/(\d+TS--->)/", $cache, $match))
		{
			return FALSE;
		}

		// Has the file expired? If so we'll delete it.
		if (time() >= trim(str_replace('TS--->', '', $match['1'])))
		{
			if (is_really_writable($cache_path))
			{
				@unlink($filepath);
				return FALSE;
			}
		}

		// Display the cache
		$this->_display(str_replace($match['0'], '', $cache));
		return TRUE;
	}
}