<?php
/**
 * Website Main Controller
 *
 * Controller base del front-end del sito internet
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Website extends Milk_Controller {

	public function __construct() {
		parent::__construct();

		//Se l'utente è loggato, imposto lo stage come attivo
		if ($this->auth->is_logged()) {
			$this->content->set_stage(TRUE);
			$this->output->enable_profiler();
		} else {
			$this->content->set_stage(FALSE);
		}

		$this->load->helper('menu');
	}

	/**
	 * Website homepage
	 */
	function home() {
		//Estraggo il menu di default
		$this->view->set('tree', $this->tree->get_default());

		$this->view->javascript = array('jquery.js', 'application.js');
		$this->view->css = array('style.css');

		//Renderizzo il template home
		$this->view->render_template('home');
	}

	/**
	 * Cambia il theme del sito
	 * Invocata da: /go-{theme}
	 * @param string $new_language
	 */
	function change_theme($new_theme) {
		$this->view->set_theme($new_theme);
		redirect('/');
	}

	/**
	 * Cambia la lingua del sito
	 * Invocata da: /change-language/{lang}
	 * @param string $new_language
	 */
	function change_language($new_language) {
		$this->lang->set_lang($new_language);
		$this->lang->set_cookie();
		redirect('/');
	}

	/**
	 * Website Routing
	 * Metodo per il routing generale del front-end
	 */
	function router() {

		$this->view->javascript = array('jquery.js', 'application.js');
		$this->view->css = array('style.css');

		$found = FALSE;

		//Segmento pagina/record attuale
		if (isset($this->tree)) {
			$current_page = $this->tree->current_page_uri;
		}

		$current_request = $this->uri->uri_string();

		//Array con la pagina/record attuale
		$result = array();
		if (isset($this->records))
		{
			$result = $this->records->set_type()->full_uri($current_request)->documents(FALSE)->limit(1)->get();
		}

		//Controllo se la pagina richiesta esiste
		if (!count($result))
		{
			//Potrebbe essere un contenuto
			$result = $this->records->where('uri', $current_page)->documents(FALSE)->limit(5)->get();

			if (count($result))
			{
				//Estraggo la pagina padre
				$parent_page_uri = str_replace('/'.$current_page, '', $current_request);
				$result_pages = $this->records->full_uri($parent_page_uri)->documents(FALSE)->limit(1)->get();
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
			show_404(_('Page not found'));
		}

		//Ottengo l'albero delle pagine
		$this->view->set('tree', $this->tree->get_default());

		//Imposto title, description e keywords
		$this->view->title = $record->get('meta_title');
		if (!$this->view->title)
		{
			//Se non c'è il meta title, uso il title
			$this->view->title = $record->get('title');
		}
		$this->view->keywords = $record->get('meta_keywords');
		$this->view->description = $record->get('meta_description');

		//Controllo se e' una pagina
		if ($record->is_page())
		{
			$page = $record;

			//Imposto la lingua corrente in base alla pagina
			$this->lang->set_lang($page->get('lang'));

			//Imposto la cache per la pagina corrente se presente
			$cache = (int)$page->get('page_cache');
			if ($cache > 0)
			{
				$this->output->cache($cache);
			}

			switch ($page->get('action'))
			{
				case 'list':
					$categories = $page->get('action_list_categories');
					if ($categories) {
						$categories = explode(',', $categories);

						$this->load->categories();
						$cat_ids = $this->categories->name_in($categories)->get_ids();

						$category_record_ids = $this->categories->get_records_for_categories($cat_ids);
						$this->db->start_cache();
						$this->records->id_in($category_record_ids);
						$this->db->stop_cache();
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



					$this->db->start_cache();

					$sql_where = $page->get('action_list_where');
					if ($sql_where) {
						$this->records->where($sql_where);
					}

					if (isset($type['fields']['date_publish']))
					{
						//Estraggo solo record pubblicati
						$this->records->where('date_publish <= ' . time());
					}

					//Ottengo i records
					if ($tipo)
					{
						$this->records->type($tipo);
					}
					//Ottengo i contenuti per questa pagina
					$this->records->set_list(TRUE);
					$this->records->language();
					$this->db->stop_cache();

					$records = $this->records->get();

					//Paginazione se impostato un limite
					if ($limit)
					{
						$pagination = array(
				        	'total_rows'			=> $this->records->count(), //TODO reapply filters
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

					if ($this->view->is_feed)
					{
						$this->load->frlibrary('feed');
						$feed_header = array(
							'title' 		=> $page->get('title'),
							'description'	=> $page->get('contenuto')
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
									'description'	=> $record->get('contenuto')
								);
								$this->feed->add_item($item);
							}
						}
						$this->feed->render();
						return;

					} else if ($page->get('action_list_has_feed') == 'T')
					{
						$this->view->has_feed = TRUE;
					}

					$page->set('records', $records);
					break;

				case 'action':
					$folder = $this->config->item('custom_controllers_folder');
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

			$this->view->set('page', $page);
			$this->view->render_template($page->get('view_template'));

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
				show_404(_('Page not found'));
			}
			
			$this->tree->breadcrumbs[$record->id] = array(
				'title'	=> $record->get('title'),
				'link'	=> uri_string().'/'
			);

			$this->view->set('page', $page);
			$this->view->set('record', $record);
			$this->view->render_template($template);

		}
	}
}