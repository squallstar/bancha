<?php
Class Table_list {
	
	private $CI;

	public $scheme = FALSE;
	public $xml_folder;
	public $type = FALSE;

	public function __construct()
	{
		$this->CI = get_instance();
		$this->xml_folder = $this->CI->config->item('xml_folder'); 
	}

	public function set_scheme($scheme)
	{
		$this->scheme = $scheme;
		return $this;
	}

	public function set_type($type)
	{
		$this->type = $type;
	}

}