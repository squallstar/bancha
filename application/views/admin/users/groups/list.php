<?php
$this->load->helper('text');

?>

<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Groups and permissions'); ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/plus.png'); ?>" /> <a href="<?php echo admin_url('users/groups/edit'); ?>"><?php echo _('Add new group'); ?></a></li>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url('users/'); ?>"><?php echo _('Back to users'); ?></a></li>
		</ul>
	</div>

	<div class="block_content">
	
	<p class="breadcrumb"><a href="<?php echo admin_url('users/groups'); ?>"><?php echo _('Groups and permissions'); ?></a> &raquo; <?php echo _('List'); ?></p>

	<?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>


		<form action="" method="post">

	<?php if (is_array($groups)) { ?>

		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

			<thead>
				<tr>
					<th>ID</th>
					<th><?php echo _('Group name'); ?></th>
					<td>&nbsp;</td>
				</tr>
			</thead>

			<tbody>
<?php
	foreach ($groups as $group) {
		echo '<tr>';

			//Campi ricorrenti
			echo '<td>'.$group->id_group.'</td>'
				.'<td><a href="'.admin_url('users/groups/edit/'.$group->id_group).'">'.$group->group_name.'</a></td>';
			echo '<td class="delete">'
					.'<a href="'.admin_url('users/group_delete/'.$group->id_group).'" onclick="return confirm(\''._('Do you want to delete this group?').'\');">'._('Delete group').'</a>'
				.'</td>';
		echo '</tr>';
	}
?>
			</tbody>
		</table>


		

	</form>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>

<?php
}else{
	echo 'Nessun record trovato.';
}