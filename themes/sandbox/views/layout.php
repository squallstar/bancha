<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bancha Sandbox Base Layout
 *
 * The root of your theme. All the rendering processes starts here.
 *
 * @package		Bancha
 * @author		(PHP) Nicholas Valbusa - @squallstar
 * @author		(MARKUP, JS, CSS) Matteo Gildone - @DomSmasher
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 * @credits     HTML5 Boilerplate team
 *
 */

?><!doctype html>
<html class="no-js" lang="<?php echo language(); ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo title() . settings('website_name'); ?></title>
	<meta name="description" content="<?php echo page_description(); ?>">
	<meta name="keywords" content="<?php echo page_keywords(); ?>">
	<meta name="author" content="<?php echo page_author(); ?>">

	<meta name="viewport" content="width=device-width,initial-scale=1">

	<?php echo link_tag(theme_url('css/sandbox.css')); ?>
	<?php echo link_tag(theme_url('css/style.css')); ?>

	<?php page_feed(); ?>
	<?php page_css(); ?>

	<script src="<?php echo theme_url('js/modernizr.js');?>"></script>
</head>

<body>
    <div id="wrapper">
		<?php template(); ?>
    </div>

	<script src="<?php echo theme_url('js/jquery.js');?>"></script>
	<script src="<?php echo theme_url('js/application.js');?>"></script>
	<?php page_js(); ?>

	<!-- Google Analytics code -->
	<script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
	<!-- End of Google Analytics code -->

</body>
</html>
