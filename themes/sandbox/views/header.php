<header>
	
	<div class="logo">
		<a href="<?php echo site_url(); ?>"><?php echo settings('website_name'); ?></a>
	</div>
	
	<nav><?php echo menu(tree(), 1); ?></nav>

	<?php
	load_helper('breadcrumbs');
	echo 'You are here: ' . breadcrumbs(tree('breadcrumbs'));
	?>

</header>

<hr />