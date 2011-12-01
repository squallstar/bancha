<?php
/**
 * Base Layout
 *
 * Website base rendering layout
 *
 * @package		Bancha
 * @author		Matteo Gildone - @DomSmasher
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?><!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo title() . settings('website_name'); ?></title>
	<meta name="description" content="<?php echo $this->view->description; ?>">
	<meta name="keywords" content="<?php echo $this->view->keywords; ?>">
	<meta name="author" content="<?php echo $this->view->author; ?>">

	<meta name="viewport" content="width=device-width,initial-scale=1">

<?php
	echo link_tag(minify('css/style.css', 1));

	page_feed();
	page_css();
?>
	<script src="<?php echo theme_url();?>js/modernizr-2.0.6.min.js"></script>


</head>
<body>
    <div id="wrapper">
		<?php template(); ?>
    </div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo theme_url();?>js/jquery.js"><\/script>')</script>
<script src="<?php echo minify(array('js/plugins.js', 'js.application.js'));?>"></script>

<?php page_js(); ?>

<script>
	var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
	g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));
</script>

<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
	<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
<![endif]-->

</body>
</html>
