<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Menu List View
 *
 * Content type list
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 *
 */

$records = & $page->get('records');

?>

<div class="details">
	<h1><?php echo $page->get('title'); ?></h1>
	<p class="info"><?php echo menu($this->tree->get_current_branch()); ?></p>
</div>

<div class="body"><?php echo $page->get('content'); ?></div>

<?php if ($records && is_array($records) && count($records)) { ?>

	<ul>
	<?php foreach ($records as $record) { ?>
		<li>
			<?php echo $record->get('date_publish'); ?> - 
			<strong><?php echo $record->get('title'); ?></strong><br />
			<a href="<?php echo current_url() . '/' . $record->get('uri'); ?>"><?php echo _('View detail'); ?></a>
			<br /><br />
		</li>
	<?php } ?>
	</ul>
	
	<?php
	if (isset($this->pagination))
	{
		echo $this->pagination->create_links();
	}
}

/* End of file list.php */
/* Location: /type_templates/Menu/list.php */