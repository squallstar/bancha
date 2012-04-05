<?php
/**
 * Xml Library Class
 *
 * The library that works with the XML schemes
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
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

    /**
     * @var array Translations
     */
    private $_translations = array();

  	public function __construct()
  	{
   		$this->CI = & get_instance();

    	$this->xml_folder	= $this->CI->config->item('xml_typefolder');
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
    		if (is_array($type))
    		{
    			$tipo = $type;
    		} else {
    			$tipo = $this->CI->content->type($type);
    		}

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
              				$value = '|'.implode('||', $value).'|';
            			}
          				$node = dom_import_simplexml($column);
          				$single_node = $node->ownerDocument;
          				$node->appendChild($single_node->createCDATASection($value));
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
  	function parse_xmlscheme($filepath)
  	{
    	$node = simplexml_load_file($filepath);
    	if (!$node)
    	{
      		show_error('Cannot parse the XML scheme: '. $filepath);
    	}
      return $node;
    }

    /**
     * Parses a YAML file and converts it into an Array
     * It is one of the most important functions of the framework and the content types cache uses it
     * @param string $filepath
     */
    function parse_yamlscheme($filepath)
    {
      require_once(APPPATH . '/libraries/externals/spyc.php');
      
      //1. parse yaml scheme
      $yaml = Spyc::YAMLLoad($filepath) or show_error('Cannot parse the YAML scheme: ' . $filepath);
      //debug($yaml);

      //2. convert yaml scheme to a new SimpleXML object
      $xml = new SimpleXMLElement('<content id="' . $yaml['id'] . '"></content>');

      //Base level (1)
      $xml->addChild('name', $yaml['name']);

      $node_descr = $xml->addChild('descriptions');
      $node_descr->addAttribute('label', $yaml['descriptions']['full_name']);
      $node_descr->addAttribute('new', $yaml['descriptions']['new_record']);

      $xml->addChild('tree', isset($yaml['tree']) ? ($yaml['tree'] ? 'true' : 'false') : 'false');

      $node_tables = $xml->addChild('table');
      if (isset($yaml['table']['primary_key'])) $node_tables->addAttribute('key', $yaml['table']['primary_key']);
      if (isset($yaml['table']['stage'])) $node_tables->addAttribute('stage', $yaml['table']['stage']);
      if (isset($yaml['table']['production'])) $node_tables->addAttribute('production', $yaml['table']['production']);

      if (isset($yaml['order_by'])) {
        $order_by = $xml->addChild('order_by');
        $order_by->addAttribute('field', $yaml['order_by']['field']);
        $order_by->addAttribute('sort', $yaml['order_by']['sort']);
      }

      if (isset($yaml['parents'])) {
        $node_parent = $xml->addChild('parents');
        foreach ($yaml['parents'] as $parent_type) {
            $node_parent->addChild('type', $parent_type);
        }
      }

      #warning todo triggers
      
      #warning todo relations

      if (isset($yaml['categories'])) $xml->addChild('categories', $yaml['categories'] ? 'true' : 'false');
      if (isset($yaml['hierarchies'])) $xml->addChild('hierarchies', $yaml['hierarchies'] ? 'true' : 'false');

      //Fieldsets
      foreach ($yaml['fieldsets'] as $fieldset) {
        $node_fieldset = $xml->addChild('fieldset');
        $node_fieldset->addAttribute('name', $fieldset['name']);
        $node_fieldset->addAttribute('icon', $fieldset['icon']);

        if (isset($fieldset['fields']) && count($fieldset['fields'])) {
            //Fields
            foreach ($fieldset['fields'] as $field_name => $field) {
                $node_field = $node_fieldset->addChild('field');
                $node_field->addAttribute('id', $field_name);

                //Boolean options
                foreach (array('column', 'mandatory', 'admin', 'list', 'visible', 'original', 'encrypt_name') as $option) {
                    if (isset($field[$option])) $node_field->addAttribute($option, $field[$option] ? 'true' : 'false');
                }

                //String options
                foreach (array('link', 'kind', 'type', 'description', 'length', 'default', 'rules', 'resized', 'thumbnail',
                               'thumbnail_preset', 'size', 'mimes', 'max', 'onchange', 'onkeyup') as $option) {
                    if (isset($field[$option])) $node_field->addAttribute($option, $field[$option]);
                }

                //Options
                if (isset($field['options'])) {
                    $node_opts = $node_field->addChild('options');
                    if (isset($field['options']['custom']) && count($field['options']) == 1) {
                        $node_opts->addChild('custom', $field['options']['custom']);
                    } else {
                        foreach ($field['options'] as $opt_key => $opt_val) {
                            $single_opt = $node_opts->addChild('option', $opt_key);
                            $single_opt->addAttribute('value', $opt_val);
                        }
                    }
                }

                //SQL
                if (isset($field['sql'])) {
                    $sql_node = $node_field->addChild('sql');
                    if (isset($field['sql']['cache'])) {
                        $sql_node->addAttribute('cache', $field['sql']['cache'] ? 'true' : 'false');
                    }
                    foreach (array('select', 'from', 'where', 'order_by', 'limit', 'type') as $statement) {
                        if (isset($field['sql'][$statement])) $sql_node->addChild($statement, $field['sql'][$statement]);
                    }
                }
            }
        }
      }

      debug($xml);

      die;
      
      //3. return the SimpleXML object
      return $xml;
    }

    /**
     * Parses an XML or YAML scheme
     * @param string $filepath
     */
    function parse_scheme($filepath)
    {
      $tmp = explode('.', $filepath);
      switch ($tmp[count($tmp)-1]) {
        case 'xml':
          $node = $this->parse_xmlscheme($filepath);
          break;
        case 'yaml':
          $node = $this->parse_yamlscheme($filepath);
          break;
      }

    	//Gets the filename
    	$segments = explode(DIRECTORY_SEPARATOR, $filepath);
    	$filename = $segments[count($segments)-1];

    	//Filename sanitize
    	$safe_filename = str_replace(' ', '_', str_replace('.xml', '', $filename));

    	//The type name
    	$name = (string) $node->name;

    	

    	//Allowed types of field
    	$field_usable_inputs = array(
      		'text', 'textarea', 'date', 'checkbox', 'select', 'multiselect', 'radio', 'password',
      		'images', 'files', 'number', 'textarea_full', 'textarea_code', 'datetime', 'hidden', 'hierarchy'
    	);

        $attr = $node->attributes();
        $type_id = isset($attr->id) ? (int)$attr->id : 0;

        $descr_attr = $node->descriptions->attributes();

        if (isset($node->table))
        {
			$tables = $node->table->attributes();
    	}

    	$content = array(
      		'id'				      => $type_id,
      		'name'				    => $safe_filename,
      		'tree'				    => strtolower((string)$node->tree) == 'true' ? TRUE : FALSE,
      		'has_categories'	=> isset($node->categories) ? (strtolower((string)$node->categories) == 'true' ? TRUE : FALSE) : FALSE,
          'has_hierarchies'	=> isset($node->hierarchies) ? (strtolower((string)$node->hierarchies) == 'true' ? TRUE : FALSE) : FALSE,
      		'description'		  => (string) $descr_attr->label,
      		'label_new'			  => (string) $descr_attr->new,
      		'primary_key'		  => (string) (isset($tables->key) ? $tables->key : 'id_record'),
      		'table'				    => (string) (isset($tables->production) ? $tables->production : 'records')
    	);

        $this->_translations[$content['description']] = TRUE;
        $this->_translations[$content['label_new']] = TRUE;

    	if (isset($tables->stage))
    	{
    		$content['stage'] = TRUE;
    		$content['table_stage'] = (string) $tables->stage;
    	} else {
    		$content['stage'] = FALSE;
    		$content['table_stage'] = $content['table'];
    	}

    	if (!$content['primary_key'] || !$content['table'])
    	{
      	show_error($this->CI->lang->_trans('Primary key or table not defined for the content type %n.', array('n' => '['.$safe_filename.']')), 500, _('XML parser: Error'));
    	}

    	//Parent types
    	if (isset($node->parents))
    	{
      		$parent_types = array();
      		foreach ($node->parents->type as $parent_type)
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

        //Relations
        if (isset($node->relation))
        {
            $content['relations'] = array();
            foreach ($node->relation as $node_relation)
            {
                $attr = $node_relation->attributes();

                $relation = array(
                    'type'  => (string)$attr->type,
                    'with'  => (string)$attr->with,
                    'from'  => (string)$attr->from,
                    'to'    => (string)$attr->to,
                );

                $content['relations'][(string)$attr->name] = $relation;   
            }
        }

    	$content['fieldsets'] = array();

    	//The XML column, is always present on each type, but it is not on the scheme
    	$content['columns'] = array('xml');

    	//True when the type has at leasts one images/files field.
    	$content['has_attachments'] = FALSE;

    	foreach ($node->fieldset as $fieldset_node)
    	{
      		$fieldset_attr = $fieldset_node->attributes();

            if (isset($fieldset_attr->name))
            {
                $fieldset_name = convert_accented_characters(trim((string)$fieldset_attr->name));
            } else {
                $fieldset_name = _('Untitled');
            }

            //We add the fieldset name to the localized labels
            $this->_translations[$fieldset_name] = TRUE;

            
            //Fieldset name is needed
      		if ($fieldset_name == '')
      		{
        		show_error($this->CI->lang->_trans('One of the fieldsets of type %n does not have the name attribute (mandatory).', array('n' => '['.$safe_filename.']')), 500, _('XML parser: Error'));
      		} else if (array_key_exists($fieldset_name, $content['fieldsets'])) {
        		show_error($this->CI->lang->_trans('The type %t has more than one fieldset named %n.', array('t' => '['.$safe_filename.']', 'n' => '['.$fieldset_name.']')), 500, _('XML parser: Error'));
      		}

      		$fieldset = array('name' => $fieldset_name, 'fields' => array());

            if (isset($fieldset_attr->icon))
            {
                $fieldset['icon'] = (string)$fieldset_attr->icon;
            }

      		foreach ($fieldset_node->field as $field)
      		{
        		//Unique name
            	$attr = $field->attributes();
        		$field_name = (string) $attr->id;
        		if (!$field_name || $field_name == '') {
          			show_error($this->CI->lang->_trans('One of the fields of type %t does not have a name.', array('t' => '['.$safe_filename.']')), 500, _('XML parser: Error'));
        		}

        		//Physical column
        		$is_column = isset($attr->column) ? (string) $attr->column : FALSE;
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
          			show_error($this->CI->lang->_trans('The field name %n is reserved (Type: %t) and needs to be changed!', array('t' => '['.$safe_filename.']', 'n' => '['.$field_name.']')), 500, _('XML parser: Error'));
        		}

        		if (!in_array((string)$field->type, $field_usable_inputs))
        		{
          			show_error($this->CI->lang->_trans('The value of the node named type (field: %n, type %t) does not exists. Allowed values are:', array('n' => $field_name, 't' => $safe_filename, 'v' => ' '.implode(', ', $field_usable_inputs))), 500, _('XML parser: Error'));
        		}

        		$_note = FALSE;
        		if (isset($field->description))
        		{
        			$_descr = convert_accented_characters((string)$field->description);
        			$this->_translations[$_descr] = TRUE;
        			$descr_attrs = $field->description->attributes();
        			if (isset($descr_attrs->note))
        			{
        				$_note = (string)$descr_attrs->note;
        				$this->_translations[$_note] = TRUE;
        			}
        		} else {
        			$_descr = $field_name;
        		}

        		//Default fields for each field
        		$content_field = array(
          			'description'	=> $_descr,
          			'note'			=> $_note,
          			'type'			=> (string) $field->type,
          			'length'		=> isset($field->length) ? (int)$field->length : 255,
          			'mandatory'		=> isset($field->mandatory) ? (strtoupper($field->mandatory) == 'TRUE' ? TRUE : FALSE) : FALSE,
          			'admin'			=> isset($field->admin) ? (strtoupper($field->admin) == 'TRUE' ? TRUE : FALSE) : FALSE,
          			'list'			=> isset($field->list) ? (strtoupper($field->list) == 'TRUE' ? TRUE : FALSE) : FALSE,
         			'visible'		=> isset($field->visible) ? (strtoupper($field->visible) == 'TRUE' ? TRUE : FALSE) : TRUE,
          			'default'		=> isset($field->default) ? (string)$field->default : ''
        		);

        		if (isset($field->rules))
        		{
        			$content_field['rules'] = (string) $field->rules;
        		}

        		if ($content_field['type'] == 'files' || $content_field['type'] == 'images')
        		{
          			$content['has_attachments'] = TRUE;
        		}

        		//Custom kind (needed to specify a particular kind of column on the DB)
        		$custom_kind = isset($attr->kind) ? strtolower((string) $attr->kind) : FALSE;
        		if ($custom_kind)
        		{
        			$content_field['kind'] = $custom_kind;
        		}

      			if ($content_field['type'] == 'images')
        		{
        			$content_field['presets'] = array();

          			$content_field['original'] = isset($field->original) ? (strtoupper($field->original) == 'TRUE' ? TRUE : FALSE) : FALSE;
          			$content_field['resized'] = isset($field->resized) ? (string)$field->resized : FALSE;
          			$content_field['thumbnail'] = isset($field->thumbnail) ? (string)$field->thumbnail : FALSE;

          			if ($content_field['thumbnail'] != FALSE && $preset = (string)$field->thumbnail->attributes()->preset)
          			{
          				$content_field['presets']['thumbnail'] = $preset;
          			}
        		}

        		if ($content_field['type'] == 'images' || $content_field['type'] == 'files')
        		{
          			$content_field['size'] = isset($field->size) ? (int)$field->size : 102400; //max 100mb
          			$content_field['mimes'] = isset($field->mimes) ? (string)$field->mimes : '*';
          			$content_field['max'] = isset($field->max) ? (int)$field->max : 10; //max 10 files
                    $content_field['encrypt_name'] = isset($field->encrypt_name) ? (strtoupper($field->encrypt_name) == 'TRUE' ? TRUE : FALSE) : FALSE;
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
              				$opt = (string)$option;
                      $options[ (string)$option->attributes()->value ] = $opt;
                      $this->_translations[$opt] = TRUE;
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

    	//Orderby condition
    	if (isset($node->order_by)) {
    		$orderby_attr = $node->order_by->attributes();

    		$orderby_sort = strtoupper((string)$orderby_attr->sort);
    		if ($orderby_sort != 'ASC' && $orderby_sort != 'DESC')
    		{
    			$orderby_sort = 'DESC';
    		}

    		$orderby_field = (string)$orderby_attr->field;
    		if (!in_array($orderby_field, $content['columns'])) {
    			$orderby_field = 'date_update';
    		}

    		$content['order_by'] = array(
    			'field' => (string) $orderby_field,
    			'sort'	=> (string) $orderby_sort
			);
    	} else {
    		$content['order_by'] = array(
    			'field' => 'date_update',
    			'sort'	=> 'DESC'
			);
    	}

    	return $content;
  	}

    public function update_translations()
    {
        if (count($this->_translations))
        {
            $str = '//These translations are used to update the .po files and are generated automatically by the XML parser' . "\n<?php";
            foreach (array_keys($this->_translations) as $key)
            {
                if ($key != '')
                {
                    $str.= "\n_('" . str_replace("'", "\'", $key) . "');";
                }
            }
            write_file($this->CI->config->item('xml_translations_path'), $str);
        }
    }
}
