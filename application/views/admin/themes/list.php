<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Installed themes'); ?></h2>

		<ul>
		</ul>
	</div>

	<div class="block_content">

		<?php echo $this->view->get_messages(); ?>

		<?php if (count($themes) > 0) { ?>
		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

			<thead>
				<tr>
					<th><?php echo _('Name'); ?></th>
					<th><?php echo _('Description'); ?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
					<?php foreach ($themes as $name => $description) { ?>
					<tr>
						<td><?php echo $name; ?></td>
						<td><?php echo $description; ?></td>
						<td class="delete"></td>
					</tr>
					<?php } ?>
			</tbody>
		</table>
		<?php
		} else {
			echo '<p>'._('There are no installed themes.').'</p>';
		} ?>
	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>