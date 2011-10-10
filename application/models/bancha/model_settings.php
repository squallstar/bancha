<?php
/**
 * Settings Model
 *
 * Model to work with the settings
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
	 * Create/updates a key to the settings and saves it to the database
	 * @param string $key
	 * @param mixed $val
	 * @param string $module
	 */
	public function set($key, $val, $module = 'General')
	{
		//Does this setting already exists?
		$exists = isset($this->_items[$module][$key]);

		//If the value is not change, we don't do anything
		if ($exists && $this->_items[$module][$key] == $val)
		{
			return;
		}

		//Elsewhere, let's update the value
		$this->_items[$module][$key] = $val;

		if (is_array($val)) {
			$val = serialize($val);
		}

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
		return FALSE;
	}

	/**
	 * Create/updates a blocks of a theme template
	 * @param string $key
	 * @param mixed $val
	 * @param string $theme
	 * @param string $template
	 */
	public function set_block($key, $val, $theme, $template = 'default')
	{
		return $this->set($key, $val, 'blocks-' . $theme . '-' . $template);
	}

	/**
	 * Returns a single block of a theme template
	 * @param string $key
	 * @param string $theme
	 * @param string $template
	 * @return mixed value
	 */
	public function get_block($key, $theme, $template = 'default')
	{
		return $this->get($key, 'blocks-' . $theme . '-' . $template);
	}

	/**
	 * Returns a single value from the settings
	 * @param string $key
	 * @param string $module
	 * @return mixed value
	 */
	public function get($key, $module = 'General')
	{
		if (isset($this->_items[$module][$key]))
		{
			return $this->_items[$module][$key];
		}
		return FALSE;
	}

	/**
	 * Deletes a value from the settings table
	 * @param string $key
	 * @param string $module
	 * @return bool success
	 */
	public function delete($key, $module = 'General')
	{
		$module = strtolower($module);
		return $this->db->where('name', $name)->where('module', $module)->delete($this->table);
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
				$value = @unserialize($row->value);
				if ($value === false)
				{
					$value = $row->value;
				}
				$this->_items[ strlen($row->module) ? $row->module : 'default' ][$row->name] = $value;
			}
		}
		$this->load->helper('file');
		write_file($this->_cachefile, serialize($this->_items));
		return $this->_items;
	}

}