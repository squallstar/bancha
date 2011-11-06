<?php $this->load->helper('form'); ?>
<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Add new content type'); ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url($_section.'/')?>"><?php echo _('Back to types list'); ?></a></li>
		</ul>

	</div>

	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#add"><?php echo _('Add new type'); ?></a></li>
			</ul>
		</div>

		<div class="message info"><p><?php echo _('WARNING').': '._('You can\'t change the type name after its creation.'); ?></p></div>

		<div class="sidebar_content" id="add">


<?php
echo form_open();

echo '<div class="fieldset clearfix">';
echo form_label(_('Type name'). ' *', 'type_name') . '<div class="right">';
echo form_input(array('name' => 'type_name', 'class' => 'text')) . '</div></div>';

echo '<div class="fieldset clearfix">';
echo form_label(_('Type description'). ' *', 'type_description') . '<div class="right">';
echo form_input(array('name' => 'type_description', 'class' => 'text'));
echo '</div></div>';

echo '<div class="fieldset clearfix">';
echo form_label(_('New item label'). ' *', 'type_label_new') .'<div class="right">';
echo form_input(array('name' => 'type_label_new', 'class' => 'text'), _('New content'));
echo '</div></div>';

echo '<div class="fieldset clearfix">';
echo form_label(_('Type structure'), 'type_tree') . '<div class="right">';
echo form_dropdown('type_tree', array('false' => _('Simple (Contents)'), 'true' => _('Tree (Pages)')), $_section == 'pages' ? 'true' : 'false', 'class="styled"');
echo '</div></div>';

echo '<div class="fieldset clearfix noborder"><label></label><div class="right">';
echo form_submit('submit', _('Add'), 'class="submit mid"');
echo '</div></div>';
echo form_close();

?>
		</div>
	</div>
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>