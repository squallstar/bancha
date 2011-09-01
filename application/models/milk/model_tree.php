<?php
/**
 * Tree Model Class
 *
 * Classe di gestione degli alberi delle pagine/records
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_tree extends CI_Model {

	private $_tipo = '';
	private $_records;
	private $_fetched = FALSE;
	private $_menu_type;
	private $_tree = array();
	private $_show_invisibles = FALSE;
	private $_uri_segments = array();
	private $_use_lang = FALSE;

	public $current_page_uri = '';
	public $parent_page_uri = false;
	public $stage_prefix = 'stage.';

	public function __construct()
	{
		parent::__construct();

		$this->_menu_type = $this->config->item('default_tree_types');

		if (!count($this->_menu_type))
		{
			show_error('L\'albero di menu principale non &egrave; stato definito nel file di configurazione.');
		}

		//Imposto i segmenti dell'URI
		$this->_uri_segments = $this->uri->segment_array();

		$pieces = count($this->_uri_segments);

		if ($pieces)
		{
			//Controllo se e' un feed
			$last_piece = $this->_uri_segments[$pieces];
			if (in_array($last_piece,$this->config->item('feed_uri')))
			{
				list($name, $type) = explode('.',$last_piece);
				$this->uri->uri_string = str_replace('/'.$last_piece, '', $this->uri->uri_string);
				unset($this->uri->segments[$pieces]);
				$this->_uri_segments = $this->uri->segment_array();
				if (!isset($this->view))
				{
					$this->load->frlibrary('view');
				}
				$this->view->is_feed = $type;
			}
		}

		$this->current_page_uri = $this->uri->segment($this->uri->total_segments());
		$this->parent_page_uri = $this->uri->segment($this->uri->total_segments() - 1);

	}

	/**
	 * Imposta se estrarre le pagine invisibili
	 * @param bool $bool
	 * @return $this
	 */
	public function show_invisibles($extract=TRUE)
	{
		$this->_show_invisibles = (bool)$extract;
		return $this;
	}

	/**
	 * Imposta il tipo su cui estrarre l'albero
	 * @param int|string $type
	 * @return Tree
	 */
	public function type($type = '')
	{
		$this->_tipo = $this->content->type($type);
		if ($this->_tipo) {
			$this->db->where('id_type', $this->_tipo['id']);
		}
		return $this;
	}

	/**
	 * Imposta i tipi su cui estrarre l'albero
	 * @param array $types
	 * @return $this;
	 */
	public function type_in($types)
	{
		if (count($types)) {
			$in = array();
			$first = true;
			foreach ($types as $type) {
				$tipo = $this->content->type($type);
				$in[] = $tipo['id'];
				if ($first) {
					$first = false;
					$this->_tipo = $tipo;
				}
			}
			$this->db->where_in('id_type', $in);
		}
		return $this;
	}

	/**
	 * Imposta l'utilizzo di tutti i parent types di un tipo per la ricerca
	 * @param int|string $type
	 */
	public function parent_types($type = '')
	{
		$this->_tipo = $this->content->type($type);
		$this->_tipo['parent_types_id'] = array();
		foreach ($this->_tipo['parent_types'] as $parent_type) {
			$this->_tipo['parent_types_id'][] = $this->content->type_id($parent_type);
		}
		$this->db->where_in('id_type', $this->_tipo['parent_types_id']);

		return $this;
	}

	/**
	 * Imposta una condizione where nella ricerca
	 * @param string $key
	 * @param int|string $val
	 */
	public function where($key='', $val)
	{
		if ($key != '')
		{
			$this->db->where($key, $val);
		}
		return $this;
	}

	/**
	 * Funzione chiamata dalle get per estrarre i dati
	 * Il primo parametro sceglie se cercare in sviluppo (FALSE) o produzione (TRUE)
	 */
	private function _fetch($stage = FALSE)
	{

		//Se non impostato, estraggo il tipo impostato come default per i menu
		if ($this->_tipo == '')
		{
			$this->type_in($this->_menu_type);
		}

		//Imposto la lingua se richiesta
		if ($this->_use_lang)
		{
			$this->where('lang', $this->_use_lang);
		}

		$this->db->select(implode(', ', $this->config->item('page_extract_columns')));

		//Se il tipo è ad albero, estraggo i parent
		if ($this->_tipo['tree'])
		{
			$this->db->select('id_parent');
		}

		//Scelgo se estrarre le pagine invisibili
		if ($this->_show_invisibles !== TRUE)
		{
			$this->db->where('show_in_menu !=', 'F');
		}

		$this->_records = $this->db->from($this->content->is_stage ? $this->pages->table_stage : $this->pages->table)
							 ->order_by('priority', 'DESC')
						     ->get()
						 	 ->result();
		$this->_fetched = true;
	}

	/**
	 * Esclude dalla ricerca una pagina
	 * @param int $id
	 * @return $this
	 */
	public function exclude_page($id='')
	{
		if (is_numeric($id))
		{
			$this->db->where('id_record !=', $id);
		}
		return $this;
	}

	/**
	 * Esclude dalla ricerca dei record che hanno l'id_parent fornito
	 * @param int $id
	 * @return $this
	 */
	public function exclude_parent($id='')
	{
		if (is_numeric($id))
		{
			$this->db->where('(id_parent IS NULL OR id_parent != ' . (int)$id . ')');
		}
		return $this;
	}


	/**
	 * Ritorna un albero lineare
	 * @return array
	 */
	public function get_linear()
	{
		if (!$this->_fetched)
		{
			$this->_fetch();
		}
		return $this->_records;
	}

	/**
	 * Ritorna un albero lineare sotto forma di array associativo
	 * (utile per le select)
	 * @return array
	 */
	public function get_linear_dropdown()
	{
		if (!$this->_fetched)
		{
			//$this->db->order_by('uri', 'ASC'); non funziona perchè tanto l'array sotto poi si disordina
			$this->_fetch();
		}
		
		return $this->get_branch_dropdown($this->_records);
	}
	
	
	/**
	 * Ritorna un albero ramificato sotto forma di array associativo
	 * (utile per le select con alberatura)
	 * @return array
	 */
	public function get_branch_dropdown($tree = NULL, $id_parent = NULL, $level = 0, $options = array())
	{		
		foreach ($tree as $key => $node) 
		{
			
			if ($node->id_parent == $id_parent)
			{
				$options [$node->id_record] = str_repeat('---' , $level)." ".$node->title;
				unset($tree[$key]);
				$level++;
				$options = $this->get_branch_dropdown($tree, $node->id_record, $level, $options);
				$level--;
			}
		}
		return $options;
	}


	/**
	 * Forza il refresh da DB dei dati alla prossima ricerca
	 */
	public function clear()
	{
		$this->_fetched = false;
		return $this;
	}

	/**
	 * Ottiene la gerarchia di una pagina
	 * @param Record $record
	 */
	public function get_page_hierarchy($record)
	{
		if ($record instanceof Record && $record->is_page())
		{
			$parent = $record->get('id_parent');
			//TODO FINIRE
		}
	}

	/**
	 * Ottiene la path dell'albero cacheato di un tipo (in base anche a stage/production)
	 * @param int|string $type
	 */
	public function get_type_cachepath($type = '')
	{
		if ($type == '')
		{
			$name = ($this->content->is_stage ? $this->stage_prefix : '') . implode('.', $this->config->item('default_tree_types'));
		} else {
			if (is_numeric($type))
			{
				$name = $this->content->type_name($type);
			} else {
				$name = $type;
			}
		}

		//Imposto l'utilizzo della lingua dell'albero
		$name = $this->lang->current_language . '-' . $name;
		$this->_use_lang = $this->lang->current_language;

		return str_replace('{name}', $name, $this->config->item('tree_cache_folder'));
	}

	/**
	 * Ottiene il menu di default del sito
	 * Il tipo di estrazione di default viene impostato nei file di configurazione
	 * @return array
	 */
	public function get_default($type='')
	{
		//Ottengo il path dell'albero
		$tree_file = $this->get_type_cachepath($type='');
		return $this->_cache_tree($tree_file);
	}

	/**
	 * Mette in cache l'albero di un tipo, o lo crea se non esiste
	 * @param string $file path cache
	 */
	private function _cache_tree($file)
	{
		if (file_exists($file))
		{

			//Carico in memoria i dati come se fossero stati estratti dal DB
			$this->_records = unserialize(file_get_contents($file));
			$this->_fetched = true;

		} else {

			$stage = strpos($file,'/'.$this->stage_prefix);

			if($stage === FALSE)
			{
				//Fetch dai dati di produzione
				$this->_fetch();
			} else
			{
				//Fetch dai dati di sviluppo
				$this->_fetch(FALSE);
			}
			write_file($file, serialize($this->_records));
		}

		//Costruisco l'albero
		return $this->get();
	}

	/**
	 * Ottiene una struttura ad albero del tipo di contenuto estratto
	 * @return array
	 */
	public function get()
	{

		if (!$this->_fetched)
		{
			$this->_fetch();
		}

		$this->_tree = $this->get_branch($this->_records);
		return $this->_tree;

	}

	/**
	 * Ottiene un ramo dell'albero di menu principale, partendo dalla pagina con id scelto
	 * @param int $starting_id
	 * @return array
	 */
	public function get_default_branch($starting_id='')
	{
		if ($starting_id != '')
		{
			$tree = $this->get_branch($this->_records, $starting_id);

			if (isset($tree[$starting_id]['sons']))
			{
				return $tree[$starting_id]['sons'];
			} else {
				return array();
			}
		} else {
			show_error('Starting branch not set');
		}
	}

	/**
	 * Ottiene il ramo attuale dalla pagina/record corrente
	 * @return array
	 */
	public function get_current_branch()
	{
		$id = FALSE;
		$record = $this->view->get('record');
		if (!$record)
		{
			$page = $this->view->get('page');
			if ($page)
			{
				$id = $page->id;
			}
		} else {
			$id = $record->id;
		}
		return $this->get_default_branch($id);
	}


	/**
	 * Crea un albero di menu
	 * @param array $pages Array of Record objects
	 * @param int $starting_id
	 */
	public function get_branch($pages, $starting_id='')
	{
		$sons = array();
		$nodes = array();
		$root = array();
		$direct_parent = FALSE;

		foreach($pages as $page)
		{
			if ($starting_id != '')
			{
				if ($starting_id == $page->id_record)
				{
					$root[] = $page->id_record;
					$direct_parent = $page->id_parent;
				} else {
					$sons[$page->id_parent][] = $page->id_record;
				}
			} else {
				if(!$page->id_parent || $page->id_parent === null)
				{
					$root[] = $page->id_record;
				}else{
					$sons[$page->id_parent][] = $page->id_record;
				}
			}

			$nodes[$page->id_record] = array('id_parent'=>$page->id_parent,'value'=>$page);
		}

		if ($starting_id != '' && $direct_parent)
		{
			//Costruisco il path base di partenza nel caso di padre non root
			$this->base_uri = '';
			while (is_numeric($direct_parent))
			{
				$node = $nodes[$direct_parent];
				$this->base_uri .= $node['value']->uri . '/';
				$direct_parent = $node['id_parent'];
			}
			$nodes[$starting_id]['value']->uri = $this->base_uri . $nodes[$starting_id]['value']->uri;

		}

		$tree = array();
		foreach ($root as $r)
		{
			$this->_treemap($r, $nodes, $sons, '', $tree);				
		}
		if (isset($tree['sons']))
		{
			$tree = $tree['sons'];
		}
		return $tree;
	}


	/**
	 * Funzione ricorsiva interna per creare gerarchie di menu
	 * @param int $id
	 * @param array $nodes
	 * @param array $sons
	 * @param string $link
	 * @param string $arr
	 */
	private function _treemap($id, &$nodes, &$sons, $link='/', &$arr)
	{
		$page = $nodes[$id]['value'];

		$link .= $page->uri . '/';
		$alias = $page->uri;
		$page->link = $link;
		$arr['sons'][$id] = array(
					'title' => $page->title,
					'link' => $link,
					'open'	=> in_array($page->uri, $this->_uri_segments) ? TRUE : FALSE,
					'selected'	=> $this->current_page_uri == $page->uri ? TRUE : FALSE,
					'show_in_menu'	=> isset($page->show_in_menu) ? $page->show_in_menu : 'F'
		);
		if(isset($sons[$id]))
		{
			foreach($sons[$id] as $son)
			{
				$this->_treemap($son, $nodes, $sons, $link, $arr['sons'][$id]);
			}
		}

	}
	

	/**
	 * Elimina il file relativo alla cache di un tipo
	 * @param int|string $tipo
	 */
	public function clear_cache($tipo='')
	{
		$path = $this->get_type_cachepath($tipo);
		if (file_exists($path)) {
			unlink($path);
		}
	}


}