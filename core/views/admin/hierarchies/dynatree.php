<?php
/**
 * Dynatree Javascript Activator
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 * @var string $tree_id 	The div that contains the tree
 * @var string $tree_form 	The form selector
 * @var string $tree_input 	The input name
 * @var int    $tree_mode	The dynatree select mode
 * @var array  $tree		The tree :)
 *
 */

if (!defined('DYNATREE')) {
?><link type="text/css" rel="stylesheet" href="<?php echo site_url(THEMESPATH.'admin/css/dynatree.css'); ?>" />
<script type="text/javascript" src="<?php echo site_url(THEMESPATH.'admin/js/jquery-ui.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url(THEMESPATH.'admin/js/jquery.dynatree.min.js'); ?>"></script>
<?php
} else {
	define('DYNATREE', TRUE);
}
?>
<script type="text/javascript">
$(document).ready(function() {
	$("#<?php echo $tree_id; ?>").dynatree({
    	onActivate: function(node) {
      	},
      	debugLevel : 0,
      	minExpandLevel : 3,
      	checkbox: true,
        selectMode: <?php echo $tree_mode; ?>,
      	children: <?php echo json_encode($tree); ?>
    });

	$("form<?php echo $tree_form; ?>").submit(function() {

	    // then append Dynatree selected 'checkboxes':
	    var tree = $("#<?php echo $tree_id; ?>").dynatree("getTree");

		if (tree) {

			var nodeList = tree.getSelectedNodes(), arr = [];
			for(var i=0, l=nodeList.length; i<l; i++){
				//arr.push(nodeList[i].data.key);
				$("form<?php echo $tree_form; ?>")
					.append('<input type="checkbox" class="hidden" checked="checked" name="<?php echo $tree_input; ?>[]" value="'+nodeList[i].data.key+'" />');
			}
		}

		bancha.add_form_hash();
	    return true;
	});
});
</script>