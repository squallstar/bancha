<?php
/**
 * Output Core class
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
}