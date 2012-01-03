<?php
/**
 * Categories Model Class
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_categories extends CI_Model
{
	/**
	* Sets a where clause on the content type
	* @param int|string $tipo
	*/
	public function type($tipo = '')
	{
		if (!is_numeric($tipo))
		{
			$tipo = $this->content->type_id($tipo);
		}
		$this->db->where('id_type', (int)$tipo);
		return $this;
	}

	/**
	* Gets the categories of a single record
	* @param int $record_id
	* @return array
	*/
	public function get_record_categories($record_id = '')
	{
		$record_categories = array();
		if ($record_id != '')
		{
	  		$categories = $this->db->select('id_category')
	  						  	   ->from('record_categories')
	  						   	   ->where('id_record', $record_id)
	  						       ->get()->result_array();

	  		if (count($categories))
	  		{
	  			foreach ($categories as $category)
	  			{
	  				$record_categories[] = $category['id_category'];
	  			}
	  		}
		}
		return $record_categories;
	}

	/**
	* Executes the search, using the pre-setted filters
	* @return array
	*/
	public function get()
	{
		if (CACHE) $this->db->cache_on();
		$res = $this->db->select('id_category AS id, category_name AS name')
		   		    ->from('categories')->get();
		if (CACHE) $this->db->cache_off();
		return $res->result();
	}

	/**
	* Adds a category to a content type
	* @param int $type_id
	* @param string $name
	* @return bool
	*/
	public function add($type_id, $name)
	{
		$data = array(
			'id_type'	=> $type_id,
			'category_name'	=> $name
		);
		if ($this->db->insert('categories', $data))
		{
			$done = $this->db->insert_id();
			if (CACHE) $this->db->cache_delete_all();
			return $done;
		} else {
			return FALSE;
		}
	}

	/**
	* Checks whether a category exists or not
	* @param int $type_id
	* @param string $name
	* @return bool
	*/
	public function exists($type_id='', $name='')
	{
		if ($type_id != '' && $name != '')
		{
  		$res = $this->db->select('COUNT(id_category) AS total')
  						->from('categories')
  						->where('id_type', $type_id)
  						->where('category_name', $name)
  						->limit(1)->get()->row(0);

  		return $res->total > 0 ? TRUE : FALSE;
		}
		return TRUE;
	}

	/**
	* Deletes a category, given the id
	* @param int $cat_id
	* @return bool
	*/
	public function delete_by_id($cat_id='')
	{
		if ($cat_id != '')
		{
			$done = $this->db->where('id_category', $cat_id)->delete('categories');
			if (CACHE) $this->db->cache_delete_all();
			return $done;
		}
		return FALSE;
	}

	/**
	* Deletes all the associations between a record and its categories
	* @param int $record_id
	* @return bool
	*/
	public function delete_record_categories($record_id = '')
	{
		if ($record_id != '')
		{
			return $this->db->where('id_record', $record_id)->delete('record_categories');
		}
	}

	/**
	* Updates the associations between a record and its categories
	* @param int $record_id
	* @param array $new_categories
	* @return bool
	*/
	public function set_record_categories($record_id = '', $new_categories = array())
	{
		if ($record_id != '')
		{
			$this->delete_record_categories($record_id);
			if (count($new_categories) && is_array($new_categories))
			{
				foreach ($new_categories as $category)
				{
					$data = array(
						'id_record'		=> $record_id,
						'id_category'	=> $category
					);
					$this->db->insert('record_categories', $data);
				}
				return TRUE;
			}
		}
	}

	/**
	* Sets a "where_in" condition, based on the category name
	* @param array $categories
	* @return $this
	*/
	public function name_in($categories)
	{
		$cleaned = array();
		foreach ($categories as $name)
		{
			$cleaned[] = trim($name);
		}
		$this->db->where_in('category_name', $cleaned);
		return $this;
	}

	/**
	* Sets a "where in" clause on the category id
	* @param array $categories
	* @return $this
	*/
	public function category_id_in($ids)
	{
		$this->db->where_in('id_category', $ids);
		return $this;
	}

  	/**
   	* Extracts the categories id
   	* @return array
   	*/
  	public function get_ids()
  	{
		$res = $this->db->select('id_category')
			   		    ->from('categories')->get();
		$result = $res->result_array();
		$ids = array();
		foreach ($result as $category)
		{
			$ids[] = (int)$category['id_category'];
		}
		return $ids;
  	}

  	/**
   	* Returns the records ids of the passed categories, or the sql query (see the second param)
   	* @param array $array cateogories id
   	* @param bool $sql_string
   	* @return array|string
   	*/
  	public function get_records_for_categories($array, $sql_string = FALSE)
  	{
  		if (!count($array)) return array();

	  	$this->db->select('id_record')
	    		 ->from('record_categories')
				 ->where_in('id_category', $array);

		if ($sql_string)
		{
			return $this->db->get_sql();
		} else {
			$res = $this->db->get();
		}

		$result = $res->result_array();
		$ids = array();
		foreach ($result as $record)
		{
			$ids[] = (int)$record['id_record'];
		}
		return $ids;
  	}
}