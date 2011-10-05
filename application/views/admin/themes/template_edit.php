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

		<?php foreach ($blocks as $block_name => $value) { ?>
		
		<div class="theme_block"><?php echo $block_name; ?></div>

		
		<?php } ?>	
		

		<br /><br /><input type="submit" class="submit long" value="<?php echo _('Save template'); ?>" />
		<?php
		
		echo form_close();
		?>	
		
		<br /><br />

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>



