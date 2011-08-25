<?php
$this->load->helper('form');
?><div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Browse attachments'); ?></h2>

		<ul>
			<li><a href="#" onclick="window.close();"><?php echo _('Close window'); ?></a></li>
		</ul>


	</div>

	<div class="block_content">

	<?php
	if (count($documents))
	{
		echo '<table cellpadding="0" cellspacing="0" width="100%" class="sortable">'
		.'<thead><tr><th>'._('Preview').'</th><th>'._('Original').'</th><th>'._('Resized').'</th><th>'._('Thumbnail').'</th><th>'._('Alternative text').'</th><th></th></tr></thead><tbody>';
		;
		foreach ($documents as $image)
		{ 
			$src = $image->thumb_path ? $image->thumb_path : $image->path;
						echo '<tr><td><img src="'. attach_url($src) . '" alt="" border="0" /></td>'
							.'<td><a target="_blank" href="'. attach_url($image->path) . '">'._('View').'</a> - <a href="#" onclick="finder_choose(\''. attach_url($image->path) . '\');">'._('Choose').'</a><br />'.$image->width.' x '.$image->height.' px<br />'.$image->size.' Kb</td>'
							.'<td>'.($image->resized_path ? '<a target="_blank" href="'. attach_url($image->resized_path) . '">'._('View').'</a> - <a href="#" onclick="finder_choose(\''. attach_url($image->resized_path) . '\');">'._('Choose').'</a>':'').'</td>'
							.'<td>'.($src ? '<a href="#" onclick="finder_choose(\''. attach_url($src) . '\');">'._('Choose').'</a>':'').'</td>'
							.'<td>'.$image->alt_text.'</td>'
							.'<td class="delete"><img align="absmiddle" src="'.site_url('widgets/admin/icns/delete.png').'" /> <a href="#" onclick="return milk.delete.document(this, '.$image->id_document.');">'._('Delete image').'</a></td>'
							.'</tr>';

		}
		echo '</tbody></table>';
		
	
	
	 } else {
	 	echo _('No attachments found for this record.').br(2);
	 }
	 ?>

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
function getUrlParam(paramName)
{
  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
  var match = window.location.search.match(reParam) ;
 
  return (match && match.length > 1) ? match[1] : '' ;
}
</script>