<?php
/**
 * Schemes Controller
 *
 * -- development only --
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Core_Schemes extends Bancha_Controller
{

	public function __construct()
	{
	    parent::__construct();
	    $this->load->database();

	    $this->content->set_stage(TRUE);

	    $this->view->base = 'admin/';
	}

	/**
	 * List of schemes
	 */
	public function index()
	{
		$this->view->render_layout('schemes/list');
	}

	public function rebuild($type_name = '')
	{
		$this->load->dbforge();
		$this->load->frlibrary('schemeforge');

		$type = $this->content->type($type_name);
		if (!$type) show_error('Content type not found');

		$done = $this->schemeforge->recreate_by_scheme($type);

		if ($done)
		{
			$this->view->message('success', $this->lang->_trans('The tables of the content type %n has been rebuilded.', array('n' => '[' . $type_name . ']')));
		}
		$this->index();
	}

	public function rebuild_cache()
	{
		//Database cache
        if (CACHE) $this->db->cache_delete_all();

        //Settings cache
        $this->load->settings();
        $this->settings->clear_cache();

        //Pages cache
        $files = get_filenames($this->config->item('cache_path'));
        if (is_array($files) && count($files))
        {
        	foreach ($files as $file)
        	{
        		@unlink($this->config->item('cache_path') . $file);
        	}
        }

        //Content types cache
    	if (!$this->content->rebuild())
        {
    		show_error(_('Cannot renew the cache.'), 500, _('Error'));
    	} else {
            $this->view->message('success', _('The cache of the content types has been cleared.'));
            $this->index();
        }
	}
}