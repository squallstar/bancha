<div class="block">

	<div class="block_head">

		<h2><?php echo _('Modules'); ?></h2>


	</div>

	<div class="block_content">

		<?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

		<!--<div class="message info"><?php echo _('These are the currently installed modules. To install a module, just place it in the application/modules folder.'); ?></div>-->
		<?php echo $this->view->get_messages(); ?>

		<div class="internal_padding">
			<h3><?php echo _('Installed modules'); ?></h3><hr />
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
						<tr data-key="<?php echo $module; ?>">
							<td><?php echo ucfirst(str_replace('_', ' ', $module)); ?></td>
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
		<hr />

		<div class="internal_padding">
			<h3><?php echo _('Available modules on Bancha Repository'); ?></h3><hr />

			<table cellpadding="0" cellspacing="0" width="100%" class="sortable" id="available-modules">
				<thead>
					<tr>
						<th><?php echo _('Name'); ?></th>
						<th><?php echo _('Category'); ?></th>
						<th><?php echo _('Version'); ?></th>
						<th><?php echo _('Author'); ?></th>
						<th><?php echo _('Actions'); ?></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">
(function($) {
	$(document).ready(function() {
		$.ajax({
			url: "<?php echo $this->config->item('repository_url'); ?>",
			dataType: 'jsonp',
			success: function (data) {
				var tbl = $('#available-modules tbody');
				$.each(data, function(index) {
					tbl.append('<tr class="'+(index % 2 == 0 ? 'even' : 'odd')+'"><td><strong>'+this.title+'</strong><br />'+this.abstract+'</td><td>'+this.category+'</td><td>'
						       +this.version+'</td><td>'+this.author+'</td><td><form action="" method="post"><input type="hidden" name="package" value="'+this.package+'" />'
						       +'<input type="hidden" name="slug" value="'+this.slug+'" /><input type="submit" class="submit tiny" value="<?php echo _("Install"); ?>" /></form></td></tr>');
				});
			}
		});
	});
})(jQuery);
</script>