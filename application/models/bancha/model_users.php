<?php
/**
 * Users Model
 *
 * The model that lets you to manage the users
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
	 * Sets a limit condition
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
	 * Sets a where condition
	 * @param string $key
	 * @param string $val
	 * @return $this
	 */
	public function where($key, $val=null)
	{
		$this->db->where($key, $val);
		return $this;
	}

	/**
	 * Gets the users using the filtering function defined
	 * @param bool $array return as array or object
	 * @return array
	 */
	public function get($array = FALSE)
	{
		$query = $this->db->select('id_user, name, surname, email, username, group_name, users.id_group')
						  ->from('users')
						  ->join('groups', 'users.id_group = groups.id_group', 'left')
						  ->get();

		return $array ? $query->result_array() : $query->result();
	}

	public function add_user($data)
	{
		return $this->db->insert('users', $data);
	}

	/**
	 * Deletes a single user
	 * @param int $id_user
	 * @return bool
	 */
	public function delete($id_user='')
	{
		if ($id_user != '')
		{
			return $this->db->where('id_user', $id_user)->delete('users');
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
	 * Adds a new group
	 * @param string $group_name
	 * @return int auto_increment
	 */
	public function add_group($group_name)
	{
		$this->db->insert('groups', array('group_name' => $group_name));
		return $this->db->insert_id();
	}

	/**
	 * Gets a single group
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
	 * Lists of all permissions
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
	 * Adds a new permission
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

}