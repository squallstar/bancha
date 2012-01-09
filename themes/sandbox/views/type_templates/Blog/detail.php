<?php
/**
 * Blog Detail View
 *
 * Content detail - Sandbox Theme
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 *
 */

$comments =& $record->related('comments');

?>

<h1><?php echo page('title'); ?></h1>

<h2><?php echo record('title'); ?></h2>
<p><?php echo record('content'); ?></p>

<hr />

<h3><?php echo count($comments); ?> Comments</h3>

<?php
if (is_array($comments) && count($comments)) {
	echo '<ul>';
	foreach ($comments as $comment) {
		echo '<li>' . date('d/m/Y H:i', $comment->get('date_insert'))
		   . ' by <strong>' . $comment->get('author') . '</strong>'
		   . '<p>' . $comment->get('content') . '</p></li>';
	}
	echo '</ul>';
}
?>


<?php
/* End of file detail.php */
/* Location: /type_templates/Blog/detail.php */