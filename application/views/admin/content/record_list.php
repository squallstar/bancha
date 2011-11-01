<?php
$this->load->helper('form');
$fields = array_keys($tipo['fields']);

?>

<div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo ($tipo['tree']?_('Pages'):_('Contents')).': '.$tipo['description']; ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/plus.png'); ?>" /> <a href="<?php echo admin_url($_section.'/edit_record/'.$tipo['name']); ?>"><?php echo _($tipo['label_new']);?></a></li>
			<?php if ($tipo['has_categories']) { ?><li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/bookmark.png'); ?>" /> <a href="<?php echo admin_url($_section.'/type_categories/'.$tipo['name']); ?>"><?php echo _('Manage categories'); ?></a></li><?php } ?>
		</ul>
	</div>

	<div class="block_content">

	<p class="breadcrumb"><a href="<?php echo admin_url($_section); ?>"><?php echo $tipo['tree']?'Pagine':'Contenuti'; ?></a> &raquo; <?php echo $tipo['description']; ?></p>

	<?php echo $this->view->get_messages(); ?>


		<form action="" method="post">

	<?php if (count($records) || count($filters)) { ?>

		<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

			<thead>
				<tr>
					<th width="10"><input type="checkbox" class="check_all" /></th>
					<th>ID</th>
					<?php if ($tipo['stage']) { ?>
					<th><?php echo _('Status'); ?></th>
					<?php } ?>

					<?php foreach ($admin_fields as $field) {
							if ($field != $tipo['primary_key'])
							{
								$_descr = $tipo['fields'][$field]['description'];
								echo '<th>'.($_descr ? _($_descr) : $field).'</th>';
							}
						}
						?>

					<td>&nbsp;</td>
				</tr>
				<tr>
					<td></td>
					<td><?php echo form_input(array('class'	=> 'text filter tiny', 'name'	=> 'filter['.$tipo['primary_key'].']', 'value'	=> $filters[$tipo['primary_key']]));?></td>

					<?php if ($tipo['stage']) { ?>
					<td><?php echo form_dropdown('filter[published]', array('' => '', 0 => _('Draft'), 1 => _('Published'), 2 => _('Different')), $filters['published']); ?></td>
					<?php } ?>
					<?php
					foreach ($admin_fields as $field)
					{
						$filter = '';
						if ($field != $tipo['primary_key'])
						{
							switch ($tipo['fields'][$field]['type'])
							{
								case 'text':
								case 'textarea':
								case 'textarea_code':
								case 'textarea_full':
									$filter = form_input(array(
										'class'	=> 'text filter',
										'name'	=> 'filter['.$field.']',
										'value'	=> $filters[$field]
									));
									break;
								case 'hidden':
								case 'number':
									$filter = form_input(array(
										'class'	=> 'text filter tiny',
										'name'	=> 'filter['.$field.']',
										'value'	=> $filters[$field]
									));
									break;
								case 'select':
								case 'radio':
								case 'checkbox':
								case 'multiselect':
									$tipo['fields'][$field]['options'] = $this->records->get_field_options($tipo['fields'][$field]);
									if (count($tipo['fields'][$field]['options']))
									{
										//Aggiungo il primo elemento all'albero prima di stamparlo
										$new_tree = array('' => '');

										foreach ($tipo['fields'][$field]['options'] as $item_key => $item_val) {
											$new_tree[$item_key] = $item_val;
										}
										$filter = form_dropdown('filter['.$field.']',$new_tree, $filters[$field]);
									} else {
										$filter = form_input(array(
											'class'	=> 'text filter',
											'name'	=> 'filter['.$field.']',
											'value'	=> $filters[$field]
										));
									}
							}
							echo '<td>'.$filter.'</td>';
						}

					}
				?><td class="delete" style="text-align:right"><input name="apply_filters" type="submit" class="submit tiny" value="<?php echo _('Apply filters'); ?>" /></td></tr>
			</thead>

			<tbody>
<?php
	foreach ($records as $record)
	{
		$is_published = $record->get('published');
		echo '<tr' . ($tipo['stage'] && ($is_published == '0' || !$is_published) ? ' class="draft"' : '') . '>';

			$track_str = $tipo['name'].'/'.$record->id;

			$current_url = admin_url($_section.'/type/'.$tipo['name']);

			//Recurrent fields
			echo '<td><input type="checkbox" name="record[]" value="'.$record->id.'"/></td>';

			$primary_key = $tipo['primary_key'];
			echo '<td>'.$record->get($primary_key).'</td>';

			if ($tipo['stage'])
			{
				if ($is_published == '0' || !$is_published) {
					//Solo bozza
					echo '<td><a href="'.$current_url.'?publish='.$record->id.'"><img class="middle" border="0" src="'.site_url(THEMESPATH.'admin/widgets/icns/page_edit.png').'" /></a> '._('Draft').'</td>';
				} else if ($is_published == '2') {
					//Bozza + pubblico
					echo '<td><a href="'.$current_url.'?depublish='.$record->id.'"><img class="middle" border="0" src="'.site_url(THEMESPATH.'admin/widgets/icns/page_copy.png').'" /></a> '._('Different').'</td>';
				} else if (isset($tipo['fields']['date_publish']) && ((int)$record->get('_date_publish')) > time()) {
					//Programmato
					echo '<td><a href="'.$current_url.'?publish='.$record->id.'"><img class="middle" border="0" src="'.site_url(THEMESPATH.'admin/widgets/icns/time.png').'" /></a> '._('Programmed').'</td>';
				} else {
					//Pubblico
					echo '<td><a href="'.$current_url.'?depublish='.$record->id.'"><img class="middle" border="0" src="'.site_url(THEMESPATH.'admin/widgets/icns/page_green.png').'" /></a> '._('Published').'</td>';
				}
			}

			foreach ($fields as $field)
			{
				if ($tipo['fields'][$field]['admin'] === true && $field != $primary_key) {
					$value = $record->get($field);

					if (isset($tipo['fields'][$field]))
					{
						switch ($tipo['fields'][$field]['type'])
						{
							case 'select':
							case 'radio':
								if (isset($tipo['fields'][$field]['options']))
								{
									$tmp = (string)$value;
									if (isset($tipo['fields'][$field]['options'][$tmp])) {
										$value = $tipo['fields'][$field]['options'][$tmp];
									}
								}
								break;

							case 'text':
							case 'textarea':
							case 'textarea_code':
							case 'textarea_full':
							case 'hidden':
								$value = character_limiter(strip_tags($value), 30);
								break;

							case 'checkbox':
							case 'multiselect':
							case 'hierarchy':
								if (is_array($value)) {
									$values = array();
									if (isset($tipo['fields'][$field]['options'])) {
										foreach ($value as $val) {
											$tmp_val = (string)$val;
											if (isset($tipo['fields'][$field]['options'][$tmp_val])) {
												$tmp_val = $tipo['fields'][$field]['options'][$tmp_val];
											}
											$values[] = $tmp_val;
										}
									}
									$value = implode(', ', $values);
								}
								break;


						}
					}
					if ($tipo['edit_link'] == $field)
					{
						echo '<td><a href="'.admin_url($_section.'/edit_record/'.$track_str).'"><img src="'.site_url(THEMESPATH.'admin/widgets/icns/pencil.png').'" border="0" alt="" /> '.$value.'</a></td>';
					} else {
						echo '<td>'.$value.'</td>';
					}
				}

			}

			echo '<td class="delete">'

					.($tipo['tree'] ?

						'<a title="'._('Add child page').'" href="'.admin_url($_section.'/add_child_record/'.$track_str).'"><img src="'.site_url(THEMESPATH.'admin/widgets/icns/page_add.png').'" border="" alt="'._('Add child page').'" /></a>&nbsp;&nbsp;&bull;&nbsp; ' .

						'<a title="'._('View children pages').'" href="'.admin_url($_section.'/type/'.$tipo['name'].'?parent='.$record->id).'"><img src="'.site_url(THEMESPATH.'admin/widgets/icns/node-tree.png').'" border="" alt="'._('View children pages').'" /></a>&nbsp;&nbsp;&bull;&nbsp; ' : ''
					)

					.'<a title="'._('Delete').'" href="'.admin_url($_section.'/delete_record/'.$track_str).'" onclick="return confirm(\''._('Do you really want to delete this content?').'\');"><img src="'.site_url(THEMESPATH.'admin/widgets/icns/trash.png').'" border="0" alt="'._('Delete').'" /></a>'

				.'</td>';
		echo '</tr>';
	}
?>
			</tbody>
		</table>

		<div class="tableactions">
			<select name="action">
				<option value=""><?php echo _('Actions'); ?></option>
				<option value="publish"><?php echo _('Publish'); ?></option>
				<option value="depublish"><?php echo _('Unpublish'); ?></option>
				<option value="discard"><?php echo _('Discard draft'); ?></option>
				<option value="delete"><?php echo _('Delete'); ?></option>
			</select>

			<input type="submit" class="submit tiny" value="<?php echo _('Apply to selected'); ?>" />

			&nbsp;<?php echo _('There are'); ?> <strong><?php echo $total_records; ?></strong> <?php echo strtolower(_($tipo['description'])); ?>.

		</div>

		<?php }else{ ?>
		<p><br />
		<?php echo $this->lang->_trans('There are no contents/pages for this type. To start, %a.', array(
			'a'	=> '<a href="'.admin_url($_section.'/edit_record/'.$tipo['name']).'">'._('add a new one').'</a>'
		)); ?>
		</p>
		<?php } ?>

		<div class="pagination right">
			<?php echo $this->pagination->create_links(); ?>
		</div>

	</form>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>