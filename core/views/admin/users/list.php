<?php
$this->load->helper('text');

$acl_groups = $this->auth->has_permission('users', 'groups');
$acl_edit = $this->auth->has_permission('users', 'add');
$my_iduser = $this->auth->user('id');

?>

<div class="block">

	<div class="block_head">

		<h2><?php echo _('Users list')?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/plus.png'); ?>" /> <a href="<?php echo admin_url('users/edit/'); ?>"><?php echo _('Add new user'); ?></a></li>
			<?php if ($acl_groups) { ?>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/bookmark.png'); ?>" /> <a href="<?php echo admin_url('users/groups/'); ?>"><?php echo _('Groups and permissions'); ?></a></li>
			<?php } ?>
		</ul>
	</div>

	<div class="block_content">
	
	<p class="breadcrumb"><a href="<?php echo admin_url('users'); ?>"><?php echo _('Users'); ?></a> &raquo; <?php echo _('List'); ?></p>

	<?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>


		<form action="" method="post">

	<?php if (is_array($users)) { ?>

		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

			<thead>
				<tr>
					<th width="10"><input type="checkbox" class="check_all" /></th>
					<th>ID</th>
					<th><?php echo _('Username'); ?></th>
					<th><?php echo _('Name'); ?></th>
					<th><?php echo _('Surname'); ?></th>
					<th><?php echo _('Email address'); ?></th>
					<th><?php echo _('Group'); ?></th>
					<td>&nbsp;</td>
				</tr>
			</thead>

			<tbody>
<?php
	foreach ($users as $user) {
		echo '<tr>';
			echo '<td><input type="checkbox" /></td>';
			echo '<td>'.$user->id_user.'</td>'
				.($acl_edit || $user->id_user == $my_iduser ? '<td><a href="'.admin_url('users/edit/'.$user->id_user).'">'.$user->username.'</a></td>' : '<td>'.$user->username.'</td>')
				.'<td>'.$user->name.'</td>'
				.'<td>'.$user->surname.'</td>'
				.'<td>'.$user->email.'</td>'
				.($acl_groups ? '<td>'.($user->id_group ? '<a href="'.admin_url('users/groups/edit/'.$user->id_group).'">'._($user->group_name).'</a>' : '') . '</td>' : ($user->id_group ? _($user->group_name) : ''));

			echo '<td class="delete">'
					.($acl_edit ? '<a href="'.admin_url('users/delete/'.$user->id_user).'" onclick="return confirm(\''._('Do you want to delete this user?').'\');">'._('Delete').'</a>' : '')
				.'</td>';
		echo '</tr>';
	}
?>
			</tbody>
		</table>
		<div class="tableactions">
			<select>
				<option><?php echo _('Actions'); ?></option>
				<?php if ($acl_edit) { ?><option><?php echo _('Delete'); ?></option><?php } ?>
			</select>

			<input type="submit" class="submit tiny" value="<?php echo _('Apply to selected'); ?>" />
			
			<?php echo $this->lang->_trans('There are %n users.', array('n'=>'<strong>'.$total_records.'</strong>')); ?>
			
		</div>



		<div class="pagination right">
			<?php echo $this->pagination->create_links(); ?>
		</div>
		
		

	</form>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>

<?php
}else{
	echo 'Nessun record trovato.';
}