<?php
/**
 * Images Dispatcher (Library)
 *
 * Libreria per la generazione e restituzione al browser di immagini
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Dispatcher_Images
{
	public function retrieve($data)
	{
		$CI = & get_instance();
		$CI->load->library('image_lib');

		$sep = DIRECTORY_SEPARATOR;

		$CI->output->enable_profiler(FALSE);

		$path = $data['type'] . $sep . $data['field'] . $sep
			  . $data['id'] . $sep;

		$file_name =  'pic-' . $data['n'] . '.' . $data['ext'];

		$source_image  = $CI->config->item('attach_folder') . $path . $file_name;

		$store_path = $CI->config->item('attach_folder') . 'cache' . $sep . $path
					. $data['preset'] . $sep;

		if (!file_exists($store_path))
		{
			@mkdir($store_path, DIR_WRITE_MODE, TRUE);
			@chmod($store_path, DIR_WRITE_MODE);
		}

		$config = array(
			'source_image'		=> $source_image,
			'new_image'			=> $store_path . $file_name,
			'width'				=> 60,
			'height'			=> 60
		);

		$CI->image_lib->initialize($config); 

		$CI->image_lib->resize();

		//Output finale dell'immagine
		$CI->output->set_content_type($data['ext'])
				   ->set_output(file_get_contents($store_path  . $file_name));
		return;
	}

}