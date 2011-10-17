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
	 * 
	 */
	private $_dompdfpath = '';
	
	
	/**
	 * 
	 */
	public function __contruct()
	{
		$this->_dompdfpath =  str_replace(array('\\','/'), DIRECTORY_SEPARATOR,
					APPPATH . 'libraries' . DIRECTORY_SEPARATOR . 
		  		  'externals' . DIRECTORY_SEPARATOR . 
			  		'dompdf' . DIRECTORY_SEPARATOR);
		 
		if (!is_dir($this->_dompdfpath) || !file_exists($this->_dompdfpath."dompdf_config.inc.php")) {
			show_error ("DOMPDF Library not found, please install before loading the dispatcher in [".$this->_dompdfpath."]");
		}
		
	}
	
	
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
		$CI->output->enable_profiler(FALSE);
		//debug($record,'Dispatcher_Print',1);
		
		// GENERARE il PDF
		require_once($this->_dompdfpath."dompdf_config.inc.php");
		
		if ($page instanceof Record) {
			$CI->view->set('page', $page);
			$html = $CI->view->render_template($page->get('view_template'), TRUE, '', TRUE);
		} else {
			$html = $page;
		}
		
    	$dompdf = new DOMPDF();
    	$dompdf->load_html($html);
    	$dompdf->render();

		if ($return)
		{
			return $dompdf->output(); 
		} else {
			//Pdf output
			$CI->output->set_content_type('pdf')
					   ->set_output($dompdf->output());
		}

	}
}