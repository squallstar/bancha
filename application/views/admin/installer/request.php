<div class="block small center login">
        <div class="block_head">
          <div class="bheadl"></div>
          <div class="bheadr"></div>
          <h2><?php echo $this->lang->_trans('%n Installer', array('n'=>CMS)); ?></h2>
        </div>
		<div class="block_content">

<?php if ($already_installed) { ?>
		<div class="warning message"><?php echo _('WARNING').': '._('Bancha is already installed on this database!'); ?></div>
		<p><br /><?php echo _('To reinstall Bancha, you need to set the "is_installed" key on the "settings" table to "F".'); ?></p>
<?php } else { ?>

          <?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

		  <p><?php echo _('Select the operations you want to perform'); ?>:</p>

		  	<form action="<?php echo admin_url('install'); ?>" method="POST">

			  	<div>
			  		<input type="checkbox" checked="checked" name="create_directories" value="T" /> <?php echo _('Remove/Create directories'); ?><br />
			  		<input type="checkbox" checked="checked" name="create_tables" value="T" /> <?php echo _('Drop records and create tables'); ?><br />
			  		<input type="checkbox" checked="checked" name="create_types" value="T" /> <?php echo _('Restore default types'); ?><br />
			  		<input type="checkbox" checked="checked" name="clear_cache" value="T" /> <?php echo _('Clear and rebuild cache'); ?><br />
			  		<input type="checkbox" checked="checked" name="log_events" value="T" /> <?php echo _('Enable event logging'); ?><br />
					<br />
					<?php echo _('Install type'); ?><br />
					<select class="styled" name="premade">
						<option value="default"><?php echo _('Default'); ?></option>
						<option value="blog"><?php echo _('Blog'); ?></option>
					</select><br />
			  	</div>

		  		<input name="install" type="submit" class="submit" value="<?php echo _('Install'); ?>" />
		  	</form>
<?php } ?>
			<br />

        </div>
        <div class="bendl"></div>
        <div class="bendr"></div>
      </div>