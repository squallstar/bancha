<?php
$this->load->helper('form');
?><div class="block">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2>Eliminazione tipo di contenuto: <?php echo $tipo['description']; ?></h2>

		<ul>
			<li><a href="<?php echo admin_url($_section.'/')?>">Annulla</a></li>
		</ul>


	</div>

	<div class="block_content">

	<p class="breadcrumb"><a href="<?php echo admin_url($_section); ?>">Contenuti</a> &raquo; <a href="<?php echo admin_url($_section.'/type/'.$tipo['id'])?>"><?php echo $tipo['description']; ?></a> &raquo; <strong>Conferma eliminazione</strong></p>

	<p><br />Se elimini il tipo di contenuto <strong><?php echo $tipo['description']; ?></strong>, verranno cancellati dal filesystem i seguenti files:</p>

	<ul>
		<li>Schema di definizione del tipo (<strong><?php echo $tipo['name']; ?>.xml</strong>)</li>
		<li>Files relativi a viste di lista e dettaglio per i contenuti (template .php)</li>
	</ul>

<?php if ($this->config->item('delete_dead_recods')) { ?>
	<div class="message warning"><p>ATTENZIONE: Tutti i contenuti associati al tipo di contenuto verranno eliminati.<br />Puoi disattivare questa funzionalit&agrave; modificando il valore della variabile <u>DEAD RECORDS</u> nel file di configurazione di Milk.</p></div>

<?php }

echo form_open();
echo form_hidden('id_type', $tipo['id']);

echo br(1) . form_submit('delete', 'Conferma eliminaz.', 'class="submit long"');
echo form_submit('cancel', 'Annulla', 'class="submit mid"') . br(2);

echo form_close();


?>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>
</div>