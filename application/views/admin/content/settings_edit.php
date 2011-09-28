<?php
/**
 * Plain Edit View
 *
 * A standard view to edit a content type.
 * Mainly used by the Settings and the Modules.
 *
 * It doesn't features external fields such as images, files and hierarchies
 *
 * @package		Bancha
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

		<h2><?php echo _('Manage settings'); ?></h2>
	</div>

	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<?php foreach ($tipo['fieldsets'] as $fieldset) { ?>
				<li><a href="#sb-<?php echo url_title($fieldset['name']); ?>"><?php echo _($fieldset['name']); ?></a></li>
				<?php } ?>
			</ul>
			<p></p>
		</div>
<?php
$save_buttons = form_submit('_bt_save', _('Update settings'), 'class="submit long" onclick="bancha.add_form_hash();"');

echo form_open(null, array('id' => 'record_form', 'name' => 'record_form'));

$js_onload = '';
$first_lap = TRUE;
$has_full_textarea = FALSE;
$p_start = '<p>';
$p_end = '</p>';

foreach ($tipo['fieldsets'] as $fieldset)
{

	echo '<div class="sidebar_content" id="sb-'.url_title($fieldset['name']).'">';

	//Messages
	echo $this->view->get_messages();

	echo br(1).'<h3>'._($fieldset['name']).'</h3>'.br(1);

	foreach ($fieldset['fields'] as $field_name)
	{
		$field = $tipo['fields'][$field_name];

		$attributes = array();

		$label = form_label(_($field['description']), $field_name, $attributes);

		//We evaluates the evals
		if ($field['default'] && substr($field['default'], 0, 5) == 'eval:')
		{
			eval('$value = '.substr($field['default'], 5).';');
			$field['default'] = $value;
		}

		//The default value will be set when no stored value is found
		$module = $fieldset['name'];
		$field_value = $this->settings->get($field_name, $module);

		if (!$field_value)
		{
			$field_value = $field['default'];
		}

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
				$attributes['name'] = $field_name.'['.$module.']';
				$attributes['value'] = $field_value;
				$attributes['class'] = 'text'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_input($attributes).$p_end;
				break;

			case 'textarea':
				$attributes['name'] = $field_name.'['.$module.']';
				$attributes['value'] = $field_value;
				$attributes['class'] = 'wysiwyg'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_textarea($attributes).$p_end;
				break;

			case 'textarea_code':
				$attributes['name'] = $field_name.'['.$module.']';
				$attributes['value'] = $field_value;
				$attributes['class'] = 'code'.($field['mandatory']?' mandatory':'');
				$attributes['id'] = 'texteditor_'.$field_name;
				echo $p_start.$label.br(1).form_textarea($attributes).$p_end;
				$js_onload.= "bancha.tab_textarea('#".$attributes['id']."');";
				break;

			case 'textarea_full':
				$attributes['name'] = $field_name.'['.$module.']';
				$attributes['value'] = $field_value;
				$attributes['class'] = ''.($field['mandatory']?' mandatory':'');
				$attributes['id'] = 'ckeditor_'.$field_name;
				$js_onload .="CKEDITOR.replace( '".$attributes['id']."', { filebrowserBrowseUrl : admin_url + 'ajax/finder/' + $('input[name=".$tipo['primary_key']."]').val() });";
				$has_full_textarea = TRUE;
				echo $p_start.$label.br(1).form_textarea($attributes).$p_end;
				break;

			case 'date':
				$attributes['name'] = $field_name.'['.$module.']';
				$attributes['value'] = $field_value;
				$attributes['class'] = 'date_picker text small'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_input($attributes).$p_end;
				break;

			case 'datetime':
				$tmp = explode(' ', $field_value);
				$attributes['name'] = $field_name.'['.$module.']';
				$attributes['value'] = $tmp[0] ? $tmp[0] : date('d/m/Y');
				$attributes['class'] = 'date_picker text small'.($field['mandatory']?' mandatory':'');
				echo $p_start.$label.br(1).form_input($attributes);

				$attributes['name'] = '_time_'.$field_name.'['.$module.']';
				$attributes['value'] = isset($tmp[1]) ? $tmp[1] : date('H:i');
				$attributes['class'] = 'time_picker text small';
				$attributes['type'] = 'time';
				echo '&nbsp;'.form_input($attributes);
				echo $p_end;
				break;

			case 'number':
				$attributes['name'] = $field_name.'['.$module.']';
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
				echo $p_start.$label.br(1).form_dropdown($field_name.'['.$module.']', $field['options'], $field_value, $add).$p_end;
				break;

			case 'checkbox':
				echo $p_start.$label.br(1);
				foreach ($field['options'] as $opt_key => $opt_val) {
					$checked = is_array($field_value) ? (in_array($opt_key, $field_value) ? 'checked' : '') : '';
					$data = array(
					    'name'        => $field_name.'['.$module.'][]]',
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

				echo '<div class="multiselect multiselect_'.$field_name.'"><div class="multi_left">'.form_dropdown(null, $left_options, $field_value, $add);
				echo '<br /><input type="button" class="add button tiny" value="'._('Add').'" />'.'</div>';

				echo '<div class="multi_right">'.form_dropdown($field_name.'['.$module.'][]', $right_options, $field_value, $add);
				echo '<br /><input type="button" class="rem button tiny" value="'._('Remove').'" />'.'</div><div class="clear"></div></div>';

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
					    'name'        => $field_name.'['.$module.']',
					    'value'       => $opt_key,
					    'checked'     => $opt_key == $field_value ? 'checked' : '',
					    'class'       => 'radio',
					);

					echo form_radio($data).form_label(' '.$opt_val, $field_name);
				}
				echo $p_end;
				break;
		}

		if ($field['type'] != 'hidden')
		{
			echo "<br />\n";
		}

		if (isset($field['visible']))
		{
			if ($field['visible'] === false)
			{
				echo '</div>';
			}
		}
	}

	echo $save_buttons;

	echo '</div>';

} //end fieldset foreach

echo form_close() . br(2);

?>
	</div>
	<div class="bendl"></div>
	<div class="bendr"></div>

</div>

<?php if ($has_full_textarea) { ?>
<script type="text/javascript" src="<?php echo site_url() . THEMESPATH; ?>admin/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo site_url() . THEMESPATH; ?>admin/js/ckeditor/adapters/jquery.js"></script>
<?php } ?>

<script type="text/javascript">
$(document).ready(function() {
	<?php echo $js_onload; ?>
});
</script>