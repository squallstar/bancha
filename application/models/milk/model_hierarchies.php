<?php
/**
 * Hierarchies Model Class
 *
 * Model class to work with the Hierarchies on the DB
 *
 * @package		Milk
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
}