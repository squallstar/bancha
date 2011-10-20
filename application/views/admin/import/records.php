<?php $this->load->helper('form'); ?>
<div class="block withsidebar code_format">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		<h2><?php echo _('Import/export data'); ?></h2>
	</div>

	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#sb-import"><?php echo _('Import records'); ?></a></li>
				<li><a href="#sb-export"><?php echo _('Export records'); ?></a></li>
			</ul>
		</div>

		<div class="sidebar_content" id="sb-import">
			<h3><?php echo _('Import records'); ?></h3>
			<p>
			
			<?php 
			echo $this->view->get_messages();
			
			echo form_open_multipart(ADMIN_PUB_PATH . 'import/step/1');
			
			echo form_label(_('Destination content type'), 'type_id') . br(1);
			echo form_dropdown('type_id', $tipi, null, 'class="styled"') . br(1);

			echo form_label(_('Adapter type'), 'adapter_type') . br(1);
			echo form_dropdown('adapter_type', $adapters, null, 'class="styled"') . br(1);
			
			echo form_label(_('Source file'), 'records') . br(1);
			echo form_upload('records') . br(2);
			
			echo form_submit('submit', _('Import records'), 'class="submit long" onclick="$(this).fadeOut(200, function() {$(\'img.hidden\').fadeIn();});"');
			echo form_close();
			
			?>
			<img class="hidden" src="<?php echo site_url() . THEMESPATH . 'admin/widgets/loading.gif'; ?>" />
			
		</div>
		
		<div class="sidebar_content" id="sb-export">
			<h3><?php echo _('Export records'); ?></h3>
			<p>
			
			<div class="message warning"><p><?php echo _('WARNING').': '._('This function is under construction!'); ?></p></div>
			
			</p>
		</div>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>
