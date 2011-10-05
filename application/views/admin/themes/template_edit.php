<?php echo $this->load->helper('form'); ?>

<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Edit blocks') . ': ' . rtrim($template, '.php'); ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url('themes/theme/' . $theme)?>"><?php echo _('Back to templates list'); ?></a></li>
		</ul>
	</div>

	<div class="block_content theme_blocks">

		<?php
		echo form_open();
		echo $this->view->get_messages();
		?>

		<?php
		if (is_array($blocks) && count($blocks))
		{
		foreach ($blocks as $block_name => $sections) { ?>

		<div class="theme_block" data-name="<?php echo $block_name; ?>"><h3><?php echo ucfirst($block_name); ?></h3>

		<?php if (is_array($sections) && count($sections)) {
				foreach ($sections as $section) {
					//Do something
					debug($section);
				}
			?>

		<?php } ?>

			<a href="#" class="add_section" onclick="bancha.blocks.set_section('<?php echo $block_name; ?>');"><?php echo _('Add section'); ?></a>

		</div>

		<?php } ?>


			<input type="submit" class="submit long" value="<?php echo _('Save changes'); ?>" />
		<?php
		} else {
			echo '<div class="message warning">' . _('This templates has no blocks.') . '</div>';
		}


		echo form_close();
		?>
		<br />
		

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>

<div class="hidden">
		<div id="add_section">
		
			<?php echo $this->view->render('admin/themes/section_composer'); ?>


		</div>
</div>

<link type="text/css" rel="stylesheet" href="<?php echo site_url(THEMESPATH.'admin/css/colorbox.css'); ?>" />
<script type="text/javascript" src="<?php echo site_url(THEMESPATH.'admin/js/jquery.colorbox.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".add_section").colorbox({width:"65%", inline:true, href:"#add_section"});
});
</script>