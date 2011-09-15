<?php
/**
 * Picasa Module
 *
 * This module let you connect to Picasa account and get the gallery.
 *
 * Usage:
 * $picasa = $this->load->module('picasa');
 *
 * //Renders the sharing button
 * echo $picasa->type('facebook')
 * 		 	   ->url('http://example.org')
 * 		  	   ->title('Share on Facebook')
 * 		  	   ->render();
 *
 *
 * @package		Bancha
 * @author		Alessandro Maroldi - alexmaroldi@gmail.com - @alexmaroldi
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 *
 */

Class Picasa_Module extends Bancha_Module
{

  /**
   * @var string URL for Picasa API
   */	
   private $_picasaUrl = 'http://picasaweb.google.com/data/feed/api/';
   
  /**
   * @var string Username of Picasa account
   */
  private $_username = 'kikkovolley';
  
  /**
   * @var string Container of for Picasa gallery
   */
  private $_container = 'gallery';
  
  public function __construct()
  {
    parent::__construct();
  }
  
  
  public function getAlbums($maxAlbums = FALSE)
  {
  	$data = getter($this->_picasaUrl.'user/'.$this->_username.'?category=album'.($maxAlbums ? '&max-results='.$maxAlbums : '').'&access=public&alt=json');
	//var url = 'http://picasaweb.google.com/data/feed/api/user/' + pwi_username + '?category=album' + (pwi_maxalbums > 0 ? '&max-results=' + pwi_maxalbums: "") + '&access=public&alt=json';
	$array = json_decode($data);
	debug($array,'DATI DECODE JSON Picasa',1);
  }
  
  public function getAlbum($albumId, $newPage = FALSE, $maxAlbums = FALSE)
  {
  	return '';
  }

  /**
   * Sets the sharing URL (default is the current url)
   * @param string $link
   */
  public function url($link = '')
  {
    if ($link != '')
    {
      $this->_url = $link;
    }
    return $this;
  }

  /**
   * Sets the twitter sharing text
   * @param string $message
   */
  public function text($message = '')
  {
    if ($message != '')
    {
      $this->_text = $message;
    }
    return $this;
  }

  /**
   * Sets the twitter sharing text
   * Same function of $this->text();
   * @param string $message
   */
  public function message($message = '')
  {
    return $this->text($message);
  }

  /**
   * Sets the Twitter "via" param (also known as @username)
   * @param string $username
   */
  public function via($username = '')
  {
    if ($username != '')
    {
      $this->_via = str_replace('@', '', $username);
    }
    return $this;
  }

  /**
   * Sets the Twitter hashtags
   * @param string $tags
   */
  public function hashtags($tags = '')
  {
    if ($tags != '')
    {
      $this->_tags = $tags;
    }
    return $this;
  }

  /**
   * Sets the social network
   * @param string $social
   */
  public function type($social = '')
  {
    $social = strtolower($social);
    if ($social != '' && in_array($social, $this->_types))
    {
      $this->_type = $social;
    }
    return $this;
  }

  /**
   * Renders the sharing button
   * @see application/libraries/bancha/Bancha_Module::render()
   * @return xhtml
   */
  public function render()
  {
    /*if ($this->_type != '')
    {
      $this->get_link();
      $this->view->set('_sharer', array(
        'type'	=> $this->_type,
        'url'	=> $this->_url,
        'link'	=> $this->_link,
        'title'	=> $this->_title != '' ? $this->_title : $this->view->title
      ));
      return parent::render();
    }*/
    return parent::render();
  }

  /**
   * Gets the sharing link
   * @return string
   */
  public function get_link()
  {
    $this->_url = $this->_url != '' ? $this->_url : current_url();

    switch ($this->_type)
    {
      case 'facebook':
        $this->_link = '"http://www.facebook.com/sharer/sharer.php?u=' . urlencode($this->_url);
        break;

      case 'twitter':
        $this->_link = 'http://twitter.com/intent/tweet?via=' . urlencode($this->_via) . '&hashtags=' . urlencode($this->_tags)
               . '&text=' . urlencode($this->_text) . '%20' . urlencode($this->_url);
        break;

      default:
        $this->_link = $this->_url;
    }
    return $this->_link;
  }


}
