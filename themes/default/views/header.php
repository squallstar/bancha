<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Text Logo -->
<h1 id="logo" class="grid_4"><a href="<?php echo site_url(); ?>"><?php echo $this->settings->get('website_name'); ?></a></h1>

<!-- Navigation Menu -->
<div id="menu">
<?php echo menu($tree, 1); ?>
</div>

<div class="hr grid_12 clearfix">&nbsp;</div>

<!-- Caption Line -->
<div class="grid_12 caption">
<?php
$this->load->helper('breadcrumbs');
echo breadcrumbs($this->tree->breadcrumbs);
?>
</div>

<div class="hr grid_12 clearfix">&nbsp;</div>