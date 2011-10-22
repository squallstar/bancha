<?php
$this->load->helper('form');
?>
<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Manage group'); ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url('users/groups')?>"><?php echo _('Back to groups list'); ?></a></li>
		</ul>

	</div>

	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#info"><?php echo _('Group informations'); ?></a></li>
			</ul>
			<p></p>
		</div>

		<div class="sidebar_content" id="info">

<h3><?php echo ($group ? _('Group').': '._($group->group_name) : _('New group')); ?></h3>

<?php echo $this->view->get_messages(); ?>
<br />
<?php

echo form_open();

if ($group)
{
	echo form_hidden('id_group', $group->id_group);
}

echo form_label(_('Group name'), 'name') . br(1);
echo form_input(array('name' => 'name', 'class' => 'text'), $group ? $group->group_name : '') . br(2);

echo form_label(_('Permissions'), 'permissions') . br(1);

$data = array(
			    'name'        => 'acl[]',
		    	'class'       => 'checkbox',
);
foreach ($acls as $acl) {
	$data['checked'] = in_array($acl->id, $user_acls);
	$data['value'] = $acl->id;

	echo form_checkbox($data).form_label(' ' . $acl->name, 'acl[]').br(1);
}

echo br(2).form_submit('submit', $group ? _('Save changes') : _('Add group'), 'class="submit long"');
echo form_close();
?>
		</div>
	</div>
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>