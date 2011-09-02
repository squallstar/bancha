<?php
/**
 * Record Edit View
 *
 * Vista di modifica di un record
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

$this->load->helper('form'); ?>

<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo (!$record->id ? _('New content') : _('Edit content') ) . ': ' . $tipo['description']; ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url($_section.'/type/'.$tipo['id'])?>"><?php echo _('Back to list'); ?></a></li>
		</ul>

	</div>



	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<?php foreach ($tipo['fieldsets'] as $fieldset) { ?>
				<li><a href="#sb-<?php echo url_title($fieldset['name']); ?>"><?php echo $fieldset['name']; ?></a></li>
				<?php }
				if ($tipo['has_categories']) { ?>
				<li><a href="#sb_category"><?php echo _('Categories'); ?></a>
				<?php } ?>
			</ul>
			<p></p>
		</div>



<?php

$save_buttons = form_submit('_bt_save', _('Save'), 'class="submit"')
			   .form_submit('_bt_save_list', _('Save and go to list'), 'class="submit long"')
			   .form_submit('_bt_publish', _('Publish'), 'class="submit"')
;

echo form_open_multipart('admin/'.$_section.'/edit_record/'.$tipo['name'].($record->id?'/'.$record->id:''), array('id' => 'record_form', 'name' => 'record_form'));

/*
echo form_hidden('id_type', $tipo['id']);

if ($record->id) {
	echo form_hidden('id', $record->id);
}
*/

//Contiene le funzioni onchange da richiamare al load della pagina
$js_onload = '';

$first_lap = TRUE;
$has_full_textarea = FALSE;
$p_start = '<p>';
$p_end = '</p>';

foreach ($tipo['fieldsets'] as $fieldset)
{

	echo '<div class="sidebar_content" id="sb-'.url_title($fieldset['name']).'">';

	//Messaggi di errore
	echo isset($message) ? '<div class="message errormsg"><p>'.$message.'</p></div>' : '';
	echo isset($ok_message) ? '<div class="message success"><p>'.$ok_message.'</p></div>' : '';

	?>
			<p class="breadcrumb">
				<a href="<?php echo admin_url($_section); ?>">Contenuti</a> &raquo;
				<a href="<?php echo admin_url($_section.'/type/'.$tipo['id'])?>"><?php echo $tipo['description']; ?></a> &raquo;
				<strong><?php echo !$record->id ? _('New content') : _('Edit content'); ?></strong>
			</p>
			<?php

			echo br(1).'<h3>'.$fieldset['name'].'</h3>'.br(1);

	if ($first_lap == true)
	{
		$first_lap = false;

		//Spostare in xml
		if ($tipo['tree'] && isset($tree)) {

			if ($record->id && isset($page_url))
			{
				$url = site_url($page_url);
				echo _('The address of this page is:').br(1).'<a target="_blank" href="'.$url.'">'.$url.'</a>'.br(2);
			}

			//echo $p_start.form_label(_('Parent page'), 'id_parent') . br(1);
			//echo form_dropdown('id_parent', $new_tree, $record->get('id_parent'), 'class="styled"') . $p_end.br(1);
		}

	}

	foreach ($fieldset['fields'] as $field_name)
	{

		$field = $tipo['fields'][$field_name];

		$attributes = array();

		$label = form_label($field['description'], $field_name, $attributes);

		if ($field['default'] && substr($field['default'], 0, 5) == 'eval:')
		{
			eval('$value = '.substr($field['default'], 5).';');
			$field['default'] = $value;
		}

		$field_value = $record->get($field_name, $field['default']);

		if (isset($field['visible']))
		{
			if ($field['visible'] === false) {
				echo '<div class="field-'.$field_name.' hidden">';
			}
		}

		if (isset($field['onkeyup']))
		{
			$attributes['onkeyup'] = $field['onkeyup'];
			$js_onload .= trim($field['onkeyup'], ';').'; ';
		}

		if ($field['mandatory'] && in_array($field['type'], array('text', 'number', 'date', 'datetime')))
		{
			$attributes['required'] = 'required';
		}

		switch ($field['type'])
		{
			case 'hidden':
				echo form_hidden($field_name, $field_value);
				break;

			case 'text':
				$attributes['name'] = $field_name;
				$attributes['value'] = $field_value;
				$attributes['class'] = 'text'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_input($attributes).$p_end;
				break;

			case 'textarea':
				$attributes['name'] = $field_name;
				$attributes['value'] = $field_value;
				$attributes['class'] = 'wysiwyg'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_textarea($attributes).$p_end;
				break;

			case 'textarea_full':
				$attributes['name'] = $field_name;
				$attributes['value'] = $field_value;
				$attributes['class'] = ''.($field['mandatory']?' mandatory':'');
				$attributes['id'] = 'ckeditor_'.$field_name;
				$js_onload .="CKEDITOR.replace( '".$attributes['id']."', { filebrowserBrowseUrl : admin_url + 'ajax/finder/' + $('input[name=".$tipo['primary_key']."]').val() });";
				$has_full_textarea = TRUE;
				echo $p_start.$label.br(1).form_textarea($attributes).$p_end;
				break;

			case 'date':
				$attributes['name'] = $field_name;
				$attributes['value'] = $field_value;
				$attributes['class'] = 'date_picker text small'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_input($attributes).$p_end;
				break;

			case 'datetime':
				$tmp = explode(' ', $field_value);
				$attributes['name'] = $field_name;
				$attributes['value'] = $tmp[0] ? $tmp[0] : date('d/m/Y');
				$attributes['class'] = 'date_picker text small'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_input($attributes);

				$attributes['name'] = '_time_'.$field_name;
				$attributes['value'] = isset($tmp[1]) ? $tmp[1] : date('H:i');
				$attributes['class'] = 'time_picker text small';
				$attributes['type'] = 'time';
				echo '&nbsp;'.form_input($attributes);
				echo $p_end;
				break;

			case 'number':
				$attributes['name'] = $field_name;
				$attributes['type'] = 'number';
				$attributes['value'] = $field_value;
				$attributes['class'] = 'number text small'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_input($attributes).$p_end;
				break;

			case 'select':
				$add = '';
				if (isset($field['onchange'])) {
					$add .= 'onchange="'.$field['onchange'].'" ';
					$js_onload .= trim($field['onchange'], ';').'; ';
				}
				$add .= 'class="styled'.($field['mandatory']?' mandatory':'');
				$add .='" ';
				echo $p_start.$label.br(1).form_dropdown($field_name, $field['options'], $field_value, $add).$p_end;
				break;

			case 'checkbox':
				echo $p_start.$label.br(1);
				foreach ($field['options'] as $opt_key => $opt_val) {
					$checked = is_array($field_value) ? (in_array($opt_key, $field_value) ? 'checked' : '') : '';
					$data = array(
					    'name'        => $field_name.'[]',
					    'value'       => $opt_key,
					    'checked'     => $checked,
					    'class'       => 'checkbox',
					);

					echo form_checkbox($data).form_label(' '.$opt_val, $field_name.'[]');
				}
				echo $p_end;
				break;

			case 'multiselect':
				$add = '';
				if (isset($field['onchange'])) {
					$add .= 'onchange="'.$field['onchange'].'" ';
					$js_onload .= trim($field['onchange'], ';').'; ';
				}
				$add .= 'class="multi '.($field['mandatory']?' mandatory':'');
				$add .='" ';
				$field['options']['multiple'] = '';
				echo $p_start.$label.br(1);

				$left_options = array();
				$right_options = array();
				foreach ($field['options'] as $opt_key => $opt_val)
				{
					if (is_array($field_value) && in_array($opt_key, $field_value))
					{
						$right_options[$opt_key] = $opt_val;
					} else {
						$left_options[$opt_key] = $opt_val;
					}
				}

				echo '<div class="multiselect_'.$field_name.'"><div class="multi_left">'.form_dropdown($field_name.'[]', $left_options, $field_value, $add);
				echo '<a href="#" class="add">'._('Add').'</a>'.'</div>';

				echo '<div class="multi_right">'.form_dropdown('_'.$field_name, $right_options, $field_value, $add);
				echo '<a href="#" class="rem">'._('Remove').'</a>'.'</div></div>';

				$nm = 'multiselect_'.$field_name;

				$js_onload.= "$('.".$nm." .add').click(function(){ ".
					 "return !$('.".$nm." .multi_left select option:selected').remove().appendTo('.".$nm." .multi_right select'); });";
				$js_onload.= "$('.".$nm." .rem').click(function(){ ".
					 "return !$('.".$nm." .multi_right select option:selected').remove().appendTo('.".$nm." .multi_left select'); });";

				echo $p_end;
				break;

			case 'radio':
				echo $p_start.$label.br(1);
				foreach ($field['options'] as $opt_key => $opt_val) {
					$data = array(
					    'name'        => $field_name,
					    'value'       => $opt_key,
					    'checked'     => $opt_key == $field_value ? 'checked' : '',
					    'class'       => 'radio',
					);

					echo form_radio($data).form_label(' '.$opt_val, $field_name);
				}
				echo $p_end;
				break;

			case 'images':
				echo $p_start.$label.br(1);
				$count = $field_value != '' ? count($field_value) : 0;

				//Multi upload on webkit+ff browsers
				$attributes['name'] = $field_name.'[]';
				$attributes['multiple'] = ' ';

				if ($count < $field['max']) {
					echo br(1).form_upload($attributes).br(1).$this->lang->_trans('You can attach up to %n images', array('n' => $field['max']));
				} else {
					echo '<span class="limit">'._('Number of uploadable images excedeed.').'</span>';
					echo '<div class="hidden limit">'.br(1).form_upload($attributes).br(1).'('.$this->lang->_trans('You can attach up to %n images', array('n' => $field['max'])).')</div>';
				}
				if ($count && is_array($field_value)) {
					echo '<table cellpadding="0" cellspacing="0" width="100%" class="sortable">'
						.'<thead><tr><th>'._('Preview').'</th><th>'._('Original').'</th><th>'._('Resized').'</th><th>'._('Alternative text').'</th><th>'._('Order').'</th><th></th></tr></thead><tbody>';
					;
					foreach ($field_value as $image) {
						$src = $image->thumb_path ? $image->thumb_path : $image->path;
						echo '<tr><td><img src="'. attach_url($src) . '" alt="" border="0" /></td>'
							.'<td><a target="_blank" href="'. attach_url($image->path) . '">'._('View').'</a><br />'.$image->width.' x '.$image->height.' px<br />'.$image->size.' Kb</td>'
							.'<td>'.($image->resized_path ? '<a target="_blank" href="'. attach_url($image->resized_path) . '">'._('View').'</a>':'').'</td>'
							.'<td><input name="_alt_text['.$image->id_document.']" type="text" class="text small" value="' . $image->alt_text . '" /></td>'
							.'<td><input class="tbl-priority text small" name="_priority['.$image->id_document.']" type="text" value="' . $image->priority . '" /></td>'
							.'<td class="delete"><img align="absmiddle" src="'.site_url(THEMESPATH.'admin/widgets/icns/delete.png').'" /> <a href="#" onclick="return milk.delete.document(this, '.$image->id_document.');">'._('Delete image').'</a></td>'
							."</tr>\n";
					}
					echo '</tbody></table>';
				}
				echo $p_end;
				break;

			case 'files':
				echo $p_start.$label.br(1);
				$count = $field_value != '' ? count($field_value) : 0;

				//Multi upload on webkit+ff browsers
				$attributes['name'] = $field_name.'[]';
				$attributes['multiple'] = ' ';

				if ($count < $field['max']) {
					echo br(1).form_upload($attributes).br(1).'('._trans('You can attach up to %n files', array('n' => $field['max'])).')';
				} else {
					echo '<span class="limit">'._('File limit exceeded.').'</span>';
					echo '<div class="hidden limit">'.br(1).form_upload($attributes).br(1).'('._trans('You can attach up to %n files', array('n' => $field['max'])).')</div>';
				}
				if ($count && is_array($field_value)) {
					echo '<table cellpadding="0" cellspacing="0" width="100%" class="sortable">'
						.'<thead><tr><th>'._('File name').'</th><th>'._('File type').'</th><th>'._('Alternative text').'</th><th></th></tr></thead><tbody>';
					;
					foreach ($field_value as $file) {
						echo '<tr><td><a href="'. attach_url($file->path) . '">'.$file->name.'</a></td>'
							.'<td>'.$file->mime.'</td>'
							.'<td><input name="_alt_text['.$file->id_document.']" type="text" class="text small" value="' . $file->alt_text . '" /></td>'
							.'<td class="delete"><img align="absmiddle" src="'.site_url(THEMESPATH.'admin/widgets/icns/delete.png').'" /> <a href="#" onclick="return milk.delete.document(this, '.$file->id_document.');">'._('Delete file').'</a></td>'
							."</tr>\n";
					}
					echo '</tbody></table>';
				}
				echo $p_end;
				break;

		}

		if ($field['type'] != 'hidden')
		{
			echo br(1)."\n";
		}

		if (isset($field['visible'])) {
			if ($field['visible'] === false) {
				echo '</div>';
			}
		}

	}


	echo $save_buttons;

	echo '</div>';

} //end fieldset foreach

	if ($tipo['has_categories']) { ?>
	<div class="sidebar_content" id="sb_category">
		<h3><?php echo _('Categories'); ?></h3>

		<?php

		if (count($categories)) { ?>

		<p><?php echo _('This content can be associated to these categories'); ?>:<br /></p>

		<?php
			$data = array(
			    'name'        => 'categories[]',
		    	'class'       => 'checkbox',
			);
			foreach ($categories as $category) {
				$data['checked'] = is_array($record->get('categories')) ? in_array($category->id, $record->get('categories')) : FALSE;
				$data['value'] = $category->id;

				echo form_checkbox($data).form_label(' '.$category->name, 'categories[]');
			}

			echo br(3).$save_buttons;

		} else {
			echo '<p>'._('This type has no categories').'.</p>';
		}


		?>

	</div>
	<?php }

echo form_close() . br(2);

?>

	</div>
	<div class="bendl"></div>
	<div class="bendr"></div>

</div>

<?php if ($has_full_textarea) { ?>
<script type="text/javascript" src="<?php echo site_url() . THEMESPATH; ?>admin/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo site_url() . THEMESPATH; ?>admin/js/ckeditor/adapters/jquery.js"></script>
<?php }

if ($tipo['has_attachments']) {
	$js_onload.= "$('table.sortable tbody').sortable({ stop: function(event, ui) { milk.sort_priority(event, ui); } });";
	?>
<script type="text/javascript" src="<?php echo site_url() . THEMESPATH; ?>admin/js/jquery-ui.js"></script>
<?php } ?>



<script type="text/javascript">
$(document).ready(function() {
	<?php echo $js_onload; ?>
});
</script>