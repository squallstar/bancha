<div class="block">
	<div class="block_head">
		<h2><?php echo _('Unit tests'); ?></h2>
	</div>

	<div class="block_content">
		<form action="<?php echo admin_url('unit_tests/make_tests'); ?>" method="post">
            <div class="internal_padding">
            	<p><?php echo _('Press the button below to start the automatic unit tests.'); ?></p>
            	<input type="submit" class="submit" value="<?php echo _('Start tests'); ?>" />
            </div>
          </form>
	</div>
</div>