<?php
/**
 * (Core) Content Render
 *
 * The default content render of the website
 * ----------------------------------------
 * To use your custom content render, just create a file named content_render.php
 * inside the "views" directory of your theme
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if (isset($page) && $page instanceof Record)
{
	$action = $page->get('action');

	if ($action)
	{			
		switch ($action)
		{
			case 'text':
				//Page
				$this->view->render_type_template($page->tipo, 'detail');
				break;

			case 'single':
				//Single record (detail)
				if (isset($record) && $record instanceof Record)
				{
					$this->view->render_type_template($record->tipo, 'detail');
				}
				break;

			case 'list':
				$records = & $page->get('records');

				if ($records && count($records))
				{
					//We use the content type of the first record as template
					$record = $records[0];
					$this->view->render_type_template($record->tipo, 'list');
				} else {
					$this->view->render_type_template($this->content->type_name($page->get('action_list_type')), 'list');
				}
				break;
			
			case 'action_render':
				$class = $page->get('_action_class');
				if ($class)
				{
					$method = $page->get('action_custom_name');
					$class->$method('content_render');
				}
				break;

		}
	}

}

?>