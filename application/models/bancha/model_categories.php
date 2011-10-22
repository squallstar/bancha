<?php
/**
 * Categories Model Class
 *
 * Classe per lavorare con le categorie dal db
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_categories extends CI_Model {

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Imposta una clausola sul tipo
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
   * Ottiene le categorie di un record
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
	  						   ->get()->result();

	  		if (count($categories))
	  		{
	  			foreach ($categories as $category) {
	  				$record_categories[] = $category->id_category;
	  			}
	  		}
  		}
  		return $record_categories;
  }

  /**
   * Esegue la ricerca con i filtri impostati
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
   * Aggiunge una categoria ad un tipo
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
  	$done = $this->db->insert('categories', $data);
  	if ($done)
  	{
  		$done = $this->db->insert_id();
  		if (CACHE) $this->db->cache_delete_all();
  		return $done;
  	} else {
  		return FALSE;
  	}
  }

  /**
   * Controlla se una categoria esiste
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
   * Elimina una categoria dato il suo id
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
   * Elimina tutte le categorie di un record
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
   * Reimposta le associazioni di un record con le nuove fornite
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
  					return $this->db->insert('record_categories', $data);
  				}
  			}
  		}
  }

  /**
   * Imposta una condizione WHERE IN sul nome della categoria
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
   * Imposta una condizione WHERE IN sull'id della categoria
   * @param array $categories
   * @return $this
   */
  public function category_id_in($ids)
  {
  		$this->db->where_in('id_category', $ids);
  		return $this;
  }

  /**
   * Estrae gli ID delle categorie e li ritorna
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
   	* Ottiene gli ID dei record appartenenti a una determinate categorie
   	* @param array $array id categorie
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