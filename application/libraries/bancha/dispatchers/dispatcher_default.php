<?php
/**
 * Default Dispatcher (Library)
 *
 * The default router of the website
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
	 * Starts the routing
	 */
	public function start()
	{
		$CI = & get_instance();

		$found = FALSE;

		if (isset($CI->tree))
		{
			//The current page/record segment
			$current_page = $CI->tree->current_page_uri;
		}

		$current_request = $CI->uri->uri_string();

		$result = array();
		if (isset($CI->records))
		{
			//We extract a page based on the full request url
			$result = $CI->records->set_type()->full_uri($current_request)->documents(FALSE)->limit(1)->get();
		}

		if (!count($result))
		{
			//If not found, it could be a content
			$result = $CI->records->where('uri', $current_page)->documents(FALSE)->limit(5)->get();

			if (!count($result))
			{
				//Let's search also on the custom tables
				$content_types = $CI->content->types();

				$tipi_ricerca = array();
				foreach ($content_types as $id_tipo => $single_tipo)
				{
					//If the table isn't the record one and we haven't searched in this table
					if ($single_tipo['table'] != 'records' && !$found && !(in_array($id_tipo, $tipi_ricerca)))
					{
						$result = $CI->records->type($id_tipo)->where('uri', $current_page)->documents(FALSE)->limit(5)->get();
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
				$result_pages = $CI->records->full_uri($parent_page_uri)->documents(FALSE)->limit(1)->get();
				if (count($result_pages))
				{
					$page = $result_pages[0];
					foreach ($result as $single_record)
					{
						if (!$found)
						{
							if (in_array($page->get('action'), array('list', 'single')))
							{
								//We check if the parent page is listing records of the current record type
								if ($page->get('action_list_type') == $single_record->_tipo)
								{
									$found = TRUE;
									$record = $single_record;
									break;
								}
							}
						}
					}
				}
			}
		} else {
			//We get the first record found
			$record = $result[0];
			$found = TRUE;
		}

		if (!$found) {
			$CI->view->title = _('Page not found');
			$CI->view->render_template('not_found', TRUE, 404);
			return;
		}

		//We get the website pages tree
		$CI->view->set('tree', $CI->tree->get_default());

		//Let's set the meta title, keywords and description
		$CI->view->title = $record->get('meta_title');
		if (!$CI->view->title)
		{
			//If the meta title is not found, let's use the title field
			$CI->view->title = $record->get('title');
		}
		$CI->view->keywords = $record->get('meta_keywords');
		$CI->view->description = $record->get('meta_description');

		//Is this a page?
		if ($record->is_page())
		{
			$page = $record;

			//We set the language to be the same as the page
			$CI->lang->set_lang($page->get('lang'));

			//We set che cache if the page wants it
			$cache = (int)$page->get('page_cache');
			if ($cache > 0)
			{
				$CI->output->cache($cache);
			}

			switch ($page->get('action'))
			{
				case 'list':
					$categories = $page->get('action_list_categories');
					$get_category = $CI->input->get('category');

					if ($categories || $get_category) {
						if ($categories)
						{
							$categories = explode(',', $categories);
						}
						if ($get_category)
						{
							$categories[] = $get_category;
						}

						$CI->load->categories();
						$cat_ids = $CI->categories->name_in($categories)->get_ids();

						$category_record_ids = $CI->categories->get_records_for_categories($cat_ids);
						if (count($category_record_ids))
						{
							$CI->db->start_cache();
							$CI->records->id_in($category_record_ids);
							$CI->db->stop_cache();
						}
					}

					$hierarchies = $page->get('action_list_hierarchies');
					if (is_array($hierarchies) && count($hierarchies))
					{
						$CI->load->hierarchies();
						$hierarchies_record_ids = $CI->hierarchies->get_records_for_hierarchies($hierarchies);
						if (count($hierarchies_record_ids))
						{
							$CI->db->start_cache();
							$CI->records->id_in($hierarchies_record_ids);
							$CI->db->stop_cache();
						}
					}

					$limit = (int)$page->get('action_list_limit');
					if ($limit) {
						$current_cursor = $CI->input->get('page');
						if (!$current_cursor) {
							$offset = 0;
						} else {
							$offset = $current_cursor;
						}
						$CI->records->limit($limit, $offset);
					}

					$tipo = $page->get('action_list_type');
					$type = $CI->content->type($tipo);

					$order_by = $page->get('action_list_order_by');
					if ($order_by) {
						if ($type)
						{
							$order_by = str_replace('id_record', $type['primary_key'], $order_by);
						}
						$CI->records->order_by($order_by);
					}

					$CI->db->start_cache();

					$sql_where = $page->get('action_list_where');
					if ($sql_where) {
						$CI->records->where($sql_where);
					}

					if (isset($type['fields']['date_publish']))
					{
						//We extract only published records
						$CI->records->where('date_publish <= ' . time());
					}

					if ($tipo)
					{
						$CI->records->type($tipo);
					}
					//Just list fields, not the detail ones
					$CI->records->set_list(TRUE);
					$CI->records->language();
					$CI->db->stop_cache();

					$records = $CI->records->get();

					//If there's a limit, we will be a pagination
					if ($limit)
					{
						$pagination = array(
				        	'total_rows'			=> $CI->records->count(),
				        	'per_page'				=> $limit,
				        	'base_url'				=> current_url().'?',
				        	'cur_tag_open'			=> '',
				        	'cur_tag_close'			=> '',
				        	'page_query_string'		=> TRUE,
				        	'query_string_segment'	=> 'page',
				        	'first_url'				=> current_url()
						);

						$CI->view->set('total_records', $pagination['total_rows']);

						$CI->load->library('pagination');
						$CI->pagination->initialize($pagination);
					}

					$CI->db->flush_cache();

					if ($CI->view->is_feed)
					{
						$CI->load->frlibrary('feed');
						$feed_header = array(
							'title' 		=> $page->get('title'),
							'description'	=> $page->get('contenuto')
						);
						$CI->feed->create_new($feed_header, $CI->view->is_feed);

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
								$CI->feed->add_item($item);
							}
						}
						$CI->feed->render();
						return;

					} else if ($page->get('action_list_has_feed') == 'T')
					{
						$CI->view->has_feed = TRUE;
					}

					$page->set('records', $records);
					break;

				case 'action':
					$folder = $CI->config->item('custom_controllers_folder');
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
					break;

				case 'link':
					$link = $page->get('action_link_url');
					redirect($link);
					exit;
			}

			$CI->view->set('page', $page);
			$CI->view->render_template($page->get('view_template'));

		}else{
			//Single record view
			$template = $page->get('view_template');

			//Action: single record
			$page->set('action', 'single');
			$record->set('action', 'single');

			//Let's check the publish date
			$date_publish = $record->get('_date_publish');
			if ($date_publish && $date_publish > time())
			{
				$CI->view->title = _('Page not found');
				$CI->view->render_template('not_found', TRUE, 404);
				return;
			}

			$CI->tree->breadcrumbs[$record->id] = array(
						'title'	=> $record->get('title'),
						'link'	=> uri_string().'/'
			);

			//The title will be prepended
			$CI->view->title = $record->get('title') . ' - ' . $CI->view->title;

			$CI->view->set('page', $page);
			$CI->view->set('record', $record);
			$CI->view->render_template($template);
		}
	}
}