<?php
/**
 * Single render
 *
 * Relations package
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ($objects instanceof Record)
{
	$objects = array($objects);
}

if (!$objects || !count($objects))
{
	echo _('This record has no relations of this type.');
	return;
}

$tipo = $this->content->type($objects[0]->_tipo);
?>
<table cellpadding="0" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th><?php echo _('Content name'); ?></th>
			<th><?php echo _('Content type'); ?></th>
			<th><?php echo _('Insert date'); ?></th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($objects as $relation) {
	$url = admin_url(
			($tipo['tree'] ? 'pages' : 'contents') . '/edit_record/'
			. $tipo['name'] . '/'.$relation->id
		);
?>
<tr class="relation">
	<td>
		<img src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/pencil.png'); ?>" />
		 <a href="<?php echo $url; ?>"><?php echo $relation->get($tipo['edit_link']); ?></a>
	</td>
	<td><?php echo $tipo['description']; ?></td>
	<td><?php echo date(LOCAL_DATE_FORMAT, $relation->get('date_insert')); ?></td>
</tr>


<?php } ?>
	</tbody>
</table>