<?php
$this->load->helper('form');
?><div class="block withsidebar no_margin">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Browse attachments'); ?></h2>

		<ul>
			<li><a href="#" onclick="window.close();"><?php echo _('Close window'); ?></a></li>
		</ul>
	</div>

	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#sb-record"><?php echo _('Record documents'); ?></a></li>
				<li><a href="#sb-repository"><?php echo _('Repository'); ?></a></li>
			</ul>
		</div>

		<div class="sidebar_content" id="sb-record">
			<h3><?php echo _('Record documents'); ?></h3>
	<?php
	if (isset($documents) && count($documents))
	{
		echo '<table cellpadding="0" cellspacing="0" width="100%" class="sortable">'
		.'<thead><tr><th>'._('Preview').'</th><th>'._('Original').'</th><th>'._('Resized').'</th><th>'._('Thumbnail').'</th><th>'._('Alternative text').'</th><th></th></tr></thead><tbody>';
		;
		foreach ($documents as $image)
		{
			$src = $image->thumb_path ? $image->thumb_path : $image->path;
						echo '<tr><td><img src="'. attach_url($src) . '" alt="" border="0" /></td>'
							.'<td><a target="_blank" onclick="finder_choose(\''.attach_url($image->path).'\');" href="#">'._('View').'</a> - <a href="#" onclick="finder_choose(\''. attach_url($image->path) . '\');">'._('Choose').'</a><br />'.$image->width.' x '.$image->height.' px<br />'.$image->size.' Kb</td>'
							.'<td>'.($image->resized_path ? '<a target="_blank" href="'. attach_url($image->resized_path) . '">'._('View').'</a> - <a href="#" onclick="finder_choose(\''. attach_url($image->resized_path) . '\');">'._('Choose').'</a>':'').'</td>'
							.'<td>'.($src ? '<a href="#" onclick="finder_choose(\''. attach_url($src) . '\');">'._('Choose').'</a>':'').'</td>'
							.'<td>'.$image->alt_text.'</td>'
							.'<td class="delete"><img align="absmiddle" src="'.site_url(THEMESPATH . 'admin/widgets/icns/delete.png').'" /> <a href="#" onclick="return bancha.delete.document(this, '.$image->id_document.');">'._('Delete image').'</a></td>'
							.'</tr>';

		}
		echo '</tbody></table>';

	 } else {
	 	echo _('No attachments found for this record.').br(2);
	 }
	 ?>
	 	</div>

	 	<div class="sidebar_content" id="sb-repository">
			<h3><?php echo _('Documents repository'); ?></h3>

			<p><?php echo _('Here you can find the uploaded documents.'); ?></p>
			
			<?php
			echo form_open_multipart(current_url().'#sb-repository');

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
						<td><?php echo '<a target="_blank" href="#" onclick="finder_choose(\''.attach_url($file->path).'\');">'.$file->name.'</a>'; ?></td>
						<td><?php echo $presets_select; ?></td>
						<td><a href="#" class="choose" onclick="finder_choose('<?php echo attach_url($file->path); ?>');"><?php echo _('Apply preset'); ?></a></td>
						<td class="delete"><img align="absmiddle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/delete.png'); ?>" /> <a href="#" onclick="return bancha.remove.document(this, '<?php echo $file->id_document; ?>');"><?php echo _('Delete file'); ?></a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>

		</div>

	</div>
	<div class="bendl"></div>
	<div class="bendr"></div>
</div>

<style type="text/css">
#header, #footer {
	display:none;
}
</style>
<script type="text/javascript">
function finder_choose(el) {

	var parentWindow = ( window.parent == window )
	? window.opener : window.parent;

	if ( parentWindow['CKEDITOR'] )
	{
		var funcNum = getUrlParam('CKEditorFuncNum');
		parentWindow['CKEDITOR'].tools.callFunction( funcNum, el);
		window.close();
	}
}
function getUrlParam(paramName) {
  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
  var match = window.location.search.match(reParam) ;
  return (match && match.length > 1) ? match[1] : '' ;
}
function getPresetPath(el) {
	var preset = el.val();
	var _tr = el.parent('td').parent('tr');
	var url = 'attach/' + _tr.attr('data-path');
	_tr.children('.choose').attr('onclick', 'finder_choose(\''+bancha.preset_url(url, preset)+'\');');
}
$(document).ready(function() {
	$('.repository select').change(function() {
		getPresetPath($(this));
	});
});
</script>