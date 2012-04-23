<?php
$tipi = $this->content->types();
?><div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Content types'); ?></h2>

		<ul>
			<li><a href="<?php echo admin_url('schemes/rebuild_cache/'); ?>"><?php echo _('Rebuild schemes cache'); ?></a></li>
			<?php if ($this->auth->has_permission('types', 'add')) { ?>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/plus.png'); ?>" /> <a href="<?php echo admin_url('contents/add_type/'); ?>"><?php echo _('Add new type'); ?></a></li>
			<?php } ?>
		</ul>
	</div>

	<div class="block_content">

		<?php echo $this->view->get_messages(); ?>

		<?php if (count($tipi)) { ?>
		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

			<thead>
				<tr>
					<th>ID</th>
					<th><?php echo _('Name'); ?></th>
					<th><?php echo _('Description'); ?></th>
					<th><?php echo _('New item label'); ?></th>
					<th><?php echo _('Structure type'); ?></th>
					<th><?php echo _('Table'); ?></th>
					<th><?php echo _('Stage table'); ?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
					<?php foreach ($tipi as $tipo_id => $content) {
	
						if ($this->auth->has_permission('types', 'manage')) {
						?>
					<tr>
						<td><?php echo $content['id']; ?></td>
						<td><a href="<?php echo admin_url(($content['tree'] ? 'pages' : 'contents') . '/type_edit_xml/'.$content['name']); ?>"><?php echo $content['name'].'.'.(isset($content['source']) ? $content['source'] : 'xml'); ?></a></td>
						<td><?php echo $content['description']; ?></td>
						<td><?php echo $content['label_new']; ?></td>
						<td><?php echo $content['tree'] ? _('Tree') : _('Simple'); ?></td>
						<td><?php echo $content['table']; ?></td>
						<td><?php echo $content['table_stage']; ?></td>
						<td class="delete">
							<a href="<?php echo admin_url('schemes/rebuild/'.$content['name']); ?>"><?php echo _('Rebuild tables'); ?></a> 
							-&nbsp;

							<a href="<?php echo admin_url(($content['tree'] ? 'pages' : 'contents').'/type_categories/'.$content['name']); ?>"><?php echo _('Manage categories'); ?></a> 

							<?php if ($this->auth->has_permission('types', 'delete')) { ?>
							-&nbsp;
							<a onclick="return confirm('Do you want to delete this content type?');" href="<?php echo admin_url(($content['tree'] ? 'pages' : 'contents').'/type_delete/'.$content['name']); ?>"><?php echo _('Delete'); ?></a>
							<?php } ?>

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
					'link'	=> '<a href="'.admin_url('contents/add_type').'">'._('add a new one').'</a>'
				)).'</p>';
			}
			?>
			<?php } ?>
	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>