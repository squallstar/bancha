<div class="block">
	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		<h2><?php echo _('Unit tests'); ?></h2>
	</div>

	<div class="block_content">
		<form action="<?php echo admin_url('unit_tests/make_tests'); ?>" method="post">
            <p><br />
           <?php echo _('Press the button below to start the automatic unit tests.'); ?>
            </p>

            <p>
              <input type="submit" class="submit" value="<?php echo _('Start tests'); ?>" />
            </p>
          </form>
	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>