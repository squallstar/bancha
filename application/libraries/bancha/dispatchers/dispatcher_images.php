<?php
/**
 * Images Dispatcher (Library)
 *
 * This router generates the preset images and sends them to the client
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

Class Dispatcher_Images
{
	/**
	 * Generates an image if not exists, then the image will be
	 * served to the client during the same request.
	 */
	public function retrieve($data)
	{
		$CI = & get_instance();

		//We retrieve the content type
		$tipo = $CI->content->type($data['type']);

		//Let's check if the request field is an image
		if ($tipo['fields'][$data['field']]['type'] != 'images')
		{
			show_error('The field [' . $data['field'] . '] is not an "imagelist" field.', 400);
		}

		//Aumento il limite di memoria
		ini_set('memory_limit', MEMORY_LIMIT);

		//Carico le librerie che mi servono
		$CI->output->enable_profiler(FALSE);
		$CI->load->library('image_lib');
		$CI->load->config('image_presets');

		$sep = DIRECTORY_SEPARATOR;

		$presets_list = $CI->config->item('presets');

		if (isset($presets_list[$data['preset']]))
		{
			$preset = $presets_list[$data['preset']];
		} else {
			//Preset not found
			show_error('Preset ' . $data['preset'] . ' not found', 404);
			return;
		}

		$path = $data['type'] . $sep . $data['field'] . $sep
			  . $data['id'] . $sep;

		$file_name =  $data['filename'] . '.' . $data['ext'];

		$source_image  = $CI->config->item('attach_folder') . $path . $file_name;

		$store_path = $CI->config->item('attach_folder') . 'cache' . $sep . $path
					. $data['preset'] . $sep;

		//Paths will be uniformed (windows sucks at this)
		$source_image = str_replace(DIRECTORY_SEPARATOR, '/', $source_image);
		$store_path = str_replace(DIRECTORY_SEPARATOR, '/', $store_path);

		//We check if the original file exists
		if (!file_exists($source_image))
		{
			show_error('The original source image was not found.', 404);
			return;
		}

		//If the cache directory not exists, we will create it
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
		$done = TRUE;
		foreach ($preset as $operation)
		{
			if (!$done)
			{
				break;
			}
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
				if (isset($operation['fixed']) && $operation['fixed'] == TRUE)
				{
					list($img_w, $img_h) = getimagesize($config['source_image']);
					if ($img_h <= $img_w && $config['height'] != '?')
					{
						$config['width'] = round($config['height']*$img_w/$img_h);
					} else if ($config['width'] != '?') {
						$config['height'] = round($config['width']*$img_h/$img_w);
					}
				}
			}

			switch ($operation['operation'])
			{
				case 'resize':
					$CI->image_lib->initialize($config);
					$done = $CI->image_lib->resize();
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
					$done = $CI->image_lib->crop();
					break;
			}

			if (!$done)
			{
				log_message('error', $CI->image_lib->display_errors());
				return;
			}
		}

		//The final output is sent to the client
		$CI->output->set_content_type($data['ext'])
				   ->set_output(file_get_contents($store_path  . $file_name));
		return;
	}

}