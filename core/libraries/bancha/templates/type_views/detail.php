<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * {name} Detail View
 *
 * Content type detail
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 *
 */

?>

<div class="details">
	<h1><?php echo $page->get('title'); ?></h1>
	<p class="info"><?php echo menu($this->tree->get_current_branch()); ?></p>
</div>

<div class="body">

<?php if (isset($record) && $record instanceof Record) { ?>

	<h1><?php echo $record->get('title'); ?></h1>

	<div class="text"><?php echo $record->get('content'); ?></div>

	<?php
	//We display the images (from the image field) of the current record
	$images = $record->get('images');
	if (is_array($images) && count($images)) {
		foreach ($images as $image) {
			echo '<img src="' . attach_url($image->resized_path) . '" /><br /><br />';
		}
	}
}
?>
</div><div class="clear"></div>

<?php
/* End of file detail.php */
/* Location: /type_templates/{name}/detail.php */