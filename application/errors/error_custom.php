<?php
if (!function_exists('site_url')) {
	function site_url() {
		return '/';
	}
}
if (!function_exists('admin_url')) {
	function admin_url() {
		return '/admin/';
	}
}
?><!DOCTYPE html>
<html lang="it">
	<head>

		<style type="text/css" media="all">
			<?php $css_url = site_url() . 'css/admin/'; ?>
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

   		<script type="text/javascript" src="<?php echo site_url(); ?>js/admin/jquery.js"></script>

	</head>
	<body>

	<body>

			<div id="hld">
				<div class="wrapper">


				<div class="block">

					<div class="block_head">
						<div class="bheadl"></div>
						<div class="bheadr"></div>

						<h2><?php echo $heading; ?></h2>

						<ul>
							<li><a href="#" onclick="history.go(-1);">Torna indietro</a></li>
						</ul>

					</div>

					<div class="block_content">



							<h3>Descrizione dell'errore</h3>
							<div class="message errormsg"><?php echo $message; ?></div>

							<p><a href="javascript:history.go(-1);">&laquo; Torna indietro</a><br /></p>

					</div>

					<div class="bendl"></div>
					<div class="bendr"></div>

				</div>

			</div>
		</div>


	<?php $js_url = site_url() . 'js/admin/'; ?>
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
	<script type="text/javascript" src="<?php echo $js_url; ?>admin/ajaxupload.js"></script>
	<script type="text/javascript" src="<?php echo $js_url; ?>admin/jquery.pngfix.js"></script>
	<script type="text/javascript" src="<?php echo $js_url; ?>admin/custom.js"></script>

	</body>
</html>