<?php
/**
 * Hierarchies Model Class
 *
 * Model class to work with the Hierarchies on the DB
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_hierarchies extends CI_Model {

	/**
	 * @var string the table we are using
	 */
	public $table = 'hierarchies';

	/**
	 * @var array|bool contains the hierarchies
	 */
	public $list = FALSE;

 	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Returns the available hierarchies
	 * @return array
	 */
	public function get()
 	{
		if (!is_array($this->list))
		{
 			$this->list = $this->db->select('id_hierarchy, id_parent, name')
				   		   		   ->from($this->table)->get()->result();
		}
		return $this->list;
  	}

  	/**
  	 * Returns the hierarchies linear dropdown
  	 * @return array
  	 */
  	public function dropdown()
  	{
  		$this->get();
		$dropdown = array();
		foreach ($this->list as $hierarchy)
		{
			$dropdown[$hierarchy->id_hierarchy] = $hierarchy->name;
		}
		return $dropdown;
  	}

  	/**
  	 * Adds a hierarchy into the database
  	 * @param string $name
  	 * @param int $id_parent
  	 * @return bool success
  	 */
  	public function add($name = '', $id_parent = '')
  	{
  		if ($name == '')
  		{
  			show_error(_('You cannot create a hierarchy without a name.'));
  		}
  		$data = array(
  			'name'	=> $name
  		);
  		if ($id_parent != '')
  		{
  			$data['id_parent'] = $id_parent;
  		}
  		return $this->db->insert($this->table, $data);
  	}

	/**
	 * Recursive function to create a tree of the hierarchies
	 * @param int $id
	 * @param array $nodes
	 * @param array $sons
	 * @param string $link
	 * @param string $arr
	 */
	private function _hierarchymap($id, &$nodes, &$sons, $link='/', &$arr)
	{
		$hierarchy = $nodes[$id]['value'];

		$tmp = array(
			'title' 		=> $hierarchy->name,
			'id_parent' 	=> $hierarchy->id_parent ? $hierarchy->id_parent : '',
			'key'			=> $hierarchy->id_hierarchy,
			'select'		=> FALSE
		);
		$arr['children'][] = & $tmp;

		if(isset($sons[$id]))
		{
			foreach($sons[$id] as $son)
			{
				$this->_hierarchymap($son, $nodes, $sons, $link, $tmp);
			}
		}
	}

	/**
	 * Returns the tree of hierarchies
	 * @return array
	 */
	public function get_tree()
	{
		$this->get();

		$sons = array();
		$nodes = array();
		$root = array();

		foreach($this->list as $hierarchy)
		{
			if(!$hierarchy->id_parent || $hierarchy->id_parent === null)
			{
				$root[] = $hierarchy->id_hierarchy;
			}else{
				$sons[$hierarchy->id_parent][] = $hierarchy->id_hierarchy;
			}
			$nodes[$hierarchy->id_hierarchy] = array('id_parent' => $hierarchy->id_parent, 'value' => $hierarchy);
		}
		$tree = array();

		foreach ($root as $r)
		{
			$this->_hierarchymap($r, $nodes, $sons, '', $tree);
		}
		if (isset($tree['children']))
		{
			$tree = $tree['children'];
		}
		return $tree;
	}

	/**
	 * Converts a GET hierarchies string to an array
	 * @param string $get_string
	 * @return array
	 */
	public function parse_data($get_string)
	{
		$selected_hierarchies = array();
		$data = explode('&', str_replace('hierarchies=&', '', $get_string));
		foreach ($data as $item)
		{
			$selected_hierarchies[] = str_replace('selNodes=', '', $item);
		}
		return $selected_hierarchies;
	}
}