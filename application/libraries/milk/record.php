<?php
/**
 * Record Library Class
 *
 * Gestione di un singolo record (istanza)
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Record {

	private $_data	= array();
	//private $_all_data = array();

	public $id 		= FALSE;
  	public $_tipo	= '';
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

  /**
   * Imposta i dati del record
   * @param array $data
   */
  public function set_data($data)
  {
    $CI = & get_instance();
    $tipo = & $CI->content->type($this->_tipo);

    //$this->_all_data = $data;
	/*
    foreach ($tipo['fields'] as $field_name => $field)
    {
    	//Solo se non è una colonna fisica
    	if (!in_array($field_name, $CI->config->item('record_columns')))
    	{
      		$value = isset($data[$field_name]) ? $data[$field_name] : '';
      		$this->_data[$field_name] = $value;
    	}
    }

    //Aggiungo le colonne fisiche

    foreach ($CI->config->item('record_columns') as $field)
    {
      if (isset($data[$field]))
      {
        $this->_data[$field] = $data[$field];
      }
    }
    */

    foreach ($tipo['fields'] as $field_name => $field)
    {
    	$value = isset($data[$field_name]) ? $data[$field_name] : '';
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
    if (isset($data[$field['primary_key']]))
    {
    	$this->id = $data[$field['primary_key']];
    }

  }

  /**
   * Ottiene un dato del record
   * @param string $key
   * @param string $default
   */
  public function get($key='', $default='')
  {
    if ($key != '')
    {
      if (isset($this->_data[$key])) {
      	return $this->_data[$key];
      }
    }
	return $default;
  }

  /**
   * Imposta un dato nel record
   * @param string $key
   * @param mixed $val
   */
  public function set($key='', $val)
  {
    if ($key != '') {
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
      $this->xml = $CI->xml->get_record_xml($this->_tipo, $this->_data);

      //Tolgo i caratteri di a capo per recuperare spazio
      $this->xml = str_replace(array("\r\n", "\r", "\n"), "", $this->xml);
    }else{
      show_error('Impossibile costruire i nodi xml, il record &egrave; vuoto. (record/build_xml)');
    }
  }

  	/**
   	* Costruisce i dati del record dato un xml
   	*/
	public function build_data()
	{
    	if ($this->xml != '')
    	{
    		$CI = & get_instance();
    		$tipo = & $CI->content->type($this->_tipo);

	      	$xmltree = simplexml_load_string($this->xml, 'SimpleXMLElement', LIBXML_NOCDATA);

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

	      		//Converto i timestamp in date
	      		$field_type = $tipo['fields'][$field_name]['type'];
	      		if ($field_type == 'date')
	      		{
	      			$this->_data[$field_name] = date('d/m/Y', $this->_data[$field_name]);
	      		} else if ($field_type == 'datetime')
	      		{
	      			debug($this->_data[$field_name] . ' '.$this->_data['_time_'.$field_name]);
	      			$this->_data[$field_name] = date('d/m/Y H:i', $this->_data[$field_name] . ' '.$this->_data['_time_'.$field_name]);
	      		}
			}

			//Se il template non e' impostato, metto quello di default
			if ($this->is_page() && !$this->get('view_template'))
			{
	      		$CI = & get_instance();
	      		$this->set('view_template', $CI->content->item('default_view_template'));
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
