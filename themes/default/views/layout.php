<?php
/**
 * Base Layout
 *
 * Website base rendering layout
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo ($this->view->title ? $this->view->title . ' - ' : '') . _('Website title'); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<!-- Stylesheets -->
	<link rel="stylesheet" href="<?php echo theme_url(); ?>css/reset.css" />
	<link rel="stylesheet" href="<?php echo theme_url(); ?>css/styles.css" />


	<meta name="description" content="<?php echo $this->view->description; ?>">
	<meta name="keywords" content="<?php echo $this->view->keywords; ?>">
	<meta name="author" content="<?php echo $this->view->author; ?>">
	<meta name="viewport" content="width=device-width,initial-scale=1">
<?php
	if ($this->view->has_feed)
    {
      echo "\t".link_tag(current_url().'/feed.xml', 'alternate', 'application/rss+xml', isset($page) ? $page->get('title').' - Feed': 'RSS Feed');
    }

    if (isset($page))
    {
    	$css = $page->get('view_css');
    	if ($css)
    	{
    		echo "\n\t".'<style type="text/css">' . $css . '</style>';
    	}
    }
?>
</head>
<body>
    <div id="wrapper" class="container_12 clearfix">
		<?php $this->view->render($_template_file); ?>
    </div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>

<?php
if (isset($page))
{
	$js = $page->get('view_js');
   	if ($js)
   	{
   		echo "\n\t".'<script type="text/javascript">' . $js . '</script>';
   	}
}
?>

<script>
	var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
	g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));
</script>

</body>
</html>
