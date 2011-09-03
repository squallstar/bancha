<?php
/**
 * Records Model Class
 *
 * Classe per lavorare con i records del db
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_records extends CI_Model {

	/**
	 * @var bool Definisce se il tipo ricercato e' di tipo albero
	 */
  	public $last_search_has_tree = FALSE;

  	/**
  	* @var bool Imposta se estrarre i documenti dalla prossima ricerca
  	*/
  	private $_get_documents = FALSE;
  	
  	/**
  	* @var bool Definisce se e' una ricerca di tipo lista o dettaglio
  	*/
 	private $_is_list = FALSE;
 	
 	/**
 	* @var bool|array Il tipo su cui si sta effettuando la ricerca
 	*/
  	private $_single_type = FALSE;
  	
  	/**
  	* @var bool Definisce se siamo in stage
  	*/
  	private $_is_stage = FALSE;

  	/**
  	* @var string Tabella di produzione
  	*/
  	public $table;
  	
  	/**
  	* @var string Tabella di sviluppo
  	*/
 	public $table_stage;
  
 	/**
 	* @var string Tabella corrente per le operazioni
 	*/
 	public $table_current;
 	
 	/**
 	* @var string Chiave primaria delle tabelle correnti
 	*/
  	public $primary_key;
  	
  	/**
  	* @var array Colonne da estrarre nelle SELECT di questo tipo
  	*/
  	public $columns;

  	public function __construct()
  	{
    	parent::__construct();

    	//Imposto il tipo predefinito "records"
    	$this->set_type();
  	}

  	public function set_stage($bool)
  	{
  		//Imposto la tabella su cui fare query
   	 	$this->table_current = $bool ? $this->table_stage : $this->table;
   	 	$this->_is_stage = $bool;
  	}

  	/**
   	* Imposto se estrarre solo le colonne estraibili in modalità lista
   	* @param bool $extract
   	*/
  	public function set_list($extract=TRUE)
  	{
  		$this->_is_list = $extract;
  		return $this;
  	}

  	/**
   	* Imposta un filtro where sul tipo
   	* @param int|string $type
   	*/
  	public function type($type='')
  	{
    	if ($type != '')
    	{
	    	$this->set_type($type);
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
   * Imposto il tipo (tabella e pkey) su cui fare le operazioni
   * @param int|string $type
   */
  public function set_type($type='')
  {
  	if ($type != '')
	{
		$tipo = $this->content->type($type);
		$this->_single_type = $tipo;

		//Imposto tutti i riferimenti
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
   * Imposta un filtro where (anche sui campi xml del record)
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
	    else if (in_array($field, $this->columns))
	    {
	      $this->db->where($this->table_current.'.'.$field, $value);
	    } else{
	      //Xml search by tag content
	      $this->db->like($this->table_current.'.xml', '%<'.$field.'>'.CDATA_START.$value.CDATA_END.'</'.$field.'>%');
	    }
    }
    return $this;
  }

  	/**
   	* Imposta una condizione WHERE IN sull'id_record
   	* @param array $record_ids
   	*/
  	public function id_in($record_ids)
  	{
    	$this->db->where_in($this->primary_key, $record_ids);
    	return $this;
  	}

  /**
   * Imposta una JOIN con la tabella delle pagine ricercando l'URI richiesto
   * @param string $string
   * //TODO forse meglio spezzare e ricercare sulla tabella pages l'id e cercare qua senza join!
   */
  public function full_uri($string) {
	//$this->db->select('full_uri');
  	$this->db->join($this->pages->table_current,
  					$this->pages->table_current.'.id_record = '.$this->table_current.'.'.$this->primary_key,
  					'inner')
  			 ->where('full_uri', $string);
  	return $this;
  }

  /**
   * Imposta un filtro like (anche sui campi xml del record)
   * @param string $field
   * @param int|string $value
   */
  public function like($field='', $value='')
  {
      if ($field != '') {
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
   * Imposta un limite sui risultati
   * @param start $a start
   * @param string $b howmany
   */
  public function limit($a, $b=0)
  {
      $this->db->limit($a, $b);
      return $this;
  }

  /**
   * Imposta l'ordine dei risultati
   * @param string $a field name
   * @param string $b ASC|DESC
   */
  public function order_by($a, $b=null)
  {
    $this->db->order_by($a, $b);
    return $this;
  }

  /**
   * Conta i records anziché estrarli
   * @return int
   */
  public function count()
  {
      $query = $this->db->select('COUNT('.$this->db->dbprefix.$this->table_current.'.'.$this->primary_key.') as total')
                ->from($this->table_current)->get()->result();
      $row = $query[0];
      return (int)$row->total;
  }

  /**
   * Imposta se estrarre solo record pubblicati o depubblicati
   * @param bool $published
   */
  public function published($published = TRUE)
  {
      $this->db->where($this->table_current.'.published', $bool ? 1 : 0);
      return $this;
  }

  /**
   * Ottiene i record
   * @param int $id parametro opzionale per ricevere un singolo record
   * se impostato l'id, ritorna il record richiesto anzichè un array di records
   */
  public function get($id='')
  {
  	$stage = $this->content->is_stage;
  	$this->set_stage($stage);

  	$fields_to_select = array();

  	//$record_columns = $this->config->item('record_columns');
  	$record_columns = $this->columns;

  	//Controllo se sto cercando una lista di singoli tipi (non dettaglio!)
  	if ($this->_is_list && $this->_single_type)
  	{
  		foreach ($record_columns as $single_field)
  		{
			//Se è da estrarre in modalità lista e non è tra quelli da non estrarre
	 		if (isset($this->_single_type['fields'][$single_field]['list']))
	  		{
	  			if ($this->_single_type['fields'][$single_field]['list'] === TRUE
	  				//&& !in_array($single_field, $not_selectable)
	  				//TODO: da fixare o comunque controllare!
	  				)
		  			{
		  				$fields_to_select[] = $single_field;
		  			}
	  		} else {
	  			$fields_to_select[] = $single_field;
	  		}
  		}
  	} else {
  		//Estrazione standard
		$fields_to_select = $record_columns;


  	}

  	//Colonne non presenti nella tabella di produzione
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
      	$this->table_current.'.'.implode(', '.$this->table_current.'.', $this->config->item('record_select_tree_fields'))
      );
    }

    $query = $this->db->select($this->table_current.'.'.implode(', '.$this->table_current.'.', $fields_to_select))
                      ->from($this->table_current)
                      ->get();

    if ($query->num_rows())
    {
      $results = $query->result();
      $records = array();
      foreach ($results as $item) {

	        if ($item->id_type)
	        {

		    	$record = $this->content->make_record($item->id_type);

		    	$tipo = $this->content->type($item->id_type);

		        if ($record instanceof Record) {

			        $record->id = $item->{$tipo['primary_key']};
			        $record->tipo = $this->content->type_name($item->id_type);
			        $record->xml = $item->xml;

			        foreach ($fields_to_select as $column)
			        {
			        	if ($item->$column)
			        	{
			          		if (isset($tipo['fields'][$column]['type']))
			          		{
				        		if ($tipo['fields'][$column]['type'] == 'date')
				          		{
				          			//Converto la data in timestamp
				          			$record->set('_'.$column, $item->$column);
				          			$item->$column = date('d/m/Y', $item->$column);
				          		} else if ($tipo['fields'][$column]['type'] == 'datetime')
				          		{
				          			if ($item->$column)
				          			{
				          				$record->set('_'.$column, $item->$column);
				          				$item->$column = date('d/m/Y H:i', $item->$column);
				          			}
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

			        if ($this->_get_documents)
			        {
			        	$record->set_documents();
			        }

			    }else{
			    	show_error('Impossibile costruire il record. (records/get)');
			    }
		    $records[] = $record;
	     	}
      }

      //Reset the switchs
      $this->last_search_has_tree = FALSE;
      $this->_get_documents = FALSE;

      if (is_numeric($id))
      {
        return $records[0];
      }else{
        return $records;
      }

    }else{
      return array();
    }
  }

  /**
   * Aggiunge o salva un record su db
   * @param Record $record
   * @return BOOL
   */
  public function save($record)
  {
    if ($record instanceof Record)
    {

        //Costruisco l'XML del record
        $record->build_xml();

        $id = $record->id;

      	$this->set_type($record->_tipo);

      	$tipo = $this->content->type($record->_tipo);

      	//Colonne valorizzate sempre
      	$data = array(
          'id_type'		=> $record->_tipo,
          'xml'			=> $record->xml,
          //'title'		=> substr($record->get('title'), 0, 127),
          'date_update'	=> time()
        );

      	//Aggiungo le colonne fisiche
      	foreach ($tipo['columns'] as $column)
      	{
      		if (!isset($data[$column]))
      		{
      			$data[$column] = $record->get($column);
      			if (is_array($data[$column]))
      			{
      				$data[$column] = '<value>'.implode('</value><value>', $data[$column]).'</value>';
      			}
      		}
      	}

        //TODO: da migliorare questi pezzi

        $uri = '';
        if (isset($tipo['fields']['uri']))
        {
	        $uri = $record->get('uri');
	      	if (strlen($uri) < 1) {
	        	$uri = $record->get('title');
	      	}
        	$data['uri'] = $this->get_safe_uri($uri);
        }


        //Controllo se ha un parent
        if (isset($tipo['fields']['id_parent']))
        {
	        $parent = $record->get('id_parent');
	        if ($parent || $parent === '') {
	          if ($parent === '') {
	            $data['id_parent'] = null;
	          } else {
	            $data['id_parent'] = $parent;
	          }
	        }
        }

        //Inserisco le colonne fisiche addizionali
        /*
        foreach ($this->config->item('record_columns') as $column)
        {
          $value = $record->get($column);
          //Se non è ancora stata valorizzata la colonna
          if (!isset($data[$column]) && $value) {
            $data[$column] = $value;
          }
        }*/

        /* DEPRECATO - QUESTO CONTROLLO VA FATTO SU PAGES
        //Controllo se questo URI è in uso da altri record
        $uri_used = $this->uri_is_used($data['uri']);

        if ($uri_used) {
          if ($uri_used->id != $id) {
            $tipo = $this->content->type($uri_used->_tipo);
            show_error('L\'indirizzo (URI) "'.$data['uri'].'" &egrave; gi&agrave; utilizzato dal record [<a href="'.admin_url('contents/edit_record/'.$tipo['name'].'/'.$uri_used->id).'">'.$uri_used->get('title').'</a>] di tipo ['.$tipo['description'].'].', 500, 'URI Già utilizzato');
          }
       }
       */

	  $done = FALSE;

      if ($id) {

       	$is_published = $this->id_is_published($id);

         //Imposto il contenuto come non pubblicato (0 = bozza, 2 = bozza + pubblicato)
        if ($tipo['stage'])
        {
        	$data['published'] = '0';
        }


         //Tolgo la chiave primaria
         unset($data[$tipo['primary_key']]);

          //Update
          if ($this->db->where($tipo['primary_key'], $id)
               ->update($this->table_stage, $data))
               {
            $done = $id;
            $this->events->log('update', $id, $data['title'], $data['id_type']);
          } else {
            show_error('Impossibile aggiornare il record ['.$id.'].', 500, 'Aggiornamento record');
          }
      } else {
          //Insert
          if (isset($tipo['fields']['date_insert'])){
          		$data['date_insert'] = time();
          }
      		if ($tipo['stage'])
        	{
         		$data['published'] = '0';
        	}

        	unset($data[$tipo['primary_key']]);

          if ($this->db->insert($this->table_stage, $data))
          {
            $done = $this->db->insert_id();
            $this->events->log('insert', $done, $data['title'], $data['id_type']);
          } else {
            show_error('Impossibile aggiungere il record di tipo ['.$data['id_type'].'].', 500, 'Inserimento record');
          }
      }
      if ($done)
      {

      	if ($tipo['tree'])
      	{
      		$data[$tipo['primary_key']] = $done;
      		//Se è un tipo pagina, aggiorno i riferimenti
      		$this->pages->set_stage(TRUE)->save($data);
      	}

      }
      return $done;
  } else {
    show_error('Impossibile salvare un oggetto di tipo NON record.');
  }
}

  /**
   * Elimina un record dal db
   * @param int $record_id
   * @return bool
   */
  public function delete_by_id($record_id, $type = '') {

  	if ($type != '')
  	{
  		$this->set_type($type);
  	}

    $done = $this->db->where($this->primary_key, $record_id)
              ->delete($this->table);

    $done_stage = $this->db->where($this->primary_key, $record_id)
                  ->delete($this->table_stage);

    if ($done && $done_stage)
    {
      //Elimino gli allegati associati su entrambe le tabelle (stage e produzione)
      $this->load->documents();
      $this->documents->delete_by_binds($this->table, $record_id, FALSE);
      $this->documents->delete_by_binds($this->table_stage, $record_id, TRUE);
      return true;
    }
    return false;

  }

  /**
   * Ottiene un URI sicuro da utilizzare
   * @param string $uri
   * @return string
   */
  public function get_safe_uri($uri)
  {
    return substr(url_title(convert_accented_characters($uri)), 0, 127);
  }

  /**
   * Controlla se un URI è stato utilizzato
   * Se utilizzato, ritorna il record relativo
   * @param string $uri
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
   * Controlla se un record è pubblicato, dato il suo ID
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
   * Pubblica un record e i suoi allegati
   * @param int $id
   */
  function publish($id = '')
  {
      if ($id == '') {
        show_error('ID del contenuto da pubblicare non specificato. (records/publish)');
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
      if ($stage_record['date_publish'] < time())
      {
      		$stage_record['date_publish'] = time();
      }

      $this->events->log('publish', $stage_record[$this->primary_key], $stage_record['title'], $stage_record['id_type']);

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
      	//Aggiorno gli allegati
      	$this->load->documents();
      	$this->documents->put_live_documents($this->table, $id);
      	//Aggiorno il record in stage
        return $this->db->where($this->primary_key, $id)
                  		->update($this->table_stage, array('published' => 1, 'date_publish' => $stage_record['date_publish']));
      }
    }
    return FALSE;
  }

  /**
   * Depubblico un record
   * @param $id
   * @return bool
   */
  function depublish($id = '')
  {
      if ($id == '')
      {
        show_error('ID del contenuto da depubblicare non specificato. (records/depublish)');
      }
    $done = $this->db->where($this->primary_key, $id)
             ->delete($this->table);
    if ($done)
    {
      $this->events->log('depublish', $id, $id);

      //Elimino gli allegati in produzione
      $this->load->documents();
      $this->documents->delete_records_by_binds($this->table, $id, TRUE);

      //Aggiorno lo stato del record di sviluppo
      return $this->db->where($this->primary_key, $id)
              ->update($this->table_stage, array('published' => 0));
    }
  }

  /**
   * Estrazione delle options di un tipo
   * @param string (id) $field
   * @return array
   */
  function get_field_options($field)
  {
  	if (isset($field['extract'])) {
  		$options = array();

  		switch ($field['extract']) {
  			//Estrazione di una query non cacheata
  			case 'query':
  				$sql = $field['options'];
  				//Stage tables
  				$sql = str_replace('FROM (`'.$this->db->dbprefix.$this->table.'`)', 'FROM (`'.$this->db->dbprefix.$this->table_current.'`)', $sql);
  				$result = $this->db->query($sql)->result();
  				if (count($result)) {
  					foreach ($result as $option) {
  						$options[$option->value] = $option->name;
  					}
  				}
  				break;

  				//Valori interni al framework/tipo
  			case 'custom':
  				eval('$options = ' . $field['options'].';');
  				break;
  		}
  		return $options;
  	}
  	return $field['options'];
  }
}
