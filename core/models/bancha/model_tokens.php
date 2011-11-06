<?php
/**
 * Auth Model
 *
 * Class to manage user tokens
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_tokens extends CI_Model
{
	private $_shared_tokens = FALSE;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('encrypt');
		$this->_shared_tokens = $this->config->item('shared_api_token');
	}

	/**
	 * Checks whether a token stills valid or not.
	 * When a valid token is found, all user informations will be cached
	 * @param string $token
	 * @return BOOL
	 */
	public function check_token($token = '')
	{
		if (strlen($token))
		{
			//Will get safer queries
			$encoded_token = urlencode($token);

			$res = $this->db->select('username')->from('api_tokens')->where('token', $encoded_token)
						    ->limit(1)->get();
			if ($res->num_rows())
			{
				//Token exists
				$data = explode('|', $this->encrypt->decode($token));
				if (count($data) == 3)
				{
					//Token is valid
					$this->auth->user('username', $res->row(0)->username);
					$this->auth->user('id', $data[0]);
					$this->auth->user('group_id', $data[1]);
					$this->auth->cache_permissions();

					return TRUE;
				}				
			}
		}
		return FALSE;
	}

	/**
	 * Logins and gets a new token
	 * @param string $username
	 * @param string $password
	 * @return string|bool (token or FALSE)
	 */
	public function get_new($username = '', $password = '')
	{
		$user = $this->auth->get_login_resource($username, $password);
		if ($user)
		{
			//We generate the token
			$data = $user->id_user . '|' . $user->id_group . '|' . time();
			$token = urlencode($this->encrypt->encode($data));

			//We delete old tokens if the sharing is not allowed
			if (!$this->_shared_tokens)
			{
				$this->db->where('username', $username)->delete('api_tokens');
			}

			$done = $this->db->insert('api_tokens', array(
				'username'		=> $username,
				'token'			=> $token,
				'last_activity'	=> time()
			));
			if ($done)
			{
				return $token;
			}
		} else {
			return FALSE;
		}
	}

	/**
	 * Destroys a token
	 * @param string $token
	 * @return bool
	 */
	public function destroy_token($token = '')
	{
		if (strlen($token))
		{
			//Will get safer queries
			$encoded_token = urlencode($token);

			//We delete old tokens if the sharing is not allowed
			$username = $this->auth->user('username');
			if ($username && !$this->_shared_tokens)
			{
				$this->db->where('username', $username)->delete('api_tokens');
			}

			return $this->db->where('token', $encoded_token)->delete('api_tokens');
		}
	}

}