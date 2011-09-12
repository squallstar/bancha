<?php
/**
 * Events Model
 *
 * Classe per gestire gli eventi
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_events extends CI_Model {

	/**
	 * @var string Tabella per le estrazioni da DB
	 */
	public $table = 'events';

	/**
	 * Aggiunge un evento
	 * @param string $event
	 * @param int $content_id
	 * @param string $content_name
	 * @param string $content_type
	 */
	public function log($event, $content_id=null, $content_name=null, $content_type=null)
	{
		$data = array(
			'user_id'		=> $this->auth->user('id'),
			'event'			=> $event,
			'content_id'	=> $content_id,
			'content_name'	=> $content_name,
			'content_type'	=> $content_type,
			'event_date'	=> time()
		);
		return $this->db->insert($this->table, $data);
	}

	/**
	 * Ottiene gli ultimi eventi dal DB
	 * @param $limit numero eventi da estrarre
	 */
	public function get_last($limit=10)
	{
		$this->db->select('id_event, event, content_id, content_type, content_name, event_date, username as user_name')
				 ->from($this->table)
				 ->order_by('id_event', 'DESC')
				 ->join('users', 'users.id_user = '.$this->table.'.user_id')
				 ->limit($limit);

		$events = $this->db->get()->result();
		return $events;
	}

	/**
	 * Elimina gli eventi associati ad un determinato tipo di contenuto
	 * @param int $content_type
	 */
	public function delete_by_content_type($content_type)
	{
		return $this->db->where('content_type', $content_type)->delete($this->table);
	}


}