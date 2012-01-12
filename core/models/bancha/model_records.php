<?php
/**
 * Records Model Class
 *
 * A class to work with the records
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_records extends CI_Model {

	/**
	 * @var bool Defines if we searched a simple or structured type
	 */
  	public $last_search_has_tree = FALSE;

  	/**
  	* @var bool Sets if we have to extract documents during the next search
  	*/
  	private $_get_documents = FALSE;

  	/**
  	* @var bool Defines whether a search is a list or a detail
  	*/
 	private $_is_list = FALSE;

 	/**
  	* @var bool Defines whether an admin search is a list or a detail
  	*/
 	private $_is_adminlist = FALSE;

 	/**
 	* @var bool|array Il tipo su cui si sta effettuando la ricerca
 	*/
  	private $_single_type = FALSE;

  	/**
  	* @var bool Are we in stage?
  	*/
  	private $_is_stage = FALSE;

  	/**
  	* @var string Production table
  	*/
  	public $table;

  	/**
  	* @var string Stage table
  	*/
 	public $table_stage;

 	/**
 	* @var string The table we are currently working on
 	*/
 	public $table_current;

 	/**
 	* @var string Primary key of the current type
 	*/
  	public $primary_key;

  	/**
  	* @var array Columns to extract during the select queries
  	*/
  	public $columns;

  	public function __construct()
  	{
    	parent::__construct();

    	//We set the default type to be "records"
    	$this->set_type();
  	}

  	public function set_stage($bool)
  	{
  		//We set the current table
   	 	$this->table_current = $bool ? $this->table_stage : $this->table;
   	 	$this->_is_stage = $bool;
  	}

  	/**
   	* Defines is we have to extract only the "list" fields or also the "details" ones
   	* @param bool $extract
   	*/
  	public function set_list($extract=TRUE)
  	{
  		$this->_is_list = $extract || strtolower($extract) === 'true' ? TRUE : FALSE;
  		return $this;
  	}

  	/**
   	* In the administration, defines is we have to extract only the "list" fields or also the "details" ones
   	* @param bool $extract
   	*/
  	public function set_adminlist($extract=TRUE)
  	{
  		$this->_is_adminlist = $extract;
  		return $this;
  	}

  	/**
   	* Sets a where filter on the content type
   	* @param int|string|array $type
   	*/
  	public function type($type='')
  	{
  		if (is_array($type))
  		{
  			$tipi_id = array();
  			$tipi = array();
  			foreach ($type as $tipo) {
  				$tmp = $this->content->type($tipo);
  				$tipi_id[] = $tmp['id'];
  				$tipi[] = $tmp;
  			}
 
  			if ($tipi[0]['tree'])
	        {
	        	$this->last_search_has_tree = TRUE;
	        }else{
	       		$this->last_search_has_tree = FALSE;
	        }
	        $this->db->where_in('id_type', $tipi_id);
  		}
    	else if ($type != '')
    	{
    		$this->_single_type = FALSE;
	    	$this->set_type($type);
	    	if (!$this->_single_type)
	    	{
	    		//Type not exists
	    		return $this;
	    	}
	      	$tipo = $this->_single_type;

	        //Imposto tutti i riferimenti
	        $this->table = $tipo['table'];
	        $this->table_stage = $tipo['stage'] ? $tipo['table_stage'] : $tipo['table'];
	        $this->primary_key = $tipo['primary_key'];

	        $this->db->where($this->table_current.'.id_type', $tipo['id']);

	        if ($tipo['tree'])
	        {
	        	$this->last_search_has_tree = TRUE;
	        }else{
	       		$this->last_search_has_tree = FALSE;
	        }
	      	$this->_single_type = $tipo;
    	}
    	return $this;
  	}

 	/**
   * We set the type for the current extractions
	 * @param int|string|array $type
   */
	public function set_type($type='')
 	{
		if ($type != '')
		{
			if (is_array($type))
			{
				$tipo = $type;
			} else {
				$tipo = $this->content->type($type);
				if (!$tipo)
				{
					//Type not exists
					return $this;
				}
			}

			$this->_single_type = $tipo;

			//We set the references
		    $this->table = $tipo['table'];
		    $this->table_stage = $tipo['table_stage'];
		    $this->primary_key = $tipo['primary_key'];
		    $this->columns = $tipo['columns'];
		} else {
			$this->table = 'records';
			$this->table_stage = 'records_stage';
			$this->primary_key = 'id_record';
		   $this->columns = $this->config->item('record_columns');
		}
		$this->table_current = $this->_is_stage ? $this->table_stage : $this->table;
		return $this;
	}


	/**
   * Imposta un filtro sulla lingua se il tipo lo prevede
	 * @param string $language se non passato, utilizza la lingua corrente
	 */
	public function language($language = '')
 	{
		if (isset($this->_single_type['fields']['lang']))
 		{
			$this->db->where('lang', $language != '' ? $language : $this->lang->current_language);
  		}
  		return $this;
	}

	 /**
   	* Imposta se prendere anche i documenti dei record durante le estrazioni
   	* @param bool $extract
   	*/
  	public function documents($extract = TRUE)
  	{
    	$this->_get_documents = $extract;
    	return $this;
  	}

  	/**
  	 * Sets an "AND WHERE" condition
  	 * @param string $field
  	 * @param int|string $value
  	 */
  	public function where($field='', $value=null)
  	{
  		if ($value == null)
  		{
  			$this->db->where($field);
  		} else if ($field != '')
  		{
  			if ($field == 'id' || $field == $this->primary_key)
  			{
  				$this->db->where($this->table_current.'.'.$this->primary_key, $value);
  				$this->db->limit(1);
  			}
  			else if ($field == 'id_type' || $field == 'type' || $field == 'id_tipo' || $field == 'tipo')
  			{
				  $this->type($value);
  			}
  			else if (is_array($this->columns) && in_array($field, $this->columns))
  			{
				  $this->db->where($this->table_current.'.'.$field, $value);
  			} else {
				  //Xml search by tag content
				  $this->db->like($this->table_current.'.xml', '%<'.$field.'>'.CDATA_START.$value.CDATA_END.'</'.$field.'>%');
  			}
    	}
    	return $this;
  	}

    /**
     * Sets an "OR WHERE" condition
     * @param string $field
     * @param int|string $value
     */
    public function or_where($field='', $value=null)
    {
      if ($value == null)
      {
        $this->db->or_where($field);
      } else if ($field != '')
      {
        if ($field == 'id' || $field == $this->primary_key)
        {
          $this->db->or_where($this->table_current.'.'.$this->primary_key, $value);
          $this->db->limit(1);
        }
        else if ($field == 'id_type' || $field == 'type' || $field == 'id_tipo' || $field == 'tipo')
        {
          $this->type($value);
        }
        else if (is_array($this->columns) && in_array($field, $this->columns))
        {
          $this->db->or_where($this->table_current.'.'.$field, $value);
        } else {
          //Xml search by tag content
          $this->db->or_like($this->table_current.'.xml', '%<'.$field.'>'.CDATA_START.$value.CDATA_END.'</'.$field.'>%');
        }
      }
      return $this;
    }

  	/**
   	* Sets a "where in" condition for the primary key
   	* @param array $record_ids
   	* @param bool $escape Whether the first parameter need to be escaped
   	*/
  	public function id_in($record_ids, $escape = TRUE)
  	{
    	$this->db->where_in($this->primary_key, $record_ids, $escape);
    	return $this;
  	}

  	/**
   	* Sets a "where not in" condition for the primary key
   	* @param array $record_ids
   	* @param bool $escape Whether the first parameter need to be escaped
   	*/
  	public function id_not_in($record_ids, $escape = TRUE)
  	{
    	$this->db->where_not_in($this->primary_key, $record_ids, $escape);
    	return $this;
  	}

  	/**
  	 * Sets a JOIN query on the pages tables and adds a search condition on the full_uri
  	 * @param string $string
  	 */
  	public function full_uri($string)
  	{
  		$this->db->join($this->pages->table_current,
  		$this->pages->table_current.'.id_record = '.$this->table_current.'.'.$this->primary_key,
  					'inner')->where('full_uri', $string);
  		return $this;
  	}

  	/**
  	 * Sets a like search condition
  	 * @param string $field
  	 * @param int|string $value
  	 */
  	public function like($field='', $value='')
  	{
  		if ($field != '')
  		{
  			if (in_array($field, $this->_single_type['columns']))
  			{
  				$this->db->like($field, $value);
  			} else {
  				//Xml search by tag content
  				$this->db->like($this->table_current.'.xml', '<'.$field.'>'.CDATA_START.'%'.$value.'%'.CDATA_END.'</'.$field.'>');
  			}
  		}
  		return $this;
  	}

  	/**
  	 * Sets an or-like search condition
  	 * @param string $field
  	 * @param int|string $value
  	 */
  	public function or_like($field='', $value='')
  	{
  		if ($field != '')
  		{
  			if (in_array($field, $this->_single_type['columns']))
  			{
  				$this->db->or_like($field, $value);
  			} else {
  				//Xml search by tag content
  				$this->db->or_like($this->table_current.'.xml', '<'.$field.'>'.CDATA_START.'%'.$value.'%'.CDATA_END.'</'.$field.'>');
  			}
  		}
  		return $this;
  	}

  	/**
  	 * Sets a sql limit
  	 * @param start $a start
  	 * @param string $b howmany
  	 */
  	public function limit($a, $b=0)
  	{
  		if ($a > 0)
		{
			$this->db->limit($a, $b);
		}
  		return $this;
  	}

  	/**
  	 * Sets the results order
  	 * @param string $a field name
  	 * @param string $b ASC|DESC
  	 */
  	public function order_by($a, $b=null)
  	{
		$this->db->order_by($a, $b);
		return $this;
  	}

  	/**
  	 * Counts the record instead of extracting them
  	 * @return int
  	 */
  	public function count()
  	{
		$query = $this->db->select('COUNT('.$this->db->dbprefix.$this->table_current.'.'.$this->primary_key.') as total')
  						  ->from($this->table_current)
  						  ->get()
  						  ->result();
  		$row = $query[0];
  		return (int)$row->total;
  	}

  	/**
  	 * Filter the results by published or staged records
  	 * @param bool $published
  	 */
  	public function published($published = TRUE)
  	{
  		$this->db->where($this->table_current.'.published', $bool ? 1 : 0);
  		return $this;
  	}

  	/**
  	 * Gets the records
  	 * @param int $id optional parameter to get just a record instead the array
  	 */
  	public function get($id='')
  	{
  		$stage = $this->content->is_stage;
  		$this->set_stage($stage);

  		$fields_to_select = array();
  		$record_columns = $this->columns;

  		//We check if we're searching for a list (not detail), and just for one type
  		if (($this->_is_list || $this->_is_adminlist) && $this->_single_type)
  		{
  			foreach ($record_columns as $single_field)
  			{
  				//If we are in list mode, we check if we have to extract this field (only physical columns)
  				if (isset($this->_single_type['fields'][$single_field]))
  				{
  					if ($this->_single_type['fields'][$single_field]['list'] === TRUE
  					    || ($this->_single_type['fields'][$single_field]['admin'] === TRUE && $this->_is_adminlist)
  					
  					)
  					{
  						$fields_to_select[] = $single_field;
  					}
  				} else {
  					$fields_to_select[] = $single_field;
  				}
  			}
  		} else {
  			//Standard SELECT extraction
  			$fields_to_select = $record_columns;
  		}

  		//Columns not available in the production table
  		if ($this->table == $this->table_current)
  		{
  			$not_selectable = $this->config->item('record_not_live_columns');
  			$fields_to_select = array_diff($fields_to_select, $not_selectable);
  		}

  		if (is_numeric($id))
  		{
  			//Single record
  			$this->db->where($this->table_current.'.'.$this->primary_key, $id);
  			$this->db->limit(1);
  		}

  		//Additional fields for the tree type
  		if ($this->last_search_has_tree)
  		{
  			$this->db->select(
  				  $this->table_current . '.'
  				. implode(', '.$this->table_current.'.', $this->config->item('record_select_tree_fields'))
  			);
  		}

  		$query = $this->db->select(
  				$this->table_current.'.'.implode(', '.$this->table_current.'.', $fields_to_select)
  			)->from($this->table_current)->get();

  		if ($query->num_rows())
  		{
  			$results = $query->result();
  			$records = array();
            $record_ids = array();
  			foreach ($results as $item) {

  				if (!isset($item->id_type) || !$item->id_type)
  				{
  					$tipo = $this->_single_type;
  					$record = new Record();
  					$record->set_type($tipo);
  					$type_name = $tipo['name'];
  				} else {
  					$record = $this->content->make_record($item->id_type);
  					$tipo = $this->content->type($item->id_type);
  					$type_name = $this->content->type_name($item->id_type);
  				}

  				if ($record instanceof Record) {

  					$record->id = $item->{$tipo['primary_key']};
                    $record_ids[] = $record->id;
  					$record->tipo = $type_name;
  					$record->xml = $item->xml;

  					foreach ($fields_to_select as $column)
  					{
  						if ($item->$column)
  						{
  							if (isset($tipo['fields'][$column]['type']))
  							{
  								if ($tipo['fields'][$column]['type'] == 'date')
  								{
  									//We convert the date fields into timestamps
  									$record->set('_'.$column, $item->$column);
  									$item->$column = date(LOCAL_DATE_FORMAT, $item->$column);
  								} else if ($tipo['fields'][$column]['type'] == 'datetime')
  								{
  									if ($item->$column)
  									{
  										$record->set('_'.$column, $item->$column);
  										$item->$column = date(LOCAL_DATE_FORMAT . ' H:i', $item->$column);
  									}
  								}
  								else if (in_array($tipo['fields'][$column]['type'], config_item('array_field_types')))
  								{
  									$item->$column = explode('||', trim($item->$column, '|'));
  								}
  							}
  							$record->set($column, $item->$column);
  						}
  					}

  					if ($this->last_search_has_tree) {
  						foreach ($this->config->item('record_select_tree_fields') as $field_name)
  						{
  							$record->set($field_name, $item->$field_name);
  						}
  					}
  					$record->build_data();
  				} else {
  					show_error(_('Cannot build the record.').' (records/get)');
  				}
  				$records[] = $record;

  			}

            //We extract all the attachments, using the record IDS (single query = WOW!)
            if ($this->_get_documents && count($record_ids))
            {
                $this->load->documents();
                $docs = array();
                $this->db->flush_cache();
                $all_attachs = $this->documents->table($tipo['table'])->where_in('bind_id', $record_ids)->get();
                if (count($all_attachs))
                {
                    foreach ($all_attachs as $attachment)
                    {
                        $docs[$attachment->bind_id][$attachment->bind_field][] = $attachment;
                    }
                    foreach ($records as & $record)
                    {
                        if (isset($docs[$record->id]))
                        {
                            $attachments = $docs[$record->id];
                            foreach ($attachments as $field_name => $attachs)
                            {
                                $record->set($field_name, $attachs);   
                            }
                        }
                        $record->documents_extracted = TRUE;
                    }
                }
            }

  			//Reset the switches
  			$this->last_search_has_tree = FALSE;
  			$this->_get_documents = FALSE;

  			if (is_numeric($id))
  			{
  				return $records[0];
  			} else {
  				return $records;
  			}
  		} else {
  			return array();
  		}
  	}

	/**
   	* Insert or updates a Record into DB
   	* @param Record $record
   	* @return BOOL
   	*/
	public function save($record)
  	{
	    if ($record instanceof Record)
	    {
			//We build the record xml
	        $record->build_xml();

	        $id = $record->id;

	      	//If type is set, let's take it!
	      	if (!$record->_tipo_def)
	      	{
	      		$this->set_type($record->_tipo);
	      		$tipo = $this->content->type($record->_tipo);
	      	} else {
	      		$this->set_type($record->_tipo_def);
	      		$tipo = $record->_tipo_def;
	      	}

	      	//These columns are always populated
	      	$data = array(
	          'id_type'      => $record->_tipo,
	          'xml'          => $record->xml,
	          'date_update'  => time()
	        );

	      	//And we add the physical columns
	      	foreach ($tipo['columns'] as $column)
	      	{
	      		if (!isset($data[$column]))
	      		{
	      			$data[$column] = $record->get($column);
	      			if (is_array($data[$column]))
	      			{
	      				$data[$column] = '|'.implode('||', $data[$column]).'|';
	      			}
	      		}
	      	}

	        if (isset($tipo['fields']['uri']))
	        {
		        $uri = $record->get('uri');
		      	if (strlen($uri) < 1) {
		        	$uri = strtolower($record->get('title'));
		      	}
	        	$data['uri'] = $this->get_safe_uri(trim($uri));
	        }

	        //We ensure that a language will be always set if the content type supports it
	        if (isset($tipo['fields']['lang']) && (!isset($data['lang']) || !$data['lang']))
	        {
	        	$data['lang'] = $this->lang->current_language;
	        }

	        //This type has a parent field?
	        if (isset($tipo['fields']['id_parent']))
	        {
		        $parent = $record->get('id_parent');
		        if ($parent || $parent === '') {
		          if ($parent === '') {
		          	//Hard column reset on the database
		            $data['id_parent'] = null;
		          } else {
		            $data['id_parent'] = $parent;
		          }
		        }
	        }

	    	//We set the record as not published if the type has the stage table
        	if ($tipo['stage'])
        	{
        		switch ($record->get('published'))
        		{
        			case 1:
        			case 2:
        				//2 means that the record is different between the environments
        				$data['published'] = '2';
        				break;

        			default:
        				//0 means that the record exists just in stage
        				$data['published'] = '0';
        				break;
        		}
        	}

		  	$done = FALSE;

		  	//We choose an action for the triggers
			$action = $id ? 'update' : 'insert';

			//Title fix
			if (!isset($data['title']) && isset($tipo['fields']['title']))
			{
				$data['title'] = $record->get($tipo['edit_link']);
			}

			if (!isset($this->events))
			{
				$this->load->events();
			}

	      	if ($id)
	      	{
		        //The primary key will be used as update where clause
	         	unset($data[$tipo['primary_key']]);

		      	//Update query
	          	if ($this->db->where($tipo['primary_key'], $id)
	               			 ->update($this->table_stage, $data))
	          	{
	            	$done = $id;
                    if (isset($data[$tipo['edit_link']]))
                    {
	            	  $this->events->log('update', $id, $data[$tipo['edit_link']], $data['id_type']);
                    }
	          	} else {
		            show_error('Cannot update the record ['.$id.'].', 500);
	          	}

	      	} else {
	        	//Insert
	        	if (isset($tipo['fields']['date_insert']))
                {
	          		$data['date_insert'] = time();
	          	}

	        	unset($data[$tipo['primary_key']]);

	          	if ($this->db->insert($this->table_stage, $data))
	          	{
		            $done = $this->db->insert_id();
                    if (isset($data[$tipo['edit_link']]))
                    {
    	            	  $this->events->log('insert', $done, $data[$tipo['edit_link']], $data['id_type']);
                    }
	          	} else {
	          		show_error('Cannot create a new record of type ['.$data['id_type'].'].', 500);
	          	}
	      	}

	      	if ($done)
	      	{
	      		if ($tipo['tree'])
	      		{
	      			$data[$tipo['primary_key']] = $done;
	      			//If this type is a page, let's update all the references
	      			$this->pages->set_stage(TRUE)->save($data);
	      		}

				//Triggers
		  		if (isset($tipo['triggers']) && count($tipo['triggers']))
		  		{
		  			$this->load->triggers();
		  			$this->triggers->delegate($record)
		  						   ->operation($action)
		  						   ->add($tipo['triggers'][$action])
		  						   ->fire();
		  		}
	      	}
	      	return $done;
	  	} else {
	    	show_error('Cannot save a non Record object.', 500);
	  	}
	}

	/**
  	* Deletes a record
  	* @param int $record_id
  	* @return bool
  	*/
 	public function delete_by_id($record_id, $type = '')
 	{
  		if ($type != '')
  		{
  			$this->set_type($type);
  		}

		//First of all, we get the record
		$record = $this->get($record_id);

    	$done = $this->db->where($this->primary_key, $record_id)
       		             ->delete($this->table);

    	$done_stage = $this->db->where($this->primary_key, $record_id)
        			           ->delete($this->table_stage);

    	if ($done && $done_stage && $record)
    	{
      		//We delete the attachments from both tables
     		$this->load->documents();
      		$this->documents->delete_by_binds($this->table, $record_id, FALSE);
     		$this->documents->delete_by_binds($this->table_stage, $record_id, TRUE);

     		$tipo = $this->content->type($record->_tipo);

	  		if (isset($tipo['triggers']['delete']) && count($tipo['triggers']['delete']))
	  		{
	  			$triggers = $tipo['triggers']['delete'];

	  			$this->load->triggers();
				$this->triggers->delegate($record)
							   ->operation('delete');

				//Fires the triggers
				$this->triggers->set_stage(FALSE)->add($triggers)->fire();
				$this->triggers->set_stage(TRUE)->add($triggers)->fire();
	  		}
      		return true;
    	}
    	return false;
  	}

  /**
   * Gets a safe web uri
   * @param string $uri
   * @return string
   */
  public function get_safe_uri($uri)
  {
    return substr(url_title(convert_accented_characters($uri)), 0, 127);
  }

  /**
   * Checks whether a URI is used, and returns the id of that record
   * @param string $uri
   * @return bool|int
   */
  public function uri_is_used($uri='')
  {
    if ($uri != '')
    {
    	$result = $this->where('uri', $uri)->get();

	    if (count($result) > 0)
	    {
	      return $result[0];
	    }
    }
    return FALSE;
  }

  /**
   * Checks if a record is published given the id
   * @param int $id
   * @return bool
   */
  public function id_is_published($id='')
  {
  		if ($id != '')
  		{
  			$result = $this->db->where($this->primary_key, $id)
  							   ->from($this->table)
  							   ->limit(1)
  							   ->select($this->primary_key)
  							   ->get();
  			if ($result->num_rows())
  			{
  				return TRUE;
  			}
  		}
  		return FALSE;
  }

	/**
	* Publishes a record and its attachments
	* @param int $id Record id
	* @param string $type (optional) The content type
	*/
	public function publish($id = '', $type = '')
 	{
		$this->set_type($type);

		if ($id == '')
		{
			show_error('ID not specified. (records/publish)');
		}

		$record = $this->db->from($this->table_stage)
						   ->where($this->primary_key, $id)
						   ->limit(1)
						   ->select('*')
						   ->get();

		if ($record->num_rows())
		{
			$record = $record->result_array();
			$stage_record = $record[0];

      if (!isset($this->events))
      {
        $this->load->events();
      }

      //This prevent a notice on non-physical columns
      if (!isset($stage_record[$this->_single_type['edit_link']]))
      {
        $pub_label = $stage_record[$this->primary_key];
      } else {
        $pub_label = $stage_record[$this->_single_type['edit_link']];
      }

			$this->events->log('publish', $stage_record[$this->primary_key], $pub_label, $stage_record['id_type']);

			//Publishing triggers
	  		if (isset($this->_single_type['triggers']['publish']))
	  		{
	  			$this->load->triggers();
	  			$this->triggers->delegate($this->get($stage_record[$this->primary_key]))
	  						   ->operation('publish')
	  						   ->add($this->_single_type['triggers']['publish'])
	  						   ->fire();
	  		}

			$published_record = $this->db->from($this->table)
										 ->where($this->primary_key, $id)
										 ->limit(1)
										 ->select($this->primary_key)
										 ->get();
			$done = FALSE;
			if ($published_record->num_rows())
			{
				//Update
				unset($stage_record[$this->primary_key]);
				unset($stage_record['published']);
				$done = $this->db->where($this->primary_key, $id)
								 ->update($this->table, $stage_record);
			} else {
				//Insert
				unset($stage_record['published']);
				$done = $this->db->insert($this->table, $stage_record);
			}
			if ($done)
			{
				//We update the attachments
				$this->load->documents();
				$this->documents->put_live_documents($this->table, $id);
				//And we update the state of the staged record
				return $this->db->where($this->primary_key, $id)
								->update($this->table_stage, array('published' => 1, 'date_publish' => $stage_record['date_publish']));
			}
		}
		return FALSE;
	}

	/**
	* Depublishes a record
	* @param $id Record id
	* @param $type (optional) The content type
	* @return bool
	*/
	public function depublish($id = '', $type = '')
	{
		$this->set_type($type);

		if ($id == '')
		{
			show_error('ID not specified. (records/depublish)');
		}
			$done = $this->db->where($this->primary_key, $id)
							 ->delete($this->table);
		if ($done)
		{
      if (!isset($this->events))
      {
        $this->load->events();
      }
			$this->events->log('depublish', $id, $id);

			//Depublishing triggers
	  		if (isset($this->_single_type['triggers']['depublish']))
	  		{
	  			$this->load->triggers();
	  			$this->triggers->delegate($this->get($id))
	  						   ->operation('publish')
	  						   ->add($this->_single_type['triggers']['depublish'])
	  						   ->fire();
	  		}

			//We delete the attachments
			$this->load->documents();
			$this->documents->delete_records_by_binds($this->table, $id, TRUE);

			//And we update the staged record status
			return $this->db->where($this->primary_key, $id)
							->update($this->table_stage, array('published' => 0));
		}
	}

  /**
   * Extracts the custom options of a content type
   * @param string (id) $field
   * @return array
   */
  public function get_field_options($field)
  {
  	if (isset($field['extract']))
  	{
  		$options = array();

  		switch ($field['extract'])
  		{
  			//Non-cached query
  			case 'query':
  				$sql = $field['options'];
  				if ($sql)
  				{
  					log_message('error', 'Query not valid on field '.$field['description']);
  					return array();
  				}
  				//Stage tables
  				$sql = str_replace(
  					array('FROM (`'.$this->db->dbprefix.$this->table.'`)',
  						  'FROM '.$this->db->dbprefix.$this->table)
  						  ,
  					array('FROM (`'.$this->db->dbprefix.$this->table_current.'`)',
  						  'FROM '.$this->db->dbprefix.$this->table_current)
  						  , $sql);
  				$result = $this->db->query($sql)->result();
  				if (count($result))
  				{
  					foreach ($result as $option)
  					{
  						$options[$option->value] = $option->name;
  					}
  				}
  				break;

  			//Framework internal references
  			case 'custom':
  				eval('$options = ' . $field['options'].';');
  				break;
  		}
  		return $options;
  	}
  	return $field['options'];
  }

  	/**
  	 * Gets the first record. This function is identical to the get function,
  	 * but returns just a single record instead the array of records
  	 * @return Record
  	 */
  	public function get_first() {
  		$this->limit(1);
  		$records = $this->get();
  		if (is_array($records) && count($records))
  		{
  			return $records[0];
  		}
  		return FALSE;
  	}

  	/**
  	* Discards a record and takes the published one from the production table
  	* @param int $record_id
  	* @param int|string $type
  	* @return bool success
  	*/
 	public function discard($record_id, $type = '')
 	{
 		$this->set_stage(FALSE);
 		if ($type != '')
 		{
 			$this->type($type);
 		}
 		$record = $this->get($record_id);

 		$this->set_stage(TRUE);
 		return $this->save($record);
 	}

}
