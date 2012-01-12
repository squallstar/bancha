<?php
/**
 * Record Library Class
 *
 * This is the main class of every single record inside Bancha.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Record {

	/**
	 * @var array Contains all the data of the record
	 */
	private $_data= array();

	/**
	 * @var int Primary key
	 */
	public $id = FALSE;

	/**
	 * @var int Record type id
	 */
	public $_tipo = '';

	/**
	* @var int Type definition
	*/
	public $_tipo_def = array();

	/**
	 * @var string The xml string that contains some data
	 */
  	public $xml = '';

  	/**
	 * @var string Set to TRUE when the documents will be extracted
	 */
  	public $documents_extracted = FALSE;

    /**
     * @var array Contains the relations
     */
    private $_related_objects = array();

  	public function __construct($type='')
  	{
  		if ($type != '')
  		{
  			if (is_array($type))
  			{
  				$this->_tipo_def = $type;
          $this->_tipo = $type['id'];
          return;
  			} else if (!is_numeric($type))
  			{
  				$CI = & get_instance();
  				$type = $CI->content->type_id($type);
  			}
  			$this->_tipo = $type;
  		}
  	}

  	public function set_type($type)
  	{
  		$this->_tipo_def = $type;
  	}

  	/**
   	* Sets the record data
   	* @param array $data
   	*/
  	public function set_data($data)
  	{
  		$CI = & get_instance();

    	if (!$this->_tipo_def)
    	{
    		$tipo = & $CI->content->type($this->_tipo);
    		if (!$tipo) return;
    	} else {
    		$tipo = $this->_tipo_def;
    	}

    	foreach ($tipo['fields'] as $field_name => $field)
    	{
    		if (!isset($data[$field_name])) continue;

        $value = $data[$field_name];
            
    		if ($CI->config->item('strip_website_url')
                && in_array($field['type'], array('textarea', 'textarea_full', 'textarea_code')))
    		{
    			//We strip the website url from the textarea fields
    			$value = str_replace(site_url(), '/', $value);
    		}
            
   			$this->_data[$field_name] = $value;

   			if ($field['type'] == 'date' || $field['type'] == 'datetime')
   			{
   				if (is_numeric($value))
   				{
   					//The value is already a timestamp. Let's skip the checks below
   					continue;
   				}

   				//If the date includes the time, we split it
   				if (strpos($value, ':') !== FALSE)
   				{
   					list($this->_data[$field_name], $data['_time_'.$field_name]) = explode(' ', $this->_data[$field_name]);
   				}
   				switch (LOCAL_DATE_FORMAT)
    			{
    				//Computer format
    				case 'Y-m-d':
    					$tmp = explode('-', $this->_data[$field_name]);
    					if (count($tmp) == 3)
    					{
    						list($year, $month, $day) = $tmp;
    					}
    					break;
    					
    				//American format
    				case 'm/d/Y':
    					$tmp = explode('/', $this->_data[$field_name]);
    					if (count($tmp) == 3)
    					{
    						list($month, $day, $year) = $tmp;
    					}
    					break;    
    				
    				//European date
    				case 'd/m/Y':
    					$tmp = explode('/', $this->_data[$field_name]);
    					if (count($tmp) == 3)
    					{
    						list($day, $month, $year) = $tmp;
    					}
    					break;					
    			}

    			if (!isset($day) && !isset($month) && !isset($year))
    			{
    				//Prevent wrong datetime input to throw notices
    				list($day, $month, $year, $hour, $min) = explode('-', date('d-m-Y-H-i'));
    			}

    			if ($field['type'] == 'date')
	    		{
	    			$this->_data[$field_name] = mktime('00', '00', '00', $month, $day, $year);
	    		}
	    		else if ($field['type'] == 'datetime')
	    		{
	    			if (isset($data['_time_'.$field_name]) && strpos($data['_time_'.$field_name], ':') !== FALSE)
	    			{
	    				list($hour, $min) = explode(':', $data['_time_'.$field_name]);
	    			} else {
	    				$hour = date('H');
	    				$min = date('i');
	    			}
	    			$this->_data[$field_name] = mktime($hour, $min, '00', $month, $day, $year);    			
	    		}
   			}
    	}

    	//We set the primary key for the update queries
    	if (isset($data[$tipo['primary_key']]))
    	{
    		$this->id = $data[$tipo['primary_key']];
    	}

      //We set the default language if is possible
      if (!isset($this->_data['lang']) && isset($tipo['fields']['lang']))
      {
        $langs = array_keys($CI->lang->languages);
        $this->_data['lang'] = $langs[0];
      }
  	}

  	/**
   	* Returns a record value
   	* @param string $key
   	* @param string $default
   	*/
  	public function get($key='', $default='')
  	{
    	return isset($this->_data[$key]) ? $this->_data[$key] : $default;
  	}

    /**
    * Returns all record data
    * @return array
    */
    public function get_data()
    {
      return $this->_data;
    }

  	/**
   	* Sets a single value
   	* @param string $key
   	* @param mixed $val
   	*/
  	public function set($key='', $val)
  	{
    	if ($key != '')
    	{
      		$this->_data[$key] = $val;
    	}
    	return $this;
  	}

  	/**
   	* Checks if the record has a parent
   	* @return bool
   	*/
  	public function has_parent()
  	{
		return $this->get('id_parent') ? TRUE : FALSE;
  	}

  	/**
   	* Checks if the record is a page
   	* @return bool
   	*/
  	public function is_page()
  	{
  		$CI = & get_instance();
	  	$tipo = $CI->content->type($this->_tipo);
	  	if ($tipo['tree'] == TRUE)
	  	{
	  		return TRUE;
	  	} else {
	  		return FALSE;
	  	}
  	}

  	/**
   	* Builds an XML, based on the scheme
   	* Before using, sets the data with the set_data() function
   	*/
  	public function build_xml()
  	{
    	$CI = & get_instance();
    	if (count($this->_data)) {
     		$this->xml = $CI->xml->get_record_xml($this->_tipo_def ? $this->_tipo_def : $this->_tipo, $this->_data);

      		//We strip the newline and return characters
      		$this->xml = str_replace(array("\r\n", "\r", "\n"), "", $this->xml);
    	}
  	}

  	/**
   	* Builds the data from an xml
   	*/
	public function build_data()
	{
    	if ($this->xml || $this->xml == '')
    	{
    		$CI = & get_instance();
    		$tipo = & $CI->content->type($this->_tipo);
    		if (!$tipo) return;

	      	$xmltree = @simplexml_load_string($this->xml, 'SimpleXMLElement', LIBXML_NOCDATA);

	      	if ($xmltree)
	      	{
		      	foreach ($xmltree as $field_name => $field_value)
		      	{
		      		if ($field_value instanceof SimpleXMLElement)
		      		{

		      			if (isset($field_value->value))
		      			{
		      				foreach ($field_value->value as $val)
		      				{
		      					$this->_data[$field_name][] = (string)$val;
		      				}
		      			} else {
		      				$this->_data[$field_name] = (string)$field_value;
		      			}
		      		} else {
		      			$this->_data[$field_name] = $field_value;
		      		}

		      		if (!isset($tipo['fields'][$field_name]['type']))
		      		{
		      			continue;
		      		}
		      		//We convert the timestamps in dates
		      		$field_type = $tipo['fields'][$field_name]['type'];
		      		if ($field_type == 'date')
		      		{
		      			$this->_data[$field_name] = date(LOCAL_DATE_FORMAT, $this->_data[$field_name]);
		      		} else if ($field_type == 'datetime')
		      		{
		      			$this->_data[$field_name] = date(LOCAL_DATE_FORMAT . ' H:i', $this->_data[$field_name]);
		      		}
                    else if (in_array($field_type, config_item('array_field_types')) && is_string($this->_data[$field_name]))
                    {
                        $this->_data[$field_name] = explode('||', trim($this->_data[$field_name], '|'));
                    }
				}
	      	}

			//If a page hasn't the template, we will use the default one
			if ($tipo['tree'] && !$this->get('view_template'))
			{
	      		$CI = & get_instance();
	      		$this->set('view_template', $CI->config->item('default_view_template'));
	      	}

	      	$this->xml = '';

    	} else {
      		log_message('error', 'Record XML data not found. (record/build_data)');
    	}
  	}

  	/**
  	 * Extracts the documents of this record from the database
  	 */
	public function set_documents()
	{
		if ($this->documents_extracted) return;

		$CI = & get_instance();
		if (!isset($CI->documents))
		{
			$CI->load->documents();
		}
   		$tipo = & $CI->content->type($this->_tipo);

   		$has_attachments = FALSE;

		foreach ($tipo['fields'] as $field_name => $field_value)
		{
			$ordered_attachs = array();
			if ($field_value['type'] == 'files' || $field_value['type'] == 'images')
			{
				$has_attachments = TRUE;
			}
		}

		if ($has_attachments)
		{
			//Estraggo tutti gli allegati di questo record
			$all_attachs = $CI->documents->table($tipo['table'])->id($this->id)->get();

			foreach ($tipo['fields'] as $field_name => $field_value)
			{
				$ordered_attachs = array();
				if ($field_value['type'] == 'files' || $field_value['type'] == 'images')
				{
					$has_attachments = TRUE;
					//Ordino per campo gli allegati
					foreach ($all_attachs as $attach)
					{
						$ordered_attachs[$attach->bind_field][] = $attach;
					}
					//Rimetto negli array giusti gli allegati
					foreach ($ordered_attachs as $attach_field => $field_attachments)
					{
						$this->set($attach_field, $field_attachments);
					}
				}
			}
		}
		$this->documents_extracted = TRUE;
	}

    /**
     * When the record has a relation, calling this function will returns its
     * related records
     * @param $relation_name
     * @return relations 1-1 and 1-0: Record object
     * @return relations 1-n: array of records
     */
    public function related($relation_name)
    {
        if (isset($this->_related_objects[$relation_name]))
        {
            return $this->_related_objects[$relation_name];
        }

        $CI = & get_instance();

        if (!$this->_tipo_def)
        {
            $tipo = & $CI->content->type($this->_tipo);
            if (!$tipo) return FALSE;
        } else {
            $tipo = $this->_tipo_def;
        }

        if (isset($tipo['relations'][$relation_name]))
        {
            $rel = $tipo['relations'][$relation_name];

            if (!isset($rel['with']) || !isset($rel['to']) || !isset($rel['from']))
            {
            	log_message('error', 'The relation ' . $relation_name . ' is incomplete Please check your scheme.');
            }

            $CI->records->type($rel['with'])
                        ->where($rel['to'], $this->get($rel['from']));

            $type = strtolower($rel['type']);

            if (in_array($type, array('1-0', '1-1', '0-1')))
            {
                $this->_related_objects[$relation_name] = $CI->records->get_first();
            } else {
                $this->_related_objects[$relation_name] = $CI->records->get();
            }

            return $this->_related_objects[$relation_name];
        }
        return FALSE;
    }

    /**
    * Remove a single value from the current record
    * @param string $key
    * @return bool
    */
    public function remove($key = '')
    {
        unset($this->_data[$key]);
    }
}
