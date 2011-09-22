<?php 

class Picasa_Gallery
{
	/**
	 * @var mixed CodeIgniter Instance  
	 */
	private $_CI;
	
	private $_username = 'username';
	
	private $_author;
	
	public function __construct()
	{
		$this->_CI = & get_instance();
	} 
	
	public function init($jSonData, $maxAlbum = NULL)
	{
		if ($jSonData == NULL) {
			
		}
		
		debug($jSonData->version,'version');
		debug($jSonData->encoding,'encoding');		
		
		$id = $jSonData->feed->id->_t;
		$updated = $jSonData->feed->updated->_t;
		$category = $jSonData->feed->category;
	
		debug($jSonData->feed,'feed key',TRUE);
		debug($jSonData->feed->entry,'feed entry',TRUE);
	}
}
