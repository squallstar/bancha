<?php
/**
 * Contents Controller
 *
 * Gestione dei contenuti (amministrazione)
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Contents extends Milk_Controller
{

	private $_section;

  	public function __construct()
  	{
	    parent::__construct();

	    //We are always in staging here
	    $this->content->set_stage(TRUE);

	    //Views base path
	    $this->view->base = 'admin/';

	    //Contents-Pages section
	    $this->_section = $this->uri->segment(2);
	    $this->view->set('_section', $this->_section);

	    //All actions needs user login
	    $this->auth->needs_login();

	    //Loads the events model
	    $this->load->events();
  	}

  /**
   * Lista dei tipi
   */
  public function index()
  {

  	if ($this->session->flashdata('message'))
  	{
  		$this->view->set('message', $this->session->flashdata('message'));
  	}

	$tipi = $this->content->types();

	$contents = array();
	$pages = array();

	foreach ($tipi as $tipo)
	{
		if ($tipo['tree']) {
			$pages[] = $tipo;
		} else {
			$contents[] = $tipo;
		}
	}

    $this->view->set('tipi', $this->uri->segment(2) == 'contents' ? $contents : $pages);
    $this->view->render_layout('content/type_list');
  }

  /**
   * Lista record di un tipo
   * @param int|string $tipo
   */
  public function type($tipo='', $page=0)
  {

  	$type = $this->content->type($tipo);
  	$this->records->set_type($tipo);
    $this->view->set('tipo', $type);

    //Pubblicazione record
    $to_publish = $this->input->get('publish');
    if ($to_publish)
    {
    	$done = $this->records->publish($to_publish);
    	if ($done)
    	{
    		$this->pages->publish($to_publish);
    		$this->view->set('message', 'Il record ['.$to_publish.'] &egrave; stato pubblicato.');
    		$this->tree->clear_cache();
    	}
    }

  	//Depubblicazione record
    $to_depublish = $this->input->get('depublish');
    if ($to_depublish)
    {
    	$done = $this->records->depublish($to_depublish);
    	if ($done)
    	{
    		$this->pages->depublish($to_depublish);
    		$this->view->set('message', 'Il record ['.$to_depublish.'] &egrave; stato depubblicato.');
    		$this->tree->clear_cache();
    	}
    }

    //Azioni varie collettive
    if ($this->input->post('action'))
    {
    	$records = $this->input->post('record');
    	if (count($records))
    	{
	    	switch ($this->input->post('action'))
	    	{
	    		case 'publish':
	    			foreach ($records as $record)
	    			{
	    				$this->records->publish($record);
	    				$this->pages->publish($record);
	    				$this->view->set('message', 'I record sono stati pubblicati.');
	    			}
	    			break;
    			case 'depublish':
    				foreach ($records as $record)
    				{
    					$this->records->depublish($record);
    					$this->pages->depublish($record);
    					$this->view->set('message', 'I record sono stati depubblicati.');
    				}
    				break;
    			case 'delete':
    				foreach ($records as $record)
    				{
    					$this->delete_record(NULL, $record, TRUE);
    					$this->view->set('message', 'I record sono stati eliminati.');
    				}
    				break;
	    	}
    	}
    }

    //Filtri automatici
    $this->db->start_cache();
    $post_filters = $this->input->post('filter');
    foreach (array_keys($type['fields']) as $field) {
    	if ($type['fields'][$field]['admin'] === true) {
   			$admin_fields[] = $field;
   			$filters[$field] = isset($post_filters[$field]) ? $post_filters[$field] : '';
   			if ($filters[$field] != '') {
   				$this->records->like($field, $filters[$field]);
   			}
    	}
    }

    //Filtri manuali
    $filters_manual = array($type['primary_key'], 'published');
    foreach ($filters_manual as $filter) {
    	$filters[$filter] = isset($post_filters[$filter]) ? $post_filters[$filter] : '';
    	if ($filters[$filter] != '') {
    		$this->records->where($filter, $filters[$filter]);
    	}
    }

    $this->session->set_userdata('record_filters', $filters);

    $this->db->stop_cache();

    $this->view->set('filters', $filters);
    $this->view->set('admin_fields', $admin_fields);

    //Paginazione
    $pagination = array(
        	'total_rows'	=> $this->records->type($tipo)->count(),
        	'per_page'		=> $this->config->item('records_per_page'),
        	'base_url'		=> admin_url('contents/type/'.$tipo.'/'),
        	'uri_segment'	=> 5,
        	'cur_tag_open'	=> '<a href="#" class="active">',
        	'cur_tag_close'	=> '</a>'
    );

    $this->view->set('total_records', $pagination['total_rows']);

    $this->load->library('pagination');
    $this->pagination->initialize($pagination);

    //Ottengo i records
    $records = $this->records->type($tipo)
    						 ->order_by('date_update', 'DESC')
    						 ->limit($pagination['per_page'], $page)
    						 ->get();

    $this->db->flush_cache();

    $this->view->set('records', $records);

    if ($this->session->flashdata('message')) {
    	$this->view->set('message', $this->session->flashdata('message'));
    }

    $this->view->render_layout('content/record_list');
  }

  /**
   * Azione per aggiungere un contenuto figlio di un altro
   * @param int|string $type
   * @param int $child_id
   */
  public function add_child_record($type='', $child_id='')
  {
  		$this->edit_record($type, '', $child_id);
  }

  /**
   * Form di aggiunta/modifica di un record
   * @param int|string $type
   * @param int $record_id
   */
  public function edit_record($type='', $record_id='', $child_id='')
  {

	$this->load->categories();
	$this->load->documents();

    $tipo = $this->content->type($type);

    //Aggiunta-Modifica record
    if ($this->input->post('id_type', FALSE)) {

    	$id_type = $this->input->post('id_type', FALSE);

      	$record = $this->content->make_record($id_type, $this->input->post(NULL, FALSE));

      //Salvo il record
      $record_id = $this->records->save($record);

      if ($record_id) {
	      	if ($tipo['has_categories']) {
	      		//Aggiorno le categorie associate a questo record
	      		$this->categories->set_record_categories($record_id, $this->input->post('categories'));
	      	}

	      	if ($tipo['has_attachments'] && count($_FILES)) {

	      		$files_copy = $_FILES;

				foreach ($files_copy as $name => $val) {

					$count = count($val['tmp_name']);

					for ($i = 0; $i < $count; $i++)
					{
						if ($val['tmp_name'][$i] != '') {
							$upload_config = array(
								'allowed_types' => $tipo['fields'][$name]['mimes'],
								'max_size'		=> $tipo['fields'][$name]['size'],
								'encrypt_name'	=> TRUE,
								'resized'		=> isset($tipo['fields'][$name]['resized']) ? $tipo['fields'][$name]['resized'] : FALSE,
								'thumbnail'		=> isset($tipo['fields'][$name]['thumbnail']) ? $tipo['fields'][$name]['thumbnail'] : FALSE
							);

							$single_file = array(
								'name'		=> $val['name'][$i],
								'type'		=> $val['type'][$i],
								'tmp_name'	=> $val['tmp_name'][$i],
								'size'		=> $val['size'][$i],
							);

							//Fix for handling multiple files
							$_FILES[$name] = $single_file;

							$this->documents->upload($name, $upload_config, array(
								'id'	=> $record_id,
								'table'	=> 'records',
								'field'	=> $name
							));
						}
					}
				}
	      	}
      }

      //Riprendo il record aggiornato dal db
      $record = $this->records->get($record_id);

      //Aggiorno i testi alternativi delle immagini
      if ($this->input->post('_alt_text', FALSE)) {
      		$alt_texts = $this->input->post('_alt_text', FALSE);
      		$priorities = $this->input->post('_priority', FALSE);
      		foreach ($alt_texts as $document_id => $new_text) {
				$this->documents->update_alt_text($document_id, $new_text, $priorities[$document_id]);
      		}
      }

      //Pulisco la cache di questo tipo di albero
      $this->tree->clear_cache($tipo['name']);

      //Pulisco la cache del menu di default se questo tipo ne fa parte
      if (in_array($tipo['name'], $this->config->item('default_tree_types'))) {
      		$this->tree->clear_cache();
      }

      if ($this->input->post('_bt_save_list')) {
      		$this->session->set_flashdata('message', 'Il contenuto "<a href="'.admin_url('contents/edit_record/'.$tipo['name'].'/'.$record->id).'">'.$record->get('title').'</a>" &egrave; stato correttamente salvato.');
      	 	redirect('admin/contents/type/' . $tipo['name']);
      } else if ($this->input->post('_bt_publish')) {
      		$this->records->publish($record_id);
      		$this->session->set_flashdata('message', 'Il contenuto "<a href="'.admin_url('contents/edit_record/'.$tipo['name'].'/'.$record->id).'">'.$record->get('title').'</a>" &egrave; stato pubblicato.');
      		redirect('admin/contents/type/' . $tipo['name']);
      } else{
      		$this->view->set('ok_message', 'Il contenuto &egrave; stato correttamente salvato.');
      }

    }else if ($record_id != '') {
    	$record = $this->records->get($record_id);
    }else {
    	//Nuovo record
		$record = $this->content->make_record($tipo['id']);

    }

    //Controllo se il tipo e' ad albero
    if ($tipo['tree']) {

		//Estraggo l'albero di questo tipo
		$tree = $this->tree->parent_types($tipo['id'])
						   ->exclude_page($record->id)
						   ->exclude_parent($record->id)
						   ->show_invisibles()
						   ->get_linear_dropdown();

		$this->view->set('tree', $tree);

		$parent_tree = array(
			'' => '--- '._('First level').' ---'
		);

		foreach ($tree as $item_key => $item_val) {
			$parent_tree[$item_key] = $item_val;
		}
		$this->view->set('parent_tree', $parent_tree);

		//Inserimento di un contenuto figlio
		if ($child_id != '') {
			$record->set('id_parent', $child_id);
		}

		//Ottengo l'indirizzo attuale di questa pagina
		if ($record->id)
		{
			$this->view->set('page_url', $this->pages->get_record_url($record->id));
		}
    }

    //Ottengo le categorie se necessarie per questo tipo
    if ($tipo['has_categories']) {
    	$this->view->set('categories', $this->categories->type($tipo['id'])->get());
		$record->set('categories', $this->categories->get_record_categories($record->id));
    }

    //Estraggo gli allegati se c'è almeno un campo file/image
    if ($tipo['has_attachments'] && $record->id) {
		$record->set_documents();
    }

    //Estraggo le options custom se ce ne sono
    foreach ($tipo['fields'] as $field_name => $field_value) {
    	if (isset($field_value['extract'])) {
			$tipo['fields'][$field_name]['options'] = $this->records->get_field_options($field_value);
    	}
    }

    $this->view->set('tipo', $tipo);
    $this->view->set('record', $record);

    $this->view->render_layout('content/record_edit');
  }

  /**
   * Azione per eliminare un record dal DB
   * @param int|string $tipo
   * @param int $id_record
   */
  public function delete_record($tipo='', $id_record='', $callback=FALSE) {
  	if ($id_record != '') {

		if ($tipo) {
			$tipo = $this->content->type($tipo);
		} else {
			//Ottengo il tipo del record se non è stato passato
			$record = $this->records->get($id_record);
			$tipo = $this->content->type($record->_tipo);
		}

		$done = $this->records->delete_by_id($id_record);

		if (!$done) {
			show_error('Impossibile eliminare il record ['.$id_record.'] di tipo ['.$tipo['description'].'].', 500, 'Errore: eliminazione record non riuscita');
		}else {

			if ($tipo['tree'])
			{
				//Elimino le pagine
				$this->pages->delete_all($id_record);
			}

			//Elimino gli allegati collegati
  			$this->load->documents();
			$this->documents->delete_by_binds('records', $id_record);

			//Pulisco la cache dei menu
			$this->tree->clear_cache();

			//Pulisco la cache di questo tipo
			if ($tipo != '')
			{
				$this->tree->clear_cache($tipo['name']);
			}

			if (!$callback)
			{
				$this->session->set_flashdata('message', 'Il record ['.$id_record.'] &egrave; stato eliminato.');
				redirect('admin/'.$this->_section.'/type/' . $tipo['name']);
			}
		}
  	}else {
  		show_error('L\'id del record da eliminare non &egrave; settato.', 500, 'Errore: id non settato');
  	}

  }

  /**
   * Form di aggiunta di un tipo
   */
  public function add_type() {

    if ($this->input->post()) {
      $type_name = $this->input->post('type_name');
      if ($type_name) {

      	$done = $this->content->add_type(
      		$type_name,
      		$this->input->post('type_description'),
      		$this->input->post('type_tree')
      	);

       if ($done)
       {
       		redirect('admin/' . ($this->input->post('type_tree') == 'true' ? 'pages' : 'contents'));
       }

      }else {
        $this->view->set('message', _('Please insert a name for this type.'));
      }
    }

    $this->view->render_layout('content/type_add');
  }

  public function type_edit_xml($type = '') {
  		$tipo = $this->content->type($type);

  		$xml_path = $this->config->item('xml_folder').$tipo['name'].'.xml';

  		if ($this->input->post('xml')) {
			$done = write_file($xml_path, $this->input->post('xml'));
			if ($done) {

				//Imposto i messaggi di conferma per la view lista
				$link = '<a href="'.admin_url('contents/type_edit_xml/'.$tipo['name']).'">'.$tipo['name'].'</a>';
				$this->session->set_flashdata('message', 'La struttura del tipo di contenuto '.$link.' &egrave; stato aggiornata.');

				//Ricostruisco i tipi
				$this->content->rebuild();
				redirect('admin/contents/');
			} else {
				show_error('Impossibile salvare il nuovo XML di definizione del contenuto ['.$tipo['name'].'].', 500, 'Errore nel salvataggio');
			}
  		}

  		$xml = read_file($xml_path) OR show_error(_('Cannot read the XML file.'));

  		$this->view->set('tipo', $tipo);
  		$this->view->set('xml', $xml);
  		$this->view->render_layout('content/type_edit_xml');
  }

  /**
   * Lista delle categorie di un tipo
   */
  public function type_categories($type = '') {
  		$this->load->categories();

  		$tipo = $this->content->type($type);

  		$category_name = $this->input->post('category_name');

  		if (strlen($category_name)) {
  			if (!$this->categories->exists($tipo['id'], $category_name)) {
	  			$done = $this->categories->add(
	  				$tipo['id'],
	  				$category_name
	  			);
	  			if ($done) {
	  				$this->view->set('message_ok', $this->lang->_trans('The category %n has been added.', array('n' => '['.$category_name.']')));
	  			} else {
	  				$this->view->set('message', $this->lang->_trans('Cannot insert the category %n.', array('n' => '['.$category_name.']')));
	  			}
  			} else {
  				$this->view->set('message', $this->lang->_trans('A category named %n already exists.', array('n' => '['.$category_name.']')));
  			}
  		}

  		$categories = $this->categories->type($tipo['id'])->get();

  		$this->view->set('tipo', $tipo);
  		$this->view->set('categories', $categories);
  		$this->view->render_layout('content/type_categories');
  }

  /**
   * Elimina una categoria
   * @param int|string $type_id
   * @param int $cat_id
   */
  public function type_categories_delete($type_id='', $cat_id='') {
  		if ($cat_id != '') {

  			$this->load->categories();
  			$done = $this->categories->delete_by_id($cat_id);

  			if ($done) {
  				$this->view->set('message_ok', $this->lang->_trans('The category %n has been deleted.', array('n' => '['.$cat_id.']')));
  			} else {
  				$this->view->set('message', 'Cannot delete the category.');
  			}
  			$this->type_categories($type_id);
  		}
  }

  /**
   * Rinnova la cache dei tipi di contenuto
   */
  public function renew_cache() {

  	$done = $this->content->rebuild();

  	if (!$done) {
  		show_error('Impossibile ricreare la cache di sistema.', 500, 'Errore: Cache interna non aggiornata');
  	}

    $this->view->set('message', _('The cache has been cleared.'));
    $this->view->render_layout('content/renewed');

  }

  public function type_delete($type='') {
  		$tipo = $this->content->type($type);

  		if ($this->input->post('cancel')) {
  			redirect('admin/contents');
  		} else if ($this->input->post('delete')) {

	  		$xml_path = $this->config->item('xml_folder').$tipo['name'].'.xml';
	  		$done = file_exists($xml_path) ? @unlink($xml_path) : TRUE;
	  		if ($done) {

	  			//Elimino i permessi
	  			$this->load->users();
	  			$this->users->delete_acl('content', $tipo['name']);

	  			//Elimino gli eventi
	  			$this->load->events();
	  			$this->events->delete_by_content_type($tipo['name']);

	  			//Ricostruisco la cache
	  			$this->content->rebuild();

	  			$this->session->set_flashdata('message', $this->lang->_trans('The type named %n has been removed.', array('n'=>'['.$tipo['name'].']')));
	  			redirect('admin/contents');
	  		}
  		} else {
  			$this->view->set('tipo', $tipo);
  			$this->view->render_layout('content/type_delete');
  		}
  }

}
