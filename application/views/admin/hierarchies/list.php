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


			<?php if (count($hierarchies)) { ?>

<?php debug($tree); ?>

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
