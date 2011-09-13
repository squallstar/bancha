<?php
/**
 * Triggers Model
 *
 * Classe per gestire i triggers impostati dai tipi di contenuto
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_triggers extends CI_Model {

	/**
	 * @var string Operazione da eseguire
	 */
	private $_operation = '';

	/**
	 * @var array Elenco dei triggers da eseguire
	 */
	private $_triggers = array();

	/**
	 * @var mixed Delegato (oggetto dell'operazione)
	 */
	private $_delegate = FALSE;
	
	/**
	 * @var bool Indica se siamo in stage
	 */
	private $_is_stage = TRUE;
	
	/**
	 * Imposta se utilizzare i trigger sulle tabelle di stage
	 * @param bool $stage
	 */
	public function set_stage($stage)
	{
		$this->_is_stage = $stage;
		return $this;
	}

	/**
	 * Imposta l'operazione da eseguire
	 * @param string $operation
	 */
	public function operation($operation)
	{
		$this->_operation = $operation;
		return $this;
	}

	/**
	 * Imposta il delegato principale su cui usare i trigger
	 * @param Record $delegate
	 */
	public function delegate($delegate)
	{
		if ($delegate instanceof Record)
		{
			$this->_delegate = $delegate;
		}
		return $this;
	}

	/**
	 * Aggiunge uno o piu' triggers
	 * @param array $trigger
	 */
	public function add($trigger)
	{
		if (isset($trigger[0]))
		{
			foreach ($trigger as $tr)
			{
				$this->add($tr);
			}
		} else {
			$this->_triggers[]= $trigger;
		}
		return $this;
	}

	/**
	 * Esegue i trigger preimpostati
	 */
	public function fire()
	{
		if (!count($this->_triggers)) {
			return;
		}
		
		foreach ($this->_triggers as $trigger)
		{
			switch (strtolower($trigger['action']))
			{
				//Vari triggers che generano query SQL
				case 'sql':
					
					$sql = $trigger['sql'];
					$target_tipo = $this->content->type($sql['type']);
					$table_key = $this->_is_stage ? 'table_stage' : 'table';
					
					switch ($sql['action'])
					{
						//Effettua una update sul DB
						case 'update':
							if (isset($trigger['field']) && $this->_delegate)
							{
								$value = $this->_delegate->get($trigger['field']);
								if (!$value)
								{
									return;
								}
								$this->db->where($target_tipo['primary_key'], $value);
							}
	
							$this->db->set($sql['target'], $sql['value'], $sql['escape'] ? TRUE : FALSE);
							$this->db->update($target_tipo[$table_key]);
							break;
						
						//Riconta i figli di un record
						case 'recount':
							$record_tipo = $this->content->type($this->_delegate->_tipo);
							
							$value = $this->_delegate->get($trigger['field']);
							
							if ($value)
							{
								$count = $this->db->select('count(*) AS total')
												  ->from($record_tipo[$table_key])
												  ->where($trigger['field'], $value)
												  ->get()->row(0);
												  
								$this->db->set($sql['target'], $count->total);
								$this->db->where($target_tipo['primary_key'], $value);
								$this->db->update($target_tipo[$table_key]);
							}
							break;
					}
					break;
				
				//Chiamata ad azione custom
				case 'call':
					
					if (!defined('CUSTOM_TRIGGER'))
					{
						define('CUSTOM_TRIGGER', TRUE);
					}
					
					$folder = $this->config->item('custom_controllers_folder');
					require_once($folder . 'triggers.php');
					$trigger_class = new Triggers();
					$method_name = $trigger['method'];
					
					//Chiamo l'azione definita su controllers/custom/triggers
					if (is_callable(array($trigger_class, $method_name)))
					{
						$trigger_class->$method_name(isset($this->_delegate) ? $this->_delegate : NULL);
					}				
					break;
									
			} //end-switch
		} //end-foreach
		
		//Elimino i trigger
		$this->_triggers = array();
	}
}