<?php
/**
 * Schemeforge Library class
 *
 * This library creates and keep updated the tables of the content types
 * It will be tipically used by the "Schemes" controller
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Schemeforge
{
	/**
	 * @var mixed Bancha instance
	 */
	private $B;

	public function __construct()
	{
		$this->B = & get_instance();
		$this->B->load->dbforge();
	}

	/**
	 * Returns the optimal Database fields of a table, given the content type
	 * @param array $type
	 * @return array
	 */
	public function get_tablescheme($type)
    {
    	//Primary key
		$table_fields[$type['primary_key']] = array('type' => 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE);

		//Additional Fields
		$table_fields['xml'] = array('type' => 'TEXT', 'null' => TRUE);
		$table_fields['date_update'] = array('type' => 'INT', 'null' => TRUE);

		foreach ($type['columns'] as $field_name)
		{
			if ($field_name == $type['primary_key']
				|| !isset($type['fields'][$field_name]))
			{
				continue;
			}

			$field = $type['fields'][$field_name];

			$tmp = array(
				'type' => 'VARCHAR', 'null' => TRUE, 'constraint'	=> $field['length']
			);
			if (isset($field['kind']))
			{
				switch ($field['kind'])
				{
					case 'numeric':
					case 'number':
					case 'int':
					case 'integer':
						$tmp['type'] = 'INT';
						if ($field['length'] == 255)
						{
							$tmp['constraint'] = 11; //max int length
						}
						break;
					case 'text':
						$tmp['type'] = 'TEXT';
						break;
				}
			} else {
				switch ($field['type'])
				{
					case 'number':
					case 'date':
					case 'datetime':
						$tmp['type'] = 'INT';
						if ($field['length'] == 255)
						{
							$tmp['constraint'] = 11; //max int length
						}
						break;

					case 'textarea':
					case 'textarea_full':
					case 'textarea_code':
						$tmp['type'] = 'TEXT';
						unset($tmp['constraint']);
					
					case 'files':
					case 'images':
						continue;
				}
			}

			$table_fields[$field_name] = $tmp;
		}
		return $table_fields;
    }

    /**
	 * Creates and updates the tables of a content types
	 * @param array $type
	 * @return BOOL
	 */
    public function recreate_by_scheme($type)
    {
    	$DB = & $this->B->db;

    	$table = $type['table'];
    	$fields = $this->get_tablescheme($type);

    	$done = FALSE;

    	if ( ! $DB->table_exists($table) )
		{
			//New table
			$done = $this->_create_table($type, $fields);
			
			//New stage table
			$done = $this->_create_table($type, $fields, TRUE) && $done;	
					
		} else {
			//Table exists! We need to check and update it
			$done = $this->_update_table($type, $fields);
			
			//Also the stage table
			$done = $this->_update_table($type, $fields, TRUE) && $done;
		}
		return $done;
    }

    /**
	 * Updates the table of a content type
	 * @param array $type
	 * @param array $fields (generated with the get_tablescheme private function)
	 * @param bool $stage
	 * @return BOOL
	 */
    private function _update_table($type, $fields, $stage = FALSE)
    {
    	if ($stage && !$type['stage']) return TRUE;

		$FORGE = & $this->B->dbforge;

    	if ($stage && $type['stage'])
		{
			$fields['published'] = array('type' => 'INT', 'null' => TRUE, 'constraint'	=> 1);
			$table = $type['table_stage'];
		} else {
			$table = $type['table'];
			if (isset($fields['published']))
			{
				unset($fields['published']);
			}
		}

    	$scheme = $this->B->db->field_data($table);

    	$existing_fields = array();
    	$new_fields = array();
    	$alter_fields = array();
    	$drop_fields = array();

    	foreach ($scheme as $field)
    	{
    		if (isset($fields[$field->name]))
    		{
    			//Field still exists
    			$existing_fields[$field->name] = $field;
    		} else {
    			//Field has been removed from the scheme
    			//we need to check if this field is used
    			//by other content types
    			$found = FALSE;
    			$all_types = & $this->B->content->types();
    			foreach ($all_types as $ctype)
    			{
    				$tbl = $stage ? $ctype['table_stage'] : $ctype['table'];
    				if ($tbl !== $table || $ctype['id'] == $type['id'])
    				{
    					continue;
    				}
    				
    				if (in_array($field->name, $ctype['columns']))
    				{
    					$found = TRUE;
    				}
    			}

    			if (!$found)
    			{
	    			$FORGE->drop_column($table, $field->name);
	    		}
    		}
    	}

    	foreach ($fields as $field_name => $field)
    	{
    		if (!isset($existing_fields[$field_name]))
    		{
    			//New field
    			$new_fields[$field_name] = $field;
    		} else {

    			$col = $existing_fields[$field_name];

    			if (

    				// 1st condition
	    			strtolower($col->type) !== strtolower($field['type'])
    				
    				// 2nd condition
    				|| (isset($field['constraint']) && isset($col->max_length)
    					&& $field['constraint'] != $col->max_length)

    				// 3rd condition
    				|| (isset($field['constraint']) && !isset($col->max_length))
    			)
    			{

	    			//Alter field
	    			$field['name'] = $field_name;
	    			$alter_fields[$field_name] = $field;
    			}
    		}
    	}
    	
    	if (count($new_fields))
    	{
    		$FORGE->add_column($table, $new_fields);
    	}

    	if (count($alter_fields))
    	{
    		$FORGE->modify_column($table, $alter_fields);
    	}
    	return TRUE;
    }

    /**
	 * Creates the table of a content type
	 * @param array $type
	 * @param array $fields (generated with the get_tablescheme private function)
	 * @param bool $stage
	 * @return BOOL
	 */
    private function _create_table($type, $fields, $stage = FALSE)
    {
    	if ($stage && !$type['stage']) return TRUE;

    	$FORGE = & $this->B->dbforge;
		$FORGE->add_key($type['primary_key'], TRUE);

		if (isset($type['fields']['id_type']))
		{
			$FORGE->add_foreign_key('id_type', 'types', 'id_type');	
		}

		if ($stage && $type['stage'])
		{
			$fields['published'] = array('type' => 'INT', 'null' => TRUE, 'constraint'	=> 1);
			$table = $type['table_stage'];
		} else {
			$table = $type['table'];
			if (isset($fields['published']))
			{
				unset($fields['published']);
			}
		}

		$FORGE->add_field($fields);

		return $FORGE->create_table($table);
    }
}