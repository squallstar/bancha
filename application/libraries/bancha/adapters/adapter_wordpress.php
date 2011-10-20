<?php
/**
 * Wordpress Adapter Class
 *
 * ...
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Adapter_wordpress implements Adapter
{
	/**
	 * @var array Defines all the accepted mimes of the adapter
	 */
	private $_mimes;

	public function __construct()
	{
		$this->mimes = array(
			'text/xml', 'application/xml'	
		);
	}

	/**
	 * @var array Returns all the accepted mimes of the adapter
	 */
	public function get_mimes()
	{
		return $this->_mimes;
	}

	public function parse_stream($stream, $to_record = TRUE, $type = '')
	{
		

		$prepared_stream = str_replace(
			array('content:encoded>'),
			array('content>'),
			$stream
		);

		$dom = simplexml_load_string($prepared_stream, 'SimpleXMLElement', LIBXML_NOCDATA);


		if (isset($dom->channel->item))
		{
			$channel = $dom->channel;
			$data = array();
			foreach ($channel->item as $item)
			{
				$data[] = array(
					'title'			=> (string)$item->title,
					'date_publish'	=> (string)$item->pubDate,
					'content'		=> (string)$item->content,
					'abstract'		=> (string)$item->description,
					'title'	=> (string)$item->title,
				);
			}
			if (!$to_record)
			{
				return $data;
			} else {
				$records = array();
				foreach ($data as $row)
				{
					$record = new Record($type);
					if ($type != '')
					{
						$record->set_data($row);
					} else {
						foreach ($row as $key => $val)
						{
							$record->set($key, $val);
						}
					}
					$records[]= $record;
				}
				return $records;
			}
		}
		
	}
}