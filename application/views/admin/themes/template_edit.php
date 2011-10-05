<?php echo $this->load->helper('form'); ?>

<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Edit blocks') . ': ' . $template; ?></h2>

		<ul>
		</ul>
	</div>

	<div class="block_content theme_blocks">

		<?php
		echo form_open();
		echo $this->view->get_messages();
		?>

		<?php foreach ($blocks as $block_name => $sections) { ?>

		<div class="theme_block" data-name="<?php echo $block_name; ?>"><h3><?php echo $block_name; ?></h3>

		<?php if (is_array($sections) && count($sections)) {
				foreach ($sections as $section) {
					//Do something
					debug($section);
				}
			?>

		<?php } ?>

			<a href="#" class="add_section" onclick="bancha.blocks.add_section(this);"><?php echo _('Add section'); ?></a>

		</div>

		<?php } ?>


		<br /><br /><input type="submit" class="submit long" value="<?php echo _('Save changes'); ?>" />
		<?php

		echo form_close();
		?>

		<br /><br />

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>

<div class="hidden">
		<div id="add_section" style="padding:10px; background:#fff;">

		</div>
</div>

<link type="text/css" rel="stylesheet" href="<?php echo site_url(THEMESPATH.'admin/css/colorbox.css'); ?>" />
<script type="text/javascript" src="<?php echo site_url(THEMESPATH.'admin/js/jquery.colorbox.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".link").colorbox({width:"50%", inline:true, href:"#add_section"});
});
</script>