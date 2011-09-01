<?php
$this->load->helper('form');
?><div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Edit scheme'); ?>: <?php echo $tipo['description']; ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/delete.png'); ?>" /> <a href="<?php echo admin_url($_section.'/')?>"><?php echo _('Discard all'); ?></a></li>
		</ul>


	</div>

	<div class="block_content">

	<p class="breadcrumb"><a href="<?php echo admin_url($_section); ?>"><?php echo _('Contents'); ?></a> &raquo; <a href="<?php echo admin_url($_section.'/type/'.$tipo['id'])?>"><?php echo $tipo['description']; ?></a> &raquo; <strong><?php echo _('Edit scheme'); ?></strong></p>

	<div class="message warning"><p><?php echo _('WARNING').': '._('A wrong compilation of the XML tree could cause a malfunction of the website.')?></p></div>

<?php

echo form_open();
echo form_hidden('id_type', $tipo['id']);

$attributes = array();
$attributes['name'] = 'description';
$attributes['value'] = $tipo['description'];
$attributes['class'] = 'text';
echo form_label(_('Type description'), 'description') . br(1);
echo form_input($attributes) . br(2);

$attributes = array();
$attributes['name'] = 'xml';
$attributes['value'] = $xml;
$attributes['style'] = 'height:350px;';
echo form_label(_('XML Structure'), 'xml') . br(1);
echo form_textarea($attributes) . br(2);

echo form_submit('save', _('Save scheme'), 'class="submit long"') . br(2);

echo form_close();


?>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>