<?php
/**
 * Record Library Class
 *
 * Gestione di un singolo record (istanza)
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Record {

	/**
	 * @var array Contiene i dati del record
	 */
	private $_data	= array();

	/**
	 * @var int Chiave primaria del record
	 */
	public $id 		= FALSE;

	/**
	 * @var int Record type id
	 */
	public $_tipo	= '';
	
	/**
	* @var int Type definition
	*/
	public $_tipo_def	= array();

	/**
	 * @var string Stringa xml dei dati non fisici del record
	 */
  	public $xml 	= '';

  	public function __construct($type='')
  	{
  		if ($type != '')
		{
			if (!is_numeric($type))
			{
				$CI = &get_instance();
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
   	* Imposta i dati del record
   	* @param array $data
   	*/
  	public function set_data($data)
  	{
  		$CI = & get_instance();
  		
    	if (!$this->_tipo_def)
    	{
    		$tipo = & $CI->content->type($this->_tipo);
    	} else {
    		$tipo = $this->_tipo_def;
    	}

    	foreach ($tipo['fields'] as $field_name => $field)
    	{
    		$value = isset($data[$field_name]) ? $data[$field_name] : '';
    		if ($CI->config->item('strip_website_url') && $field['type'] == 'textarea' || $field['type'] == 'textarea_full')
    		{
    			//Elimino il percorso del sito dalle textarea
    			$value = str_replace(site_url(), '/', $value);
    		}
   			$this->_data[$field_name] = $value;

    		if ($field['type'] == 'date')
    		{
    			if (strpos($this->_data[$field_name], '/'))
    			{
    				$this->_data[$field_name] = implode('-', array_reverse(explode('/', $this->_data[$field_name])));
    			}
    			$this->_data[$field_name] = strtotime($this->_data[$field_name]);
    		}
    		else if ($field['type'] == 'datetime')
    		{
    			if (strpos($this->_data[$field_name], '/'))
    			{
    				$this->_data[$field_name] = implode('-', array_reverse(explode('/', $this->_data[$field_name])));
    				$this->_data[$field_name] = $this->_data[$field_name]  . ' ' . $data['_time_'.$field_name] . ':00';
    			}
    			$this->_data[$field_name] = strtotime($this->_data[$field_name]);
    		}
    	}

    	//Imposto a parte il campo ID (serve per insert-update)
    	if (isset($data[$tipo['primary_key']]))
    	{
    		$this->id = $data[$tipo['primary_key']];
    	}
  	}

  	/**
   	* Ottiene un dato del record
   	* @param string $key
   	* @param string $default
   	*/
  	public function get($key='', $default='')
  	{
    	return isset($this->_data[$key]) ? $this->_data[$key] : $default;
  	}

  	/**
   	* Imposta un dato nel record
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
   	* Controlla se il record ha un parent
   	* @return BOOL
   	*/
  	public function has_parent()
  	{
		return $this->get('id_parent') ? TRUE : FALSE;
  	}

  	/**
   	* Controlla se un record e' una pagina
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
   	* Costruisce un XML relativo al record
   	* Necessita che siano stati popolati i campi con la funzione set_data()
   	*/
  	public function build_xml()
  	{
    	$CI = & get_instance();
    	if (count($this->_data)) {
     		$this->xml = $CI->xml->get_record_xml($this->_tipo_def ? $this->_tipo_def : $this->_tipo, $this->_data);

      		//Tolgo i caratteri di a capo per recuperare spazio
      		$this->xml = str_replace(array("\r\n", "\r", "\n"), "", $this->xml);
    	} else {
      		show_error('Impossibile costruire i nodi xml, il record &egrave; vuoto. (record/build_xml)');
    	}
  	}

  	/**
   	* Costruisce i dati del record dato un xml
   	*/
	public function build_data()
	{
    	if ($this->xml || $this->xml == '')
    	{
    		$CI = & get_instance();
    		$tipo = & $CI->content->type($this->_tipo);

	      	$xmltree = simplexml_load_string($this->xml, 'SimpleXMLElement', LIBXML_NOCDATA);

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

		      		//We convert the timestamps in dates
		      		//TODO: mettere tutti i format date e datetime nel config per lingua (tipo d/m/Y)
		      		$field_type = $tipo['fields'][$field_name]['type'];
		      		if ($field_type == 'date')
		      		{
		      			$this->_data[$field_name] = date('d/m/Y', $this->_data[$field_name]);
		      		} else if ($field_type == 'datetime')
		      		{
		      			$this->_data[$field_name] = date('d/m/Y H:i', $this->_data[$field_name] . ' '.$this->_data['_time_'.$field_name]);
		      		}
                    else if (in_array($field_type, config_item('array_field_types')) && is_string($this->_data[$field_name]))
                    {
                        $this->_data[$field_name] = explode('||', trim($this->_data[$field_name], '|'));    
                    }
				}
	      	}

			//Se il template non e' impostato, metto quello di default
			if ($this->is_page() && !$this->get('view_template'))
			{
	      		$CI = & get_instance();
	      		$this->set('view_template', $CI->config->item('default_view_template'));
	      	}

	      	$this->xml = '';

    	} else {
      		show_error('I dati XML del record non sono stati trovati. (record/build_data)');
    	}
  	}

  	/**
  	 * Estrae i documenti del record
  	 */
	public function set_documents()
	{
		$CI = & get_instance();
		$CI->load->documents();
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
	}
}
