<?php
/**
 * Bancha_Loader
 *
 * An extension of the original CodeIgniter Loader
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Bancha_Loader extends CI_Loader {

	/**
	 * @var array Loaded modules
	 */
	private $_loaded_modules = array();

	/**
	 * This function loads the Bancha environment
	 */
	function bancha()
	{
		//Standard Bancha Libraries
		$this->library(
			array(
				FRNAME . '/content', FRNAME . '/xml', FRNAME . '/view'
			)
		);

		//Class that create instances of records
		require_once(FRPATH . 'record.php');

		//Generic helpers
		$this->helper(
			array(
				'file', 'website', 'text'
			)
		);

		//Loads some models that we use everywhere
		$this->auth();
		$this->records();
		$this->pages();
		$this->tree();
	}

	/**
	 * Loads a model inside Bancha framework
	 * @param string $model
	 * @param string $name
	 * @param bool $db_conn
	 */
	function frmodel($model, $name = '', $db_conn = FALSE)
	{
		$this->model(FRNAME.'/'.$model, $name, $db_conn);
	}

	/**
	 * Loads a library inside Bancha framework
	 * @param string $library
	 * @param string $name
	 */
	function frlibrary($library, $name = NULL)
	{
		$this->library(FRNAME.'/'.$library, NULL, $name);
	}

	/**
	 * Loads an external library
	 * @param string $library
	 * @param string $name
	 */
	function extlibrary($library, $name = NULL)
	{
		$this->library('external/'.$library, NULL, $name);
	}

	/**
	 * Loads the records model
	 */
	function records()
	{
		$this->model(FRNAME.'/model_records', 'records');
	}

	/**
	 * Loads the tree model
	 */
	function tree()
	{
		$this->model(FRNAME.'/model_tree', 'tree');
	}

	/**
	 * Loads the documents model
	 */
	function documents()
	{
		$this->model(FRNAME.'/model_documents', 'documents');
	}

	/**
	 * Loads the categories model
	 */
	function categories()
	{
		$this->model(FRNAME.'/model_categories', 'categories');
	}

	/**
	 * Loads the users ACLs
	 */
	function auth()
	{
		$this->model(FRNAME.'/model_auth', 'auth');
	}

	/**
	 * Loads the users model
	 */
	function users()
	{
		$this->model(FRNAME.'/model_users', 'users');
	}

	/**
	 * Loads the pages model
	 */
	function pages()
	{
		$this->model(FRNAME.'/model_pages', 'pages');
	}

	/**
	 * Loads the events model
	 */
	function events()
	{
		$this->model(FRNAME.'/model_events', 'events');
	}

	/**
	 * Loads the triggers model
	 */
	function triggers()
	{
		$this->model(FRNAME.'/model_triggers', 'triggers');
	}

	/**
	 * Loads the hierarchies model
	 */
	function hierarchies()
	{
		$this->model(FRNAME.'/model_hierarchies', 'hierarchies');
	}

	/**
	 * Loads the settings model
	 */
	function settings()
	{
		$this->model(FRNAME.'/model_settings', 'settings');
	}

	/**
	 * Loads an external module
	 * @param string $module_name
	 * @param array $properties (config)
	 */
	function module($module_name, $params = array())
	{
		if (!count($this->_loaded_modules))
		{
			require_once(FRPATH . 'module.php');
		}
		$module_name = strtolower($module_name);
		if (!isset($this->_loaded_modules[$module_name]))
		{
			$CI = & get_instance();
			require_once($CI->config->item('modules_folder').$module_name.DIRECTORY_SEPARATOR.$module_name.'_module.php');
			$this->_loaded_modules[$module_name] = TRUE;
		}
		$class_name = ucfirst($module_name).'_Module';
		$tmp = new $class_name();

		//We add the config variables
		if (count($params) && is_array($params))
		{
			foreach ($params as $key => $val)
			{
				$tmp->_set_var($key,$val);
			}
		}

		$tmp->module_name = ucfirst($module_name);
		$tmp->module_filespath = $CI->config->item('modules_folder').$module_name.DIRECTORY_SEPARATOR;

		return $tmp;
	}

	/**
	 * Loads a dispatcher
	 * @param string $name
	 * @param string $obj_name
	 */
	function dispatcher($name = 'default', $obj_name = 'dispatcher')
	{
		$name = strtolower($name);
		$CI = & get_instance();

		//We first look into the user dispatchers folder
		$user_dispatcher = USERPATH . 'dispatchers/dispatcher_' . $name . '.php';
		if (file_exists($user_dispatcher)) {
			require_once($user_dispatcher);
		} else {
			require_once(APPPATH . 'dispatchers/dispatcher_' . $name . '.php');
		}

		$class_name = 'Dispatcher_' . $name;

		if (!isset($obj_name))
		{
			$obj_name = 'dispatcher';
		}

		$CI->$obj_name = new $class_name();
	}

	/**
	 * Loads an adapter
	 * @param string $name
	 * @param string $obj_name
	 */
	function adapter($name = '', $obj_name = 'adapter')
	{
		require_once(APPPATH . '/libraries/' . FRNAME . '/adapter.php');
		$this->library(FRNAME.'/adapters/adapter_'.$name, NULL, $obj_name);
	}

	// --------------------------------------------------------------------

	/**
	 * Bancha View Loader
	 *
	 * This function is used to load views and files.
	 * Variables are prefixed with _ci_ to avoid symbol collision with
	 * variables made available to view files
	 *
	 * This method has been updated with the rendered view profiler section
	 *
	 * @param array $_ci_data
	 * @return void
	 */
	protected function _ci_load($_ci_data)
	{
		// Set the default data variables
		foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
		{
			$$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
		}

		$file_exists = FALSE;

		// Set the path to the requested file
		if ($_ci_path != '')
		{
			$_ci_x = explode('/', $_ci_path);
			$_ci_file = end($_ci_x);
		}
		else
		{
			$_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file = ($_ci_ext == '') ? $_ci_view.'.php' : $_ci_view;

			foreach ($this->_ci_view_paths as $view_file => $cascade)
			{
				if (file_exists($view_file.$_ci_file))
				{
					$_ci_path = $view_file.$_ci_file;
					$file_exists = TRUE;
					break;
				}

				if ( ! $cascade)
				{
					break;
				}
			}
		}

		if ( ! $file_exists && ! file_exists($_ci_path))
		{
			show_error('Unable to load the requested file: '.$_ci_file);
		}

		// This allows anything loaded using $this->load (views, files, etc.)
		// to become accessible from within the Controller and Model functions.

		$_ci_CI =& get_instance();
		foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
		{
			if ( ! isset($this->$_ci_key))
			{
				$this->$_ci_key =& $_ci_CI->$_ci_key;
			}
		}

		/*
		 * Extract and cache variables
		 *
		 * You can either set variables using the dedicated $this->load_vars()
		 * function or via the second parameter of this function. We'll merge
		 * the two types and cache them so that views that are embedded within
		 * other views can have access to these variables.
		 */
		if (is_array($_ci_vars))
		{
			$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
		}
		extract($this->_ci_cached_vars);

		/*
		 * Buffer the output
		 *
		 * We buffer the output for two reasons:
		 * 1. Speed. You get a significant speed boost.
		 * 2. So that the final rendered template can be
		 * post-processed by the output class.  Why do we
		 * need post processing?  For one thing, in order to
		 * show the elapsed page load time.  Unless we
		 * can intercept the content right before it's sent to
		 * the browser and then stop the timer it won't be accurate.
		 */
		ob_start();

		// If the PHP installation does not support short tags we'll
		// do a little string replacement, changing the short tags
		// to standard PHP echo statements.

		if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
		{
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
		}
		else
		{
			$_ci_CI->view->rendered_views[] = $_ci_path;

			//We set the current view
			$tmp = explode('/', $_ci_path);

			//We set the current view
			$previous_view = $this->view->current_view;
			$_ci_CI->view->current_view = $_ci_path;
			
			include($_ci_path); // include() vs include_once() allows for multiple views with the same name

			//And we set back the current view to the previous one
			$_ci_CI->view->current_view = $previous_view;

		}

		

		log_message('debug', 'File loaded: '.$_ci_path);

		// Return the file data if requested
		if ($_ci_return === TRUE)
		{
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		/*
		 * Flush the buffer... or buff the flusher?
		 *
		 * In order to permit views to be nested within
		 * other views, we need to flush the content back out whenever
		 * we are beyond the first level of output buffering so that
		 * it can be seen and included properly by the first included
		 * template and any subsequent ones. Oy!
		 *
		 */
		if (ob_get_level() > $this->_ci_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			$_ci_CI->output->append_output(ob_get_contents());
			@ob_end_clean();
		}
	}

	/**
	 * Adds a path to the view paths
	 * @param string $path
	 */
	public function add_view_path($path)
	{
		//Added path will be placed as first element of the array
		$this->_ci_view_paths = array_reverse($this->_ci_view_paths);
		$this->_ci_view_paths[$path] = TRUE;
		$this->_ci_view_paths = array_reverse($this->_ci_view_paths);
	}

	// --------------------------------------------------------------------

	/**
	 * Load Helper
	 *
	 * This function loads the specified helper file.
	 *
	 * @param	mixed
	 * @return	void
	 */
	public function helper($helpers = array())
	{
		foreach ($this->_ci_prep_filename($helpers, '_helper') as $helper)
		{
			if (isset($this->_ci_helpers[$helper]))
			{
				continue;
			}

			$helper_path = 'helpers/'.config_item('subclass_prefix').$helper.'.php';

			$ext_helper = file_exists(USERPATH . $helper_path) ? USERPATH . $helper_path : APPPATH . $helper_path;

			// Is this a helper extension request?
			if (file_exists($ext_helper))
			{
				$base_helper = BASEPATH.'helpers/'.$helper.'.php';

				if ( ! file_exists($base_helper))
				{
					show_error('Unable to load the requested file: helpers/'.$helper.'.php');
				}

				include_once($ext_helper);
				include_once($base_helper);

				$this->_ci_helpers[$helper] = TRUE;
				log_message('debug', 'Helper loaded: '.$helper);
				continue;
			}

			// Try to load the helper
			foreach ($this->_ci_helper_paths as $path)
			{
				if (file_exists($path.'helpers/'.$helper.'.php'))
				{
					include_once($path.'helpers/'.$helper.'.php');

					$this->_ci_helpers[$helper] = TRUE;
					log_message('debug', 'Helper loaded: '.$helper);
					break;
				}
			}

			// unable to load the helper
			if ( ! isset($this->_ci_helpers[$helper]))
			{
				show_error('Unable to load the requested file: helpers/'.$helper.'.php');
			}
		}
	}

}
