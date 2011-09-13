<?php
$this->load->helper('form');
?>
<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Add new content type'); ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url(THEMESPATH.'admin/widgets/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url($_section.'/')?>"><?php echo _('Back to types list'); ?></a></li>
		</ul>

	</div>



	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#intro"><?php echo _('Intro'); ?></a></li>
				<li><a href="#add"><?php echo _('Add new type'); ?></a></li>
			</ul>
			<p></p>
		</div>

		<div class="sidebar_content" id="intro">
			<h3>Introduzione ai tipi di contenuto</h3>
			<p>Bancha ti permette di definire diversi contenuti per il tuo sito internet.
			Ogni contenuto, è basato su un file XML che ne descrive tutti i campi gestibili.
			In questo modo, puoi creare centinaia di schemi per amministrare i vari contenuti del tuo sito internet.
			Ad esempio, un tipo di contenuto potrebbero essere i <strong>Prodotti</strong>, oppure delle <strong>Gallerie immagini</strong>.
			<br /><br />
			Un tipo di contenuto deve sempre essere uno tra i seguenti tipi:
			<ul>
				<li><strong>Semplice</strong> (per contenuti lineari, senza gerarchia)</li>
				<li><strong>Ad albero</strong> (per contenuti strutturabili gerarchicamente, come le pagine di un sito internet)</li>
			</ul>
			Come avrai intuito, anche le pagine stesse di un sito internet sono a loro volta un tipo di contenuto. &Egrave; proprio per questo che
			dovr&agrave; essere definito almeno un tipo di contenuto associato all'albero delle pagine del sito. Tale associazione viene impostata nel file di configurazione di Bancha alla voce "<strong>DEFAULT TREE TYPE</strong>".
			<br /><br />
			Aggiungendo un nuovo tipo di contenuto, verranno <strong>automaticamente creati</strong> i seguenti files:<br /><br />
			<ul>
				<li><strong>/application/xml/Nome_contenuto.xml</strong> che conterrà la struttura dei campi gestibili,</li>
				<li><strong>/application/views/templates/Nome_contenuto/list.php</strong> ovvero il template xhtml per la visualizzazione come lista dei contenuti,</li>
				<li><strong>/application/views/templates/Nome_contenuto/detail.php</strong> rispettivo template per la visualizzazione di dettaglio.</li>

			</ul>
			</p>
		</div>

		<div class="sidebar_content" id="add">



<h3><?php echo _('Add new content type'); ?></h3><br />

<div class="message info"><p><?php echo _('WARNING').': '._('You can\'t change the type name after its creation.'); ?></p></div>

<?php
echo form_open();

echo form_label(_('Type name'), 'type_name') . br(1);
echo form_input(array('name' => 'type_name', 'class' => 'text')) . br(2);

echo form_label(_('Type description'), 'type_description') . br(1);
echo form_input(array('name' => 'type_description', 'class' => 'text')) . br(2);

echo form_label(_('Type structure'), 'type_tree') . br(1);
echo form_dropdown('type_tree', array('false' => _('Simple (Contents)'), 'true' => _('Tree (Pages)')), null, 'class="styled"') . br(1);

echo form_submit('submit', _('Add'), 'class="submit mid"');
echo form_close();

?>

		</div>

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>
