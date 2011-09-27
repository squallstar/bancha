<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Text Logo -->
<h1 id="logo" class="grid_4">Name</h1>

<!-- Navigation Menu -->
<?php echo menu($tree, 1); ?>

<div class="hr grid_12 clearfix">&nbsp;</div>

<!-- Caption Line -->
<div class="grid_12 caption">
<?php
$this->load->helper('breadcrumbs');
echo breadcrumbs($this->tree->breadcrumbs);
?>
</div>

<div class="hr grid_12 clearfix">&nbsp;</div>