<?php echo $this->load->helper('form'); ?>

<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Sections'); ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/delete.png'); ?>" /> <a href="#" onclick="$('#cboxClose').click();"> <?php echo _('Close'); ?></a></li>
		</ul>
	</div>

	<div class="block_content">
		
		<div class="sidebar">
			<ul class="sidemenu">
				<!--<li><a href="#module"><?php echo _('Add module'); ?></a></li>-->
				<li><a href="#code"><?php echo _('Add HTML'); ?></a></li>
			</ul>
			<p>This section is purely experimental!</p>
		</div>

		<div class="sidebar_content" id="code">
			<h3><?php echo _('Add HTML'); ?></h3>

			<?php echo form_open('', array('onsubmit' => 'return false;')); 
			echo form_hidden('section_type', 'html');
			
			echo form_textarea(array('name' => 'html', 'class' => 'code')).br(2);
			echo form_submit(array('name' => 'html-submit', 'class' => 'submit mid', 'onclick' => "bancha.blocks.save_section('#code');"), 'Add');
			echo form_close();
			?>

		</div>

		<div class="sidebar_content" id="module">
			<h3><?php echo _('Add module'); ?></h3>

		</div>

		

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>