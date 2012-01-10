<?php
/**
 * Sharer Module
 *
 * This module let you renders the sharing buttons or
 * generate the sharing urls for the main social networks.
 *
 * Usage:
 * $sharer = $this->load->module('sharer');
 *
 * //Renders the sharing button
 * echo $sharer->type('facebook')
 * 		 	   ->url('http://example.org')
 * 		  	   ->title('Share on Facebook')
 * 		  	   ->render();
 *
 * //Gets the sharing url
 * $link = $sharer->type('twitter')
 * 				  ->via('@squallstar')
 * 			      ->message('This is cool')
 * 				  ->get_link();
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Sharer_Module extends Bancha_Module
{
    /**
    * @var string The social network
    */
    private $_type = '';

    /**
    * @var array Defined socials
    */
    private $_types = array('facebook', 'twitter');

    private $_url	= '';
    private $_link	= '';
    private $_title	= '';
    private $_via	= '';
    private $_tags	= '';
    private $_text	= '';

    public function __construct()
    {
        parent::__construct();
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
        if ($this->_type != '')
        {
            $this->get_link();
            $this->view->set('_sharer', array(
                'type'	=> $this->_type,
                'url'	=> $this->_url,
                'link'	=> $this->_link,
                'title'	=> $this->_title != '' ? $this->_title : $this->view->title
            ));
            return parent::render();
        }
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
                $this->_link = 'http://www.facebook.com/sharer/sharer.php?u=' . urlencode($this->_url);
                break;

            case 'twitter':
                $this->_link = 'http://twitter.com/intent/tweet?'.($this->_via != '' ? 'via=' . urlencode($this->_via) . '&' : '') . 'hashtags=' . urlencode($this->_tags)
                . '&text=' . urlencode($this->_text) . '%20' . urlencode($this->_url);
                break;

            default:
                $this->_link = '';
        }
        return $this->_link;
    }
}
