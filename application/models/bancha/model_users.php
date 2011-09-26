<?php
/**
 * Users Model
 *
 * This class is used to manage users, groups and their permissions
 *
 * @package		Bancha
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
	* Counts the users
	* @return int
	*/
	public function count()
	{
		$query = $this->db->select('COUNT(id_user) as total')
					  ->from('users')->get()->result();
		$row = $query[0];
		return (int)$row->total;
	}

	/**
	 * Adds a limit condition
	 * @param int $limit
	 * @param int $offset
	 * @return $this
	 */
	public function limit($limit, $offset=null)
	{
		$this->db->limit($limit, $offset);
		return $this;
	}

	/**
	 * Adds a where condition
	 * @param string $key
	 * @param int|string $val
	 * @return $this
	 */
	public function where($key, $val=null)
	{
		$this->db->where($key, $val);
		return $this;
	}

	/**
	 * Gets the users
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

	/**
	 * Adds an user to the database
	 * @param array $data
	 * @return int|false the insert id (or false when fails)
	 */
	public function add_user($data)
	{
		if ($this->db->insert('users', $data))
		{
			return $this->db->insert_id();
		}
		return FALSE;
	}

	/**
	 * Returns all the groups
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
	 * Adds a group
	 * @param string $group_name
	 * @return int|false the insert id (or false when fails)
	 */
	public function add_group($group_name)
	{
		if ($this->db->insert('groups', array('group_name' => $group_name)))
		{
			return $this->db->insert_id();
		}
		return FALSE;
	}

	/**
	 * Returns a single group
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
	 * Checks if a group exists, given its name
	 * @param string $group_name
	 * @return bool
	 */
	public function group_exists($group_name = '')
	{
		if ($group_name != '')
		{
			$query = $this->db->select('id_group')
							  ->from('groups')
							  ->where('group_name', $group_name)
							  ->get();
			$result = $query->result();
			if (count($result) == 0)
			{
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * Lists all the permissions
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
	 * Adds a permission on the database
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
	 * Deletes a permission from the database
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

	/**
	 * Deletes a groups and its acl relations
	 * @param int $id_group
	 * @return bool success
	 */
	public function delete_group($id_group = '')
	{
		if ($id_group == '') return FALSE;

		$done = $this->db->where('id_group', $id_group)
						 ->delete('groups');

		$done_2 = $this->db->where('group_id', $id_group)
						   ->delete('groups_acl');

		return $done && $done_2;
	}

}