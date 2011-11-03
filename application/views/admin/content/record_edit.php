<?php
/**
 * Record Edit View
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

$this->load->helper('form');
$this->load->frlibrary('form_renderer');
$CI = & get_instance();

?>

<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo (!$record->id ? _($tipo['label_new']) : _('Edit content') ) . ': ' . $tipo['description']; ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url($_section.'/type/'.$tipo['id'])?>"><?php echo _('Back to list'); ?></a></li>
		</ul>

	</div>



	<div class="block_content">

<?php

	//We store all the breadcrumbs into a single variable
echo '<p class="breadcrumb"><a href="'.admin_url($_section).'">'.($_section == 'contents' ? _('Contents') : _('Pages')).'</a> '
						 . '&raquo; <a href="'.admin_url($_section.'/type/'.$tipo['id']).'">'.$tipo['description'].'</a> &raquo; '
						 . (!$record->id ? _($tipo['label_new']) : (_('Edit content') . ' &raquo; <strong>' . $record->get($tipo['edit_link']) . '</strong>')) . '</p>'; ?>


		<div class="sidebar">
			<ul class="sidemenu">
				<?php echo $CI->form_renderer->get_sidebar($tipo); ?>
			</ul>
			<p></p>
		</div>



<?php

//Messages
echo $this->view->get_messages();

echo form_open_multipart(isset($action) ? $action : ADMIN_PUB_PATH.$_section.'/edit_record/'.$tipo['name'].($record->id?'/'.$record->id:''), array('id' => 'record_form', 'name' => 'record_form'));

/******************************/
/* Recurring variables */

	$js_onload = '';
	$first_lap = TRUE;
	$has_full_textarea = FALSE;
	$p_start = '<div class="fieldset clearfix">';
	$p_end = '</div></div>';
	$validator_rules = array();

/* End of recurring variables */
/******************************/



foreach ($tipo['fieldsets'] as $fieldset)
{

	echo '<div class="sidebar_content" id="sb-'.url_title($fieldset['name']).'">';

			echo '<h3>'._($fieldset['name']).'</h3>';

	if ($first_lap == true)
	{
		$first_lap = false;
		if ($tipo['tree'])
		{
			if ($record->id && isset($page_url))
			{
				$url = site_url($page_url);
				echo '<div class="fieldset clearfix">'
					 . '<label>' . _('Page address:') . '</label>'
					 . '<label class="full"><a target="_blank" href="'.$url.'">'.$url.'</a></label></div>';
			}
		}
	}

	foreach ($fieldset['fields'] as $field_name)
	{
		$field = $tipo['fields'][$field_name];

		$attributes = array();

		$field_note = '';
		if ($field['note'])
		{
			$field_note = '<span class="note">' . _($field['note']) . '</span>';
		}

		//Validation rules
		if (isset($field['rules']) && strlen($field['rules']))
		{
			$validator_rules[]= array(
				'name'		=> $field_name,
				'display'	=> '['._($field['description']).']',
				'rules'		=> $field['rules']
			);
		}

		$label = form_label(_($field['description']), $field_name, $attributes) . '<div class="right">';

		//We evaluates the evals
		if ($field['default'] && substr($field['default'], 0, 5) == 'eval:')
		{
			eval('$value = '.substr($field['default'], 5).';');
			$field['default'] = $value;
		}

		//The default value will be set when no stored value is found
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
			//$attributes['required'] = 'required';
		}

		//Localized options
		if (isset($field['options']) && is_array($field['options']) && $field['type'] != 'hierarchy')
		{
			$tmp = array();
			foreach ($field['options'] as $opt_key => $opt_val)
			{
				$tmp[$opt_key] = _($opt_val);
			}
			$field['options'] = $tmp;
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
				echo $p_start.$label.form_input($attributes).$field_note.$p_end;
				break;

			case 'password':
				$attributes['name'] = $field_name;
				$attributes['value'] = $field_value;
				$attributes['class'] = 'text'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.form_password($attributes).$field_note.$p_end;
				break;

			case 'textarea':
				$attributes['name'] = $field_name;
				$attributes['value'] = $field_value;
				$attributes['class'] = 'wysiwyg'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.form_textarea($attributes).$p_end;
				break;

			case 'textarea_code':
				$attributes['name'] = $field_name;
				$attributes['value'] = $field_value;
				$attributes['class'] = 'code'.($field['mandatory']?' mandatory':'');
				$attributes['id'] = 'texteditor_'.$field_name;
				echo $p_start.$label.form_textarea($attributes).$p_end;
				$js_onload.= "bancha.tab_textarea('#".$attributes['id']."');";
				break;

			case 'textarea_full':
				$attributes['name'] = $field_name;
				$attributes['value'] = $field_value;
				$attributes['class'] = ''.($field['mandatory']?' mandatory':'');
				$attributes['id'] = 'ckeditor_'.$field_name;
				$js_onload .="CKEDITOR.replace( '".$attributes['id']."', { filebrowserBrowseUrl : admin_url + 'ajax/finder/' + $('input[name=".$tipo['primary_key']."]').val() });";
				$has_full_textarea = TRUE;
				echo $p_start.$label.form_textarea($attributes).$p_end;
				break;

			case 'date':
				$attributes['name'] = $field_name;
				$attributes['value'] = $field_value;
				$attributes['class'] = 'date_picker text small'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.form_input($attributes).$field_note.$p_end;
				break;

			case 'datetime':
				if (is_numeric($field_value))
				{
					$field_value = date(LOCAL_DATE_FORMAT . ' H:i', $field_value);
				}
				$tmp = explode(' ', $field_value);
				$attributes['name'] = $field_name;
				$attributes['value'] = $tmp[0] ? $tmp[0] : date(LOCAL_DATE_FORMAT);
				$attributes['class'] = 'date_picker text small'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.form_input($attributes);

				$attributes['name'] = '_time_'.$field_name;
				$attributes['value'] = isset($tmp[1]) ? $tmp[1] : date('H:i');
				$attributes['class'] = 'time_picker text small';
				$attributes['type'] = 'time';
				echo '&nbsp;'.form_input($attributes).$field_note.$p_end;
				break;

			case 'number':
				$attributes['name'] = $field_name;
				$attributes['type'] = 'number';
				$attributes['value'] = $field_value;
				$attributes['class'] = 'number text small'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_input($attributes).$field_note.$p_end;
				break;

			case 'select':
				$add = '';
				if (isset($field['onchange'])) {
					$add .= 'onchange="'.$field['onchange'].'" ';
					$js_onload .= trim($field['onchange'], ';').'; ';
				}
				$add .= 'class="styled'.($field['mandatory']?' mandatory':'');
				$add .='" ';
				echo $p_start.$label.form_dropdown($field_name, $field['options'], $field_value, $add).$field_note.$p_end;
				break;

			case 'checkbox':
				echo $p_start.$label;
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
				$add .= 'multiple size="999" class="multi '.($field['mandatory']?' mandatory':'');
				$add .='" ';
				$field['options']['multiple'] = '';
				echo $p_start.$label;

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

				echo '<div class="multiselect multiselect_'.$field_name.'"><div class="multi_left">'.form_dropdown(null, $left_options, $field_value, $add);
				echo '<br /><input type="button" class="add button tiny" value="'._('Add').'" />'.'</div>';

				echo '<div class="multi_right">'.form_dropdown($field_name.'[]', $right_options, $field_value, $add);
				echo '<br /><input type="button" class="rem button tiny" value="'._('Remove').'" />'.'</div><div class="clear"></div></div>';

				$nm = 'multiselect_'.$field_name;

				$js_onload.= "$('.".$nm." .add').click(function(){ ".
					 "return !$('.".$nm." .multi_left select option:selected').remove().appendTo('.".$nm." .multi_right select'); });";
				$js_onload.= "$('.".$nm." .rem').click(function(){ ".
					 "return !$('.".$nm." .multi_right select option:selected').remove().appendTo('.".$nm." .multi_left select'); });";

				echo $field_note.$p_end;
				break;

			case 'radio':
				echo $p_start.$label;
				foreach ($field['options'] as $opt_key => $opt_val) {
					$data = array(
					    'name'        => $field_name,
					    'value'       => $opt_key,
					    'checked'     => $opt_key == $field_value ? 'checked' : '',
					    'class'       => 'radio',
					);

					echo form_radio($data).form_label(' '.$opt_val, $field_name);
				}
				echo $field_note.$p_end;
				break;

			case 'images':
				echo $p_start.$label;
				$count = $field_value != '' ? count($field_value) : 0;

				//Multi upload on webkit+ff browsers
				$attributes['name'] = $field_name.'[]';
				$attributes['multiple'] = ' ';

				if ($count < $field['max']) {
					echo form_upload($attributes).br(1).$this->lang->_trans('You can attach up to %n images', array('n' => $field['max']));
				} else {
					echo '<span class="limit">'._('Number of uploadable images excedeed.').'</span>';
					echo '<div class="hidden limit">'.br(1).form_upload($attributes).br(1).'('.$this->lang->_trans('You can attach up to %n images', array('n' => $field['max'])).')</div>';
				}
				if ($count && is_array($field_value)) {
					echo '<table cellpadding="0" cellspacing="0" width="100%" class="sortable cursor">'
						.'<thead><tr><th>'._('Preview').'</th><th>'._('Original').'</th><th>'._('Resized').'</th><th>'._('Alternative text').'</th><th>'._('Order').'</th><th></th></tr></thead><tbody>';
					;
					foreach ($field_value as $image) {
						if (array_key_exists('thumbnail', $field['presets']))
						{
							$src = 'cache/' . $tipo['name'] . '/' . $field_name . '/' . $record->id . '/' . $field['presets']['thumbnail'] . '/' . $image->name;
						} else {
							$src = $image->thumb_path ? $image->thumb_path : $image->path;
						}
						echo '<tr><td><img src="'. attach_url($src) . '" alt="" border="0" /></td>'
							.'<td><a target="_blank" href="'. attach_url($image->path) . '">'._('View').'</a><br />'.$image->width.' x '.$image->height.' px<br />'.$image->size.' Kb</td>'
							.'<td>'.($image->resized_path ? '<a target="_blank" href="'. attach_url($image->resized_path) . '">'._('View').'</a>':'').'</td>'
							.'<td><input name="_alt_text['.$image->id_document.']" type="text" class="text small" value="' . $image->alt_text . '" /></td>'
							.'<td><input class="tbl-priority text small" name="_priority['.$image->id_document.']" type="text" value="' . $image->priority . '" /></td>'
							.'<td class="delete"><img align="absmiddle" src="'.site_url(THEMESPATH.'admin/widgets/icns/delete.png').'" /> <a href="#" onclick="return bancha.remove.document(this, '.$image->id_document.');">'._('Delete image').'</a></td>'
							."</tr>\n";
					}
					echo '</tbody></table>';
				}
				echo $p_end;
				break;

			case 'files':
				echo $p_start.$label;
				$count = $field_value != '' ? count($field_value) : 0;

				//Multi upload on webkit+ff browsers
				$attributes['name'] = $field_name.'[]';
				$attributes['multiple'] = ' ';

				if ($count < $field['max']) {
					echo form_upload($attributes).br(1).'('.$this->lang->_trans('You can attach up to %n files', array('n' => $field['max'])).')';
				} else {
					echo '<span class="limit">'._('File limit exceeded.').'</span>';
					echo '<div class="hidden limit">'.br(1).form_upload($attributes).br(1).'('.$this->lang->_trans('You can attach up to %n files', array('n' => $field['max'])).')</div>';
				}
				if ($count && is_array($field_value)) {
					echo '<table cellpadding="0" cellspacing="0" width="100%" class="sortable cursor">'
						.'<thead><tr><th>'._('File name').'</th><th>'._('File type').'</th><th>'._('Alternative text').'</th><th></th></tr></thead><tbody>';
					;
					foreach ($field_value as $file) {
						echo '<tr><td><a target="_blank" href="'. attach_url($file->path) . '">'.$file->name.'</a></td>'
							.'<td>'.$file->mime.'</td>'
							.'<td><input name="_alt_text['.$file->id_document.']" type="text" class="text small" value="' . $file->alt_text . '" /></td>'
							.'<td class="delete"><img align="absmiddle" src="'.site_url(THEMESPATH.'admin/widgets/icns/delete.png').'" /> <a href="#" onclick="return bancha.remove.document(this, '.$file->id_document.');">'._('Delete file').'</a></td>'
							."</tr>\n";
					}
					echo '</tbody></table>';
				}
				echo $p_end;
				break;

			case 'hierarchy':
				$dyna_name = '_dyna_'.$field_name;
				echo $p_start.$label.br(1).'<div id="'.$dyna_name.'"></div>';

				$data = array(
						'tree_input'	=> $field_name,
						'tree_id'		=> $dyna_name,
						'tree_form'		=> '#record_form',
						'tree_mode'		=> 2,
						'tree'			=> $field['options']
				);
				$this->view->render('admin/hierarchies/dynatree', $data);

				echo $p_end;

				break;

		}

		echo "\r";

		if (isset($field['visible'])) {
			if ($field['visible'] === false) {
				echo '</div>';
			}
		}

	}

	echo '</div>';

} //end fieldset foreach

	if ($tipo['has_categories']) { ?>
	<div class="sidebar_content" id="sb_category">
		<?php

		echo '<div class="fieldset clearfix"><label>'._('Categories').'</label><div class="right">';

		if (count($categories)) { ?>

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

		} else {
			echo '<p>'._('This type has no categories').'.</p>';
		}

		echo '</div></div>';

		?>

	</div>
	<?php }

	if ($tipo['has_hierarchies']) { ?>
	<div class="sidebar_content" id="sb_hierarchies">

		<?php

		if ($this->config->item('hierarchies')) {

			echo form_hidden('_hierarchies');

			?>

		<div class="fieldset clearfix">
			<label><?php echo _('Hierarchies'); ?></label>
			<div class="right">
				<div id="hierarchies"></div>

				<?php
					

				} else {
					echo '<p>'._('There are no hierarchies').'.</p>';
				}


			?>
			</div>
		</div>

	</div>
	<?php }

	if (isset($tipo['relations']) && FALSE) { ?>
	<div class="sidebar_content" id="sb_relations">

		<?php foreach ($tipo['relations'] as $rel_name => $relation) { ?>
		<div class="fieldset clearfix">
			<label><?php echo $rel_name; ?></label>
			<div class="right">
				<?php
				if ($record->id) {
					echo form_submit(
						array(
							'name' 		=> '_relation_' . $rel_name,
							'class'		=> 'submit button',
							'type'		=> 'button',
							'onclick'	=> "bancha.relations.load('" . $rel_name . "', ".$record->id.", '".$tipo['name']."');"
						), _('Load')
					);
				} ?>
			</div>
		</div>
		<?php } ?>

	</div>
	<?php }

	echo '<div class="fieldset noborder"><label></label><div class="right">'
		 . form_submit('_bt_save', _('Save'), 'class="submit"')
		 . form_submit('_bt_save_list', _('Save and go to list'), 'class="submit long"')
		 . ($tipo['stage'] ? form_submit('_bt_publish', _('Publish'), 'class="submit"') : '')
		 . '</div><div class="clear"></div></div>';

echo form_close();

?>
	</div>
</div>

<div class="hidden">
		<div id="js_errors">
			<div class="block no_margin">

				<div class="block_head">
					<div class="bheadl"></div>
					<div class="bheadr"></div>

					<h2><?php echo _('Errors'); ?></h2>

					<ul>
						<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/delete.png'); ?>" /> <a href="#" onclick="$('#cboxClose').click();"> <?php echo _('Close'); ?></a></li>
					</ul>
				</div>

				<div class="block_content"></div>
			</div>
		</div>
</div>

<?php if ($has_full_textarea) { ?>
<script type="text/javascript" src="<?php echo site_url() . THEMESPATH; ?>admin/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo site_url() . THEMESPATH; ?>admin/js/ckeditor/adapters/jquery.js"></script>
<?php }

if ($tipo['has_attachments']) {
	$js_onload.= "$('table.sortable tbody').sortable({ stop: function(event, ui) {"
				." bancha.sort_priority(event, ui); } });";
	?>
<script type="text/javascript" src="<?php echo site_url() . THEMESPATH; ?>admin/js/jquery-ui.js"></script>
<?php } ?>

<link type="text/css" rel="stylesheet" href="<?php echo site_url(THEMESPATH.'admin/css/colorbox.css'); ?>" />
<script type="text/javascript" src="<?php echo site_url(THEMESPATH.'admin/js/jquery.colorbox.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url() . THEMESPATH; ?>admin/js/validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	<?php echo $js_onload; ?>

	var validator = new FormValidator('record_form',
		<?php echo json_encode($validator_rules); ?>,
		function(errors, events) {

		    if (errors.length > 0) {
		        var el = $('#js_errors .block_content');
		        el.html('');
		        $.each(errors, function(er) {
		        	el.append('<div class="message error">' + this + '</div>');
		        });
		        $.colorbox({width:"65%", inline:true, href:"#js_errors"});

		    } else {
		    	bancha.add_form_hash('#record_form');
		    }
		}
	);
});
</script>

<?php if ($tipo['has_hierarchies']) {
	$data = array(
		'tree_input'	=> '_hierarchies',
		'tree_id'		=> 'hierarchies',
		'tree_form'		=> '#record_form',
		'tree_mode'		=> 2,
		'tree'			=> $this->config->item('hierarchies')
	);
	$this->view->render('admin/hierarchies/dynatree', $data);
}