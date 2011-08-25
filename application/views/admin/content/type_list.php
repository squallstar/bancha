<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo $_section == 'contents' ? _('Contents') : _('Pages'); ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url('widgets/admin/icns/plus.png'); ?>" /> <a href="<?php echo admin_url($_section.'/add_type/'); ?>"><?php echo _('Add new type'); ?></a></li>
		</ul>
	</div>

	<div class="block_content">

		<?php if (isset($message)) { ?><div class="message success"><p><?php echo $message; ?></p></div><?php } ?>

		<?php if (count($tipi)) { ?>
		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

			<thead>
				<tr>
					<th>ID</th>
					<th><?php echo _('Name'); ?></th>
					<th><?php echo _('Description'); ?></th>
					<th><?php echo _('Structure type'); ?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
					<?php foreach ($tipi as $tipo_id => $content) {
						if ($this->auth->has_permission('content', $content['name'])) {
						?>
					<tr>
						<td><?php echo $content['id']; ?></td>
						<td><a href="<?php echo admin_url($_section.'/type/'.$content['name']); ?>"><?php echo $content['name']; ?></a></td>
						<td><?php echo $content['description']; ?></td>
						<td><?php echo $content['tree'] ? _('Tree') : _('Simple'); ?></td>
						<td class="delete">
							<a href="<?php echo admin_url($_section.'/type_categories/'.$content['name']); ?>"><?php echo _('Manage categories'); ?></a> -&nbsp;
							<a href="<?php echo admin_url($_section.'/type_edit_xml/'.$content['name']); ?>"><?php echo _('Edit scheme'); ?></a> -&nbsp;
							<a onclick="return confirm('Sei sicuro di voler eliminare questo tipo?');" href="<?php echo admin_url($_section.'/type_delete/'.$content['name']); ?>"><?php echo _('Delete'); ?></a>
						</td>
					</tr>
					<?php }
					}	?>
			</tbody>
		</table>
		<?php } else {
		
			if ($_section == 'contents')
			{
				echo '<p>'.$this->lang->_trans('No type of contents found. To start, %link.', array(
					'link'	=> '<a href="'.admin_url($_section.'/add_type').'">'._('add a new one').'</a>'
				)).'</p>';
			} else {
				
			}
			?>
			<?php } ?>
	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>