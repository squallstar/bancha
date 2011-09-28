<?php
/**
 * Tree Model Class
 *
 * Classe di gestione degli alberi delle pagine/records
 *
 * @package		Bancha
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
	private $_current_branch = FALSE;

	public $current_page_uri = '';
	public $parent_page_uri = false;
	public $stage_prefix = 'stage.';
	public $breadcrumbs = array();
	public $last_piece = '';

	public function __construct()
	{
		parent::__construct();

		$this->_menu_type = $this->config->item('default_tree_types');

		if (!count($this->_menu_type))
		{
			show_error('The default content type has not been defined in the file config/bancha.php!');
		}

		//We set the URI segments
		$this->_uri_segments = $this->uri->segment_array();

		$pieces = count($this->_uri_segments);

		if ($pieces)
		{
			//We check if the requested URL is a feed
			$last_piece = $this->_uri_segments[$pieces];
			if (in_array($last_piece,$this->config->item('feed_uri')))
			{
				$this->last_piece = '/' . $last_piece;
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
	 * Defines if the hidden pages needs to be extracted
	 * @param bool $bool
	 * @return $this
	 */
	public function show_invisibles($extract=TRUE)
	{
		$this->_show_invisibles = (bool)$extract;
		return $this;
	}

	/**
	 * Sets the content type to extract
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
	 * Sets the content types to extract
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
	 * Sets the parent types to search in
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
	 * Adds a where condition
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
	 * The getters call it to fetch the data from the database
	 * @param bool $stage Are we in stage?
	 */
	private function _fetch($stage = FALSE)
	{
		//If type has not be setted, we use the default ones
		if ($this->_tipo == '')
		{
			$this->type_in($this->_menu_type);
		}

		//We extract only the pages for the current lang
		if ($this->_use_lang)
		{
			$this->where('lang', $this->_use_lang);
		}

		$this->db->select(implode(', ', $this->config->item('page_extract_columns')));

		//If type is tree, let's extract also the column id_parent
		if ($this->_tipo['tree'])
		{
			$this->db->select('id_parent');
		}

		//We choose if the invisible pages must be extracted
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
	 * Excludes a page from the search
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
	 * Excludes the records that have that id_parent
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
	 * Returns a linear tree
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
	* Returns a linear tree as an associative array, useful for the Select fields
	* @return array
	*/
	public function get_linear_dropdown()
	{
		if (!$this->_fetched)
		{
			$this->_fetch();
		}
		return $this->get_branch_dropdown($this->_records);
	}


	/**
	* Gets a tree as an associative array, starting from a pages list
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
	 * Force the data to be fetched again from the database on the next request
	 */
	public function clear()
	{
		$this->_fetched = false;
		return $this;
	}

	/**
	 * Gets a page hierarchy
	 * @param Record $record
	 */
	public function get_page_hierarchy($record)
	{
		if ($record instanceof Record && $record->is_page())
		{
			$parent = $record->get('id_parent');
			
			//TODO: !!!!!!!!!!!!!!!!!!
		}
	}

	/**
	 * Returns the path of the cache file, given the type
	 * The path changes between stage and production.
	 * @param int|string $type
	 */
	public function get_type_cachepath($type = '')
	{
		$name = $this->lang->current_language . '-';
		if ($type == '')
		{
			$name = ($this->content->is_stage ? $this->stage_prefix : '') . $name . implode('.', $this->config->item('default_tree_types'));
		} else {
			if (is_numeric($type))
			{
				$name = $name . $this->content->type_name($type);
			} else {
				$name = $name . $type;
			}
		}

		$this->_use_lang = $this->lang->current_language;

		return str_replace('{name}', $name, $this->config->item('tree_cache_folder'));
	}

	/**
	 * Returns the paths of all the cache files of a type,
	 * one for each language and one for the stage mode
	 * @param int|string $type
	 */
	public function get_type_cachepaths($type = '')
	{
		if ($type == '')
		{
			$name = implode('.', $this->config->item('default_tree_types'));
		} else {
			if (is_numeric($type))
			{
				$name = $this->content->type_name($type);
			} else {
				$name = $type;
			}
		}
		$languages = array_keys($this->lang->languages);
		$this->_use_lang = $this->lang->current_language;
		
		$source_name = $this->config->item('tree_cache_folder');
		$tmp = array();
		foreach ($languages as $lang)
		{
			$tmp[] = str_replace('{name}', $lang.'-'.$name, $source_name);
			$tmp[] = str_replace('{name}', $this->stage_prefix.$lang.'-'.$name, $source_name);
		}
		return $tmp;

	}

	/**
	 * Gets the default tree of a website, using the content types defined in the config file
	 * @return array
	 */
	public function get_default($type='')
	{
		$tree_file = $this->get_type_cachepath($type='');
		return $this->_cache_tree($tree_file);
	}

	/**
	 * This will caches a tree, or will create it if not exists (and then, caches it!) 
	 * @param string $file path cache
	 */
	private function _cache_tree($file)
	{
		if (file_exists($file))
		{
			//We reads the record from the cache file instead of performing a query
			$this->_records = unserialize(file_get_contents($file));
			$this->_fetched = true;

		} else {

			$stage = strpos($file,'/'.$this->stage_prefix);

			if($stage === FALSE)
			{
				//Fetch from the production
				$this->_fetch();
			} else {
				//Fetch from the stage
				$this->_fetch(FALSE);
			}
			write_file($file, serialize($this->_records));
		}
		//And then, we create the tree
		return $this->get();
	}

	/**
	 * Returns the tree, using the setted conditions
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
	 * Returns a leaf (branch) of the main tree, starting from the given id
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
	 * Returns the tree starting from the current page
	 * @return array
	 */
	public function get_current_branch()
	{
		//Prevent to be called twice during the same request
		if (!$this->_current_branch)
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
			$this->_current_branch = $this->get_default_branch($id);
		}
		return $this->_current_branch;
	}


	/**
	 * Builds a single tree, given the pages
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
			//We build the base path if we are not starting from the root
			//Is useful when a child page requests a menu branch
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
	 * Internal recursive function that generates the trees
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
		$open = in_array($page->uri, $this->_uri_segments) ? TRUE : FALSE;
		$arr['sons'][$id] = array(
			'title' => $page->title,
			'link' => $link,
			'open'	=> $open,
			'selected'	=> $this->current_page_uri == $page->uri ? TRUE : FALSE,
			'show_in_menu'	=> isset($page->show_in_menu) ? $page->show_in_menu : 'F'
		);
		if ($open)
		{
			$this->breadcrumbs[$id] = array(
				'title' => $page->title,
				'link'	=> $link
			);
		}
		if(isset($sons[$id]))
		{
			foreach($sons[$id] as $son)
			{
				$this->_treemap($son, $nodes, $sons, $link, $arr['sons'][$id]);
			}
		}
	}
	

	/**
	 * Deletes a tree cached file, given the type (when not given, it deletes the default one)
	 * @param int|string $tipo
	 */
	public function clear_cache($tipo='')
	{
		$paths = $this->get_type_cachepaths($tipo);

		foreach ($paths as $path)
		{
			if (file_exists($path))
			{
				unlink($path);
			}
		}
	}


}