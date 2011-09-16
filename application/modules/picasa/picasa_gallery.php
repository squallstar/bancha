<?php 

class Picasa_Gallery
{
	/**
	 * @var mixed CodeIgniter Instance  
	 */
	private $_CI;
	
	private $_username = 'username';
	
	public function __construct()
	{
		$this->_CI = & get_instance();
	} 
	
	public function init($JsonData = NULL)
	{
		
	}
}
