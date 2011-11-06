<?php echo $this->load->helper('form'); ?>

<div class="block">

	<div class="block_head">

		<h2><?php echo _('Theme templates') . ': ' . $theme_description; ?></h2>

		<ul>
		</ul>
	</div>

	<div class="block_content">

		<?php echo $this->view->get_messages(); ?>

		<?php if (count($templates) > 0) { ?>

		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

			<thead>
				<tr>
					<th><?php echo _('Template name'); ?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
					<?php foreach ($templates as $filename) { ?>
					<tr>
						<td><?php echo $filename; ?></td>
						<td class="delete"><!--<a href="#"><?php echo _('Duplicate template'); ?></a> - --><a href="<?php echo admin_url('themes/theme/' . $theme . '/' . urlencode(str_replace('.php', '', str_replace('/', '|', $filename)))); ?>"><?php echo _('Edit blocks'); ?></a></td>
					</tr>
					<?php } ?>
			</tbody>
		</table>

		<?php } ?>

		<?php if (count($files) > 0) { ?>

		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

			<thead>
				<tr>
					<th><?php echo _('Filename'); ?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
					<?php foreach ($files as $filename) { ?>
					<tr>
						<td><?php echo $filename; ?></td>
						<td class="delete"><a href="<?php echo admin_url('themes/theme/' . $theme . '/' . urlencode(str_replace('.php', '', $filename))); ?>"><?php echo _('Edit blocks'); ?></a></td>
					</tr>
					<?php } ?>
			</tbody>
		</table>

		<?php } ?>
		

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>



