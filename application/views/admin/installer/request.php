<?php
$this->load->helper('form');
?><div class="block small center login">
        <div class="block_head">
          <div class="bheadl"></div>
          <div class="bheadr"></div>
          <h2><?php echo $this->lang->_trans('%n Installer', array('n'=>CMS)); ?></h2>
        </div>
		<div class="block_content">

<?php if ($already_installed === 'T') { ?>
		<div class="warning message"><?php echo _('WARNING').': '._('Bancha is already installed on this database!'); ?></div>
		<form><div class="fieldset clearfix"><label class="full"><?php echo _('To reinstall Bancha, you need to delete the "is_installed" key on the "settings" table.'); ?></label></div></form>
<?php } else { ?>

          <?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

		  	<form action="<?php echo admin_url('install'); ?>" method="POST">

		  		<div class="fieldset noborder clearfix">
			  		<label class="full"><?php echo _('Bancha will be installed on the current host. Please select the operations that you want to perform'); ?>:</label>
			  </div>

		  		<div class="fieldset clearfix">
		  			<label><?php echo _('Select operations'); ?></label>
				  	<div class="right">
				  		<input type="checkbox" checked="checked" name="create_directories" value="T" /> <?php echo _('Remove/Create directories'); ?><br />
				  		<input type="checkbox" checked="checked" name="create_tables" value="T" /> <?php echo _('Drop records and create tables'); ?><br />
				  		<input type="checkbox" checked="checked" name="create_types" value="T" /> <?php echo _('Restore default types'); ?><br />
				  		<input type="checkbox" checked="checked" name="populate_settings" value="T" /> <?php echo _('Restore default settings'); ?><br />
				  		<input type="checkbox" checked="checked" name="clear_cache" value="T" /> <?php echo _('Clear and rebuild cache'); ?><br />
				  		<input type="checkbox" checked="checked" name="log_events" value="T" /> <?php echo _('Enable event logging'); ?><br />
					</div>
				</div>

				<div class="fieldset clearfix">
		  			<label><?php echo _('Install type'); ?></label>
				  	<div class="right">
						<select class="styled" name="premade">
							<option value="blog"><?php echo _('Blog'); ?></option>
							<option value="default"><?php echo _('Default'); ?></option>
						</select>
					</div>
				</div>

				<div class="fieldset clearfix">
		  			<label><?php echo _('Theme'); ?></label>
				  	<div class="right">
						<?php echo form_dropdown('theme', $this->config->item('installed_themes'), null, 'class="styled"'); ?>
					</div>
				</div>

				<div class="fieldset clearfix noborder">
					<label></label>
					<div class="right">
				  		<input name="install" onclick="$(this).fadeOut(200, function() {$('img.hidden').fadeIn();});" type="submit" class="submit" value="<?php echo _('Install'); ?>" />
				  		<img class="hidden" src="<?php echo site_url() . THEMESPATH . 'admin/widgets/loading.gif'; ?>" />
			  		</div>
			  	</div>
		  	</form>
<?php } ?>
        </div>
      </div>