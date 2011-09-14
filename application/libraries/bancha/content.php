<?php
/**
 * Content Library Class
 *
 * This library manage the content types of the website and lets you read and rebuild them.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Content {

	/**
	 * @var array Content types list
	 */
	private $_content_types;

	/**
	 * @var array List of all content types names
	 */
	private $_string_types;

	/**
	 * @var mixed Code Igniter instance
	 */
	private $CI;

	/**
	 * @var string This directory contains the XML schemes
	 */
	public $xml_folder;

	/**
	 * @var string This is the file that caches the content types
	 */
	public $types_cache_folder;

	/**
	 * @var bool Defines if we are in stage
	 */
	public $is_stage = FALSE;

	public function __construct()
	{
		$this->CI = & get_instance();

		$this->xml_folder	= $this->CI->config->item('xml_folder');
		$this->types_cache_folder	= $this->CI->config->item('types_cache_folder');

		//We read the content types
		$this->read();
	}

	/**
	 * Sets the current operations to stage or production
	 * @param boolean $bool
	 */
	public function set_stage($stage)
	{
		$this->is_stage = $stage;
		if (isset($this->CI->records))
		{
			$this->CI->records->set_stage($stage);
		}
		if (isset($this->CI->pages))
		{
			$this->CI->pages->set_stage($stage);
		}
	}

	/**
	 * Reads the XML schemes from the cache.
	 * It will also create the cache file if it not exists
	 */
	public function read()
	{
		if (!file_exists($this->types_cache_folder))
		{
			@mkdir($this->CI->config->item('fr_cache_folder'), DIR_WRITE_MODE, TRUE);
			$this->rebuild();
		}
		$this->_content_types = unserialize(file_get_contents($this->types_cache_folder));
		foreach ($this->_content_types as $key => $val)
		{
			$this->_string_types[$val['name']] = $key;
		}
	}

	/**
	 * Adds a content type to the DB
	 * @param string $type_name
	 * @param string $type_description
	 * @param bool $type_structure True when pages, False when contents
	 * @return int Type id (autoincrement)
	 */
	public function add_type($type_name, $type_description, $type_structure, $delete_if_exists=FALSE)
	{
		$this->CI->load->library('parser');

		//We clears the type name
		$type_name = url_title(convert_accented_characters($type_name), 'underscore');

		if ($type_name == 'cache')
		{
			//Cached images uses the "cache" name for their attach folder
			show_error(_('You cannot create a content type named [cache].'), 500, _('Name reserved'));
		}

		//Let's check if already exists on filesystem
		$storage_path = $this->CI->config->item('xml_folder').$type_name.'.xml';
		if (file_exists($storage_path) && !$delete_if_exists) {
			show_error(
				$this->CI->lang->_trans('A content type named %n already exists', array('n' => '['.$type_name.']')),
				500, _('Cannot create that type'));
		} else {
			$this->delete_type($type_name);
		}

		//Saves the content type into the DB
		$done = $this->CI->db->insert('types', array(
			'name'	=> $type_name
		));
		if (!$done)
		{
			show_error(_('Cannot insert that content type.') . ' (content/add_type)', 500, _('Error'));
		}
		$type_id = $this->CI->db->insert_id();

		//Loads the base XML (Type_simple of Type_tree)
		if (is_bool($type_structure))
		{
			$type_complexity = $type_structure;
		} else {
			$type_complexity = strtolower($type_structure) == 'true' ? 'tree' : 'simple';
		}
		$xml = read_file($this->CI->config->item('templates_folder').'Type_'.$type_complexity.'.xml');

		$type_description = strip_tags($type_description);
		if (!$type_description)
		{
			$type_description = $type_name;
		}

		//Parses the base file with the content types variables
		$xml = $this->CI->parser->parse_string($xml, array(
		          'id'			=> $type_id,
		          'name'		=> $type_name,
		          'description'	=> $type_description,
		          'version'		=> BANCHA_VERSION
		),TRUE);


		//Saves the XML scheme
		if (write_file($storage_path, $xml)) {

			//We add the ACL for this content type
			$this->CI->load->users();
			$acl_id = $this->CI->users->add_acl('content', $type_name, 'Manage ' . $type_name);

			//Permissions to the current user
			$this->CI->auth->add_permission($acl_id);
			$this->CI->auth->cache_permissions();

			//We create the directory with the view templates
			$type_view_abs_dir = $this->CI->config->item('views_absolute_templates_folder') . $type_name . '/';
			$this->CI->load->helper('directories');

			if (!delete_directory($type_view_abs_dir)) {
				$this->delete_type($type_name);
				show_error(_('Cannot delete the template view directory for the content type %n.', array('n' => '['.$type_name.']'),
				500, _('Error')));
			}
			$created = mkdir($type_view_abs_dir, DIR_WRITE_MODE);

			if ($created) {
				//Copio anche i templates di default
				$templates_to_copy = $this->CI->config->item('view_templates_to_copy');
				foreach ($templates_to_copy as $template_name) {
					$template_content = read_file($this->CI->config->item('templates_folder') .
		        				 'type_views/' . $template_name . '.php');
					$template_destination = $type_view_abs_dir . $template_name . '.php';

					$template_content = $this->CI->parser->parse_string($template_content, array(
		        				'name'	=> $type_name
					),TRUE);

					write_file($template_destination, $template_content);
				}

			} else {
				$this->delete_type($type_name);
				show_error('Impossibile creare la directory di template per le view del tipo ['.$type_name.'].', 500, 'Errore di scrittura');
			}

			//Rinnovo la cache
			$this->rebuild();

		}else {
			$this->delete_type($type_name);
			show_error('Impossibile scrivere il file ['.$type_name.'.xml] nella directory dei tipi.', 500, 'Errore di scrittura');
		}

		return $type_id;

	}

	/**
	 * Rimuove un tipo dal DB
	 * @param string $name
	 * @return bool
	 */
	public function delete_type($name)
	{
		return $this->CI->db->where('name', $name)->delete('types');
	}

	/**
	 * Restituisce lo schema di un tipo di contenuto
	 * E' possibile chiamare la funzione sia passando l'ID che il nome del tipo
	 * @param int|string $type
	 */
	public function type($type='')
	{
		if ($type!='')
		{
			//Controllo se mi viene richiesto il tipo da un numero o stringa
			if (!is_numeric($type))
			{
				foreach ($this->_content_types as $key => $val)
				{
					if ($val['name'] == $type) {
						return $this->_content_types[$key];
					}
				}
			} else {
				if (isset($this->_content_types[$type]))
				{
					return $this->_content_types[$type];
				}
			}

			log_message('error', 'Il tipo di contenuto ['.$type.'] non esiste. (content/type)', 500, 'Tipo di contenuto non trovato');

		} else {
			log_message('error', 'Tipo ['.$type.'] non trovato. (content/type)', 500, 'Tipo non trovato');
		}
	}

	/**
	 * Restituisce l'id di un tipo dato il suo nome
	 * @param string $type_string
	 */
	public function type_id($type_string)
	{
		if (isset($this->_string_types[$type_string]))
		{
			return $this->_string_types[$type_string];
		}
		return 0;
	}

	/**
	 * Restituisce il nome di un tipo dato il suo nome
	 * @param int $type_id
	 */
	public function type_name($type_id)
	{
		if (isset($this->_content_types[$type_id]))
		{
			$tipo = & $this->_content_types[$type_id];
			return $tipo['name'];
		} else {
			show_error('Tipo ['.$type_id.'] non trovato. (content/type_name)', 500, 'Tipo non trovato');
		}
	}

	/**
	 * Returns all the content types schemes
	 * @return array
	 */
	public function types()
	{
		return $this->_content_types;
	}

	/**
	 * Rebuilds the content types cache
	 * @return bool success
	 */
	public function rebuild()
	{
		//All types
		$this->CI->load->helper(array('file', 'text'));

		$filenames = get_filenames($this->xml_folder);

		//Restricted names
		$restricted_names = $this->CI->config->item('restricted_field_names');

		//Will contains all types
		$contents = array();

		$all_types = array();
		$all_types_id = array();

		if (!isset($this->CI->xml))
		{
			$this->CI->load->frlibrary('xml');
		}

		foreach ($filenames as $filename)
		{
			$content = $this->CI->xml->parse_file($this->xml_folder . $filename);

			$all_types_id[] = $content['id'];
			$all_types = $content['name'];

			//Aggiungo il tipo
			$contents[$content['id']] = $content;
		}

		if ($this->CI->config->item('delete_dead_records') == TRUE)
		{
			//Elimino i dead records
			$this->CI->load->records();
			//TODO: modifica per fare il delete in base alla tabella del tipo
			$this->CI->db->where_not_in('id_type', $all_types_id)->delete($this->CI->records->table_stage);
			$this->CI->db->where_not_in('id_type', $all_types_id)->delete($this->CI->records->table);
		}

		//Scrivo in cache i tipi
		$done = write_file($this->types_cache_folder, serialize($contents));

		//Pulisco il menu principale del sito
		if (isset($this->CI->tree))
		{
			$this->CI->tree->clear_cache();
		}

		$this->CI->xml->update_translations();

		return $done;
	}

	/**
	 * Makes a Record
	 * @param int|string $type
	 * @param array $recordData
	 * @return Record
	 */
	function make_record($type='', $recordData='')
	{
		$record = new Record($type);

		if ($recordData != '')
		{
			$record->set_data($recordData);
		}
		return $record;
	}

	/**
	 * FUNZIONE NON ANCORA IMPLEMENTATA
	 * In futuro verra' implementata la creazione automatica
	 * delle tabelle dei tipi su tabelle esterne
	 * @param int|string $type
	 */
	function build_type_table($type)
	{
		//Blah blah blash
	}



}