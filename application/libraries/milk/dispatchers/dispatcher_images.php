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
	/**
	 * Genera una immagine con un preset scelto se non esiste
	 * sul filesystem. Dopodiche' la restituisce al client
	 */
	public function retrieve($data)
	{
		$CI = & get_instance();
		$CI->output->enable_profiler(FALSE);

		$CI->load->library('image_lib');
		$CI->load->config('image_presets');

		$sep = DIRECTORY_SEPARATOR;

		$presets_list = $CI->config->item('presets');

		if (isset($presets_list[$data['preset']]))
		{
			$preset = $presets_list[$data['preset']];
		} else {
			//Preset non trovata
			echo 'Preset not found';
			return;
		}

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
			'new_image'			=> $store_path . $file_name
		);

		$first = TRUE;
		foreach ($preset as $operation)
		{
			$config = array(
				'source_image'		=> $first ? $source_image : $store_path . $file_name,
				'new_image'			=> $store_path . $file_name
			);
			$first = FALSE;

			if (isset($operation['quality']))
			{
				$config['quality'] = (int) $operation['quality'];
			}

			if (isset($operation['ratio']))
			{
				$config['maintain_ratio'] = $operation['ratio'];
			} else {
				$config['maintain_ratio'] = FALSE;
			}

			if ($operation['operation'] == 'resize' || $operation['operation'] == 'crop')
			{
				list($width, $height) = explode('x', $operation['size']);
				if ($width == '?')
				{
					$config['width'] = 1;
					$config['height'] = (int)$height;
					$config['maintain_ratio'] = TRUE;
					$config['master_dim'] = 'height';
				} else if ($height == '?') {
					$config['height'] = 1;
					$config['width'] = (int)$width;
					$config['maintain_ratio'] = TRUE;
					$config['master_dim'] = 'width';
				} else {
					$config['width'] = (int)$width;
					$config['height'] = (int)$height;
				}
			} 

			switch ($operation['operation'])
			{
				case 'resize':
					$CI->image_lib->initialize($config); 
					$CI->image_lib->resize();

					break;
				
				case 'crop':
					if (isset($operation['x']))
					{
						$config['x_axis'] = (int) $operation['x'];
					}
					if (isset($operation['y']))
					{
						$config['y_axis'] = (int) $operation['y'];
					}
					$CI->image_lib->initialize($config); 
					$CI->image_lib->crop();
					

			}
		}

		

		//Output finale dell'immagine
		$CI->output->set_content_type($data['ext'])
				   ->set_output(file_get_contents($store_path  . $file_name));
		return;
	}

}