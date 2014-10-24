<?php
/**
 * Bancha Custom Error page
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if (!function_exists('site_url')) {
	function site_url($path = '') {
		return '/' . $path;
	}
}
if (!function_exists('admin_url')) {
	function admin_url() {
		return '/' . ADMIN_PUB_PATH;
	}
}

?><!DOCTYPE html>
<html lang="en">
	<head>
		<style type="text/css" media="all">
			<?php $css_url = site_url(NULL, FALSE) . THEMESPATH . 'admin/css/'; ?>
			@import url("<?php echo $css_url; ?>style.css");
			@import url("<?php echo $css_url; ?>jquery.wysiwyg.css");
			@import url("<?php echo $css_url; ?>facebox.css");
			@import url("<?php echo $css_url; ?>visualize.css");
			@import url("<?php echo $css_url; ?>date_input.css");
   		</style>
		<script type="text/javascript">
		var site_url = '<?php echo site_url(); ?>';
		var admin_url = '<?php echo admin_url(); ?>/';
		</script>
   		<script type="text/javascript" src="<?php echo site_url(NULL, FALSE) . THEMESPATH; ?>admin/js/jquery.js"></script>

	</head>
	<body class="no-header">

		<div id="hld">
			<div class="wrapper">
				<div class="block no_margin">

					<div class="block_head">
						<div class="bheadl"></div>
						<div class="bheadr"></div>

						<h2><?php echo $heading; ?></h2>

						<ul>
							<li><a href="#" onclick="history.go(-1);"><?php echo _('Go back'); ?></a></li>
						</ul>

					</div>

					<div class="block_content">

							<div class="message errormsg"><?php echo $message; ?></div>

							<div class="internal_padding">
								<p><a href="javascript:history.go(-1);">&laquo; <?php echo _('Go back'); ?></a></p>
							</div>
					</div>
				</div>
			</div>
		</div>

		<?php $js_url = site_url(NULL, FALSE) . THEMESPATH . 'admin/js/'; ?>
		<!--[if IE]><script type="text/javascript" src="<?php echo $js_url; ?>excanvas.js"></script><![endif]-->
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.img.preload.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.filestyle.mini.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.wysiwyg.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.date_input.pack.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>facebox.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.visualize.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.visualize.tooltip.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.select_skin.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.tablesorter.min.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>ajaxupload.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.pngfix.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>custom.js"></script>
	</body>
</html>