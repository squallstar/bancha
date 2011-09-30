<?php
/**
 * Users Controller
 *
 * Users and groups management
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

	public function type() {
		//Legacy: breadcrumbs in edit_user will use this route to go back to list
		$this->lista();
	}

	public function lista($page=0)
	{
		$this->auth->check_permission('users', 'list');

		//Pagination
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

	public function delete($id_username='')
	{
		$this->auth->check_permission('users', 'add');
		$done = $this->users->delete($id_username);
		if ($done)
		{
			$this->session->set_flashdata('message', _('The user has been deleted.'));
        	redirect('admin/users/lista');
		}
	}

	public function edit($id_username='')
	{
		$this->auth->check_permission('users', 'list');

		$this->load->categories();
        $this->load->hierarchies();
        $this->load->documents();

		//We get the Users scheme
		$type_definition = $this->xml->parse_scheme($this->config->item('xml_folder') . 'Users.xml');

		$user = new Record();
		$user->set_type($type_definition);

		if ($this->input->post())
		{
			$user->set_data($this->input->post());
			$done = $this->records->save($user);

			if ($done)
			{
				$msg = _('The user informations have been updated.');
				if ($this->input->post('_bt_save_list'))
        		{
        			$this->session->set_flashdata('message', $msg);
        			redirect('admin/users/lista');
        		} else {
        			$this->view->message('success', $msg);
        		}
			}
		}

		if ($id_username != '')
		{
			//We search for this user
			$users = $this->records->set_type($type_definition)->limit(1)->where('id_user', $id_username)->get();

			if (!$users)
			{
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
	 * Method to manage groups
	 * @param string $action
	 * @param string|int $param
	 */
	public function groups($action='', $param='')
	{
		$this->auth->check_permission('users', 'groups');

		if ($action == 'edit')
		{
			if ($this->input->post('submit', FALSE))
			{
				if ($param != '')
				{
					//Existing group
					$new_acls = $this->input->post('acl', FALSE);
					$this->auth->update_permissions($new_acls, $param);
					$this->view->message('success', _('The group has been updated.'));
				} else {
					//New group
					$group_name = $this->input->post('name');

					if ($this->users->group_exists($group_name))
					{
						$this->view->message('warning', _('A group with that name already exists.'));
					} else {
						$id_group = $this->users->add_group($group_name);

						if ($id_group){
							$param = $id_group;
							$acls = $this->input->post('acl');
							if (count($acls))
							{
								$this->auth->update_permissions($acls, $id_group);
							}
							$this->view->message('success', _('The group has been created.'));
						}
					}
				}

				if ($param == $this->auth->user('group_id'))
				{
					//If I updated my group, let's update also my session permissions
					$this->auth->cache_permissions();
				}

			}

			//Let's get the group
			$group = $this->users->get_group($param);
			if (!$group)
			{
				if ($param)
				{
					show_error(_('The requested group has not been found.'));
				} else {
					$group = FALSE;
				}
			}

			//We get all ACLs
			$acl = $this->users->get_acl_list();

			//And the user permissions
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

	/**
	 * Removes a group
	 * @param int $group_id
	 */
	public function group_delete($group_id = '')
	{
		$done = $this->users->delete_group($group_id);

		if ($done)
		{
			$this->view->message('success', _('The group has been deleted.'));
		}
		$this->groups();
	}

}