<?php
/**
 * Settings Model
 *
 * Model to work with the cms settings
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Model_settings extends CI_Model
{
	/**
	 * @var string The cache files
	 */
	private $_cachefile = 'settings.tmp';

	/**
	 * @var string The database table
	 */
	public $table = 'settings';

	/**
	 * @var array The settings items
	 */
	private $_items = array();

	public function __construct()
	{
		parent::__construct();
		$this->_cachefile = $this->config->item('fr_cache_folder') . $this->_cachefile;

		if (file_exists($this->_cachefile))
		{
			//Loads the cache file
			$this->_items = unserialize(file_get_contents($this->_cachefile));
		} else {
			//Rebuilds it
			$this->_items = $this->build_cache();
		}
	}

	/**
	 * Sets a value and saves it to the database
	 * @param string $key
	 * @param mixed $val
	 * @param string $module
	 */
	public function set($key, $val, $module = 'default')
	{
		//Does this setting already exists?
		$exists = isset($this->_items[$module][$key]);

		$this->_items[$module][$key] = $val;

		//And let's save that record also into the database
		if ($exists)
		{
			return $this->db->where('name', $key)
							->where('module', $module)
							->update($this->table, array('value' => $val));
		} else {
			return $this->db->insert($this->table,
						array('name' => $key, 'value' => $val, 'module' => $module)
				   );
		}
	}

	/**
	 * Returns a pre-setted value from the view
	 * @param string $key
	 * @param string $module
	 */
	public function get($key, $module = 'default')
	{
		if (isset($this->_items[$module][$key]))
		{
			return $this->_items[$module][$key];
		}
		return FALSE;
	}

	/**
	 * Clears the settings cache
	 * The settings cache file will be generated during the next request!
	 */
	public function clear_cache()
	{
		if (file_exists($this->_cachefile))
		{
			return unlink($this->_cachefile);
		}
	}

	/**
	 * Rebuild the settings cache, writes the file and returns the settings
	 * @return array
	 */
	public function build_cache()
	{
		$res = $this->db->select('name, value, module')->from($this->table)->get()->result();
		$this->_items = array();
		if (count($res))
		{
			foreach ($res as $row)
			{
				$this->_items[ strlen($row->module) ? $row->module : 'default' ][$row->name] = $row->value;
			}
		}
		write_file($this->_cachefile, serialize($this->_items));
		return $this->_items;
	}

}