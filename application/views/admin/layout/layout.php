<?php
/**
 * Bancha Layout View (Administration)
 *
 * Vista di layout per l'amministrazione
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

$css_url = site_url() . THEMESPATH . 'admin/css/';
$js_url = site_url() . THEMESPATH . 'admin/js/';

//If is an ajax request, we will render just the content.
if ($this->input->is_ajax_request())
{
	$this->load->view($view, $content);
} else {

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->lang->current_language; ?>">
	<head>
		<title><?php echo $title != '' ? $title . ' &bull; ' : ''; ?>Bancha</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
		<meta http-equiv="X-UA-Compatible" content="IE=7" />
		<style type="text/css" media="all">
			@import url("<?php echo $css_url; ?>style_new.css");
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
				<!--<div id="content_wrapper">--><?php $this->load->view($view, $content); ?><!--</div>-->
				<div class="clear"></div>
				<?php if ($header) { $this->load->view($base.'layout/footer', $content); } ?>
			</div>
		</div>
		<script type="text/javascript">
		var site_url = '<?php echo site_url(); ?>';
		var admin_url = '<?php echo admin_url(); ?>/';
		var current_url = '<?php echo current_url(); ?>';
		var local_date_format = '<?php echo LOCAL_DATE_FORMAT; ?>';
		</script>
		<!--[if IE]><script type="text/javascript" src="<?php echo $js_url; ?>excanvas.js"></script><![endif]-->
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.img.preload.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.wysiwyg.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.date_input.pack.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.select_skin.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.tablesorter.min.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>jquery.pngfix.js"></script>
		<script type="text/javascript" src="<?php echo $js_url; ?>custom.js"></script>
	</body>
</html><?php } ?>