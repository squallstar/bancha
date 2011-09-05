<?php
/**
 * Installer Class
 *
 * Libreria per installare Milk
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Installer
{
	/**
	 * @var mixed Istanza di CodeIgniter
	 */
	private $CI;

	/**
	 * @var mixed Reference all'oggetto dbforge di CodeIgniter
	 */
	private $dbforge;

	/**
	 * @var mixed Reference al model_users
	 */
	private $users;

	/**
	 * @var int Contiene l'id del gruppo creato come default (Administrators)
	 */
	public $group_id;

	public function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->dbforge();
		$this->CI->load->users();

		$this->CI->load->helper('directories');
		$this->CI->load->helper('directory');

		$this->dbforge = & $this->CI->dbforge;
		$this->users = & $this->CI->users;
	}

	/**
	 * Crea le tabelle su database
	 */
	public function create_tables()
	{
		//Creazione tabelle records
		$record_fields = array(
            'id_record'		=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'date_insert'	=> array('type'	=> 'INT'),
            'date_update'	=> array('type'	=> 'INT'),
            'date_publish'	=> array('type'	=> 'INT'),
            'id_type'		=> array('type'	=> 'INT', 'null' => FALSE),
            'lang'			=> array('type' => 'VARCHAR', 'null' => TRUE, 'constraint' => '2'),
            'xml'			=> array('type'	=> 'TEXT', 'null' => FALSE),
            'uri'			=> array('type'	=> 'VARCHAR', 'constraint'	=> 255),
            'id_parent'		=> array('type'	=> 'INT', 'null' => TRUE, 'unsigned' => TRUE),
            'title'			=> array('type'	=> 'VARCHAR', 'constraint'	=> 255),
            'show_in_menu'	=> array('type'	=> 'VARCHAR', 'constraint'=> '1'),
            'published'		=> array('type' => 'INT', 'unsigned' => TRUE, 'null' => FALSE, 'default' => 0, 'constraint' => 1),
			'priority'		=> array('type'	=> 'INT', 'unsigned' => TRUE, 'default' => 0, 'constraint' => 2)
		);

		$this->dbforge->drop_table('records_stage');
		$this->dbforge->add_field($record_fields);
		$this->dbforge->add_key('id_record', TRUE);
		$this->dbforge->create_table('records_stage');

		$record_fields['id_record']['auto_increment'] = FALSE;
		unset($record_fields['published']);

		$this->dbforge->drop_table('records');
		$this->dbforge->add_field($record_fields);
		$this->dbforge->add_key('id_record', TRUE);
		$this->dbforge->create_table('records');

		//Creazione tabelle pages
		$page_fields = array(
		    'id_record'		=> array('type'	=> 'INT', 'unsigned' => TRUE),
		    'date_publish'	=> array('type'	=> 'INT'),
		    'id_type'		=> array('type'	=> 'INT', 'null' => FALSE),
            'lang'			=> array('type' => 'VARCHAR', 'null' => TRUE, 'constraint' => '2'),
		    'uri'			=> array('type'	=> 'VARCHAR', 'constraint'	=> 255),
		    'full_uri'		=> array('type'	=> 'VARCHAR', 'constraint'	=> 255),
		    'id_parent'		=> array('type'	=> 'INT', 'null' => TRUE, 'unsigned'	=> TRUE),
		    'title'			=> array('type'	=> 'VARCHAR', 'constraint'	=> 255),
		    'show_in_menu'	=> array('type'	=> 'VARCHAR', 'constraint'=> '1'),
			'priority'		=> array('type'	=> 'INT', 'unsigned' => TRUE, 'default' => 0, 'constraint' => 2)
		);

		$this->dbforge->drop_table('pages');
		$this->dbforge->add_field($page_fields);
		$this->dbforge->add_key('id_record', TRUE);
		$this->dbforge->create_table('pages');

		$this->dbforge->drop_table('pages_stage');
		$this->dbforge->add_field($page_fields);
		$this->dbforge->add_key('id_record', TRUE);
		$this->dbforge->create_table('pages_stage');

		//Creazione tabella types
		$types_fields = array(
		    'id_type'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'name'		=> array('type'	=> 'VARCHAR', 'constraint'	=> 255)
		);

		$this->dbforge->drop_table('types');
		$this->dbforge->add_field($types_fields);
		$this->dbforge->add_key('id_type', TRUE);
		$this->dbforge->create_table('types');

		//Creazione tabella utenti
		$user_fields = array(
		    'id_user'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'id_group'	=> array('type'	=> 'INT', 'unsigned'	=> TRUE, 'constraint' => 3, 'null' => FALSE),
		    'username'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 64, 'null' => FALSE),
		    'password'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 64, 'null' => FALSE),
		    'name'		=> array('type'	=> 'VARCHAR', 'constraint'	=> 64),
		    'surname'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 64),
		    'email'		=> array('type'	=> 'VARCHAR', 'constraint'	=> 255)
		);

		$this->dbforge->drop_table('users');
		$this->dbforge->add_field($user_fields);
		$this->dbforge->add_key('id_user', TRUE);
		$this->dbforge->create_table('users');

		//Creazione tabella acl
		$acl_fields = array(
		    'id_acl'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'acl_name'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 64, 'null' => FALSE),
		    'area'		=> array('type'	=> 'VARCHAR', 'constraint'	=> 64, 'null' => FALSE),
		    'action'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 64, 'null' => FALSE)
		);

		$this->dbforge->drop_table('acl');
		$this->dbforge->add_field($acl_fields);
		$this->dbforge->add_key('id_acl', TRUE);
		$this->dbforge->create_table('acl');

		//Creazione tabella groups
		$group_fields = array(
		    'id_group'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'group_name'=> array('type'	=> 'VARCHAR', 'constraint'	=> 64, 'null' => FALSE)
		);

		$this->dbforge->drop_table('groups');
		$this->dbforge->add_field($group_fields);
		$this->dbforge->add_key('id_group', TRUE);
		$this->dbforge->create_table('groups');

		//Creazione tabella groups acl
		$group_acl_fields = array(
		    'id_group_acl'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'group_id'		=> array('type'	=> 'INT', 'null' => FALSE),
		    'acl_id'		=> array('type'	=> 'INT', 'null' => FALSE)
		);

		$this->dbforge->drop_table('groups_acl');
		$this->dbforge->add_field($group_acl_fields);
		$this->dbforge->add_key('id_group_acl', TRUE);
		$this->dbforge->create_table('groups_acl');

		//Creazione tabella categories
		$categories_fields = array(
		    'id_category'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'category_name'	=> array('type'	=> 'VARCHAR', 'constraint' => 64, 'null' => FALSE),
		    'id_type'		=> array('type'	=> 'INT', 'null' => FALSE)
		);

		$this->dbforge->drop_table('categories');
		$this->dbforge->add_field($categories_fields);
		$this->dbforge->add_key('id_category', TRUE);
		$this->dbforge->create_table('categories');

		//Creazione tabelle documents
		$documents_fields = array(
		    'id_document'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'date_upload'	=> array('type'	=> 'INT', 'null' => TRUE),
		    'name'			=> array('type'	=> 'VARCHAR', 'constraint'	=> 255, 'null' => FALSE),
		    'path'			=> array('type'	=> 'VARCHAR', 'constraint'	=> 255, 'null' => FALSE),
		    'alt_text'		=> array('type'	=> 'VARCHAR', 'constraint'	=> 255, 'null' => TRUE),
		    'resized_path'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 255, 'null' => TRUE),
		    'thumb_path'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 255, 'null' => TRUE),
		    'size'			=> array('type'	=> 'INT', 'null' => FALSE),
		    'bind_table'	=> array('type'	=> 'VARCHAR', 'constraint'=> '32', 'null' => TRUE),
		    'bind_id'		=> array('type'	=> 'INT', 'null' => TRUE),
		    'bind_field'	=> array('type'	=> 'VARCHAR', 'constraint'=> '32', 'null' => TRUE),
		    'width'			=> array('type'	=> 'VARCHAR', 'null' => TRUE, 'constraint' => 5),
		    'height'		=> array('type'	=> 'VARCHAR', 'null' => TRUE, 'constraint' => 5),
		    'mime'			=> array('type'	=> 'VARCHAR', 'null' => FALSE, 'constraint' => 32),
		    'priority'		=> array('type'	=> 'INT', 'constraint' => 4, 'null' => TRUE, 'default' => 0),
		);

		$this->dbforge->drop_table('documents_stage');
		$this->dbforge->add_field($documents_fields);
		$this->dbforge->add_key('id_document', TRUE);
		$this->dbforge->create_table('documents_stage');

		$documents_fields['id_document']['auto_increment'] = FALSE;

		$this->dbforge->drop_table('documents');
		$this->dbforge->add_field($documents_fields);
		$this->dbforge->add_key('id_document', TRUE);
		$this->dbforge->create_table('documents');

		//Creazione tabella events
		$event_fields = array(
		    'id_event'		=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'user_id'		=> array('type'	=> 'INT', 'null' => TRUE, 'unsigned' => TRUE),
		    'event_date'	=> array('type'	=> 'INT', 'null' => FALSE, 'unsigned' => TRUE),
		    'event'			=> array('type'	=> 'VARCHAR', 'constraint'	=> 32, 'null' => FALSE),
		    'content_id'	=> array('type'	=> 'INT', 'null' => TRUE),
		    'content_name'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 128, 'null' => TRUE),
		    'content_type'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 128, 'null' => TRUE)
		);

		$this->dbforge->drop_table('events');
		$this->dbforge->add_field($event_fields);
		$this->dbforge->add_key('id_event', TRUE);
		$this->dbforge->create_table('events');

		//Creazione tabella record categories
		$record_categories_fields = array(
		    'id_record'		=> array('type'	=> 'INT', 'null' => FALSE, 'unsigned' => TRUE),
		    'id_category'	=> array('type'	=> 'INT', 'null' => FALSE, 'unsigned' => TRUE)
		);

		$this->dbforge->drop_table('record_categories');
		$this->dbforge->add_field($record_categories_fields);
		$this->dbforge->create_table('record_categories');

		return TRUE;
	}

	/**
	 * Crea i gruppi + acl di default
	 */
	public function create_groups()
	{
		//Inserisco le ACL di default
		$acls = array();
		$acls[]= $this->users->add_acl('users', 'list', 'Lista utenti');
		$acls[]= $this->users->add_acl('users', 'add', 'Aggiunta utenti');
		$acls[]= $this->users->add_acl('types', 'add', 'Aggiunta tipi di contenuto');
		$acls[]= $this->users->add_acl('types', 'manage', 'Modifica schema tipo di contenuto');
		$acls[]= $this->users->add_acl('types', 'delete', 'Eliminazione tipi di contenuto');

		$this->group_id = $this->users->add_group('Amministratore');
		$this->users->add_group('Editor');

		$this->CI->auth->update_permissions($acls, $this->group_id);

	}

	/**
	 * Crea un utente
	 * @param string $username
	 * @param string $password
	 * @param string $name
	 * @param string $surname
	 * @param string $email
	 */
	public function create_user($username, $password, $name, $surname, $email = 'admin@example.org')
	{
		$data = array(
			'name' => $name,
			'surname' => $surname,
			'email' => $email,
			'username' => $username,
			'password' => $password,
			'id_group' => $this->group_id
		);
		return $this->users->add_user($data);
	}

	/**
	 * Crea i tipi di contenuto di default
	 */
	public function create_types()
	{
		$default = $this->CI->config->item('default_tree_types');
		if (count($default))
		{
			foreach ($default as $type)
			{
				$this->CI->content->add_type($type, $type, 'true', TRUE);
			}
		} else {
			show_error(_('Default content type not defined'));
		}
		$this->CI->content->rebuild();
	}

	/**
	 * Crea gli indici delle tabelle su DB
	 */
	public function create_indexes()
	{
		$indexes = array(
			'records'			=> 'id_type',
			'records'			=> 'date_publish',
			'records'			=> 'lang',
			//'records_stage'		=> 'id_type',
			'pages'				=> 'id_type',
			'pages'				=> 'full_uri',
			//'pages_stage'		=> 'id_type',
			//'pages_stage'		=> 'full_uri',
			'categories'		=> 'category_name',
			'documents'			=> 'bind_id',
			//'documents_stage'	=> 'bind_id',
			'record_categories'	=> 'id_category'
		);

		//TODO: correggere gli indici duplicati come nome (commentati sopra)

		foreach ($indexes as $table => $column)
		{
			//In futuro, metterlo nel db forge per differenziarlo tra ogni db
			$sql = 'CREATE INDEX idx_'.$column.' ON '.$this->CI->db->dbprefix.$table.' ('.$column.');';
			$this->CI->db->query($sql);
		}

	}

	/**
	 * Elimina e ricrea le directory base
	 */
	public function create_directories()
	{
		$directories = array(
			$this->CI->config->item('attach_folder'),					//Attachs directory
			$this->CI->config->item('xml_folder'),						//XML Types schemes
			$this->CI->config->item('views_absolute_templates_folder'),	//XML Views,
			$this->CI->config->item('fr_cache_folder')					//Path file di cache
		);

		foreach ($directories as $dir)
		{
			delete_directory($dir);
			mkdir($dir, DIR_WRITE_MODE);
		}
	}

	/**
	 * Crea una installazione premade, ad esempio per un blog
	 * @param string $type Tipo di installazione
	 */
	public function create_premade($type = '')
	{
		if ($type == '')
		{
			return;
		}

		$folder = $this->CI->config->item('templates_folder').'premades'.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR;

		$premades_xml = array();
		
		if (file_exists($folder))
		{
			$premades_xml = get_filenames($folder);
			if (count($premades_xml))
			{
				foreach ($premades_xml as $file_name)
				{
					$name = str_replace('.xml', '', $file_name);
					$type_id = $this->CI->content->add_type($name, $name, 'false', TRUE);
					$this->copy_premade_xml($folder.$file_name, $name, $type_id);
				}
			}
		}
		
		//Svuoto e ricarico la cache dei tipi
		$this->CI->content->rebuild();
		$this->CI->content->read();

		switch (strtolower($type))
		{
			case 'blog':
				//Aggiungo le colonne che mi servono
				$fields = array(
					'post_id' => array('type' => 'INT', 'null' => TRUE)
				);
				$this->dbforge->add_column('records', $fields);
				$this->dbforge->add_column('records_stage', $fields);
				
				//Creo un post			
				$post = new Record('Blog');
				$post->set('title', _('My first post'))
					 ->set('contenuto', _('Hello world'))
					 ->set('lang', $this->CI->lang->current_language)
					 ->set('date_publish', time())
				;
				$this->CI->records->save($post);

				//Creo una pagina di vista lista contenuti
				$page = new Record('Menu');
				$page->set('title', 'Blog')
					 ->set('action', 'list')
					 ->set('lang', $this->CI->lang->current_language)
					 ->set('show_in_menu', 'T')
					 ->set('action_list_type', $this->CI->content->type_id('Blog'))
				;
				$this->CI->records->save($page);

				break;
				
			case 'default':
				//Creo una pagina dimostrativa
				$page = new Record('Menu');
				$page->set('title', 'My first page')
				->set('action', 'text')
				->set('lang', $this->CI->lang->current_language)
				->set('show_in_menu', 'T')
				->set('contenuto', _('Hello world'))
				;
				$this->CI->records->save($page);
				
				break;
		}
		
		//Pulisco la cache di questo tipo di albero
		$this->CI->tree->clear_cache('Menu');
	}

	public function copy_premade_xml($path, $type_name, $type_id)
	{
		$xml = read_file($path);

		//Parso il file con le pseudovariabili
		$xml = $this->CI->parser->parse_string($xml, array(
		          'id'			=> $type_id,
		          'version'		=> MILK_VERSION
		),TRUE);

		$storage_path = $this->CI->config->item('xml_folder').$type_name.'.xml';
		return write_file($storage_path, $xml);
	}
}