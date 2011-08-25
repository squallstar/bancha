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
	* Costruisce l'xml di un record
	* @param int|string $type
	* @param array $data
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
				if (!in_array($field_name, $this->CI->config->item('record_columns')))
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
	
	function records_from_sql_xml($sql, $id_type = '')
	{
		$this->CI->db->select((string)$sql->select);
		
		//Uso la tabella di staging se sono in staging
		$from_tbl = (string)$sql->from;
		if ($this->CI->content->is_stage && $from_tbl == 'records' || $from_tbl == 'pages')
		{
			$from_tbl = $from_tbl . '_stage';
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
}