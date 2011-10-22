<?php echo $this->load->helper('form'); ?><div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Installed themes'); ?></h2>

		<ul>
		</ul>
	</div>

	<div class="block_content">

		<?php echo $this->view->get_messages(); ?>

		<?php if (count($themes) > 0) {
			echo form_open();
		?>
		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

			<thead>
				<tr>
					<th><?php echo _('Name'); ?></th>
					<th><?php echo _('Description'); ?></th>
					<th><?php echo _('Desktop theme'); ?></th>
					<th><?php echo _('Mobile theme'); ?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
					<?php foreach ($themes as $name => $description) { ?>
					<tr>
						<td><?php echo $name; ?></td>
						<td><?php echo $description; ?></td>
						<td align="center"><?php echo form_radio('desktop_theme', $name, $desktop_theme == $name ? TRUE : FALSE); ?></td>
						<td align="center"><?php echo form_radio('mobile_theme', $name, $mobile_theme == $name ? TRUE : FALSE); ?></td>
						<td class="delete"><a href="<?php echo admin_url('themes/theme/' . $name); ?>"><?php echo _('Manage templates'); ?></a></td>
					</tr>
					<?php } ?>
			</tbody>
		</table>

		<input type="submit" class="submit tiny" value="<?php echo _('Apply changes'); ?>" /><br /><br />
		<?php

		echo form_close();
		} else {
			echo '<p>'._('There are no installed themes.').'</p>';
		} ?>
	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>