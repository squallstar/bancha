<?php 

class Picasa_Gallery
{
	/**
	 * @var string URL for Picasa API
	 */	
	const PICASA_API_URL = 'http://picasaweb.google.com/data/feed/api/';
	
	/**
	 * @var mixed CodeIgniter Instance  
	 */
	private $_CI;
	
	private $_username = NULL;
	
	private $_numAlbum = NULL;
	
	private $_author = NULL;
	
	private $_resourceData = NULL;
	
	private $_entry = array();
	
	
	
	public function __construct()
	{
		$this->_CI = & get_instance();
		
	} 
	
	public function init($jSonData)
	{
		if ($jSonData == NULL) {
			return FALSE;
		}
		$this->_resourceData = $jSonData;		
		$this->_numAlbum = count($this->_resourceData->feed->entry);
		
		
		debug($this->_resourceData->version,'version');
		debug($this->_resourceData->encoding,'encoding');		
		
		$id = $this->_resourceData->feed->id->_t;
		$updated = $this->_resourceData->feed->updated->_t;
		$category = $this->_resourceData->feed->category;
		$title = $this->_resourceData->feed->title;
		$subtitle = $this->_resourceData->feed->subtitle;
		$icon = $this->_resourceData->feed->icon->_t;
		$link = $this->_resourceData->feed->link;
		$author = $this->_resourceData->feed->author;
		$openSearchTotResult = $this->_resourceData->feed->openSearch_totalResults->_t;
		$openSearchStartIndex = $this->_resourceData->feed->openSearch_startIndex->_t;
		$openSearchItemsPerPage = $this->_resourceData->feed->openSearch_itemsPerPage->_t;
		$gPhotoUser = $this->_resourceData->feed->gphoto_user->_t;
		$gPhotoNickname = $this->_resourceData->feed->gphoto_nickname->_t;
		$gPhotoThumbnail = $this->_resourceData->feed->gphoto_thumbnail->_t;
		$this->_getAlbum();
		
		//debug($jSonData->feed->entry,'feed entry',TRUE);
	}
	
	private function _getAlbum()
	{
		if ($this->_resourceData == NULL) {
			return $this->_entry = array();
			return;
		}
		
		foreach ($this->_resourceData->feed->entry as $album) {
			debug($album,'album',TRUE);	
		}
	}

}
