<?php
/**
 * Users Controller
 *
 * Gestione utenti (amministrazione)
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Users extends Bancha_Controller
{
	public function __construct()
	{
	    parent::__construct();
	    $this->view->base = 'admin/';

	    $this->auth->needs_login();

	    $this->load->users();
	}

	public function index()
	{
		$this->lista();
	}

	public function lista($page=0)
	{
		//Paginazione
		$pagination = array(
		    	'total_rows'	=> $this->users->count(),
		    	'per_page'		=> $this->config->item('records_per_page'),
		    	'base_url'		=> admin_url('users/list/'),
		    	'uri_segment'	=> 4,
		    	'cur_tag_open'	=> '<a href="#" class="active">',
		    	'cur_tag_close'	=> '</a>'
		);

		$this->load->library('pagination');
		$this->pagination->initialize($pagination);

		$users = $this->users->limit($pagination['per_page'], $page)
						     ->get();

		$this->view->set('users', $users);
		$this->view->set('total_records', $pagination['total_rows']);

		$this->view->render_layout('users/list');
	}

	public function edit($id_username='')
	{		
		//We get the Users scheme
		$type_definition = $this->xml->parse_file($this->config->item('xml_folder') . 'Users.xml');

		$user = new Record();
		$user->set_type($type_definition);
		
		if ($id_username != '')
		{
			if ($this->input->post())
			{
				$user->set_data($this->input->post());
				$this->records->save($user);
			}
			
			//We search for this user
			$users = $this->records->set_type($type_definition)->limit(1)->where('id_user', $id_username)->get();
			
			if (!$users) {
				show_error(_('User not found'));
			} else {
				$user = $users[0];
			}
		} else {
			//New user
			$this->view->set('user', FALSE);
		}
		
		$this->view->set('tipo', $type_definition);
		$this->view->set('_section', 'users');
		$this->view->set('action', 'admin/users/edit/' . $user->id);
		$this->view->set('record', $user);

		$this->view->render_layout('content/record_edit');
	}

	/**
	 * Metodi per la gestione dei gruppi
	 * @param string $action
	 * @param string|int $param
	 */
	public function groups($action='', $param='')
	{
		if ($action == 'edit')
		{
			if ($this->input->post('submit', FALSE))
			{
				if ($param != '')
				{
					//Gruppo esistente
					$new_acls = $this->input->post('acl', FALSE);
					$this->auth->update_permissions($new_acls, $param);
				} else {
					//Nuovo gruppo
				}

				if ($param == $this->auth->user('group_id'))
				{
					//Se ho aggiornato il mio gruppo, aggiorno i permessi
					$this->auth->cache_permissions();
				}

			}

			//Ottengo il gruppo
			$group = $this->users->get_group($param);
			if (!$group)
			{
				if ($param)
				{
					show_error('Il gruppo con ID ['.$param.'] non &egrave; stato trovato.');
				} else {
					$group = FALSE;
				}
			}

			//Ottengo tutti i permessi impostabili
			$acl = $this->users->get_acl_list();

			//Ottengo i permessi dell'utente
			$user_acls = $this->auth->get_permissions_id($param);

			$this->view->set('group', $group);
			$this->view->set('acls', $acl);
			$this->view->set('user_acls', $user_acls);
			$this->view->render_layout('users/groups/edit');
			return;
		}
		$this->view->set('groups', $this->users->get_groups());
		$this->view->render_layout('users/groups/list');
	}

}