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
            <p>
              <label><?php echo _('Username'); ?>:</label> <br />
              <input name="username" type="text" class="text" value="<?php echo $this->input->post('username'); ?>" />
            </p>

            <p>
              <label><?php echo _('Password'); ?>:</label> <br />
              <input name="password" type="password" class="text" value="" />
            </p>

            <p>
              <input type="submit" class="submit" value="<?php echo _('Login'); ?>" /> &nbsp;
              <input type="checkbox" class="checkbox" checked="checked" id="rememberme" /> <label for="rememberme"><?php echo _('Remember me'); ?></label>
            </p>
          </form>

        </div>

        <div class="bendl"></div>
        <div class="bendr"></div>

      </div>
