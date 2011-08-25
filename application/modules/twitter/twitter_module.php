<?php
Class Twitter_Module extends Milk_Module
{
	public $username;
	public $feed_url = 'http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=';

	private $_feed;

	public function __construct()
	{
		parent::__construct();
	}

	public function username($username)
	{
		$this->username = $username;
		$this->view->set('username', $username);
		return $this;
	}

	public function render()
	{
		$this->_feed = $this->get_feed();
		$this->view->set('twitter_feed', $this->_feed);
		parent::render();
	}

	public function get_feed()
	{
		$data = getter($this->feed_url.$this->username);

		debug($data);die;
	}


}