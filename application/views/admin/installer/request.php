<div class="block small center login">

        <div class="block_head">
          <div class="bheadl"></div>
          <div class="bheadr"></div>

          <h2><?php echo $this->lang->_trans('%n Installer', array('n'=>CMS)); ?></h2>

        </div>

		<div class="block_content">

          <?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

		  <p>When you're ready...</p>

		  	<form action="<?php echo admin_url('install'); ?>" method="POST">
		  		<input name="install" type="submit" class="submit" value="<?php echo _('Install'); ?>" />
		  	</form>

			<br />



        </div>

        <div class="bendl"></div>
        <div class="bendr"></div>

      </div>
