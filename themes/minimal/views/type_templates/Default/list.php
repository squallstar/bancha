<?php
/**
 * List View
 *
 * Content type list
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 *
 */

if(!defined('BASEPATH')) exit('No direct script access allowed');

$records = & $page->get('records');

echo '<div class="details"><h1>'.$page->get('title').'</h1>'.
	 '<p class="info">'.menu($this->tree->get_current_branch()).'</p></div>'.
	 '<div class="body">'.$page->get('content').br(2);

if ($records && is_array($records) && count($records)) {
?>
	<ul>
	<?php

		foreach ($records as $record) {
			?>
			<li>
				<strong><?php echo $record->get('title'); ?></strong><br />
				<a href="<?php echo current_url() . '/' . $record->get('uri'); ?>"><?php echo _('View detail'); ?></a>
				<br /><br />
			</li>
			<?php
		}

	?>
	</ul>
	<?php

	if (isset($this->pagination))
	{
		echo $this->pagination->create_links();
	}

}

echo '</div><div class="clear"></div>';

/* End of file list.php */
/* Location: /type_templates/Default/list.php */