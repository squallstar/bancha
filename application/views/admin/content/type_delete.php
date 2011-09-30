<?php
$this->load->helper('form');
?><div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Type delete'); ?>: <?php echo $tipo['description']; ?></h2>

		<ul>
			<li><a href="<?php echo admin_url($_section.'/')?>"><?php echo _('Cancel'); ?></a></li>
		</ul>


	</div>

	<div class="block_content">

	<p class="breadcrumb"><a href="<?php echo admin_url($_section); ?>"><?php echo _('Contents'); ?></a> &raquo; <a href="<?php echo admin_url($_section.'/type/'.$tipo['id'])?>"><?php echo $tipo['description']; ?></a> &raquo; <strong><?php echo _('Content type delete'); ?></strong></p>

	<p><br /><?php echo $this->lang->_trans('If you delete the content type %n, will be also deleted from the filesystem these files:', array('n' => '<strong>'.$tipo['description'].'</strong>')); ?></p>

	<ul>
		<li><?php echo _('Content type scheme definition'); ?> (<strong><?php echo $tipo['name']; ?>.xml</strong>)</li>
		<li><?php echo _('View files related to list/detail actions of this content type (php templates).'); ?></li>
	</ul>

<?php if ($this->config->item('delete_dead_records')) { ?>
	<div class="message warning"><p><?php echo _('WARNING'); ?>: <?php echo _('All contents associated to this type will be deleted.'); ?><br /><?php echo _('You can enable/disable this function through the variabile "DEAD RECORDS" in the config file.'); ?></p></div>

<?php } else { ?>
	<div class="message info"><p><?php echo _('All contents associated to this type will NOT be deleted.'); ?><br /><?php echo _('You can enable/disable this function through the variabile "DEAD RECORDS" in the config file.'); ?></p></div>
<?php }

echo form_open();
echo form_hidden('id_type', $tipo['id']);

echo br(1) . form_submit('delete', _('Delete type'), 'class="submit long"');
echo form_submit('cancel', _('Cancel'), 'class="submit mid"') . br(2);

echo form_close();


?>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>