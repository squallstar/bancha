<?php
/**
 * Xml Library Class
 *
 * The library that works with the XML schemes
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Xml
{
	/**
	 * @var mixed Code Igniter instance
	 */
  	private $CI;

  	/**
	 * @var string Directory with the XML schemes
	 */
  	public $xml_folder;

  	/**
	 * @var string The file which contains the content types cache
	 */
  	public $types_cache_folder;

  	public function __construct()
  	{
   		$this->CI = & get_instance();

    	$this->xml_folder	= $this->CI->config->item('xml_folder');
    	$this->types_cache_folder	= $this->CI->config->item('types_cache_folder');
  	}

  	/**
  	 * Builds the XML of a record, given it's type and its field values
  	 * @param int|string $type
  	 * @param array $data
  	 * @return string xml
  	 */
  	function get_record_xml($type='', $data)
  	{
    	if ($type != '')
    	{
      		$tipo = $this->CI->content->type($type);

      		$xmlstring = read_file($this->CI->config->item('templates_folder').'Record.xml');

      		$xml = new SimpleXMLElement($xmlstring);

      		foreach ($tipo['fields'] as $field_name => $field)
      		{
        		//Solo se è una colonna non fisica
        		if (!in_array($field_name, $tipo['columns']))
        		{
          			$value = isset($data[$field_name]) ? $data[$field_name] : '';

          			//Inserisco il nodo solo se non è vuoto
          			if ($value != '') {
            			$column = $xml->addChild($field_name);

            			if (is_array($value))
            			{
              				foreach ($value as $val)
              				{
                				$column->addChild('value', $val);
              				}
            			} else {
              				$node = dom_import_simplexml($column);
              				$single_node = $node->ownerDocument;
              				$node->appendChild($single_node->createCDATASection($value));
            			}
          			}
        		}
      		}
      		return $xml->asXML();
    	} else {
      		show_error(_('Type not set.') . ' (xml/get_record_xml)');
    	}
  	}

  	/**
  	 * Takes the result of a single query starting from an SQL node (extracted from an XML scheme)
   	 * @param SimpleXMLElement $sql
   	 * @param int $id_type
   	 * @return array
   	 */
  	function records_from_sql_xml($sql, $id_type = '')
  	{
    	if (!isset($this->CI->content))
    	{
    		//TODO: fix - how and when it happens?
    		return;
    	}

    	$this->CI->db->select((string)$sql->select);

    	$tipo = $this->CI->content->type($id_type);

    	//We will use the staging table if we are in stage
    	$from_tbl = (string)$sql->from;

    	$this->CI->db->from($from_tbl);

    	if (isset($sql->where))
    	{
      		if ((string)$sql->where != '')
      		{
        		$this->CI->db->where((string)$sql->where);
      		}
    	}
    	if (isset($sql->order_by))
    	{
     		$this->CI->db->order_by((string)$sql->order_by);
    	}
    	if (isset($sql->limit))
    	{
      		$this->CI->db->limit((string)$sql->limit);
    	}
    	if (isset($sql->type))
    	{
      		$id_tipo = is_numeric((string)$sql->type) ? (string)$sql->type : $this->CI->content->type_id((string)$sql->type);
     		$this->CI->db->where('id_type', $id_tipo);
    	}
   		return $this->CI->db->get()->result();
  	}

  	/**
  	 * Parses an XML file and converts it into an Array
  	 * It is one of the most important functions of the framework and the content types cache uses it
   	 * @param string $filepath
   	 */
  	function parse_file($filepath)
  	{
    	$node = simplexml_load_file($filepath);
    	if (!$node)
    	{
      		show_error('File not found: '.$filepath);
    	}

    	//Gets the filename
    	$segments = explode(DIRECTORY_SEPARATOR, $filepath);
    	$filename = $segments[count($segments)-1];

    	//Filename sanitize
    	$safe_filename = str_replace(' ', '_', str_replace('.xml', '', $filename));

    	//The type name
    	$name = (string) $node->name;

    	$type_id = (int) $node->id;

    	//Allowed types of field
    	$field_usable_inputs = array(
      		'text', 'textarea', 'date', 'checkbox', 'select', 'multiselect', 'radio',
      		'images', 'files', 'number', 'textarea_full', 'datetime', 'hidden'
    	);

    	$content = array(
      		'id'			=> $type_id,
      		'name'			=> $safe_filename,
      		'tree'			=> strtolower((string)$node->tree) == 'true' ? true : false,
      		'has_categories'=> strtolower((string)$node->has_categories) == 'true' ? true : false,
      		'description'	=> (string) $node->description,
      		'primary_key'	=> (string) $node->primary_key,
      		'table'			=> (string) $node->table
    	);

    	if (isset($node->table_stage))
    	{
    		$content['stage'] = TRUE;
    		if (strtolower((string)$node->table_stage) == 'true')
    		{
				$content['table_stage'] = (string) $node->table.'_stage';
			} else {
				$content['table_stage'] = (string) $node->table_stage;
			}
    	} else {
    		$content['stage'] = FALSE;
    		$content['table_stage'] = $content['table'];
    	}

    	if (!$content['primary_key'] || !$content['table'])
    	{
      	show_error($this->CI->lang->_trans('Primary key or table not defined for the content type %n.', array('n' => '['.$safe_filename.']')), 500, _('XML parser: Error'));
    	}

    	//Parent types
    	if (isset($node->parent_types))
    	{
      		$parent_types = array();
      		foreach ($node->parent_types->type as $parent_type)
      		{
        		$parent_types[] = (string) $parent_type;
      		}
      		$content['parent_types'] = $parent_types;
    	}

    	//Triggers
    	if (isset($node->triggers))
    	{
    		$triggers = array();
    		foreach ($node->triggers->trigger as $node_trigger)
    		{
    			$trigger = array();
				$attr = $node_trigger->attributes();

    			//Action field
    			if (isset($attr->field))
    			{
    				$trigger['field'] = str_replace(' ', '_', trim((string) $attr->field));
    			}

    			//We add the SQL node if it exists
    			if (isset($node_trigger->sql))
    			{
    				$node_sql = $node_trigger->sql;
    				$sql_attrib = $node_sql->attributes();
    				$trigger['action'] = 'sql';
    				$trigger['sql'] = array(
    					'action'	=> trim((string) $sql_attrib->action),
    					'type'		=> trim((string) $sql_attrib->type),
    					'target'	=> trim((string) $sql_attrib->target),
    					'value'		=> (string)	$sql_attrib->value,
    					'escape'	=> isset($sql_attrib->escape) ? (trim(strtolower((string)$sql_attrib->escape)) == 'false' ? FALSE : TRUE) : TRUE
    				);
    			}

				//Let's add a custom call it it exists
    			if (isset($node_trigger->call))
    			{
    				$trigger['action'] = 'call';
					$trigger['method'] = (string)$node_trigger->call->attributes()->action;
				}

    			//Trigger will be triggered on...
    			if (isset($attr->on))
    			{
    				$tmp = explode(',', $attr->on);
    				foreach ($tmp as $fire)
    				{
    					$fire = trim($fire);
    					if (!isset($triggers[$fire]))
    					{
    						$triggers[$fire] = array();
    					}
    					$triggers[$fire][] = $trigger;
    				}
    			}
    		}
    		$content['triggers'] = $triggers;
    	}

    	$content['fieldsets'] = array();

    	//The XML column, is always present on each type, but it is not on the scheme
    	$content['columns'] = array('xml');

    	//True when the type has at leasts one images/files field.
    	$content['has_attachments'] = FALSE;

    	foreach ($node->fieldset as $fieldset_node)
    	{
      		$fieldset_name = isset($fieldset_node->name) ? (string)$fieldset_node->name : _('Untitled');

      		if ($fieldset_name == '')
      		{
        		show_error($this->CI->_trans('One of the fieldsets of type %n does not have the node <name> (mandatory).', array('n' => '['.$safe_filename.']')), 500, _('XML parser: Error'));
      		} else if (array_key_exists($fieldset_name, $content['fieldsets'])) {
        		show_error($this->CI->_trans('The type %t has more than one fieldset named %n.', array('t' => '['.$safe_filename.']', 'n' => '['.$fieldset_name.']')), 500, _('XML parser: Error'));
      		}

      		$fieldset = array('name' => $fieldset_name, 'fields' => array());

      		foreach ($fieldset_node->field as $field)
      		{
        		//Unique name
            $attr = $field->attributes();
        		$field_name = (string) $attr->id;
        		if (!$field_name || $field_name == '') {
          			show_error($this->CI->_trans('One of the fields of type %t does not have a name.', array('t' => '['.$safe_filename.']')), 500, _('XML parser: Error'));
        		}

        		//Physical column
        		$is_column = isset($field->attributes()->column) ? (string) $field->attributes()->column : FALSE;
        		if (strtoupper($is_column) == 'TRUE')
        		{
          			$content['columns'][] = $field_name;
        		}

            //Includes a link?
            if (isset($attr->link))
            {
              $link = (string) $attr->link;
              switch (strtolower($link))
              {
                case 'edit':
                  //Admin edit record link
                  $content['edit_link'] = $field_name;
                  break;
              }
            }

        		//Reserved names check
        		if (in_array($field_name, $this->CI->config->item('restricted_field_names')))
        		{
          			show_error($this->CI->_trans('The field name %n is reserved (Type: %t) and needs to be changed!', array('t' => '['.$safe_filename.']', 'n' => '['.$field_name.']')), 500, _('XML parser: Error'));
        		}

        		if (!in_array((string)$field->type, $field_usable_inputs))
        		{
          			show_error($this->CI->_trans('The value of the node named type (field: %n, type %t) does not exists. Allowed values are:', array('n' => $field_name, 't' => $safe_filename, 'v' => ' '.implode(', ', $field_usable_inputs))), 500, _('XML parser: Error'));
        		}

        		//Default fields for each field
        		$content_field = array(
          			'description'	=> isset($field->description) ? (string)$field->description : '',
          			'type'			=> (string) $field->type,
          			'length'		=> isset($field->length) ? (int)$field->length : 255,
          			'mandatory'		=> isset($field->mandatory) ? (strtoupper($field->mandatory) == 'TRUE' ? TRUE : FALSE) : FALSE,
          			'admin'			=> isset($field->admin) ? (strtoupper($field->admin) == 'TRUE' ? TRUE : FALSE) : FALSE,
          			'list'			=> isset($field->list) ? (strtoupper($field->list) == 'TRUE' ? TRUE : FALSE) : FALSE,
         			'visible'		=> isset($field->visible) ? (strtoupper($field->visible) == 'TRUE' ? TRUE : FALSE) : TRUE,
          			'default'		=> isset($field->default) ? (string)$field->default : ''
        		);

        		if ($content_field['type'] == 'files' || $content_field['type'] == 'images')
        		{
          			$content['has_attachments'] = TRUE;
        		}

        		if ($content_field['type'] == 'images')
        		{
          			$content_field['original'] = isset($field->original) ? (strtoupper($field->original) == 'TRUE' ? TRUE : FALSE) : FALSE;
          			$content_field['encrypt_name'] = isset($field->encrypt_name) ? (strtoupper($field->encrypt_name) == 'TRUE' ? TRUE : FALSE) : FALSE;
          			$content_field['resized'] = isset($field->resized) ? (string)$field->resized : FALSE;
          			$content_field['thumbnail'] = isset($field->thumbnail) ? (string)$field->thumbnail : FALSE;
        		}

        		if ($content_field['type'] == 'images' || $content_field['type'] == 'files')
        		{
          			$content_field['size'] = isset($field->size) ? (int)$field->size : 102400; //max 100mb
          			$content_field['mimes'] = isset($field->mimes) ? (string)$field->mimes : '*';
          			$content_field['max'] = isset($field->max) ? (int)$field->max : 10; //max 10 files
        		}

        		//Onchange JS
        		if (isset($field->onchange))
        		{
          			$content_field['onchange'] = (string) $field->onchange.';';
        		}

        		//Onkeyup JS
        		if (isset($field->onkeyup))
        		{
          			$content_field['onkeyup'] = (string) $field->onkeyup.';';
        		}

        		//Options
       			$options = array();
        		if (isset($field->options))
        		{
          			if (isset($field->options->custom))
          			{
            			//Custom references as source
            			$content_field['options'] = (string) $field->options->custom;
            			$content_field['extract'] = 'custom';
          			} else {
            			foreach ($field->options->option as $option)
            			{
              				$options[ (string)$option->attributes()->value ] = (string)$option;
            			}
            			$content_field['options'] = $options;
          			}
        		}

        		//Options SQL extractions
        		if (isset($field->sql))
        		{
          			$sql = $field->sql;

          			//Let's extract the records
          			$records = $this->records_from_sql_xml($sql, $content['id']);

          			//Check if the query has to be cached
          			if (isset($sql->attributes()->cache))
          			{
          				$cache = (string) $sql->attributes()->cache;
          			} else {
          				$cache = 'false';
          			}

          			if ($cache == 'true')
          			{
            			//We now make the options key-value array
            			if (count($records))
            			{
	            			foreach ($records as $record)
	            			{
	              				$options[$record->value] = $record->name;
	            			}
            			}

            			$content_field['options'] = $options;

          			} else {
            			//Save the query string for later
           				$query = str_replace("\n", ' ', $this->CI->db->last_query());
            			$content_field['options'] = $query;
            			$content_field['extract'] = 'query';
          			}
        		}

        		//Adds the properties to this field
        		$content['fields'][$field_name] = $content_field;

        		//We add the field name to the available fields
        		$fieldset['fields'][] = $field_name;
      		} //end foreach field

      		//And we add a fieldset to our list
      		$content['fieldsets'][] = $fieldset;

    	} //end foreach fieldsets
    	return $content;
  	}
}
