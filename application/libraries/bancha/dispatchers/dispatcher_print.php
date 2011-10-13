<?php
/**
 * Print Dispatcher (Library)
 *
 * This router generates the print page for client
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
	 * Generates an image if not exists, then the image will be
	 * served to the client during the same request.
	 */
	public function render($page, $return = FALSE)
	{
		$CI = & get_instance();
		$CI->output->enable_profiler(FALSE);
		//debug($record,'Dispatcher_Print',1);
		
		// GENERARE il PDF
		$path = APPPATH . 'libraries' . DIRECTORY_SEPARATOR . 
		  		  'externals' . DIRECTORY_SEPARATOR . 
			  		'dompdf' . DIRECTORY_SEPARATOR;
		$path = str_replace(array('\\','/'), DIRECTORY_SEPARATOR, $path);
		require_once($path."dompdf_config.inc.php");
		
		if ($page instanceof Record) {
			$CI->view->set('page', $page);
			$html = $CI->view->render_template($page->get('view_template'), TRUE, '', TRUE);
		} else {
			$html = $page;
		}
		
    	$dompdf = new DOMPDF();
    	$dompdf->load_html($html);
    	$dompdf->render();
		
		if ($return) {
			return $dompdf->output(); 
		} else {
			//The final output is sent to the client
			$CI->output->set_content_type('pdf')
					   ->set_output($dompdf->output());
		}
		return;
	}

}