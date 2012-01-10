<?php
/**
 * Installer Class
 *
 * This library let you install the CMS
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Installer
{
	/**
	 * @var mixed Code Igniter instance
	 */
	private $CI;

	/**
	 * @var mixed Reference to the Code Igniter DB Forge
	 */
	private $dbforge;

	/**
	 * @var mixed Reference to the users model
	 */
	private $users;

	/**
	 * @var int The administrator group autoincrement ID
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

	public function is_already_installed()
	{
		return $this->CI->db->table_exists('settings');
	}

	/**
	 * Creates all the database tables
	 */
	public function create_tables()
	{
		//Records and Records_stage tables
		$record_fields = array(
            'id_record'		=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
            'date_insert'	=> array('type'	=> 'INT', 'null' => TRUE),
            'date_update'	=> array('type'	=> 'INT', 'null' => TRUE),
            'date_publish'	=> array('type'	=> 'INT', 'null' => TRUE),
            'id_type'		=> array('type'	=> 'INT', 'null' => FALSE),
            'lang'			=> array('type' => 'VARCHAR', 'null' => TRUE, 'constraint' => '2'),
            'xml'			=> array('type'	=> 'TEXT', 'null' => FALSE),
            'uri'			=> array('type'	=> 'VARCHAR', 'constraint'	=> 255, 'null' => TRUE),
            'id_parent'		=> array('type'	=> 'INT', 'null' => TRUE, 'unsigned' => TRUE),
			'child_count'	=> array('type'	=> 'INT', 'null' => TRUE, 'unsigned' => TRUE, 'default'	=> 0),
            'title'			=> array('type'	=> 'VARCHAR', 'constraint'	=> 255, 'null' => TRUE),
            'show_in_menu'	=> array('type'	=> 'VARCHAR', 'constraint'=> '1', 'null' => TRUE),
            'published'		=> array('type' => 'INT', 'unsigned' => TRUE, 'null' => TRUE, 'default' => 0, 'constraint' => 1),
			'priority'		=> array('type'	=> 'INT', 'unsigned' => TRUE, 'default' => 0, 'constraint' => 3, 'null' => TRUE)
		);

		$this->dbforge->drop_table('records_stage');
		$this->dbforge->add_field($record_fields);
		$this->dbforge->add_key('id_record', TRUE);
		$this->dbforge->add_foreign_key('id_type', 'types', 'id_type');
		$this->dbforge->create_table('records_stage');

		$record_fields['id_record']['auto_increment'] = FALSE;
		unset($record_fields['published']);

		$this->dbforge->drop_table('records');
		$this->dbforge->add_field($record_fields);
		$this->dbforge->add_key('id_record', TRUE);
		$this->dbforge->add_foreign_key('id_type', 'types', 'id_type');
		$this->dbforge->add_foreign_key('id_record', 'records_stage', 'id_record');
		$this->dbforge->create_table('records');

		//Pages and Pages_stage tables
		$page_fields = array(
		    'id_record'		=> array('type'	=> 'INT', 'unsigned' => TRUE),
		    'date_publish'	=> array('type'	=> 'INT'),
		    'id_type'		=> array('type'	=> 'INT', 'null' => FALSE),
            'lang'			=> array('type' => 'VARCHAR', 'null' => TRUE, 'constraint' => 2),
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

		//Cotent types table
		$types_fields = array(
		    'id_type'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'name'		=> array('type'	=> 'VARCHAR', 'constraint'	=> 255)
		);

		$this->dbforge->drop_table('types');
		$this->dbforge->add_field($types_fields);
		$this->dbforge->add_key('id_type', TRUE);
		$this->dbforge->create_table('types');

		//Users table
		$user_fields = array(
		    'id_user'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'date_update'	=> array('type'	=> 'INT'),
            'id_type'		=> array('type'	=> 'INT', 'null' => TRUE),
            'xml'			=> array('type'	=> 'TEXT', 'null' => TRUE),
		    'id_group'	=> array('type'	=> 'INT', 'unsigned'	=> TRUE, 'constraint' => 3, 'null' => FALSE),
		    'username'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 64, 'null' => FALSE),
		    'password'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 64, 'null' => FALSE),
		    'name'		=> array('type'	=> 'VARCHAR', 'constraint'	=> 64),
		    'surname'	=> array('type'	=> 'VARCHAR', 'constraint'	=> 64),
		    'email'		=> array('type'	=> 'VARCHAR', 'constraint'	=> 255),
		    'admin_lang' => array('type' => 'VARCHAR', 'constraint' => 2, 'null', TRUE)
		);

		$this->dbforge->drop_table('users');
		$this->dbforge->add_field($user_fields);
		$this->dbforge->add_key('id_user', TRUE);
		$this->dbforge->create_table('users');

		//ACL table
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

		//Groups table
		$group_fields = array(
		    'id_group'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'group_name'=> array('type'	=> 'VARCHAR', 'constraint'	=> 64, 'null' => FALSE)
		);

		$this->dbforge->drop_table('groups');
		$this->dbforge->add_field($group_fields);
		$this->dbforge->add_key('id_group', TRUE);
		$this->dbforge->create_table('groups');

		//Groups ACL table
		$group_acl_fields = array(
		    'id_group_acl'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'group_id'		=> array('type'	=> 'INT', 'null' => FALSE),
		    'acl_id'		=> array('type'	=> 'INT', 'null' => FALSE)
		);

		$this->dbforge->drop_table('groups_acl');
		$this->dbforge->add_field($group_acl_fields);
		$this->dbforge->add_key('id_group_acl', TRUE);
		$this->dbforge->create_table('groups_acl');

		//Categories table
		$categories_fields = array(
		    'id_category'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'category_name'	=> array('type'	=> 'VARCHAR', 'constraint' => 64, 'null' => FALSE),
		    'id_type'		=> array('type'	=> 'INT', 'null' => FALSE)
		);

		$this->dbforge->drop_table('categories');
		$this->dbforge->add_field($categories_fields);
		$this->dbforge->add_key('id_category', TRUE);
		$this->dbforge->create_table('categories');

		//Documents table
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

		//Events table
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

		//Record categories table
		$record_categories_fields = array(
		    'id_record'		=> array('type'	=> 'INT', 'null' => FALSE, 'unsigned' => TRUE),
		    'id_category'	=> array('type'	=> 'INT', 'null' => FALSE, 'unsigned' => TRUE)
		);

		$this->dbforge->drop_table('record_categories');
		$this->dbforge->add_field($record_categories_fields);
		$this->dbforge->create_table('record_categories');

		//Hierarchies table
		$hierarchies_fields = array(
		    'id_hierarchy'	=> array('type'	=> 'INT', 'unsigned' => TRUE, 'auto_increment' => TRUE),
		    'id_parent'		=> array('type'	=> 'INT', 'null' => TRUE, 'unsigned' => TRUE),
			'name'			=> array('type'	=> 'VARCHAR', 'null' => FALSE, 'constraint' => 64)
		);

		$this->dbforge->drop_table('hierarchies');
		$this->dbforge->add_field($hierarchies_fields);
		$this->dbforge->add_key('id_hierarchy', TRUE);
		$this->dbforge->create_table('hierarchies');

		//Record hierarchies table
		$record_hierarchies_fields = array(
		    'id_record'		=> array('type'	=> 'INT', 'null' => FALSE, 'unsigned' => TRUE),
		    'id_hierarchy'	=> array('type'	=> 'INT', 'null' => FALSE, 'unsigned' => TRUE)
		);

		$this->dbforge->drop_table('record_hierarchies');
		$this->dbforge->add_field($record_hierarchies_fields);
		$this->dbforge->create_table('record_hierarchies');

		//Settings table
		$settings_fields = array(
			'name'		=> array('type'	=> 'VARCHAR', 'null' => FALSE, 'constraint' => 64),
			'value'		=> array('type'	=> 'VARCHAR', 'null' => TRUE, 'constraint' => 255),
			'module'	=> array('type'	=> 'VARCHAR', 'null' => FALSE, 'constraint' => 64)
		);

		$this->dbforge->drop_table('settings');
		$this->dbforge->add_field($settings_fields);
		$this->dbforge->create_table('settings');

		//User tokens table
		$api_tokens = array(
		    'username'		=> array('type'	=> 'VARCHAR', 'null'	=> FALSE, 'constraint' => 64),
		    'token'			=> array('type'	=> 'VARCHAR', 'null' => FALSE, 'constraint' => 255),
		    'content'		=> array('type'	=> 'VARCHAR', 'null' => FALSE, 'constraint' => 255),
			'last_activity'	=> array('type'	=> 'INT', 'null' => FALSE, 'unsigned' => TRUE)
		);

		$this->dbforge->drop_table('api_tokens');
		$this->dbforge->add_field($api_tokens);
		if (strpos($this->CI->db->database, 'sqlite:') === FALSE)
		{
			//We create the index only if the Database driver is not SQLite
			$this->dbforge->add_key('token');
		}
		$this->dbforge->create_table('api_tokens');

		return TRUE;
	}

	/**
	 * Creates the basic ACLs and groups
	 */
	public function create_groups()
	{
		//We insert the default ACLs
		$acls = array();
		$acls[]= $this->users->add_acl('users', 'list', 'Users list');
		$acls[]= $this->users->add_acl('users', 'add', 'Create/Edit users');
		$acls[]= $this->users->add_acl('users', 'groups', 'Manage groups and permissions');
		$acls[]= $this->users->add_acl('types', 'add', 'Add content types');
		$acls[]= $this->users->add_acl('types', 'manage', 'Edit XML schemes');
		$acls[]= $this->users->add_acl('types', 'delete', 'Delete content types');
		$acls[]= $this->users->add_acl('settings', 'manage', 'Manage website settings');
		$acls[]= $this->users->add_acl('hierarchies', 'manage', 'Manage hierarchies');

		$this->group_id = $this->users->add_group('Administrators');
		$this->users->add_group('Editors');

		//These strings are here just for translations
		$dummy = _('Administrators') . _('Editors');

		$this->CI->auth->update_permissions($acls, $this->group_id);

	}

	/**
	 * Insert a new user
	 * @param string $username
	 * @param string $password
	 * @param string $name
	 * @param string $surname
	 * @param string $email
	 */
	public function create_user($username, $password, $name, $surname, $lang='', $email = 'admin@example.org')
	{
		$data = array(
			'name' => $name,
			'surname' => $surname,
			'email' => $email,
			'username' => $username,
			'password' => $password,
			'id_group' => $this->group_id,
			'admin_lang' => $lang =! '' ? $lang : $this->CI->lang->current_language
		);
		return $this->users->add_user($data);
	}

	/**
	 * Create the default content types
	 */
	public function create_types()
	{
		$default = $this->CI->config->item('default_tree_types');
		if (count($default))
		{
			foreach ($default as $type)
			{
				$this->CI->content->add_type($type, $type, 'true', TRUE, $type == 'Menu' ? 'New page' : 'New ' . $type);
			}
		} else {
			show_error(_('Default content type not defined'));
		}
		$this->CI->content->rebuild();
	}

	/**
	 * Create the Database indexes
	 */
	public function create_indexes()
	{
		$indexes = array(
			array('records', 'id_type'),
			array('records', 'date_publish'),
			array('records', 'lang'),
			array('records_stage', 'id_type'),
			array('pages', 'id_type'),
			array('pages', 'full_uri'),
			array('pages_stage', 'id_type'),
			array('pages_stage', 'full_uri'),
			array('categories', 'category_name'),
			array('documents', 'bind_id'),
			array('documents_stage', 'bind_id'),
			array('record_categories','id_record'),
			array('record_categories', 'id_category'),
			array('record_hierarchies', 'id_record'),
			array('record_hierarchies', 'id_hierarchy')
		);

		//If we encounter index with same name, we will append _N (where N is the index number)
		//This is because in a database you cannot have indexes with the same name
		$created_indexes = array();

		foreach ($indexes as $index)
		{
			list($table, $column) = $index;
			$index_name = $column;

			$created_indexes[$column][] = TRUE;
			if (array_key_exists($column, $created_indexes) && count($created_indexes[$column]) > 1)
			{
				$index_name = $column . '_' . count($created_indexes[$column]);
			}

			//TODO: move this query into DB forge to let it change between different Databases
			$sql = 'CREATE INDEX idx_bancha_'.$index_name.' ON '.$this->CI->db->dbprefix.$table.' ('.$column.');';
			$this->CI->db->query($sql);
		}

	}

	/**
	 * Delete and re-create the directories
	 */
	public function create_directories()
	{
		$directories = array(
			$this->CI->config->item('attach_folder'),					//Attachs directory
			$this->CI->config->item('xml_typefolder'),					//XML Types schemes
			//$this->CI->config->item('views_absolute_templates_folder'),	//Content type Views - DEPRECATED,
			$this->CI->config->item('fr_cache_folder'),					//Bancha Cache files,
			$this->CI->config->item('cache_path')						//CI Cache folder
		);

		foreach ($directories as $dir)
		{
			delete_directory($dir);
			@mkdir($dir, DIR_WRITE_MODE, TRUE);
			if ($dir != $this->CI->config->item('xml_typefolder'))
			{
				write_file($dir.'index.html', CMS.' does not allow directory listing.');
			}
		}
	}

	/**
	 * Populates the default settings
	 */
	public function populate_settings($theme = '')
	{
		$this->CI->load->settings();
		$this->CI->settings->set('is_installed', 'T');

		$this->CI->settings->set('website_name', 'My website');
		$this->CI->settings->set('website_claim', 'This is my first website!');

		$this->CI->settings->set('website_desktop_theme', $theme);
		$this->CI->settings->set('website_mobile_theme', $theme);
		$this->CI->settings->set('website_active_languages', array_keys($this->CI->config->item('website_languages_select')));
	}

	public function create_homepages()
	{
		if (!isset($this->CI->settings))
		{
			$this->CI->load->settings();
		}

		$languages = array_keys($this->CI->config->item('website_languages'));

		//We make an homepage for each language we found
		foreach ($languages as $lang)
		{
			$page = new Record('Menu');
			$page->set('title', 'Homepage')
				 ->set('uri', 'home')
				 ->set('lang', $lang)
				 ->set('action', 'text')
				 ->set('view_template', 'home')
			;
			$page_id = $this->CI->records->save($page);
			$this->CI->records->publish($page_id, 'Menu');
			$this->CI->pages->publish($page_id);
			$this->CI->settings->set('website_homepage_' . $lang, 'home');
		}
	}

	/**
	 * Create a custom installation (example: for a Blog)
	 * @param string $type Premade name
	 */
	public function create_premade($type = '')
	{
		if ($type == '')
		{
			return;
		}

		//This directory contains the premade schemes of the chosen type
		$folder = $this->CI->config->item('templates_folder') . 'premades' . DIRECTORY_SEPARATOR
				. $type . DIRECTORY_SEPARATOR;

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

		//Let the framework rebuild the content types cache
		$this->CI->content->rebuild();
		$this->CI->content->read();

		switch (strtolower($type))
		{
			case 'blog':
				//We add the columns that we need
				$fields = array(
					'post_id' => array('type' => 'INT', 'null' => TRUE)
				);
				$this->dbforge->add_column('records', $fields);
				$this->dbforge->add_column('records_stage', $fields);

				//Then, we create a dummy post
				$post = new Record('Blog');
				$post->set('title', _('My first post'))
					 ->set('content', _('Hello world'))
					 ->set('lang', $this->CI->lang->default_language)
					 ->set('date_publish', time())
				;
				$post_id = $this->CI->records->save($post);
				$this->CI->records->publish($post_id, 'Blog');

				//A sample comment linked to this post
				$comment = new Record('Comments');
				$comment->set('author', 'Nicholas')
						->set('www', 'http://getbancha.com')
						->set('post_id', $post_id)
						->set('lang', $this->CI->lang->default_language)
						->set('content', 'I am cool.')
				;
				$this->CI->records->set_type('Comments')->save($comment);

				//And a simple page that lists the posts
				$page = new Record('Menu');
				$page->set('title', 'Blog')
					 ->set('uri', 'blog')
					 ->set('action', 'list')
					 ->set('lang', $this->CI->lang->default_language)
					 ->set('show_in_menu', 'T')
					 ->set('action_list_type', $this->CI->content->type_id('Blog'))
					 ->set('action_list_order_by', 'date_publish DESC');

				$page_id = $this->CI->records->save($page);
				$this->CI->records->publish($page_id, 'Menu');
				$this->CI->pages->publish($page_id);

				//break; < no break! we will build also default pages

			case 'default':
				//We create a dummy page
				$page = new Record('Menu');
				$page->set('title', 'About us')
				->set('action', 'text')
				->set('lang', $this->CI->lang->default_language)
				->set('show_in_menu', 'T')
				->set('child_count', 0)
				->set('uri', 'about-us')
				->set('content', _('Hello world by a sample page.'))
				;
				$this->CI->records->save($page);

				break;
		}

		//This tree needs to be cleared because we added some pages few lines above
		$this->CI->tree->clear_cache('Menu');
	}

	/**
	 * Duplicates an XML from a premade overriding the default one
	 * @param string $path Source of the XML scheme
	 * @param string $type_name Type name
	 * @param int $type_id Primary key of this type
	 * @return bool
	 */
	public function copy_premade_xml($path, $type_name, $type_id)
	{
		$xml = read_file($path);

		//We parse the file with some pseudovariables
		$xml = $this->CI->parser->parse_string($xml, array(
		          'id'			=> $type_id,
		          'version'		=> BANCHA_VERSION
		),TRUE);

		$storage_path = $this->CI->config->item('xml_typefolder').$type_name.'.xml';
		return write_file($storage_path, $xml);
	}
}