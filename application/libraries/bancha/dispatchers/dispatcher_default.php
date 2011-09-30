<?php
/**
 * Default Dispatcher (Library)
 *
 * The default dispatching (and routing) class of the website
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Dispatcher_default
{
	/**
	 * @var Code Igniter instance
	 */
	private $_CI;

	public function __construct()
	{
		$this->_CI = & get_instance();
	}

	/**
	 * Starts the routing
	 * @param Record $record (optional, for manual routing)
	 */
	public function start($record = '')
	{
		if ($record != '' && $record instanceof Record)
		{
			//Record has been passed!
		} else {
			//Auto-routing
			$record = $this->get_current_record();

			if (!$record)
			{
				$this->_CI->view->title = _('Page not found');
				$this->_CI->view->render_template('not_found', TRUE, 404);
				return;
			}
		}

		//We get the website pages tree
		$this->_CI->view->set('tree', $this->_CI->tree->get_default());

		//Let's set the meta title, keywords and description
		$this->_CI->view->title = $record->get('meta_title');
		if (!$this->_CI->view->title)
		{
			//If the meta title is not found, let's use the title field
			$this->_CI->view->title = $record->get('title');
		}
		$this->_CI->view->keywords = $record->get('meta_keywords');
		$this->_CI->view->description = $record->get('meta_description');

		//Is this a page?
		if ($record->is_page())
		{
			$page = $record;

			//We set the language to be the same as the page
			$this->_CI->lang->set_lang($page->get('lang'));

			//We set che cache if the page wants it
			$cache = (int)$page->get('page_cache');
			if ($cache > 0)
			{
				$this->_CI->output->cache($cache);
			}

			switch ($page->get('action'))
			{
				case 'list':
					$page = $this->set_recordlist($page);
					break;

				case 'action':
					$this->call_action($page);
					break;

				case 'link':
					$link = $page->get('action_link_url');
					redirect($link);
			}

			$this->_CI->view->set('page', $page);
		} else {
			//Single record view
			$page = $this->set_singleview($record);
		}
		$this->_CI->view->render_template($page->get('view_template'));
	}

	protected function set_singleview($record, $parent_page) {
		//Single record view
		$template = $record->get('view_template');

		if (!$parent_page)
		{
			$parent_page = $record->get('_parentpage');
		}

		if (! ($record instanceof Record))
		{
			$this->_CI->view->title = _('Page not found');
			$this->_CI->view->render_template('not_found', TRUE, 404);
		}

		//Action: single record
		$parent_page->set('action', 'single');
		$record->set('action', 'single');

		//Let's check the publish date
		$date_publish = $record->get('_date_publish');
		if ($date_publish && $date_publish > time())
		{
			$this->_CI->view->title = _('Page not found');
			$this->_CI->view->render_template('not_found', TRUE, 404);
			return;
		}

		$this->_CI->tree->breadcrumbs[$record->id] = array(
					'title'	=> $record->get('title'),
					'link'	=> uri_string().'/'
		);

		//The title will be prepended
		$this->_CI->view->title = $record->get('title') . ' - ' . $this->_CI->view->title;
		$this->_CI->view->set('record', $record);
		$parent_page->set('view_template', $template);
		$parent_page->set('_record', $record);
		debug($parent_page);die;
		return $page;
	}

	protected function set_recordlist($page)
	{
		$categories = $page->get('action_list_categories');
		$get_category = $this->_CI->input->get('category');

		if ($categories || $get_category) {
			if ($categories)
			{
				$categories = explode(',', $categories);
			}
			if ($get_category)
			{
				$categories[] = $get_category;
			}

			$this->_CI->load->categories();
			$cat_ids = $this->_CI->categories->name_in($categories)->get_ids();

			$category_record_ids = $this->_CI->categories->get_records_for_categories($cat_ids);
			if (count($category_record_ids))
			{
				$this->_CI->db->start_cache();
				$this->_CI->records->id_in($category_record_ids);
				$this->_CI->db->stop_cache();
			}
		}

		$hierarchies = $page->get('action_list_hierarchies');
		if (is_array($hierarchies) && count($hierarchies))
		{
			$this->_CI->load->hierarchies();
			$hierarchies_record_ids = $this->_CI->hierarchies->get_records_for_hierarchies($hierarchies);
			if (count($hierarchies_record_ids))
			{
				$this->_CI->db->start_cache();
				$this->_CI->records->id_in($hierarchies_record_ids);
				$this->_CI->db->stop_cache();
			}
		}

		$limit = (int)$page->get('action_list_limit');
		if ($limit) {
			$current_cursor = $this->_CI->input->get('page');
			if (!$current_cursor) {
				$offset = 0;
			} else {
				$offset = $current_cursor;
			}
			$this->_CI->records->limit($limit, $offset);
		}

		$tipo = $page->get('action_list_type');
		$type = $this->_CI->content->type($tipo);

		$order_by = $page->get('action_list_order_by');
		if ($order_by) {
			if ($type)
			{
				$order_by = str_replace('id_record', $type['primary_key'], $order_by);
			}
			$this->_CI->records->order_by($order_by);
		}

		$this->_CI->db->start_cache();

		$sql_where = $page->get('action_list_where');
		if ($sql_where) {
			$this->_CI->records->where($sql_where);
		}

		if (isset($type['fields']['date_publish']))
		{
			//We can extract only published records
			$this->_CI->records->where('date_publish <= ' . time());
		}

		if ($tipo)
		{
			$this->_CI->records->type($tipo);
		}
		//Just list fields, not the detail ones
		$this->_CI->records->set_list(TRUE);
		$this->_CI->records->language();
		$this->_CI->db->stop_cache();

		$records = $this->_CI->records->get();

		//If there's a limit, we will be a pagination
		if ($limit)
		{
			$pagination = array(
	        	'total_rows'			=> $this->_CI->records->count(),
	        	'per_page'				=> $limit,
	        	'base_url'				=> current_url().'?',
	        	'cur_tag_open'			=> '',
	        	'cur_tag_close'			=> '',
	        	'page_query_string'		=> TRUE,
	        	'query_string_segment'	=> 'page',
	        	'first_url'				=> current_url()
			);

			$this->_CI->view->set('total_records', $pagination['total_rows']);

			$this->_CI->load->library('pagination');
			$this->_CI->pagination->initialize($pagination);
		}

		$this->_CI->db->flush_cache();

		if ($this->_CI->view->is_feed)
		{
			$this->_CI->load->frlibrary('feed');
			$feed_header = array(
				'title' 		=> $page->get('title'),
				'description'	=> $page->get('contenuto')
			);
			$this->_CI->feed->create_new($feed_header, $this->_CI->view->is_feed);

			if (count($records))
			{
				foreach ($records as $record)
				{
					$date_pub = $record->get('_date_publish');
					if (!$date_pub)
					{
						$date_pub = $record->get('_date_insert');
					}
					$item = array(
						'title'			=> $record->get('title'),
						'link'			=> current_url().'/'.$record->get('uri'),
						'guid'			=> current_url().'/'.$record->get('uri'),
						'pubDate'		=> date(DATE_RFC822, (int)$date_pub),
						'description'	=> $record->get('contenuto')
					);
					$this->_CI->feed->add_item($item);
				}
			}
			$this->_CI->feed->render();
			return;

		} else if ($page->get('action_list_has_feed') == 'T')
		{
			$this->_CI->view->has_feed = TRUE;
		}

		$page->set('records', $records);
		return $page;
	}

	protected function call_action($page)
	{
		$folder = $this->_CI->config->item('custom_controllers_folder');
		define('CUSTOM_ACTION', TRUE);
		$custom_actions_controller = $folder . 'actions.php';
		require_once($custom_actions_controller);
		$actions = new Actions();
		$action_name = $page->get('action_custom_name');

		if (is_callable(array($actions, $action_name)))
		{
			$render_type = $page->get('action_custom_mode');
			if ($render_type == 'C')
			{
				$page->set('action', 'action_render');
				$page->set('_action_class', $actions);
			} else {
				$actions->$action_name('dispatcher');
				return;
			}
		} else {
			show_error('The custom action named "'.$action_name.'()" has not been found in '.$custom_actions_controller);
		}
	}

	/**
	 * Auto-routing method
	 * Based on the current URI
	 * @return Record|FALSE
	 */
	protected function get_current_record()
	{
		$found = FALSE;

		if (isset($this->_CI->tree))
		{
			//The current page/record segment
			$current_page = $this->_CI->tree->current_page_uri;
		}

		$current_request = $this->_CI->uri->uri_string();

		$result = array();
		if (isset($this->_CI->records))
		{
			//We extract a page based on the full request url
			$result = $this->_CI->records->set_type()->full_uri($current_request)->documents(FALSE)->limit(1)->get();
		}

		if (!count($result))
		{
			//If not found, it could be a content
			$result = $this->_CI->records->where('uri', $current_page)->documents(FALSE)->limit(5)->get();

			if (!count($result))
			{
				//Let's search also on the custom tables
				$content_types = $this->_CI->content->types();

				$tipi_ricerca = array();
				foreach ($content_types as $id_tipo => $single_tipo)
				{
					//If the table isn't the record one and we haven't searched in this table
					if ($single_tipo['table'] != 'records' && !$found && !(in_array($id_tipo, $tipi_ricerca)))
					{
						$result = $this->_CI->records->type($id_tipo)->where('uri', $current_page)->documents(FALSE)->limit(5)->get();
						if (count($result)) {
							break;
						}
						$tipi_ricerca[] = $id_tipo;
					}
				}
			}

			if (count($result))
			{
				//We extracted a record, so let's extract the parent page
				$parent_page_uri = str_replace('/'.$current_page, '', $current_request);
				$result_pages = $this->_CI->records->full_uri($parent_page_uri)->documents(FALSE)->limit(1)->get();
				if (count($result_pages))
				{
					$page = $result_pages[0];
					foreach ($result as $single_record)
					{
						if (in_array($page->get('action'), array('list', 'single')))
						{
							//We check if the parent page is listing records of the current record type
							if ($page->get('action_list_type') == $single_record->_tipo)
							{
								$single_record->set('_parentpage', $page);
								return $single_record;
								$page->set('_record', $single_record);
								return $page;
							}
						}
					}
				}
			}
		} else {
			//We get the first record
			return $result[0];
		}
		return FALSE;
	}
}