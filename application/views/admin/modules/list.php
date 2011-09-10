<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Installed modules'); ?></h2>


	</div>

	<div class="block_content">

		<?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

		<?php if (count($modules)) { ?>
		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">
			<thead>
				<tr>
					<th><?php echo _('Name'); ?></th>
					<th><?php echo _('Documentation'); ?></th>
				</tr>
			</thead>
			<tbody>
					<?php foreach ($modules as $module) {
						?>
					<tr>
						<td><?php echo ucfirst($module); ?></td>
						<td><a href="<?php echo admin_url('modules/docs/'.$module); ?>"><?php echo _('View'); ?></a></td>

					</tr>
					<?php }
					?>
			</tbody>
		</table>
			<?php } else {
				echo _('There are no modules installed.');
			} ?>
	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>