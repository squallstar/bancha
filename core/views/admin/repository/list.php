<?php
$this->load->helper('form');
?><div class="block withsidebar">

	<div class="block_head">

		<h2><?php echo _('Repository'); ?></h2>

	</div>

	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#sb-repository"><?php echo _('Images'); ?></a></li>
			</ul>
		</div>

	 	<div class="sidebar_content" id="sb-repository">
			<h3><?php echo _('Images repository'); ?></h3>

			<p><?php echo _('Here you can find the last uploaded images.'); ?></p>
			
			<?php
			echo form_open_multipart();

			$presets_select = form_dropdown('preset', $presets);

			$attributes = array(
				'name'		=> 'documents[]',
				'multiple'	=> ' '
			);
			echo form_upload($attributes).br(2);

			echo form_submit('upload', _('Upload file'), 'class="submit mid"').br(2);

			echo form_close();

			?>

			<table cellpadding="0" cellspacing="0" width="100%" class="sortable repository">
				<thead>
					<tr>
						<th><?php echo _('Thumbnail'); ?></th>
						<th><?php echo _('Filename'); ?></th>
						<th><?php echo _('Preset'); ?></th>
						<th><?php echo _('Choose'); ?></th>
						<th><?php echo _('Actions'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($repository_files as $file) { ?>
					<tr data-path="<?php echo $file->path; ?>">
						<td><?php
						if (in_array($file->mime, array('png', 'jpg', 'gif', 'jpeg')))
						{
							echo '<img src="' . attach_url($file->thumb_path) . '" border="0" alt="" />';
						}
						?></td>
						<td><?php echo $file->name . '<br /><a target="_blank" href="'.attach_url($file->path).'">'._('View').'</a>'; ?></td>
						<td><?php echo $presets_select; ?></td>
						<td><a href="#" class="choose"><?php echo _('Apply preset'); ?></a></td>
						<td class="delete"><img align="absmiddle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/delete.png'); ?>" /> <a href="#" onclick="return bancha.remove.document(this, '<?php echo $file->id_document; ?>');"><?php echo _('Delete file'); ?></a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

		</div>

		

	</div>
</div>

<script type="text/javascript">

function getUrlParam(paramName) {
  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
  var match = window.location.search.match(reParam) ;
  return (match && match.length > 1) ? match[1] : '' ;
}
function getPresetPath(el) {
	var preset = el.val();
	var _tr = el.parent('td').parent('tr');
	var urlpath = 'attach/' + _tr.attr('data-path');

	_tr.find('.choose').unbind('click').click(function() {
		var url = bancha.preset_url(urlpath, preset, true);
		window.open(url);
	});
}
$(document).ready(function() {
	$('.repository select').change(function() {
		getPresetPath($(this));
	});
});
</script>