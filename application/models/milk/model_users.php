<?php
/**
 * Users Model
 *
 * Classe per interagire con gli utenti
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_users extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Conta gli utenti
	* @return int
	*/
	public function count()
	{
		$query = $this->db->select('COUNT(id_user) as total')
					  ->from('users')->get()->result();
		$row = $query[0];
		return (int)$row->total;
	}

	public function limit($a, $b=null)
	{
		$this->db->limit($a, $b);
		return $this;
	}

	public function where($key, $val=null)
	{
		$this->db->where($key, $val);
		return $this;
	}

	/**
	 * Ottiene gli utenti secondo le condizioni definite
	 * @return array
	 */
	public function get()
	{
		$query = $this->db->select('id_user, name, surname, email, username, group_name, users.id_group')
						  ->from('users')
						  ->join('groups', 'users.id_group = groups.id_group', 'left')
						  ->get();

		return $query->result();
	}

	public function add_user($data)
	{
		return $this->db->insert('users', $data);
	}

	/**
	 * Ottiene tutti i gruppi
	 * @return array
	 */
	public function get_groups()
	{
		$query = $this->db->select('id_group, group_name')
						  ->from('groups')
						  ->order_by('group_name', 'ASC')
						  ->get();
		return $query->result();
	}

	/**
	 * Aggiunge un gruppo
	 * @param string $group_name
	 * @return int auto_increment
	 */
	public function add_group($group_name)
	{
		$this->db->insert('groups', array('group_name' => $group_name));
		return $this->db->insert_id();
	}

	/**
	 * Ottiene un gruppo
	 * @param int $id
	 * @return array|bool
	 */
	public function get_group($id = '')
	{
		if ($id != '')
		{
			$query = $this->db->select('id_group, group_name')
							  ->from('groups')
							  ->order_by('group_name', 'ASC')
							  ->where('id_group', $id)
							  ->get();
			$result = $query->result();
			if (count($result))
			{
				return $result[0];
			}
		}
		return FALSE;
	}

	/**
	 * Lista di tutti i permessi disponibili
	 * @return array
	 */
	public function get_acl_list()
	{
		$result = $this->db->select('id_acl AS id, acl_name AS name, area, action')
				 		   ->from('acl')
				 		   ->get()->result();
		return $result;
	}

	/**
	 * Aggiunge un permesso sul DB
	 * @param string $area
	 * @param string $action
	 * @param string $name
	 */
	public function add_acl($area, $action, $name='')
	{
		$data = array(
			'area'		=> $area,
			'action'	=> $action,
			'acl_name'	=> $name != '' ? $name : $area . ' ' . $action
		);
		$this->db->insert('acl', $data);
		return $this->db->insert_id();
	}

	/**
	 * Elimina un permesso dal DB
	 * @param string $area
	 * @param string $action
	 */
	public function delete_acl($area, $action)
	{
		$result = $this->db->select('id_acl')->from('acl')
						   ->where('area', $area)->where('action', $action)
						   ->limit(1)->get()->result_array();
		if (count($result))
		{
			$acl_id = $result[0]['id_acl'];

			//Delete all permissions
			$this->db->where('id_acl', $acl_id)->delete('acl');
			$this->db->where('acl_id', $acl_id)->delete('groups_acl');
		}
		return TRUE;
	}

}