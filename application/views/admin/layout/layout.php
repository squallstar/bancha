<?php
/**
 * Milk Layout View (Administration)
 *
 * Vista di layout per l'amministrazione
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

$css_url = site_url() . THEMESPATH . 'admin/css/';
$js_url = site_url() . THEMESPATH . 'admin/js/';

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
	<head>
		<title><?php echo $title != '' ? $title . ' &bull; ' : ''; ?>Milk</title>
		<meta http-equiv="X-UA-Compatible" content="IE=7" />
		<?php echo meta(array(
			array('Content-type', 'text/html; charset=utf-8', 'equiv'),
			array('name' => 'description', 'content' => 'Milk'),
			array('name' => 'robots', 'content' => 'no-cache')
		)); ?>
		<style type="text/css" media="all">
			@import url("<?php echo $css_url; ?>style.css");
			@import url("<?php echo $css_url; ?>jquery.wysiwyg.css");
			@import url("<?php echo $css_url; ?>facebox.css");
			@import url("<?php echo $css_url; ?>visualize.css");
			@import url("<?php echo $css_url; ?>date_input.css");
   		</style>
   		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.js"></script>
	</head>
	<body>
		<div id="hld">
			<div class="wrapper">
				<?php if ($header) { $this->load->view($base.'layout/header', $content); } ?>
				<?php $this->load->view($view, $content); ?>
				<?php if ($header) { $this->load->view($base.'layout/footer', $content); } ?>
			</div>
		</div>
		<script type="text/javascript">
		var site_url = '<?php echo site_url(); ?>';
		var admin_url = '<?php echo admin_url(); ?>/';
		</script>
		<!--[if IE]><script type="text/javascript" src="<?php echo $js_url; ?>excanvas.js"></script><![endif]-->
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.img.preload.js"></script>
		<!--<script type="text/javascript" src="<?php echo $js_url; ?>jquery.filestyle.mini.js"></script>-->
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.wysiwyg.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.date_input.pack.js"></script>
		<!--<script type="text/javascript" src="<?php echo $js_url; ?>facebox.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.visualize.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.visualize.tooltip.js"></script>-->
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.select_skin.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.tablesorter.min.js"></script>
		<!--<script type="text/javascript" src="<?php echo $js_url; ?>ajaxupload.js"></script>-->
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.pngfix.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>custom.js"></script>
	</body>
</html>