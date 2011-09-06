<?php
/**
 * Triggers Model
 *
 * Classe per gestire i trigger dei tipi di contenuto
 *
 * @package		Milk
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
	 * Esegue i trigger
	 */
	public function fire()
	{
		if (count($this->_triggers))
		{
			foreach ($this->_triggers as $trigger)
			{
				switch (strtolower($trigger['action']))
				{
					case 'sql':
						$sql = $trigger['sql'];
						$tipo = $this->content->type($sql['type']);

						if (isset($trigger['field']) && $this->_delegate)
						{
							$this->db->where($tipo['primary_key'], $this->_delegate->get($trigger['field']));
						}

						//Nuovo valore
						$this->db->set($sql['field'], $sql['value'], $sql['escape'] ? TRUE : FALSE);

						//Commit
						$this->db->update($tipo['table_stage']);

						break;
				}
			}
		}
	}

}