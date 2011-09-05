<?php
/**
 * Auth Model
 *
 * Classe per autenticazione utenti
 *
 * @package		Milk
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
	 * @var array ACL dell'utente attuale
	 */
	private $_acl;

	public function __construct()
	{
		parent::__construct();
		$this->_acl = $this->user('acl');
	}

	/**
	 * Controlla se un utente e' loggato, altrimenti lo reindirizza al login
	 */
	public function needs_login()
	{
		if (!$this->session->userdata($this->_str_loggedin))
		{
			redirect('/admin/auth/login');
		}
	}

	/**
	 * Controlla se un utente e' loggato
	 * @return bool
	 */
	public function is_logged()
	{
		return $this->session->userdata($this->_str_loggedin);
	}

	/**
	 * Prova ad effettuare il login per un utente
	 * @param string $username
	 * @param string $password
	 * @return bool
	 */
	public function login($username, $password)
	{
		$result = $this->db->select('id_user, name, surname, id_group')
					   	    ->from('users')
					        ->where('username', $username)
					        ->where('password', $password)
					        ->limit(1)->get();

		if ($result->num_rows())
		{
			$user = $result->row(0);

			$this->session->set_userdata($this->_str_loggedin, TRUE);
			$this->user('username', $username);
			$this->user('full_name', $user->name . ' ' . $user->surname);
			$this->user('id', $user->id_user);
			$this->user('group_id', $user->id_group);

			//Carico i permessi dell'utente
			$this->cache_permissions();

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Distrugge la sessione ed effettua il logout
	 * @return bool
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		return TRUE;
	}

	/**
	 * Ottiene un valore dalla sessione, o ne imposta uno
	 * se viene passato il valore come secondo parametro
	 * @param string $key
	 * @param string|int $val
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
		//Prima controllo se esiste già
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
	 * Ottiene tutti gli id dei permessi del gruppo
	 * @param int $group_id se non fornito usa il gruppo dell'utente
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
	* Aggiorna i permessi di un gruppo
	* @param array $permissions
	* @param int $user_id
	* @return bool
	*/
	public function update_permissions($permissions, $group_id='')
	{
		$this->db->where('group_id', $group_id)->delete('groups_acl');
		if (count($permissions))
		{
			foreach ($permissions as $acl)
			{
				$data = array(
								'group_id'			=> $group_id,
								'acl_id'			=> $acl
				);
				$this->db->insert('groups_acl', $data);
			}
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Mette in sessione i permessi dell'utente
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
			$permissions.= '||'.$permission->area.'|'.$permission->action;
		}
		$this->user('acl', $permissions);
		$this->_acl = $permissions;
	}

	/**
	 * Controlla se un utente ha dei permessi
	 * @param string $area
	 * @param string $action
	 */
	public function has_permission($area, $action)
	{
		return strpos($this->_acl, '||'.$area.'|'.$action) == 0 ? FALSE : TRUE;
	}

}