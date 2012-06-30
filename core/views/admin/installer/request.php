<?php $this->load->helper('form');


?><div class="container">
	<div class="sixteen columns bancha-green-logo"></div>
	<div class="sixteen columns clearfix">
		<?php if ($already_installed === 'T') { ?>
				<div class="warning message"><?php echo _('WARNING').': '._('Bancha is already installed on this database!'); ?></div>
				<h4><?php echo _('To reinstall Bancha, you need to delete the "is_installed" key on the "settings" table.'); ?></h4>
		<?php } else { ?>
		<h1><?php echo $this->lang->_trans('%n Installer', array('n'=>'Bancha ' . BANCHA_VERSION)); ?></h1>

		<?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

	  	<form action="<?php echo admin_url('install'); ?>" method="POST">

	  		<h4><?php echo _('Bancha will be installed on the current host. Please select the operations that you want to perform'); ?></h4>

		  	<div class="sixteen columns clearfix alpha omega">
		  		<div class="one-third column alpha">
					<h5>1. <?php echo _('Basic operations'); ?></h5>

					<input type="checkbox" checked="checked" name="create_directories" value="T" /> <?php echo _('Remove/Create directories'); ?><br />
					<input type="checkbox" checked="checked" name="create_tables" value="T" /> <?php echo _('Drop records and create tables'); ?><br />
					<input type="checkbox" checked="checked" name="create_types" value="T" /> <?php echo _('Restore default types'); ?><br />
					<input type="checkbox" checked="checked" name="populate_settings" value="T" /> <?php echo _('Restore default settings'); ?><br />
					<input type="checkbox" checked="checked" name="clear_cache" value="T" /> <?php echo _('Clear and rebuild cache'); ?><br />
		  		</div>
		  		<div class="one-third column">
		  			<h5>2. <?php echo _('Options'); ?></h5>
					<label><?php echo _('Admin default language'); ?></label>
					<?php echo form_dropdown('language', $this->config->item('languages_select'), $this->lang->current_language, 'class="styled"'); ?>

					<label><?php echo _('Install type'); ?></label>
					<select class="styled" name="premade">
					<option value="blog"><?php echo _('Full'); ?></option>
					<option value="default"><?php echo _('Minimal'); ?></option>
					</select>

		  		</div>
		  		<div class="one-third column omega">
					<h5>3. <?php echo _('Final touches'); ?></h5>
					<label><?php echo _('Preinstalled schemes format'); ?></label>
					<select class="styled" name="scheme_format">
					<option value="yaml">YAML (<?php echo _('Expert'); ?>)</option>
					<option value="xml">XML (<?php echo _('Novice'); ?>)</option>
					</select>

					<label><?php echo _('Theme'); ?></label>
					<?php echo form_dropdown('theme', $this->view->get_available_themes(), 'teabag', 'class="styled"'); ?>

		  		</div>
		  	</div>

		  	<div class="sixteen columns clearfix alpha omega">
		  		<div class="one-third column alpha">
					&nbsp;
		  		</div>
		  		<div class="one-third column">
		  			&nbsp;
		  		</div>
		  		<div class="one-third column omega">
					<input name="install" onclick="$(this).fadeOut(200, function() {$('div.hidden').fadeIn();});" type="submit" class="btn submit green-box" value="<?php echo _('Install'); ?>" />
			  		<div class="hidden grey-loader"></div>
		  		</div>
		  	</div>

	  	</form>

		<?php } ?>
	</div>
</div>