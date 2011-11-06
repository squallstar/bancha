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
	
	private $_resourceData = NULL;
	
	private $_galleryId = NULL;
	
	private $_galleryTitle = NULL;
	
	private $_galleryAuthors = NULL;
	
	private $_userThumbnail = NULL;
	
	private $_numAlbum = NULL;
	
	private $_albums = array();
	
	private $_maxAlbums = FALSE;
	
	
	
	public function __construct()
	{
		$this->_CI = & get_instance();
	}
	
	
	public function init()
	{
		
		if ($this->_resourceData == NULL) {
			$this->_resourceData = json_decode(str_replace('$', '_',getter(self::PICASA_API_URL.'user/'.$this->_username.'?category=album'.($this->_maxAlbums ? '&max-results='.$this->_maxAlbums : '').'&access=public&alt=json')));
		}
		return $this;
		
		/*$this->_numAlbum = count($this->_resourceData->feed->entry);
		
		$this->_titleGallery = $this->_resourceData->feed->title;
				
		// Gets the properties of the given object
		// with get_object_vars function
		$d = (is_object($this->_resourceData->feed) ? get_object_vars($this->_resourceData->feed): array());
		
		foreach ($d as $key => $value) {
			switch ($key) {
				case 'id':
					$this->_galleryId = $value->_t;
					break;
				case 'title':
					$this->_galleryTitle = $value->_t;
					break;
				case 'author':
					$this->_galleryAuthors = $value; 
					break;
				case 'gphoto_thumbnail':
					$this->_userThumbnail = $value->_t;
					break;
			}
			if ($key == 'entry'){
				echo "LOL";
			}
		}
		debug($this,'this',TRUE);
		die;
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
		*/
	}
	
	public function getAuthors() {
		return $this->_resourceData->feed->author;
	}
	
	public function getAlbumCovers() {
		$albumCovers = array();
		foreach ($this->_resourceData->feed->entry as $key => $album) {
			$albumCovers['id'] = $album->id->_t;
			$albumCovers['date_published'] = $album->published->_t;
			$albumCovers['date_updated'] = $album->updated->_t;
			$albumCovers['title'] = $album->title->_t;
			$albumCovers['summary'] = $album->summary->_t;
			$albumCovers[] = $album->
			debug($album,$key);
		}
		die();
	}


	public function setUsername ($value) {
		$this->_username = $value;
		$this->init();
		return $this;
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
