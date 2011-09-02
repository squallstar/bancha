<?php
Class Dispatcher_default
{
	public function start()
	{
		$CI = & get_instance();
		
		$found = FALSE;
		
		//Segmento pagina/record attuale
		if (isset($CI->tree)) {
			$current_page = $CI->tree->current_page_uri;
		}
		
		$current_request = $CI->uri->uri_string();
		
		//Array con la pagina/record attuale
		$result = array();
		if (isset($CI->records))
		{
			$result = $CI->records->set_type()->full_uri($current_request)->documents(FALSE)->limit(1)->get();
		}
		
		//Controllo se la pagina richiesta esiste
		if (!count($result))
		{
			//Potrebbe essere un contenuto
			$result = $CI->records->where('uri', $current_page)->documents(FALSE)->limit(5)->get();
		
			if (count($result))
			{
				//Estraggo la pagina padre
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
								//Controllo se il record è dello stesso tipo di quello che sto ciclando
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
			//Ottengo il primo record dei risultati
			$record = $result[0];
			$found = TRUE;
		}
		
		
		if (!$found) {
			$CI->view->title = _('Page not found');
			$CI->view->render_template('not_found', TRUE, 404);
			return;
		}
		
		//Ottengo l'albero delle pagine
		$CI->view->set('tree', $CI->tree->get_default());
		
		//Imposto title, description e keywords
		$CI->view->title = $record->get('meta_title');
		if (!$CI->view->title)
		{
			//Se non c'è il meta title, uso il title
			$CI->view->title = $record->get('title');
		}
		$CI->view->keywords = $record->get('meta_keywords');
		$CI->view->description = $record->get('meta_description');
		
		//Controllo se e' una pagina
		if ($record->is_page())
		{
			$page = $record;
		
			//Imposto la lingua corrente in base alla pagina
			$CI->lang->set_lang($page->get('lang'));
		
			//Imposto la cache per la pagina corrente se presente
			$cache = (int)$page->get('page_cache');
			if ($cache > 0)
			{
				$CI->output->cache($cache);
			}
		
			switch ($page->get('action'))
			{
				case 'list':
					$categories = $page->get('action_list_categories');
					if ($categories) {
						$categories = explode(',', $categories);
		
						$CI->load->categories();
						$cat_ids = $CI->categories->name_in($categories)->get_ids();
		
						$category_record_ids = $CI->categories->get_records_for_categories($cat_ids);
						$CI->db->start_cache();
						$CI->records->id_in($category_record_ids);
						$CI->db->stop_cache();
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
						//Estraggo solo record pubblicati
						$CI->records->where('date_publish <= ' . time());
					}
		
					//Ottengo i records
					if ($tipo)
					{
						$CI->records->type($tipo);
					}
					//Ottengo i contenuti per questa pagina
					$CI->records->set_list(TRUE);
					$CI->records->language();
					$CI->db->stop_cache();
		
					$records = $CI->records->get();
		
					//Paginazione se impostato un limite
					if ($limit)
					{
						$pagination = array(
						        	'total_rows'			=> $CI->records->count(), //TODO reapply filters
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
						$actions->$action_name();
						return;
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
			//Visualizzazione dettaglio record singolo
			$template = $page->get('view_template');
		
			//Azione: singolo record
			$page->set('action', 'single');
			$record->set('action', 'single');
		
			//Controllo la data di pubblicazione
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
		
			$CI->view->set('page', $page);
			$CI->view->set('record', $record);
			$CI->view->render_template($template);
		
		}
	}
}