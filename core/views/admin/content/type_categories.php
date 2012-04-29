<?php $this->load->helper('form'); ?>
<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Categories'); ?> : <?php echo $tipo['description']; ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url($_section.'/type/'.$tipo['name']); ?>"><?php echo _('Back to contents'); ?></a></li>
		</ul>

	</div>



	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#list"><?php echo _('Categories list'); ?></a></li>
				<li><a href="#add"><?php echo _('Add new category'); ?></a></li>
			</ul>
			<p></p>
		</div>

		<?php if (isset($message)) { ?><div class="message errormsg"><p><?php echo $message; ?></p></div><?php } ?>

			<?php if (isset($message_ok)) { ?><div class="message success"><p><?php echo $message_ok; ?></p></div><?php } ?>

		<div class="sidebar_content no_margin" id="list">			

			<?php if (count($categories)) { ?>
			<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

				<thead>
					<tr>
						<th>ID</th>
						<th><?php echo _('Category name'); ?></th>
						<td>&nbsp;</td>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($categories as $category) { ?>
					<tr>
						<td><?php echo $category->id; ?></td>
						<td><?php echo $category->name; ?></td>
						<td class="delete"><a href="<?php echo admin_url($_section.'/type_categories_delete/'.$tipo['name'].'/'.$category->id); ?>" onclick="return confirm('<?php echo _('Do you want to delete this category?'); ?>');"><?php echo _('Delete category'); ?></a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } else { ?>
			<div class="internal_padding"><h3><?php echo _('This content type has no categories.'); ?></h3></div>
			<?php } ?>


		</div>

		<div class="sidebar_content no_margin" id="add">
			<div class="internal_padding"><h3><?php echo _('Add new category'); ?></h3>
			<br />
			<p><?php echo _('Categories permits your contents to be more differents each other.'); ?><br /><?php echo _('They can be useful to make different extractions and use them such as tags.'); ?></p></div>
			<?php
			echo form_open(ADMIN_PUB_PATH.$_section.'/type_categories/'.$tipo['name']);

			echo '<div class="fieldset clearfix">'.form_label(_('Category name'), 'category_name').'<div class="right">';
			echo form_input(array('name' => 'category_name', 'class' => 'text')) . '</div></div>';

			echo '<div class="fieldset clearfix noborder"><label></label><div class="right">'.form_submit('submit', _('Add'), 'class="submit mid"').'</div></div>';
			echo form_close();

			?>

		</div>


	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>
