<?php
/**
 * Default Dispatcher (Library)
 *
 * The default dispatching (and routing) class of the website
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Dispatcher_default extends Core
{
	public function __construct()
	{
		$this->load->database();

		//Front-end helper
		$this->load->helper('frontend');
	}

	/**
	 * Starts the routing
	 * @param Record $record (optional, for manual routing)
	 */
	public function start($record = '')
	{
		if ($record == '' || !($record instanceof Record))
		{
			//Auto-routing
			$record = $this->get_current_record();

			if (!$record)
			{
				$this->view->title = _('Page not found');
				$this->view->render_template('not_found', TRUE, 404);
				return;
			}
		}

		//We get the website pages tree
		$this->view->set('tree', $this->tree->get_default());

		//Let's set the meta title, keywords and description
		$this->view->title = $record->get('meta_title');
		if (!$this->view->title)
		{
			//If the meta title is not found, let's use the title field
			$this->view->title = $record->get('title');
		}
		$this->view->keywords = $record->get('meta_keywords');

		$this->view->description = strip_tags($record->get('meta_description'));
		if (!$this->view->description)
		{
			//If the meta description is not found, let's use the content field
			$this->load->helper('text');
			$this->view->description = character_limiter(strip_tags($record->get('content')), 150, '...');
		}

		//Is this a page?
		if ($record->is_page())
		{
			$page = $record;

			//We set the language to be the same as the page
			$this->lang->set_lang($page->get('lang'));

			//We set che cache if the page wants it
			$cache = (int)$page->get('page_cache');
			if ($cache > 0)
			{
				$this->output->cache($cache);
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
					redirect($link, 'location', 301);
			}
		} else {
			//Single record view
			$page = $this->set_singleview($record);
		}

		//Last check before dispatching
		if ($page instanceof Record)
		{
			if ($this->view->is_feed == 'pdf')
			{
				$this->load->dispatcher('print', 'dispatcher_print');
				$this->dispatcher_print->render($page);
			} else {
				//We add a page global variable
				$GLOBALS['page'] = & $page;
				$this->view->set('page', $page);
				$this->view->render_template($page->get('view_template'));
			}
		}
	}

	protected function set_singleview($record, $parent_page = '')
	{
		if (!$parent_page)
		{
			$parent_page = $record->get('_parentpage');
			$record->set('_parentpage', NULL);
		}

		//Single record view
		$template = $parent_page->get('view_template');

		if (! ($record instanceof Record))
		{
			$this->view->title = _('Page not found');
			$this->view->render_template('not_found', TRUE, 404);
		}

		//Action: single record
		$parent_page->set('action', 'single');
		$record->set('action', 'single');

		//Let's check the publish date
		$date_publish = $record->get('_date_publish');
		if ($date_publish && $date_publish > time())
		{
			$this->view->title = _('Page not found');
			$this->view->render_template('not_found', TRUE, 404);
			return;
		}

		$this->tree->breadcrumbs[$record->id] = array(
					'title'	=> $record->get('title'),
					'link'	=> uri_string().'/'
		);

		$parent_page->set('view_template', $template);
		$this->view->set('record', $record);
		$GLOBALS['record'] = & $record;

		return $parent_page;
	}

	protected function set_recordlist($page)
	{
		$categories = $page->get('action_list_categories');
		$get_category = $this->input->get('category');

		//First of all, let's extract the categories (we prepare a select statement)
		$cat_ids = FALSE;
		if ($categories || $get_category) {
			if ($categories)
			{
				$categories = explode(',', $categories);
			}
			if ($get_category)
			{
				$categories[] = $get_category;
			}

			$this->load->categories();
			$cat_ids = $this->categories->name_in($categories)->get_ids();
			if ($cat_ids)
			{
				$cat_sql = $this->categories->get_records_for_categories($cat_ids, TRUE);
			}
			
		}

		//Now we extract the hierarchies (just a select statement)
		$hierarchies = $page->get('action_list_hierarchies');
		if (is_array($hierarchies) && count($hierarchies))
		{
			$this->load->hierarchies();
			$hie_sql = $this->hierarchies->get_records_for_hierarchies($hierarchies, TRUE);
			if (count($hie_sql))
			{
				$this->records->id_in($hie_sql, FALSE);
			} else {
				$this->records->where(1, 2);
			}
		}

		//After the query above we can apply the SELECT statement on the category
		if ($cat_ids)
		{
			$this->records->id_in($cat_sql, FALSE);
		} else if ($categories || $get_category) {
			$this->records->where(1, 2);
		}

		$limit = (int)$page->get('action_list_limit');
		if ($limit) {
			$current_cursor = $this->input->get('page');
			if (!$current_cursor) {
				$offset = 0;
			} else {
				$offset = $current_cursor;
			}
			$this->records->limit($limit, $offset);
		}

		$tipo = $page->get('action_list_type');
		$type = $this->content->type($tipo);

		$order_by = $page->get('action_list_order_by');
		if ($order_by) {
			if ($type)
			{
				$order_by = str_replace('id_record', $type['primary_key'], $order_by);
			}
			$this->records->order_by($order_by);
		}

		$sql_where = $page->get('action_list_where');
		if ($sql_where) {
			$this->records->where($sql_where);
		}

		if (isset($type['fields']['date_publish']))
		{
			//We can extract only published records
			$this->records->where('date_publish <= ' . time());
		}

		if ($tipo)
		{
			$this->records->type($tipo);

			$search_query = $this->input->get('search');
			if ($search_query)
			{
				$this->records->like('title', $search_query);
				$this->db->bracket('open');
				$this->records->or_like('content', $search_query);
				$this->db->bracket('close');
			}
		}
		//Just list fields, not the detail ones
		$this->records->set_list(TRUE);
		$this->records->language();

		$records = $this->records->documents(TRUE)->get();

		//If there's a limit, we will make a pagination
		if ($limit)
		{
			if ($tipo)
			{
				$this->records->type($tipo);
				if ($search_query)
				{
					$this->records->like('title', $search_query);
					$this->records->or_like('content', $search_query);
				}
			}
			if (isset($type['fields']['date_publish']))
			{
				//We can extract only published records
				$this->records->where('date_publish <= ' . time());
			}
			if ($sql_where) {
				$this->records->where($sql_where);
			}
			if (isset($category_record_ids))
			{
				$this->records->id_in($category_record_ids);
			}
			if (isset($hierarchies_record_ids))
			{
				$this->records->id_in($hierarchies_record_ids);
			}

			$count = $this->records->documents(FALSE)->set_list(TRUE)->language()->count();

			$pagination = array(
	        	'total_rows'			=> $count,
	        	'per_page'				=> $limit,
	        	'base_url'				=> current_url().'?',
	        	'cur_tag_open'			=> '',
	        	'cur_tag_close'			=> '',
	        	'page_query_string'		=> TRUE,
	        	'query_string_segment'	=> 'page',
	        	'first_url'				=> current_url()
			);

			$this->view->set('total_records', $pagination['total_rows']);

			$this->load->library('pagination');
			$this->pagination->initialize($pagination);
		}

		$this->db->flush_cache();

		if ($this->view->is_feed && $this->view->is_feed != 'pdf')
		{
			$this->load->frlibrary('feed');

			if ($this->config->item('type_custom_feeds') && isset($type['name']))
			{
				$page->set('records', $records);
				$this->view->set('page', $page);
				$GLOBALS['page'] = & $page;
				$this->view->set('records', $records);
				$GLOBALS['records'] = & $records;
				$this->view->render_type_template($type['name'], 'feed', TRUE);
				return;
			}

			$feed_header = array(
				'title' 		=> $page->get('title'),
				'description'	=> $page->get('content')
			);
			$this->feed->create_new($feed_header, $this->view->is_feed);

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
						'description'	=> str_replace('src="/attach', 'src="'.$url.'attach', $record->get('content'))
					);
					$this->feed->add_item($item, array('title', 'description'));
				}
			}
			$this->feed->render();
			return;

		} else if ($page->get('action_list_has_feed') == 'T')
		{
			$this->view->has_feed = TRUE;
		}

		$page->set('records', $records);
		return $page;
	}

	/**
	 * Calls the action of a page
	 * Works only if the action_custom_name is set
	 * @param Record $page
	 */
	protected function call_action($page)
	{
		$folder = $this->config->item('custom_controllers_folder');
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

		if (isset($this->tree))
		{
			//The current page/record segment
			$current_page = $this->tree->current_page_uri;
		} else {
			$current_page = '';
		}

		$current_request = $this->uri->uri_string();

		$result = array();
		if (isset($this->records))
		{
			//We extract a page based on the full request url
			$result = $this->records->set_type()
										 ->set_list(FALSE)
										 ->full_uri($current_request)
										 ->documents(FALSE)
										 ->where('lang', $this->lang->current_language)
										 ->limit(1)->get();
		}

		if (!count($result))
		{
			//If not found, let's search for the parent page
			$parent_page_uri = str_replace('/'.$current_page, '', $current_request);
			$result_pages = $this->records->full_uri($parent_page_uri)->documents(FALSE)->limit(1)->get();

			if (count($result_pages))
			{
				$page = $result_pages[0];

				//Page found! Now we need to find the child record
				if (in_array($page->get('action'), array('list', 'single')))
				{
					$child_type = $page->get('action_list_type');
					$result_childs = $this->records->type($child_type)->where('uri', $current_page)->documents(FALSE)->limit(1)->get();

					if (count($result_childs))
					{
						$record = $result_childs[0];
						$record->set('_parentpage', $page);
						return $record;
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