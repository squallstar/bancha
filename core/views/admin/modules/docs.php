<div class="block withsidebar code_format docs">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		<h2><?php echo _('Module documentation').': '.$module; ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url('modules')?>"><?php echo _('Back to modules'); ?></a></li>
		</ul>
	</div>

	<div class="block_content">

		<?php echo $documentation; ?>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>
