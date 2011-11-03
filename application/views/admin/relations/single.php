<?php
/**
 * Single render
 *
 * Relations package
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ($objects instanceof Record)
{
	$objects = array($objects);
}
if (!count($objects)) return;

$tipo = $this->content->type($objects[0]->_tipo);

foreach ($objects as $relation) {
	$url = admin_url(
			($tipo['tree'] ? 'pages' : 'contents') . '/edit_record/'
			. $tipo['name'] . '/'.$relation->id
		);
?>
<div class="item">
	<a href="<?php echo $url; ?>"><?php echo $relation->get($tipo['edit_link']); ?></a>
</div>
 
 
<?php	
}