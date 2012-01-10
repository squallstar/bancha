<?php
/**
 * Twitter Module
 *
 * See the Twitter Documentation for an example of usage
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Twitter_Module extends Bancha_Module
{
	/**
	 * @var string The user twitter username :)
	 */
	public $username;

	/**
	 * @var string Twitter Timeline API URL
	 */
	public $feed_url = 'http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=';

	/**
	 * @var mixed Contains the feed when grabbed
	 */
	private $_feed;
	
	/**
	 * Sets the Username
	 * @param string $username
	 */
	public function username($username)
	{
		$this->username = $username;
		$this->view->set('username', $username);
		return $this;
	}

	/**
	 * Grabs the feed
	 */
	public function get_feed()
	{
		$data = getter($this->feed_url.$this->username);

		debug($data);die;
	}

	public function render()
	{
		$this->_feed = $this->get_feed();
		$this->view->set('twitter_feed', $this->_feed);
		parent::render();
	}


}