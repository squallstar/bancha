<?php
/**
 * Dynatree Javascript Activators
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
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

?><link type="text/css" rel="stylesheet" href="<?php echo site_url(THEMESPATH.'admin/css/dynatree.css'); ?>" />
<script type="text/javascript" src="<?php echo site_url(THEMESPATH.'admin/js/jquery-ui.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url(THEMESPATH.'admin/js/jquery.dynatree.min.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#<?php echo $tree_id; ?>").dynatree({
    	onActivate: function(node) {
      	},
      	minExpandLevel : 3,
      	checkbox: true,
        selectMode: <?php echo $tree_mode; ?>,
      	children: <?php echo json_encode($tree); ?>

    });

	$("form<?php echo $tree_form; ?>").submit(function() {
		
		// Serialize standard form fields:
	    var formData = $(this).serializeArray();

	    // then append Dynatree selected 'checkboxes':
	    var tree = $("#<?php echo $tree_id; ?>").dynatree("getTree");
	 
	    formData = formData.concat(tree.serializeArray());

	    // and/or add the active node as 'radio button':
	    if(tree.getActiveNode()){
	    	formData.push({name: "activeNode", value: tree.getActiveNode().data.key});
	    }

	    $('input[name=<?php echo $tree_input; ?>]').val(jQuery.param(formData));
	    return true;
	});
});
</script>