<?php
/**
 * Xml Library Class
 *
 * Libreria/Helper per lavorare con gli XML
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
  private $CI;

  public $xml_folder;
  public $types_cache_folder;

  public function __construct()
  {
    $this->CI = & get_instance();

    $this->xml_folder	= $this->CI->config->item('xml_folder');
    $this->types_cache_folder	= $this->CI->config->item('types_cache_folder');

  }

  /**
  * Costruisce l'xml di un record dato il suo tipo di contenuto e i campi da popolare
  * @param int|string $type
  * @param array $data
  * @return string xml
  */
  function get_record_xml($type='', $data)
  {
    if ($type != '') {

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
              foreach ($value as $val) {
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
      show_error('Tipo non settato (get_record_xml)');
    }
  }

  /**
   * Ottiene il risultato di una query creata partendo da un nodo SQL (estratto da un file xml)
   * @param SimpleXMLElement $sql
   * @param int $id_type
   * @return array
   */
  function records_from_sql_xml($sql, $id_type = '')
  {
    $this->CI->db->select((string)$sql->select);

    $tipo = $this->CI->content->type($id_type);

    //Uso la tabella di staging se sono in staging
    $from_tbl = (string)$sql->from;
    if ($this->CI->content->is_stage)
    {
    	$from_tbl = str_replace($tipo['table'], $tipo['table_stage'], $from_tbl);
    }

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
   * Effettua il parsing di un file XML e lo converte in Array
   * E' una delle funzioni principali del framework e viene usata principalmente dalla cache dei tipi di contenuto
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

    //Tipi di campi utilizzabili
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
    	$content['table_stage'] = (string) $node->table_stage;
    } else {
    	$content['stage'] = FALSE;
    	$content['table_stage'] = $content['table'];
    }

    if (!$content['primary_key'] || !$content['table'])
    {
      show_error('Chiave primaria o tabella non definita per il tipo ['.$safe_filename.'].');
    }

    //Tipi utilizzabili come parent di questo tipo
    if (isset($node->parent_types))
    {
      $parent_types = array();
      foreach ($node->parent_types->type as $parent_type)
      {
        $parent_types[] = (string) $parent_type;
      }
      $content['parent_types'] = $parent_types;
    }

    $content['fieldsets'] = array();
    $content['columns'] = array('xml');

    //True when the type has at leasts one images/files field.
    $content['has_attachments'] = FALSE;

    foreach ($node->fieldset as $fieldset_node)
    {

      $fieldset_name = isset($fieldset_node->name) ? (string)$fieldset_node->name : _('Untitled');

      if ($fieldset_name == '')
      {
        show_error('Uno dei fieldset del tipo ['.$safe_filename.'] non presenta il campo Nome obbligatorio.', 500, 'Errore XML');
      } else if (array_key_exists($fieldset_name, $content['fieldsets'])) {
        show_error('Il tipo ['.$safe_filename.'] presenta pi&ugrave; di un fieldset con nome ['.$fieldset_name.'].', 500, 'Errore XML');
      }

      $fieldset = array('name' => $fieldset_name, 'fields' => array());

      foreach ($fieldset_node->field as $field)
      {
        //Unique name
        $field_name = (string) $field->attributes()->id;
        if (!$field_name || $field_name == '') {
          show_error('Tipo ['.$safe_filename.']: uno dei campi non presenta il nome (XML).', 500, 'Errore XML');
        }

        //Physical column
        $is_column = isset($field->attributes()->column) ? (string) $field->attributes()->column : FALSE;
        if (strtoupper($is_column) == 'TRUE')
        {
          $content['columns'][] = $field_name;
        }

        //Reserved names check
        if (in_array($field_name, $this->CI->config->item('restricted_field_names')))
        {
          show_error('Tipo ['.$safe_filename.']: Il nome del campo ['.$field_name.'] &egrave; riservato. Utilizzare un altro nome (XML).', 500, 'Uno dei campi &egrave; riservato');
        }

        if (!in_array((string)$field->type, $field_usable_inputs))
        {
          show_error('Tipo ['.$safe_filename.']: Il valore utilizzato nel nodo "type" del campo ['.$field_name.'] non esiste. Valori ammessi: '.implode(', ', $field_usable_inputs).'.', 500, 'Valore sconosciuto');
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
            //Controllo se usa riferimenti custom come sorgente
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


        //Estrazioni SQL per le options
        if (isset($field->sql))
        {
          $sql = $field->sql;

          //Estraggo i record
          $records = $this->records_from_sql_xml($sql, $content['id']);

          //Controllo se la query di estrazione è da cacheare
          $cache = (string) $sql->attributes()->cache;

          if ($cache == 'true')
          {
            //Preparo le options
            foreach ($records as $record)
            {
              $options[$record->value] = $record->name;
            }

            $content_field['options'] = $options;

          } else {
            //Salvo la stringa della query
            $query = str_replace("\n", ' ', $this->CI->db->last_query());
            $content_field['options'] = $query;
            $content_field['extract'] = 'query';
          }
        }

        //Aggiungo ai fields del tipo questo field
        $content['fields'][$field_name] = $content_field;

        //Aggiungo a questo fieldset il campo (solo nome)
        $fieldset['fields'][] = $field_name;
      } //end foreach field


      //Aggiungo un singolo fieldset
      $content['fieldsets'][] = $fieldset;

    } //end foreach fieldsets

    //Aggiungo il tipo
    return $content;
  }

}
