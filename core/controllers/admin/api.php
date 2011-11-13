<?php
/**
 * Api Controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Core_Api extends Bancha_Controller
{
	public function __construct()
	{
	    parent::__construct();

		$this->content->set_stage($this->input->post('production') == TRUE ? FALSE : TRUE);
	    $this->view->base = 'admin/';

	    $this->load->frmodel('model_tokens', 'tokens');
	    //$this->auth->needs_login();
	}

	private function _display($data = array(), $code = 200, $message = 'OK')
	{
		if (!is_array($data))
		{
			$data = array();
		}

		$dt = array(
			'status'	=> $code,
			'data'		=> & $data,
			'message'	=> $message
		);

		//Sends the content to the browser
		$this->output->set_content_type('json')
			     	 ->set_output(json_encode($dt));
	}

	private function _check_token()
	{
		$ok = $this->tokens->check_token($this->input->get_post('token'));

		if (!$ok)
		{
			$this->_display(NULL, 400, 'BAD_TOKEN');
		}
		return $ok;
	}

	public function index()
	{
		if ($this->_check_token()) $this->_display();
	}

	public function login()
	{
		$token = $this->tokens->get_new(
				$this->input->get_post('username'),
				$this->input->get_post('password')
			);

		if ($token)
		{
			$this->load->events();
			$this->events->log('api-login');

			$this->_display(array('token' => $token));			
		} else {
			$this->_display(NULL, 403, 'USER_PWD_WRONG');
		}
	}

	public function logout()
	{
		$this->tokens->destroy_token($this->input->get_post('token'));
		$this->_display(NULL, 200, 'OK');
	}

	public function records()
	{
		if (! $this->_check_token()) return;

		$query = array_filter(explode('|', $this->input->get_post('query')));
		if (is_array($query) && count($query))
		{
			$result = array();
			foreach ($query as $action)
			{
				$params = explode(':', $action);
				if (is_array($params) && count($params))
				{
					if (!isset($params[1]))
					{
						$params[1] = '';
					}
					list($method, $param) = $params;
					if (strpos($param, ','))
					{
						//Two params
						list($first_param, $second_param) = explode(',', $param);
						$result = $this->records->$method($first_param, $second_param);
					} else {
						//Single param
						if ($method == 'type')
						{
							$type = $this->content->type($param);
							//Check ACL for this content type
							if (!$this->auth->has_permission('content', $type['name']))
							{
								$this->_display(array('query' => $this->input->get_post('query')), 400, 'NOT_AUTHORIZED');
								return;
							}
						}
						$result = $this->records->$method($param);
					}
				} else {
					//No params
					$method = $params[0];
					$result = $this->records->$method();
				}
			}
			if (is_array($result) && count($result) && $result[0] instanceof Record)
			{	
				$compiled_result = array();
				foreach ($result as $record)
				{
					if (!$record instanceof Record) continue;
					$rec = $record->get_data();
					unset($rec['xml']);
					$compiled_result[] = $rec;
				}
				$this->_display(array('records' => $compiled_result, 'count' => count($compiled_result)));
			} else {
				if ($result)
				{
					//Output, but not Record objects
					$this->_display($result);
				} else {
					//No results
					$this->_display(NULL, 200, 'NO_RECORDS');
				}	
			}
		} else {
			$this->_display(NULL, 400, 'BAD_QUERY');
		}
	}

	public function types($name = '')
	{
		if (! $this->_check_token()) return;

		//We check the post data for a single type
		if ($name == '')
		{
			$name = $this->input->post('type');
		}	
		if ($name)
		{
			//Single content type
			$type = $this->content->type($name);
			if ($type)
			{
				unset($type['name']);
				$this->_display(array($name => $type), 200, 'OK');
			} else {
				$this->_display(NULL, 400, 'CONTENT_TYPE_NOT_FOUND');
			}
		} else {
			$types = array();
			$tmp = $this->content->types();
			foreach ($tmp as $type)
			{
				if ($this->auth->check_permission('content', $type['name']))
				{
					$name = $type['name'];
					unset($type['name']);
					$types[$name] = $type;
				}
			}
			$this->_display($types, 200, 'OK');
		}
	}
}