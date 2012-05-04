<div class="container">
	<header class="row clearfix">
		<div class="grid_6">
			<a class="logo" href="<?php echo site_url(); ?>"><?php echo settings('website_name'); ?></a>			
		</div>
		<nav class="grid_6"><?php echo menu(tree(), 1); ?></nav>
	</header>
</div>

<div class="container">
	<div class="row clearfix"><div class="grid_12"><hr class="styled"/></div></div>

	<div class="row clearfix">
		<div class="grid_12">
			<span class="uhere">You are here: </span>
			<?php
			load_helper('breadcrumbs');
			echo breadcrumbs(tree('breadcrumbs'));
			?>
		</div>
	</div>
	<div class="row clearfix margin_bottom_30"><div class="grid_12"><hr /></div></div>
</div>