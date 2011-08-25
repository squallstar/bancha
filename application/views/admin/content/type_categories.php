<?php $this->load->helper('form'); ?>
<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2>Categorie : <?php echo $tipo['description']; ?></h2>

		<ul>
			<li><img class="middle" src="<?php echo site_url('widgets/admin/icns/arrow_left.png'); ?>" /> <a href="<?php echo admin_url($_section.'/type/'.$tipo['name']); ?>">Torna ai contenuti</a></li>
		</ul>

	</div>



	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#list">Lista categorie</a></li>
				<li><a href="#add">Aggiungi nuova categoria</a></li>
			</ul>
			<p></p>
		</div>

		<div class="sidebar_content" id="list">
			<h3>Lista categorie</h3>
			<p></p>

			<?php if (isset($message)) { ?><div class="message errormsg"><p><?php echo $message; ?></p></div><?php } ?>

			<?php if (isset($message_ok)) { ?><div class="message success"><p><?php echo $message_ok; ?></p></div><?php } ?>

			<?php if (count($categories)) { ?>
			<table cellpadding="0" cellspacing="0" width="100%" class="sortable">

				<thead>
					<tr>
						<th>ID</th>
						<th>Nome categoria</th>
						<td>&nbsp;</td>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($categories as $category) { ?>
					<tr>
						<td><?php echo $category->id; ?></td>
						<td><?php echo $category->name; ?></td>
						<td class="delete"><a href="<?php echo admin_url($_section.'/type_categories_delete/'.$tipo['name'].'/'.$category->id); ?>" onclick="return confirm('Eliminare questa categoria?');">Elimina categoria</a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php } else { ?>
			<p>Nessuna categoria inserita per questo tipo di contenuto.</p>
			<?php } ?>


		</div>

		<div class="sidebar_content" id="add">
			<h3>Aggiungi nuova categoria</h3>
			<br />
			<p>Le categorie permettono ai tuoi contenuti di differenziarsi maggiormente.<br />Possono essere molto utili per definire estrazioni diverse dalle pagine, o dalle tue azioni personalizzate.</p>
			<?php
			echo form_open('admin/'.$_section.'/type_categories/'.$tipo['name']);

			echo form_label('Nome della categoria', 'category_name') . br(1);
			echo form_input(array('name' => 'category_name', 'class' => 'text')) . br(2);

			echo form_submit('submit', 'Aggiungi', 'class="submit mid"');
			echo form_close();

			?>

		</div>


	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>
