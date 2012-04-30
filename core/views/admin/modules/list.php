<div class="block">

	<div class="block_head">

		<h2><?php echo _('Modules'); ?></h2>
	</div>

	<div class="block_content">

		<?php echo $this->view->get_messages(); ?>

		<div class="internal_padding">
			<img align="absmiddle" src="<?php echo site_url(THEMESPATH . 'admin/widgets/icns/plugin_add.png'); ?>" border="0" alt=""/> <?php echo _('You can manually upload a package to install it.'); ?><hr />
			<form action="" method="post" enctype="multipart/form-data">
				<input type="file" name="package" /><input type="submit" class="submit tiny" value="Install" />
			</form>
		</div>
		<hr />

		<div class="internal_padding">
			<h3><img align="absmiddle" src="<?php echo site_url(THEMESPATH . 'admin/widgets/icns/plugin.png'); ?>" border="0" alt=""/> <?php echo _('Installed modules'); ?></h3><hr />
			<?php if (count($modules)) { ?>
			<table cellpadding="0" cellspacing="0" width="100%" class="sortable" id="installed-modules">
				<thead>
					<tr>
						<th><?php echo _('Name'); ?></th>
						<th><?php echo _('Version'); ?></th>
						<th><?php echo _('Documentation'); ?></th>
					</tr>
				</thead>
				<tbody>
						<?php foreach ($modules as $module) {
							?>
						<tr data-key="<?php echo $module; ?>">
							<td><?php echo ucfirst(str_replace('_', ' ', $module)); ?></td>
							<td data-type="version">0.4v</td>
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
			<h3><img align="absmiddle" src="<?php echo site_url(THEMESPATH . 'admin/widgets/icns/resources.png'); ?>" border="0" alt=""/> <?php echo _('Available modules on Bancha Repository'); ?></h3><hr />

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
				var tbl = $('#available-modules tbody'), instbl = $('#installed-modules tbody'), action = '';
				$.each(data, function(index) {
					var instrow = $('tr[data-key="'+this.slug+'"]', instbl);

					if (instrow.length) {
						//Already installed. Version check
						if ($('td[data-type="version"]', instrow).text() != this.version) {
							//Upgrade
							action = '<input type="submit" class="submit tiny" value="<?php echo _("Update"); ?>"';
						} else {
							//Same
							action = '<?php echo _("Already installed"); ?>';
						}
					} else {
						//Not installed
						action = '<input type="submit" class="submit tiny" value="<?php echo _("Install"); ?>"';
					}
					var data = '<tr class="'+(index % 2 == 0 ? 'even' : 'odd')+'"><td><strong>'+this.title+'</strong><br />'+this.abstract+'</td><td>'+this.category+'</td><td>'
						       +this.version+'</td><td>'+this.author+'</td><td><form action="" method="post"><input type="hidden" name="package" value="'+this.package+'" />'
						       +'<input type="hidden" name="slug" value="'+this.slug+'" />'+action+'</form></td></tr>';
					tbl.append(data);
				});
				tbl.on('click', 'input[type="submit"]', function() {
					$(this).parent('form').submit();
					$(this).replaceWith('<img src="<?php echo site_url(THEMESPATH . "admin/widgets/loading.gif"); ?>" border="0" alt="" />');
				});
			}
		});
	});
})(jQuery);
</script>