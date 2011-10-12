<?php
/**
 * Print Dispatcher (Library)
 *
 * This is the PDF dispatcher class of the routing system.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @author		Alessandro Maroldi - alexmaroldi@gmail.com - @alexmaroldi
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Dispatcher_Print
{
	/**
	 * Generates the PDF, or returns the content
	 * @param Record|html $page
	 * @param bool $return
	 * @return mixed
	 */
	public function render($page, $return = FALSE)
	{
		$CI = & get_instance();

		if ($page instanceof Record)
		{
			$html = $CI->view->render_template($page->get('view_template'));
		} else {
			$html = & $page;
		}

		//Here goes the PDF generation

		if ($return)
		{
			return 1;
		} else {
			//Pdf output
			$CI->output->set_content_type('pdf')
				   ->set_output($html);
		}

	}