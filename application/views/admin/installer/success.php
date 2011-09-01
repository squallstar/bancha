<div class="block small center login">

        <div class="block_head">
          <div class="bheadl"></div>
          <div class="bheadr"></div>

          <h2><?php echo $this->lang->_trans('%n Installer', array('n'=>CMS)); ?></h2>

        </div>

    <div class="block_content">

          <?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

      <p>
        <?php if (isset($username)) { ?>
        <ul>
          <li>Username: <strong><?php echo $username; ?></strong></li>
          <li>Password: <strong><?php echo $password; ?></strong></li>
        </ul>
        <?php } ?>

        <form action="<?php echo admin_url('auth'); ?>" method="POST">
          <input type="submit" class="submit" value="<?php echo _('Login'); ?>" />
        </form>


      </p>


        </div>

        <div class="bendl"></div>
        <div class="bendr"></div>

      </div>
