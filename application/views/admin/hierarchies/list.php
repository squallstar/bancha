<?php
/**
 * Hierarchies List
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

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
			<p><?php echo _('Here you will find the inserted hierarchies.'); ?></p>

			<?php echo $this->view->get_messages(); ?>

			<?php if (count($hierarchies)) { ?>
			<form action="" method="POST" class="tree">
				
				<div id="tree" name="selNodes"></div>
				<br />
				<?php echo form_submit('submit', _('Delete selected'), 'class="submit long"'); ?>
			</form>


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

echo form_hidden('new', '1');
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

<?php
$data = array(
	'tree_input'	=> 'hierarchies',
	'tree_id'		=> 'tree',
	'tree_form'		=> '.tree',
	'tree_mode'		=> 2,
	'tree'			=> $tree
);
$this->view->render('admin/hierarchies/dynatree', $data);
?>