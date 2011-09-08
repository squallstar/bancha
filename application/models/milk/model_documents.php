<?php
/**
 * Documents Model
 *
 * Classe per estrarre e inserire files e immagini
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_Documents extends CI_Model {

	/**
	 * @var string Directory che contiene gli allegati
	 */
	public $attach_folder;

	/**
	 * @var string Tabella di produzione
	 */
	public $table = 'documents';

	/**
	 * @var string Tabella di stage
	 */
	public $table_stage = 'documents_stage';

	/**
	 * @var string Tabella corrente per le estrazioni
	 */
	public $table_current = '';

	public function __construct()
	{
		parent::__construct();
		$this->attach_folder = $this->config->item('attach_folder');
		$this->set_stage($this->content->is_stage);
	}

	/**
	 * Imposta se siamo in stage
	 * @param bool $is_stage
	 */
	public function set_stage($is_stage)
	{
		//Imposto la tabella su cui fare query
		$this->table_current = $is_stage ? $this->table_stage : $this->table;
	}

	/**
	 * Salva un documento su DB (solo record) nella tabella di stage
	 * @param array $data
	 */
	public function save($data = array())
	{
		if (isset($data['path']))
		{
			if (file_exists($this->attach_folder . $data['path']))
			{
				return $this->db->insert($this->table_stage, $data);
			}
		}
	}

	/**
	 * Metodo per uplodare un files dall'array $_FILES di PHP.
	 * @param string $name Chiave (nome) su $_FILES
	 * @param array $specs specifiche di caricamento
	 * @param array $save_params Parametri di salvataggio su DB (chiavi: id, table)
	 */
	public function upload($name = '', $specs = array(), $save_params = array())
	{
		if ($name != '' && is_array($specs) && count($specs))
		{

			//Increase limit
			ini_set('memory_limit', MEMORY_LIMIT);

			$custom_path = $save_params['type'] . DIRECTORY_SEPARATOR
				. $save_params['field'] . DIRECTORY_SEPARATOR
				. $save_params['id'] . DIRECTORY_SEPARATOR;

			//Attach folder
			$specs['upload_path'] = $this->attach_folder . $custom_path;


			//Create the directory if not exists
			if (!file_exists($specs['upload_path'])) {
				mkdir($specs['upload_path'], DIR_WRITE_MODE, TRUE);
			}

			//$count = count(get_filenames($specs['upload_path']));
			//$specs['file_name'] = 'pic-'.($count+1);

			//Sanitize del nome del file
			$specs['filename'] = url_title(convert_accented_characters($save_params['name']), 'underscore');

			$specs['encrypt_name'] = FALSE;

			$this->load->library('upload');
			$this->upload->initialize($specs);

			//Uploads the file
			if ($this->upload->do_upload($name))
			{
				$data = $this->upload->data();

				//Resize if set
				if ($specs['resized'])
				{
					$resized_name = $this->resize_image(
						$this->attach_folder . $custom_path . $data['file_name'],
						$specs['resized'],
						'r_'
					);
				}

				//Thumbnail if set
				if ($specs['thumbnail'])
				{
					$thumbnail_name = $this->resize_image(
						$this->attach_folder . $custom_path . $data['file_name'],
						$specs['thumbnail'],
						't_'
					);
				}

				//Check if record needs to be saved on DB
				if (is_array($save_params) && count($save_params))
				{
					$save_data = array(
						'bind_table'	=> $save_params['table'],
						'bind_id'		=> $save_params['id'],
						'bind_field'	=> $save_params['field'],
						'name'			=> $data['orig_name'],
						'path'			=> $custom_path . $data['file_name'],
						'size'			=> (int)$data['file_size'],
						'width'			=> $data['image_width'],
						'height'		=> $data['image_height'],
						'mime'			=> str_replace('.', '', $data['file_ext']),
						'date_upload'	=> time()
					);

					if (isset($resized_name))
					{
						$save_data['resized_path'] = $custom_path . $resized_name;
					}

					if (isset($thumbnail_name))
					{
						$save_data['thumb_path'] = $custom_path . $thumbnail_name;
					}

					$done = $this->save($save_data);
					return TRUE;
				}
			} else {
				show_error($this->upload->display_errors());
			}
		}
		return FALSE;
	}

	/**
	 * Imposta la tabella esterna per l'estrazione dei documenti
	 * @param string $table_name Nome tabella esterna
	 */
	public function table($table_name)
	{
		$this->db->where('bind_table', $table_name);
		return $this;
	}

	/**
	 * Imposta l'id della tabella esterna su cui estrarre i documenti
	 * @param int $table_id Chiave primaria tabella esterna
	 */
	public function id($table_id = '')
	{
		if ($table_id == '' || !is_numeric($table_id))
		{
			show_error('ID tabella esterna non settato correttamente. (documents/id)');
		} else {
			$this->db->where('bind_id', (int)$table_id);
		}
		return $this;
	}

	/**
	 * Imposta il nome del campo della tabella esterna a cui associare i record
	 * @param int $table_id Chiave primaria tabella esterna
	 */
	public function field($field_id = '')
	{
		if ($field_id == '') {
			show_error('Nome del campo su tabella esterna non settato (documents/field)');
		} else {
			$this->db->where('field_id', $field_id);
		}
		return $this;
	}

	/**
	 * Estrae i documenti richiesti
	 * @return array
	 */
	public function get()
	{

		$result = $this->db->select(implode(', ', $this->config->item('documents_select_fields')))
				 		   ->from($this->table_current)
				 		   ->order_by('priority', 'DESC')
				 		   ->get();

		if ($result->num_rows())
		{
			return $result->result();
		} else {
			return array();
		}
	}

	/**
	 * Imposta una clausola di where nelle estrazioni
	 * @param string $column colonna
	 * @param string $value valore
	 */
	public function where($column, $value='')
	{
		$this->db->where($column, $value);
		return $this;
	}

	/**
	 * Imposta la clausola di limit nelle estrazioni
	 * @param int $a numero di record da estrarre
	 * @param int $b offset
	 */
	public function limit($a, $b='')
	{
		$this->db->limit($a, $b);
		return $this;
	}

	/**
	 * Elimina un documento dal DB (stage) e dal file system
	 * @param int $document_id
	 * @return bool success
	 */
	public function delete_by_id($document_id = '')
	{
		if ($document_id != '')
		{
			$document = $this->db->select('path')
								 ->from($this->table_stage)
								 ->where('id_document', $document_id)
								 ->limit(1)
								 ->get()->result();
			if (count($document))
			{
				$document = $document[0];
				if (file_exists($this->attach_folder . $document->path))
				{
					@unlink($this->attach_folder . $document->path);
				}
				return $this->db->where('id_document', $document_id)->delete($this->table_stage);
			}
		}
	}

	/**
	 * Elimina tutti gli allegati di un determinato record collegato
	 * @param string $table
	 * @param int $id
	 * @param bool $stage imposta se utilizzare come ricerca la tabella di produzione
	 * @return bool success
	 */
	public function delete_by_binds($table, $id, $production=FALSE)
	{
		$doc_table = $production ? $this->table : $this->table_stage;
		$documents = $this->db->select('path')
					 		  ->from($doc_table)
					 		  ->where('bind_table', $table)
					 		  ->where('bind_id', $id)
					 		  ->get();
		if ($documents->num_rows())
		{
			foreach ($documents->result() as $document)
			{
				if (file_exists($this->attach_folder . $document->path))
				{
					@unlink($this->attach_folder . $document->path);
				}
			}
			$this->delete_records_by_binds($table, $id, $production);
		}
		return TRUE;
	}

	/**
	 * Elimina solo i record su db dei documenti, e non i file fisici
	 * @param unknown_type $table
	 * @param unknown_type $id
	 */
	public function delete_records_by_binds($table, $id, $production=FALSE)
	{
		$doc_table = $production ? $this->table : $this->table_stage;
		return $this->db->where('bind_table', $table)
						->where('bind_id', $id)
						->delete($doc_table);
	}

	/**
	 * Metodo che porta in produzione dei documenti
	 * Elimina anche i dead-records e dead-files
	 * @param string $table
	 * @param int $id
	 */
	public function put_live_documents($table, $id)
	{
		//Per prima cosa, prendo tutti gli allegati in produzione
		$live_documents = $this->db->select('path')
								   ->from($this->table)
								   ->where('bind_table', $table)
					 		 	   ->where('bind_id', $id)
					 		  	   ->get();
		$live_documents_paths = array();
		if ($live_documents->num_rows()) {
			foreach ($live_documents->result_array() as $document)
			{
				$live_documents_paths[$document['path']] = TRUE;
			}
		}

		//Elimino i documenti (DB) in produzione
		$this->delete_by_binds($table, $id, TRUE);

		//Prendo tutti gli allegati in sviluppo
		$stage_documents = $this->db->select('*')
								   ->from($this->table_stage)
								   ->where('bind_table', $table)
					 		 	   ->where('bind_id', $id)
					 		  	   ->get();
		if ($stage_documents->num_rows())
		{
			//Copio in produzione i record dei documenti stage
			foreach ($stage_documents->result_array() as $stage_document)
			{
				$this->db->insert($this->table, $stage_document);
				//Imposto questo documento come "non da eliminare"
				unset($live_documents_paths[$stage_document['path']]);
			}
		}

		//Elimino i dead-files della produzione precedente
		foreach (array_keys($live_documents_paths) as $document_to_remove)
		{
			if (file_exists($this->attach_folder . $document_to_remove))
			{
				@unlink($this->attach_folder . $document_to_remove);
			}
		}
	}

	/**
	 * Metodo per ridimensionare le immagini
	 * @param string $path Full path dell'immagine
	 * @param string $resize_to Dimensioni immagine ridimensionata. Esempio: 100x?
	 * @param unknown_type $marker Marcatore da prependere al nome dell'immagine salvata
	 * @return string Nome dell'immagine ridimensionata
	 */
	public function resize_image($path, $resize_to, $marker)
	{

		$this->load->library('image_lib');

		//Uniformo il path (fix per windows)
		$tmp_path = str_replace(DIRECTORY_SEPARATOR, '/', $path);
		$tmp = explode('/', $tmp_path);

		$image_config = array(
			'maintain_ratio'	=> TRUE,
			'source_image'		=> $path,
			'new_image'			=> $marker. $tmp[count($tmp)-1]
		);

		list($resize_width, $resize_height) = explode('x', $resize_to);

		//Imposto su quale dimensione effettuare il resize
		if ($resize_width == '?')
		{
			$image_config['height'] = (int)$resize_height;
			$image_config['width'] = 1;
			$image_config['master_dim'] = 'height';
		} else if ($resize_height == '?')
		{
			$image_config['width'] = (int)$resize_width;
			$image_config['height'] = 1;
			$image_config['master_dim'] = 'width';
		} else
		{
			$image_config['width'] = (int)$resize_width;
			$image_config['height'] = (int)$resize_height;
		}

		$this->image_lib->initialize($image_config);
		if (!$this->image_lib->resize()) {
			show_error($this->image_lib->display_errors() . $path, 500, 'Ridimensionamento immagine non riuscito');
		} else {
			return $image_config['new_image'];
		}
	}

	/**
	 * Aggiorna il testo alternativo di un documento
	 * @param int $document_id
	 * @param string $alt_text
	 * @return bool success
	 */
	public function update_alt_text($document_id='', $alt_text='', $priority=0)
	{
		if ($document_id != '')
		{
			return $this->db->set('alt_text', $alt_text)
							->set('priority', $priority)
							->where('id_document', $document_id)->update($this->table_stage);
		}
	}
}