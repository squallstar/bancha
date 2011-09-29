<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<header>
<div class="title"><a href="<?php echo site_url(); ?>"><?php echo $this->settings->get('website_name'); ?></a></div>
<nav><?php echo menu($tree, 1); ?></nav>
</header>
