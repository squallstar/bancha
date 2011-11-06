<div class="block small center login">

        <div class="block_head">
          <div class="bheadl"></div>
          <div class="bheadr"></div>

          <h2><?php echo _('Administration login'); ?></h2>
          <ul>
            <li><a href="<?php echo site_url(); ?>"><?php echo _('Back to site'); ?></a></li>
          </ul>
        </div>

		<div class="block_content">

          <?php if (isset($message)) { ?><div class="message errormsg"><p><?php echo $message; ?></p></div><?php } ?>

          <form action="" method="post">
         	<div class="fieldset clearfix">
              	<label class="full"><?php echo _('Please log in with your provided credentials'); ?>:</label>
              	
            </div>
            <div class="fieldset clearfix">
              	<label><?php echo _('Username'); ?>:</label>
              	<div class="right">
              		<input name="username" type="text" class="text" value="<?php echo $this->input->post('username'); ?>" />
            	</div>
            </div>

            <div class="fieldset clearfix">
              	<label><?php echo _('Password'); ?>:</label>
              	<div class="right">
              		<input name="password" type="password" class="text" value="" />
              	</div>
            </div>

            <div class="fieldset noborder clearfix">
              	<label></label>
              	<div class="right">
              		<input type="submit" class="submit" value="<?php echo _('Login'); ?>" /> &nbsp;
              		<input type="checkbox" class="checkbox" checked="checked" id="rememberme" /> <label for="rememberme"><?php echo _('Remember me'); ?></label>
              	</div>
            </div>
          </form>

        </div>

        <div class="bendl"></div>
        <div class="bendr"></div>

      </div>
