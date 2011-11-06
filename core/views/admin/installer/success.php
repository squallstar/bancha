<div class="block small center login">

        <div class="block_head">
          <h2><?php echo $this->lang->_trans('%n Installer', array('n' => CMS)); ?></h2>
        </div>

    <div class="block_content">

        <?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

        <form action="<?php echo admin_url('auth'); ?>" method="POST">
        
            <?php if (isset($username)) { ?>
            <div class="fieldset clearfix">
              <label class="full">Username: <strong><?php echo $username; ?></strong></label>
            </div>
            <div class="fieldset clearfix">
              <label class="full">Password: <strong><?php echo $password; ?></strong></label>
            </div>
            <?php } ?>
        
            <div class="fieldset noborder clearfix">
                <label class="full">
                    <input type="submit" class="submit" value="<?php echo _('Login'); ?>" />
                </label>
            </div>
        </form>
    </div>
</div>