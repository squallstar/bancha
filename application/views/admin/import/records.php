<?php $this->load->helper('form');

?>
<div class="block withsidebar code_format">

	<div class="block_head">
		<h2><?php echo _('Import/export data'); ?></h2>
	</div>

	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#sb-import"><?php echo _('Import records'); ?></a></li>
			</ul>
		</div>

		<?php echo $this->view->get_messages(); ?>

		<div class="sidebar_content no_margin" id="sb-import">
			<?php 
			echo form_open_multipart(ADMIN_PUB_PATH . 'import/step/1');
			
			echo '<div class="fieldset clearfix">';
			echo form_label(_('Destination content type'), 'type_id');
			echo '<div class="right">';
			echo form_dropdown('type_id', $tipi, null, 'class="styled"');
			echo '</div></div>';

			echo '<div class="fieldset clearfix">';
			echo form_label(_('Adapter type'), 'adapter_type');
			echo '<div class="right">';
			echo form_dropdown('adapter_type', $adapters, null, 'class="styled"');
			echo '</div></div>';
			
			echo '<div class="fieldset clearfix">';
			echo form_label(_('Source file'), 'records');
			echo '<div class="right">';
			echo form_upload('records');
			echo '</div></div>';
			
			echo '<div class="fieldset clearfix"><label></label><div class="right">';
			echo form_submit('submit', _('Import records'), 'class="submit long" onclick="$(this).fadeOut(200, function() {$(\'img.hidden\').fadeIn();});"');
			?>
			<img class="hidden" src="<?php echo site_url() . THEMESPATH . 'admin/widgets/loading.gif'; ?>" />
			<?php
			echo '</div></div>';
			echo form_close();
			
			?>
			
		</div>
	</div>
</div>
