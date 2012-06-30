<?php $this->load->helper('form');

?><div class="container">
    <div class="sixteen columns bancha-green-logo"></div>
    <div class="sixteen columns clearfix">

        <h1><?php echo _('Install completed!'); ?></h1>

        <?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

        <form action="<?php echo admin_url('auth'); ?>" method="POST">

            <div class="sixteen columns clearfix alpha omega">
                <?php if (isset($username)) { ?>

                <h5>1. <?php echo _('Your account details'); ?>:</h5>
                <div class="one-third column alpha">
                    <label class="full">Username: <strong><?php echo $username; ?></strong></label>
                    <label class="full">Password: <strong><?php echo $password; ?></strong></label>
                </div>
                <div class="one-third column">
                    &nbsp;
                </div>
                <?php } ?>

                <div class="one-third column omega">
                    <input type="submit" class="submit green-box btn" value="<?php echo _('Login'); ?>" />
                </div>

            </div>
        </form>
    </div>
</div>