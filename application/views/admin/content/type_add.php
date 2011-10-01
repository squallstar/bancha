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
			<p></p>
		</div>

		<div class="sidebar_content" id="add">

<h3><?php echo _('Add new content type'); ?></h3><br />

<div class="message info"><p><?php echo _('WARNING').': '._('You can\'t change the type name after its creation.'); ?></p></div>

<?php
echo form_open();

echo form_label(_('Type name'). ' *', 'type_name') . br(1);
echo form_input(array('name' => 'type_name', 'class' => 'text')) . br(2);

echo form_label(_('Type description'). ' *', 'type_description') . br(1);
echo form_input(array('name' => 'type_description', 'class' => 'text')) . br(2);

echo form_label(_('New item label'). ' *', 'type_label_new') . br(1);
echo form_input(array('name' => 'type_label_new', 'class' => 'text'), _('New content')) . br(2);

echo form_label(_('Type structure'), 'type_tree') . br(1);
echo form_dropdown('type_tree', array('false' => _('Simple (Contents)'), 'true' => _('Tree (Pages)')), $_section == 'pages' ? 'true' : 'false', 'class="styled"') . br(1);

echo form_submit('submit', _('Add'), 'class="submit mid"');
echo form_close();

?>
		</div>
	</div>
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>