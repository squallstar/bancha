<?php
$this->load->helper('form');
?>
<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Manage group').': '._($group->group_name); ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url('users/groups')?>"><?php echo _('Back to groups list'); ?></a></li>
		</ul>

	</div>

	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#info"><?php echo _('Group informations'); ?></a></li>
			</ul>
			
		</div>
		<?php echo $this->view->get_messages(); ?>

		<div class="sidebar_content no_margin" id="info">
<?php

echo form_open();

if ($group)
{
	echo form_hidden('id_group', $group->id_group);
}

echo '<div class="fieldset clearfix">'.form_label(_('Group name'), 'name').'<div class="right">';
echo form_input(array('name' => 'name', 'class' => 'text'), $group ? $group->group_name : '').'</div></div>';

echo '<div class="fieldset clearfix">'.form_label(_('Permissions'), 'permissions').'<div class="right">';

$data = array(
			    'name'        => 'acl[]',
		    	'class'       => 'checkbox',
);
foreach ($acls as $acl) {
	$data['checked'] = in_array($acl->id, $user_acls);
	$data['value'] = $acl->id;

	echo form_checkbox($data).form_label(' ' . $acl->name, 'acl[]').'<div class="clear"></div>';
}

echo '</div></div><div class="fieldset noborder clearfix"><label></label><div class="right">'.form_submit('submit', $group ? _('Save changes') : _('Add group'), 'class="submit long"').'</div></div>';
echo form_close();
?>
		</div>
	</div>
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>