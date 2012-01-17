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
		//Bancha Method (under development)
		/*
		$B =& CI_Controller::get_instance();
		$this->$key =& $B->$key;
		return $this->$key;
		*/
		
		//CodeIgniter standard Method
		$B =& CI_Controller::get_instance();
		return $B->$key;		
	}
}