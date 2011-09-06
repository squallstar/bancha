<?php
/**
 * Content Library Class
 *
 * Gestione di contenuti, classe di utilità generale
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Content {

	/**
	 * @var array Elenco dei tipi
	 */
	private $_content_types;

	/**
	 * @var array Elenco dei nomi dei tipi
	 */
	private $_string_types;

	/**
	 * @var mixed Istanza di CodeIgniter
	 */
	private $CI;

	/**
	 * @var string Directory che contiene gli XML dei tipi
	 */
	public $xml_folder;

	/**
	 * @var string Directory che contiene i files di cache dei tipi
	 */
	public $types_cache_folder;

	/**
	 * @var bool Definisce se siamo in stage
	 */
	public $is_stage = FALSE;

	public function __construct()
	{
		$this->CI = & get_instance();

		$this->xml_folder	= $this->CI->config->item('xml_folder');
		$this->types_cache_folder	= $this->CI->config->item('types_cache_folder');

		//Carica i tipi
		$this->read();
	}

	/**
	 * Imposta se stiamo in stage
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
	 * Legge i tipi XML e li carica in sessione
	 */
	public function read()
	{
		if (!file_exists($this->types_cache_folder))
		{
			$this->rebuild();
		}
		$this->_content_types = unserialize(file_get_contents($this->types_cache_folder));
		foreach ($this->_content_types as $key => $val)
		{
			$this->_string_types[$val['name']] = $key;
		}
	}

	/**
	 * Aggiunge un tipo al DB
	 * @param string $name
	 * @return int Type id (autoincrement)
	 */
	public function add_type($type_name, $type_description, $type_structure, $delete_if_exists=FALSE)
	{

		$this->CI->load->helper(array('file', 'text'));
		$this->CI->load->library('parser');

		//Pulisco il nome del tipo
		$type_name = url_title(convert_accented_characters($type_name), 'underscore');

		//Controllo se esiste
		$storage_path = $this->CI->config->item('xml_folder').$type_name.'.xml';
		if (file_exists($storage_path) && !$delete_if_exists) {
			show_error('Esiste gi&agrave; un tipo denominato ['.$type_name.'].', 500, 'Errore: Impossibile salvare il tipo');
		} else {
			$this->delete_type($type_name);
		}

		//Salvo il tipo su db
		$done = $this->CI->db->insert('types', array(
			'name'	=> $type_name
		));
		if (!$done)
		{
			show_error('Impossibile inserire il tipo. (content/add_type)', 500, 'Tipo non inserito');
		}
		$type_id = $this->CI->db->insert_id();

		//In base al tipo scelto, carico il relativo xml
		$type_complexity = $type_structure == 'true' ? 'tree' : 'simple';

		//Leggo il template di default di un tipo su DB
		$xml = read_file($this->CI->config->item('templates_folder').'Type_'.$type_complexity.'.xml');

		$type_description = strip_tags($type_description);
		if (!$type_description)
		{
			$type_description = $type_name;
		}

		//Parso il file con le pseudovariabili
		$xml = $this->CI->parser->parse_string($xml, array(
		          'id'			=> $type_id,
		          'name'		=> $type_name,
		          'description'	=> $type_description,
		          'version'		=> MILK_VERSION
		),TRUE);


		//Salvo il file xml di definizione dei campi
		if (write_file($storage_path, $xml)) {

			//Aggiungo le acl di questo tipo di contenuto
			$this->CI->load->users();
			$acl_id = $this->CI->users->add_acl('content', $type_name, 'Gestione ' . $type_name);

			//Aggiungo i permessi all'utente corrente
			$this->CI->auth->add_permission($acl_id);
			$this->CI->auth->cache_permissions();

			//Creo la directory con i template di questo tipo
			$type_view_abs_dir = $this->CI->config->item('views_absolute_templates_folder') . $type_name . '/';
			$this->CI->load->helper('directories');

			if (!delete_directory($type_view_abs_dir)) {
				$this->delete_type($type_name);
				show_error('Impossibile eliminare la directory di template per le view del tipo ['.$type_name.'].', 500, 'Errore');
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
	 * Restituisce un tipo
	 * @param int|string $type
	 */
	public function type($type='')
	{
		if ($type!='')
		{
			//Check if a number is given (id_type)
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
	 * Restituisce i tipi
	 * @return array
	 */
	public function types()
	{
		return $this->_content_types;
	}

	/**
	 * Ricostruisce la cache dei tipi
	 * @return bool success
	 */
	public function rebuild()
	{
		//All types
		$this->CI->load->helper('file');
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

		return $done;
	}

	/**
	 * Costruisce un Record
	 * @param int|string $type
	 * @param array $recordData
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
	 * In futuro verra' implementata la creazione automatica
	 * delle tabelle dei tipi su tabelle esterne
	 * @param int|string $type
	 */
	function build_type_table($type)
	{

	}



}