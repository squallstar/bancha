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
 * 		 	   ->render();
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
  const PICASA_API_URL = 'http://picasaweb.google.com/data/feed/api/';

  /**
   * @var string Container of for Picasa gallery
   */
  private $_container = NULL;
  
  /**
   * @var string Username of for Picasa gallery
   */
  private $_username = NULL;
  
  
  public function __construct()
  {
    parent::__construct();
  }
 
  
  public function getGallery()
  {
  	$this->load('gallery');
	$this->gallery->setUsername($this->username)->init();
  }
  
  
  public function getAlbum($albumId, $newPage = FALSE, $maxPhoto = FALSE)
  {
  	return '';
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
}
