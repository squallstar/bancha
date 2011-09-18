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
	 * @var string the table we use for relations between records and hierarchies
	 */
	public $table_relations = 'record_hierarchies';

	/**
	 * @var array|bool contains the hierarchies
	 */
	public $list = FALSE;
	
	/**
	 * @var array The current setted relations
	 */
	private $_current_relations = array();

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
			if (CACHE) $this->db->cache_on();
 			$this->list = $this->db->select('id_hierarchy, id_parent, name')
				   		   		   ->from($this->table)->get()->result();
 			if (CACHE) $this->db->cache_off();
		}
		return $this->list;
  	}
  	
  	public function set_active_nodes($relations)
  	{
  		$this->_current_relations = $relations;
  	}
  	
  	public function get_record_hierarchies($record_id)
  	{
  		$result = $this->db->select('id_hierarchy')
  						   ->from($this->table_relations)
  						   ->where('id_record', $record_id)
  						   ->get()->result_array();
  		$hierarchies = array();
  		if (count($result))
  		{
  			foreach ($result as $hierarchy)
  			{
  				$hierarchies[]= $hierarchy['id_hierarchy'];
  			}
  		}
  		return $hierarchies;
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
  		$done = $this->db->insert($this->table, $data);
  		if (CACHE) $this->db->cache_delete_all();
  		return $done;
  	}

  	/**
  	 * Delete one or more hierarchies
  	 * @param int|array $hierarchy The hyerarchy, or the hierarchies to delete
  	 * @return bool success
  	 */
  	public function delete($hierarchy)
  	{
  		if (is_array($hierarchy))
  		{
  			$this->db->where_in('id_hierarchy', $hierarchy);
  			$this->db->or_where_in('id_parent', $hierarchy);
  		} else if (is_numeric($hierarchy))
  		{
  			$this->db->where('id_hierarchy', $hierarchy);
  			$this->db->or_where('id_parent', $hierarchy);
  		}
  		$this->db->delete($this->table);
  		
  		//And the relations
  		if (is_array($hierarchy))
  		{
  			$this->db->where_in('id_hierarchy', $hierarchy);
  		} else if (is_numeric($hierarchy))
  		{
  			$this->db->where('id_hierarchy', $hierarchy);
  		}
  		$this->db->delete($this->table_relations);
  		
  		if (CACHE) $this->db->cache_delete_all();
  		return true;
  	}

  	/**
	 * Updates all the hierarchies of a record
	 * @param int $id_record
	 * @param array $new_hierarchies
	 */
  	public function update_record_hierarchies($id_record, $new_hierarchies)
  	{
  		//First of all, let's delete the current hierarchies
  		$this->db->where('id_record', $id_record)->delete($this->table_relations);

  		//And now, let's add the hierarchies
  		if ($new_hierarchies != '' && count($new_hierarchies))
  		{
  			foreach ($new_hierarchies as $hierarchy)
  			{
  				$this->db->insert($this->table_relations, array(
  					'id_record'		=> $id_record,
  					'id_hierarchy'	=> $hierarchy
	  			));
  			}
  		}
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
			'select'		=> in_array($hierarchy->id_hierarchy, $this->_current_relations) ? TRUE : FALSE
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
}