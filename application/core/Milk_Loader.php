<?php
Class Milk_Loader extends CI_Loader {

	private $_loaded_modules = array();

	/**
	 * Funzione che precarica ciò che serve normalmente in Milk
	 */
	function milk()
	{

		//Loads milk standard libraries
		$this->library(
			array(
				'milk/content', 'milk/xml', 'milk/view'
			)
		);

		//Preload the record class
		require_once(FRPATH . 'record.php');

		//Loads the default helpers
		$this->helper(
			array(
				'file', 'website', 'text'
			)
		);

		//Loads the authentication model
		$this->auth();

		//Loads the record model
		$this->records();

		//Loads the pages model
		$this->pages();

		//Loads the tree model
		$this->tree();
	}

	function frmodel($model, $name = '', $db_conn = FALSE)
	{
		$this->model(FRNAME.'/'.$model, $name, $db_conn);
	}

	function records()
	{
		$this->model(FRNAME.'/model_records', 'records');
	}

	function tree()
	{
		$this->model(FRNAME.'/model_tree', 'tree');
	}

	function documents()
	{
		$this->model(FRNAME.'/model_documents', 'documents');
	}

	function categories()
	{
		$this->model(FRNAME.'/model_categories', 'categories');
	}

	function auth()
	{
		$this->model(FRNAME.'/model_auth', 'auth');
	}

	function users()
	{
		$this->model(FRNAME.'/model_users', 'users');
	}

	function pages()
	{
		$this->model(FRNAME.'/model_pages', 'pages');
	}

	function events()
	{
		$this->model(FRNAME.'/model_events', 'events');
	}

	function module($module_name)
	{
		if (!count($this->_loaded_modules))
		{
			require_once(FRPATH . 'module'.EXT);
		}
		$module_name = strtolower($module_name);
		if (!isset($this->_loaded_modules[$module_name]))
		{
			$CI = & get_instance();
			require_once($CI->config->item('modules_folder').$module_name.DIRECTORY_SEPARATOR.$module_name.'_module'.EXT);
			$this->_loaded_modules[$module_name] = TRUE;
		}
		$class_name = ucfirst($module_name).'_Module';
		return new $class_name();
	}

	// --------------------------------------------------------------------

	/**
	 * Loader
	 *
	 * This function is used to load views and files.
	 * Variables are prefixed with _ci_ to avoid symbol collision with
	 * variables made available to view files
	 *
	 * @param	array
	 * @return	void
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
			$this->view->rendered_views[] = $_ci_path;
			include($_ci_path); // include() vs include_once() allows for multiple views with the same name
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

	public function add_view_path($path)
	{
		$this->_ci_view_paths[$path] = TRUE;
	}

}
