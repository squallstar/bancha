<?php
/**
 * Detail View
 *
 * Page detail - Sandbox Theme
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 *
 */

?>

<div class="container">
	<div class="row clearfix">
		<div class="grid_12">
			<h1><?php echo page('title'); ?></h1>

			<p class="submenu">
				<?php echo menu(tree('current')); ?>
			</p>

			<p><?php echo page('content'); ?></p>
		</div>
	</div>
</div>

<?php
/* End of file detail.php */
/* Location: /type_templates/Default-Page/detail.php */