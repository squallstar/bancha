<?php
Class Core
{
	/**
	 * __get
	 *
	 * Allows classes to access Bancha's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string
	 * @access private
	 */
	function __get($key)
	{
		$B =& CI_Controller::get_instance();
		$this->$key =& $B->key;
		return $this->$key;

		/*
		CI:
		We need to find which of these two syntax uses low memory (and speed time)

		$CI =& get_instance();
		return $CI->$key;

		*/
	}
}