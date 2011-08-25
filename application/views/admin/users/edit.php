<?php
$this->load->helper('form');
?>
<div class="block withsidebar">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo $user ? 'Modifica utente' : 'Inserimento nuovo utente'; ?></h2>

		<ul>
			<li><a href="<?php echo admin_url('users/lista')?>">Torna alla lista degli utenti</a></li>
		</ul>

	</div>



	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#personal">Informazioni personali</a></li>
				<li><a href="#groups">Gruppo di appartenenza</a></li>
			</ul>


			<p></p>
		</div>


		<div class="sidebar_content" id="personal">



<h3><?php echo $user ? 'Modifica utente: '.$user->username : 'Nuovo utente'; ?></h3><br />

<?php
echo form_open();

echo form_label('Nome', 'name') . br(1);
echo form_input(array('name' => 'name', 'class' => 'text', 'value' => $user ? $user->name : '')) . br(2);

echo form_label('Cognome', 'surname') . br(1);
echo form_input(array('name' => 'surname', 'class' => 'text', 'value' => $user ? $user->surname : '')) . br(2);

echo form_label('Username', 'username') . br(1);
echo form_input(array('name' => 'username', 'class' => 'text', 'value' => $user ? $user->username : '')) . br(2);

echo form_label('Indirizzo e-mail', 'email') . br(1);
echo form_input(array('name' => 'email', 'class' => 'text', 'value' => $user ? $user->email : '')) . br(2);

echo form_label('Nuova password', 'password') . br(1);
echo form_password(array('name' => 'password', 'class' => 'text', 'value' => '')) . br(2);

echo form_label('Conferma nuova password', 'password2') . br(1);
echo form_password(array('name' => 'password2', 'class' => 'text')) . br(2);




if (!$user) {
	echo '<div class="message info"><p>Per effettuare l\'accesso al pannello, verr&agrave; utilizzato lo <u>Username</u> scelto.</p></div><br />';
}

echo form_submit('submit', $user ? 'Salva modifiche' : 'Aggiungi utente', 'class="submit long"');
echo form_close();
?>

		</div>

		<div class="sidebar_content" id="groups">
			<h3>Gruppo di appartenenza</h3>
			<p>Il gruppo di appartenenza di un utente, definisce i suoi ruoli e permessi all'interno del pannello di amministrazione.</p>
		</div>



	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>
