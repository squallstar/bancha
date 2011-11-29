<?php
/**
 * Auth Model
 *
 * Classe per autenticazione utenti
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_auth extends CI_Model {

	/**
	 * @var string Nome del cookie di sessione
	 */
	private $_str_loggedin = 'logged_in';

	/**
	 * @var array Current user acls
	 */
	private $_acl = '';

	public function __construct()
	{
		parent::__construct();
		$this->_acl = $this->user('acl');
	}

	/**
	 * First it checks if an user is logged in. Then, if not,
	 * the user will be redirect to the login page
	 */
	public function needs_login()
	{
		if (!$this->session->userdata($this->_str_loggedin))
		{
			$request = '';
			if (isset($_SERVER['REQUEST_URI']))
			{
				$request = '?continue=' . urlencode($_SERVER['REQUEST_URI']);
			}
			redirect('/admin/auth/login' . $request);
		}
	}

	/**
	 * Checks whether an user is logged in
	 * @return bool
	 */
	public function is_logged()
	{
		return $this->session->userdata($this->_str_loggedin);
	}

	/**
	 * Tries to do a user login
	 * @param string $username
	 * @param string $password
	 * @return bool success
	 */
	public function login($username, $password)
	{
		$user = $this->get_login_resource($username, $password);

		if ($user)
		{
			$this->session->set_userdata($this->_str_loggedin, TRUE);
			$this->user('username', $username);
			$this->user('full_name', $user->name . ' ' . $user->surname);
			$this->user('id', $user->id_user);
			$this->user('group_id', $user->id_group);

			$this->lang->set_lang($user->admin_lang);
			$this->lang->set_cookie();

			//We also set a single cookie to help the Output class to send cached pages
			$_SESSION['prevent_cache'] = TRUE;

			//Loads the user permissions
			$this->cache_permissions();

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Tries to do a user login and returns the user object if login succeeds
	 * @param string $username
	 * @param string $password
	 * @return Object|bool
	 */
	public function get_login_resource($username, $password)
	{
		if (!$username || !$password)
		{
			return FALSE;
		}
		
		$result = $this->db->select('id_user, name, surname, id_group, admin_lang')
					   	   ->from('users')
					       ->where('username', $username)
					       ->where('password', $password)
					       ->limit(1)->get();

		if ($result->num_rows())
		{
			return $result->row(0);
		}
		return FALSE;
	}

	/**
	 * Destroys the user session
	 * @return bool
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		unset($_SESSION['prevent_cache']);
		session_destroy();
		return TRUE;
	}

	/**
	 * Gets or sets a param into the user session
	 * @param string $key the key to search in
	 * @param string|int $val the value to set
	 * @return mixed
	 */
	public function user($key, $val='')
	{
		if ($val == '')
		{
			return $this->session->userdata('user_'.$key);
		} else {
			$this->session->set_userdata('user_'.$key, $val);
		}
	}

	/**
	 * Aggiunge un permesso al gruppo dell'utente corrente
	 * @param int $acl_id
	 * @return boolean
	 */
	function add_permission($acl_id)
	{
		//We first check if the permission already exists
		$result = $this->db->select('acl_id')
		->from('groups_acl')
		->where('acl_id', $acl_id)
		->limit(1)->get();

		if (!$result->num_rows())
		{
			$data = array(
				'acl_id'	=> $acl_id,
				'group_id'	=> $this->user('group_id')
			);
			return $this->db->insert('groups_acl', $data);
		}
		return TRUE;
	}

	/**
	 * Gets all the acl ids of a single group
	 * @param int $group_id if not used, the user group will be used instead
	 * @return array
	 */
	function get_permissions_id($group_id = '')
	{
		$result = $this->db->select('acl_id')
						   ->from('groups_acl')
						   ->where('group_id', $group_id != '' ? $group_id : $this->user('group_id'))
						   ->get()->result_array();
		$ids = array();
		foreach ($result as $acl)
		{
			$ids[] = $acl['acl_id'];
		}
		return $ids;
	}

	/**
	* Updates the user permissions
	* @param array $permissions
	* @param int $user_id
	* @return bool
	*/
	public function update_permissions($permissions, $group_id='')
	{
		$this->db->where('group_id', $group_id)->delete('groups_acl');
		if (is_array($permissions) && count($permissions))
		{
			foreach ($permissions as $acl)
			{
				$data = array(
					'group_id'	=> $group_id,
					'acl_id'	=> $acl
				);
				$this->db->insert('groups_acl', $data);
			}
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Caches the user permissions into the session
	 */
	public function cache_permissions()
	{
		$result = $this->db->select('area, action')
						   ->from('groups_acl')
						   ->join('acl', 'id_acl = acl_id')
						   ->where('group_id', $this->user('group_id'))
						   ->get()->result();
		$permissions = '';
		foreach ($result as $permission)
		{
			$permissions.= '<'.$permission->area.'|'.$permission->action.'>';
		}
		$this->user('acl', $permissions);
		$this->_acl = $permissions;
	}

	/**
	 * Checks whether an users has a permission
	 * @param string $area
	 * @param string $action
	 * @return bool
	 */
	public function has_permission($area, $action)
	{
		return strpos($this->_acl, '<'.$area.'|'.$action.'>') === FALSE ? FALSE : TRUE;
	}

	/**
	 * Same as has_permission(), but when fails the user will be redirected to a "Forbidden 400" page
	 * @param string $area
	 * @param string $action
	 */
	public function check_permission($area, $action)
	{
		if (!$this->has_permission($area, $action)) show_400();
	}
}