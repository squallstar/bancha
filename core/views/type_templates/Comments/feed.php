<?php
/**
 * Comments feed list view
 *
 * Helps the feed to be rendered.
 * This files gives you the ability to choose the fields to be displayed on the feeds.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 *
 */

if(!defined('BASEPATH')) exit('No direct script access allowed');

$feed_header = array(
	'title' 		=> $page->get('title'),
	'description'	=> $page->get('content')
);

$this->feed->create_new($feed_header, $this->view->is_feed);

if (isset($records) && count($records))
{
	foreach ($records as $record)
	{
		$date_pub = $record->get('_date_publish');
		if (!$date_pub)
		{
			$date_pub = $record->get('_date_insert');
		}
		$item = array(
			'title'			=> $record->get('title'),
			'link'			=> current_url().'/'.$record->get('uri'),
			'guid'			=> current_url().'/'.$record->get('uri'),
			'pubDate'		=> date(DATE_RFC822, (int)$date_pub),
			'description'	=> $record->get('content')
		);
		$this->feed->add_item($item, array('title', 'description'));
	}
}

$this->feed->render();

/* End of file feed.php */
/* Location: /type_templates/Comments/feed.php */