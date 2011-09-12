<?php
$this->load->helper('form');
?>
<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Manage hierarchies'); ?></h2>

		<ul>
		</ul>

	</div>



	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#list"><?php echo _('Hierarchies list'); ?></a></li>
				<li><a href="#add"><?php echo _('Add hierarchy'); ?></a></li>
			</ul>
			<p></p>
		</div>

		<div class="sidebar_content" id="list">
			<h3><?php echo _('Hierarchies list'); ?></h3>
			<p>Lorem ipsum dolor sit amet
			</p>

			<form action="" method="POST" class="tree">
			<div id="tree" name="selNodes"></div>

			<?php echo form_submit('submit', _('Aggiorna'), 'class="submit mid"'); ?>
			</form>

			<?php debug($this->input->post()); ?>


			<?php if (count($hierarchies)) { ?>



			<?php
			} else {
				echo _('There are no hierarchies.');
			}
			?>
		</div>

		<div class="sidebar_content" id="add">



<h3><?php echo _('Add hierarchy'); ?></h3><br />

<?php
echo form_open();

echo form_label(_('Hierarchy name'), 'name') . br(1);
echo form_input(array('name' => 'name', 'class' => 'text')) . br(2);



echo form_label(_('Parent hierarchy'), 'id_parent') . br(1);
echo form_dropdown('id_parent', $dropdown, null, 'class="styled"') . br(1);

echo form_submit('submit', _('Add'), 'class="submit mid"');
echo form_close();

?>

		</div>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>

<link type="text/css" rel="stylesheet" href="<?php echo site_url(THEMESPATH.'admin/css/dynatree.css'); ?>" />
<script type="text/javascript" src="<?php echo site_url(THEMESPATH.'admin/js/jquery-ui.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url(THEMESPATH.'admin/js/jquery.dynatree.min.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#tree").dynatree({
    	onActivate: function(node) {
        	// A DynaTreeNode object is passed to the activation handler
        	// Note: we also get this event, if persistence is on, and the page is reloaded.
        	alert("You activated " + node.data.title);
      	},
      	checkbox: true,
        selectMode: 3,
      	children: <?php echo json_encode($tree); ?>
    });

	$("form.tree").submit(function() {
	      // Serialize standard form fields:
	      var formData = $(this).serializeArray();

	      // then append Dynatree selected 'checkboxes':
	      var tree = $("#tree").dynatree("getTree");
	      alert(tree);
	});
});
</script>