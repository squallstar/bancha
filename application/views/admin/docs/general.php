<div class="block withsidebar code_format docs">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>
		<h2>Documentazione di <?php echo CMS; ?></h2>
	</div>

	<div class="block_content">

		<div class="sidebar">
			<ul class="sidemenu">
				<li><a href="#sb-intro">1. Introduction to Bancha</a></li>
				<li><a href="#sb-install">2. How to install</a></li>
				<li><a href="#sb-content-types">3. Tipi di contenuto</a></li>
				<li><a href="#sb-types-xml">4. Schema XML dei tipi</a></li>
				<li><a href="#sb-page-actions">5. Azioni delle pagine</a></li>
				<li><a href="#sb-template-layout">6. Template e Layout</a></li>
				<li><a href="#sb-views">7. Temi, view e rendering</a></li>
				<li><a href="#sb-extract-records">8. Estrazione di contenuti</a></li>
				<li><a href="#sb-documents">9. Documenti (files)</a>
				<li><a href="#sb-trees">10. Alberi di menu</a></li>
				<li><a href="#sb-caching">11. Caching</a></li>
				<li><a href="#sb-feeds">12. Feed dei contenuti</a></li>
				<li><a href="#sb-triggers">13. Triggers (attivatori)</a></li>
				<li><a href="#sb-image-presets">14. Image presets</a></li>
				<li><a href="#sb-categories">15. Categorie, Gerarchie</a></li>
				<li><a href="#sb-dispatchers">16. Dispatchers</a></li>
				<li><a href="#sb-helpers">17. Helpers</a></li>
				<li><a href="#sb-modules">18. Moduli</a></li>
				<li><a href="#sb-settings">19. Impostazioni</a></li>
			</ul>
			<p>Versione: <?php echo BANCHA_VERSION; ?><br />Data: 29 Set 2011</p>
		</div>

		<div class="sidebar_content" id="sb-intro">
			<h3>1. Introduction to Bancha</h3>

<p>BANCHA is a Content Management System made with <strong>PHP5</strong> and <strong>Code Igniter</strong>, a light and powerful PHP framework.<br />
It is capable to manage any kind of project/website, specially when it have many different types of contents.</p>

<p>BANCHA bases its power on some pillars that make it different from other CMS:</p>
<ul>
	<li>It allows you to handle any type of content: pages, news, photo galleries, products, etc ... through XML schemas.</li>
	<li>In 10 minutes, you can configure a website to manage, list and view any kind of thing you want.</li>
	<li>It doesn't sacrifice the performance of a static site, because under the hood it uses many different caching systems.</li>
	<li>It's modular, so it can be extended with different types of modules that you can develop yourself.</li>
	<li>It's totally open-source (you can download the source from http://getbancha.com).</li>
	<li>It's easy to install and to maintain. It doesnt't need any complicated or advanced infrastructure: just PHP5, some extensions and a DB server.</li>
	<li>It totally separates the application framework (MVC) from the website themes, so it's easy to use for web developers as well as web designers.</li>
	<li>It use a wonderful ORM system to "play" with the database objects without the needs to write a single query.</li>
</ul>

			<h3>Bancha MVC architecture</h3>
			<p>Here below you can see the Bancha Model-View-Controller architecture.</p>
			<img src="<?php echo site_url(THEMESPATH.'admin/widgets/mvc_scheme.png'); ?>" border="0" alt="Bancha MVC" />
			<br />

			<br />
			<h3>Credits</h3>

			Bancha has been developed (and is currently mantained) by <a href="http://www.squallstar.it">Nicholas Valbusa</a>.<br />
			Bancha is released under GNU/GPL license. You can read the entire license from the License.txt file in the project root directory.<br />
			<br />
			Copyright <a href="http://www.squallstar.it/">Squallstar Studio</a> &copy;2011
			</p>
		</div>

		<div class="sidebar_content" id="sb-install">
			<h3>2. How to install</h3>

<p>1. Before all, sets your current environment in the <strong>/index.php</strong> file.<br />
The default environment is "sqlite" (while developing is very useful) but you are encouraged to use <strong>development</strong> or production as environment type, and run the database on a real server.</p>
<p>2. Sets the database connecting parameters here: <strong>application/config/database.php</strong> (or sqlite/database.php under sqlite environments)</p>
<p>3. Go through your browser to this URL: http://yourwebsitename/admin/install</p>
<p>4. Choose your install type between "Default" and "Blog".<br />
The Blog one, will create and configures the "Blog" and "Comments" content types for you (the Default will create only the website menu)</p>
<p>5. You're done! The install script will create a content type named "Menu" which is linked to the page tree of your website.</p>

		</div>

		<div class="sidebar_content" id="sb-content-types">
			<h3>3. Tipi di contenuto</h3>
			<p>Bancha ti permette di definire diversi contenuti per il tuo sito internet.
			Ogni contenuto, &egrave; basato su un file XML che ne descrive tutti i campi gestibili.
			In questo modo, puoi creare centinaia di schemi per amministrare i vari contenuti del tuo sito internet.
			Ad esempio, un tipo di contenuto potrebbero essere i <strong>Prodotti</strong>, oppure delle <strong>Gallerie immagini</strong>.</p>
			<p>
			Un tipo di contenuto deve sempre essere uno tra i seguenti tipi:</p>
			<ul>
				<li><strong>Semplice</strong> (per contenuti lineari, senza gerarchia)</li>
				<li><strong>Ad albero</strong> (per contenuti strutturabili gerarchicamente, come le pagine di un sito internet)</li>
			</ul>
			<p>
			Come avrai intuito, anche le pagine stesse di un sito internet sono a loro volta un tipo di contenuto. &Egrave; proprio per questo che
			dovr&agrave; essere definito almeno un tipo di contenuto associato all'albero delle pagine del sito. Tale associazione viene impostata nel file di configurazione di Bancha alla voce <strong>DEFAULT TREE TYPE</strong>.</p>

			<p>Aggiungendo un nuovo tipo di contenuto, verranno <strong>automaticamente creati</strong> i seguenti files:</p>
			<ul>
				<li><strong>/application/xml/Nome_contenuto.xml</strong> che conterr&agrave; la struttura dei campi gestibili,</li>
				<li><strong>/application/views/website/type_templates/Nome_contenuto/list.php</strong> ovvero il template xhtml per la visualizzazione come lista dei contenuti,</li>
				<li><strong>/application/views/website/type_templates/Nome_contenuto/detail.php</strong> rispettivo template per la visualizzazione di dettaglio.</li>
			</ul>
			<p>Bene, ora sei pronto per imparare a configurare un tipo di contenuto!<br />Prosegui con la prossima sezione della documentazione: <strong>4. Schema XML dei tipi</strong>.
		</div>

		<div class="sidebar_content" id="sb-types-xml">
			<h3>4. Schema XML dei tipi</h3>
<p>Ogni tipo di contenuto &egrave; associato ad un relativo file XML presente nella directory <strong><?php echo $this->config->item('xml_typefolder'); ?></strong> che ne descrive i campi associati. Puoi editare tale file anche dall'amministrazione, premendo il link <strong>Modifica schema</strong> nella lista dei tipi di contenuto/pagine. La struttura base di un tipo di contenuto/pagina &egrave; definita in questo modo:</p>
			<pre class="prettyprint"><code>&lt;?xml version="1.0" encoding="utf-8"?&gt;
&lt;content>
	&lt;id&gt;1&lt;/id&gt;
	&lt;name&gt;Prodotti&lt;/name&gt;
	&lt;description&gt;Prodotti E-commerce&lt;/description&gt;
	&lt;tree&gt;true&lt;/tree&gt;
	&lt;parent_types&gt;
		&lt;type&gt;pagine&lt;/type&gt;
	&lt;/parent_types&gt;
	&lt;has_categories&gt;true&lt;/has_categories&gt;
	&lt;has_hierarchies&gt;false&lt;/has_hierarchies&gt;
	&lt;primary_key&gt;id_record&lt;/primary_key&gt;
	&lt;table&gt;records&lt;/table&gt;
	&lt;table_stage&gt;records_stage&lt;/table_stage&gt;
	&lt;fieldset&gt;
	&lt;/fieldset&gt;
&lt;/content&gt;</code></pre><br />
<p>Il nodo <strong>&lt;id&gt;</strong> verr&agrave; popolato automaticamente da Bancha quando verr&agrave; creato il tipo di contenuto, cos&igrave; come i nodi <strong>&lt;name&gt;</strong> e <strong>&lt;description&gt;</strong> che definiscono rispettivamente il nome utilizzato internamente come chiave, e quello visualizzato all'utente nel pannello.<br />
Il nodo <strong>&lt;tree&gt;</strong> &egrave; un booleano che descrive se il contenuto &egrave; strutturato ad albero o lineare/semplice (vedi la sezione <strong>3. Tipi di contenuto</strong> per maggiori informazioni).<br /><br />
Il nodo <strong>&lt;parent_types&gt;</strong> &egrave; obbligatorio per i contenuti ad albero, e descrive i nomi di tutti i tipi di contenuto da utilizzare come riferimento per le pagine padre. Di default, viene impostato con il tipo di contenuto stesso.
<br /><br />
I parametri <strong>primary_key</strong> e <strong>table</strong> permettono di definire rispettivamente la chiave primaria e la tabella da utilizzare nel salvataggio del tipo di contenuto.<br />
Sentiti libero di creare ulteriori tabelle oltre alla tabella records nel caso ne avessi bisogno. Se il nodo <strong>table_stage</strong> &egrave; definito, il tipo di contenuto user&agrave; anche uno staging sulla tabella scelta (che dovr&agrave; contenere una colonna chiamata "published" definita come INT(1) DEFAULT 0).<br /><br />
Il nodo <strong>&lt;has_categories&gt;</strong> &egrave; sempre un booleano, e definisce se il tipo di contenuto deve presentare la sezione <strong>Categorie</strong>, che permette di raggruppare i contenuti di quel tipo in diverse categorie amministrabili dal pannello.</p>

<h3>Fieldsets</h3>
<p>Ogni tipo di contenuto pu&ograve; contiene infiniti fieldsets. Ogni fieldset, visivamente sar&agrave; una sotto-sezione e potr&agrave; contenere infiniti field (campi di inserimento). Ogni fieldset dovr&agrave; avere un nome unico tra quelli dello stesso tipo di contenuto (definito attraverso il nodo <strong>&lt;name&gt;</strong>) e conterr&agrave; uno o pi&ugrave; nodi di tipo <strong>&lt;field&gt;</strong>.</p>
<div class="message info">Strutturare i tuoi campi in fieldset ordinati semanticamente, aiuter&agrave; gli utilizzatori durante l'inserimento dati.</div>
<pre class="prettyprint"><code>&lt;?xml version="1.0" encoding="utf-8"?&gt;
&lt;content>
	...
	&lt;fieldset&gt;
		&lt;name&gt;Campi generali&lt;/name&gt;
		&lt;field id="campo_1"&gt;...&lt;/field&gt;
		&lt;field id="campo_2"&gt;...&lt;/field&gt;
	&lt;/fieldset&gt;
	&lt;fieldset&gt;
		&lt;name&gt;Campi secondari&lt;/name&gt;
		&lt;field id="campo_3"&gt;...&lt;/field&gt;
		&lt;field id="campo_4"&gt;...&lt;/field&gt;
	&lt;/fieldset&gt;
&lt;/content&gt;</code></pre><br />

<h3>Fields</h3>
<p>I nodi di tipo fields all'interno di un nodo fieldset, sono utilizzati per descrivere un singolo campo di input per tale di tipo di contenuto.
Ogni nodo dovr&agrave; avere un id univoco descritto attraverso l'attributo <strong>id</strong> e dovr&agrave; essere uno dei seguenti tipi di campo (definito tramite il nodo "type"):</p>
<ul>
	<li><strong>text</strong> - Per utilizzare un input testuale su riga singola senza stile</li>
	<li><strong>textarea</strong> - Per utilizzare un input multiriga con possibilit&agrave; di inserimento stili e codice HTML</li>
	<li><strong>textarea_full</strong> - Per utilizzare un campo di inserimento multiriga tramite editor CKEDITOR (avanzato)</li>
	<li><strong>textarea_code</strong> - Per utilizzare un campo multiriga per inserire codice (ad esempio: css e javascript)</li>
	<li><strong>select</strong> - Per utilizzare un campo di scelta singola a tendina</li>
	<li><strong>multiselect</strong> - Per utilizzare un campo di scelta multipla (due tendine con add/remove)</li>
	<li><strong>checkbox</strong> - Per utilizzare un campo di scelta multipla in stile "checkbox"</li>
	<li><strong>hierarchy</strong> - Per utilizzare un campo di scelta multipla in stile "esplora risorse"</li>
	<li><strong>files</strong> - Per utilizzare un campo di caricamento files</li>
	<li><strong>images</strong> - Per utilizzare un campo di caricamento immagini</li>
	<li><strong>hidden</strong> - Per utilizzare un campo di tipo hidden</li>
	<li><strong>date</strong> - Per utilizzare un campo di tipo data</li>
	<li><strong>datetime</strong> - Per utilizzare un campo di tipo data e ora</li>
</ul>
<p>Il nodo field pu&ograve; avere anche associato l'attributo <strong>column</strong> (booleano) che definisce se la colonna &egrave; fisica sul database. Se non definito, verr&agrave; usato l'xml per lo storage del campo.<br /></p>
<div class="message warning">Ogni field dovr&agrave; sempre specificare il tipo di campo di appartenenza (tra quelli sopra descritti) attraverso il nodo <strong>&lt;type&gt;</strong></div>
<p>Inoltre, ogni field ha a disposizione le seguenti propriet&agrave; definibili sempre tramite nodi:</p>
<ul>
	<li><strong>&lt;description&gt;</strong> (string) - per definire il testo da utilizzare come label del campo</li>
	<li><strong>&lt;mandatory&gt;</strong> (bool) - per definire se un campo &egrave; obbligatorio</li>
	<li><strong>&lt;length&gt;</strong> (int) - per definire la lunghezza massima accettata per i campi testuali</li>
	<li><strong>&lt;list&gt;</strong> (bool) - per definire se il campo andr&agrave; estratto nelle operazioni di "Lista contenuti"</li>
	<li><strong>&lt;admin&gt;</strong> (bool) - per definire se il campo dovr&agrave; essere visualizzato nella lista dei contenuto di questo tipo in amministrazione</li>
	<li><strong>&lt;visible&gt;</strong> (bool) - per definire se inizialmente un campo deve essere nascosto via CSS (display:none)</li>
	<li><strong>&lt;default&gt;</strong> (string) - per definire il valore da utilizzare come predefinito</li>
	<li><strong>&lt;onkeyup&gt;</strong> (string) - per definire del codice Javascript da eseguire al "key up" sul campo</li>
	<li><strong>&lt;onchange&gt;</strong> (string) - per definire del codice Javascript da eseguire al change del valore (solo per i campi di tipo select)</li>
</ul>

<div class="message warning">Ogni campo che dovr&agrave; essere salvato su una colonna fisica della propria tabella, necessita dell'attributo <strong>"column"</strong> impostato a "true".</div>

<p>Ecco un esempio di definizione di un campo di testo semplice:</p>
<pre class="prettyprint"><code>&lt;field id="title" column="true"&gt;
	&lt;description&gt;Titolo&lt;/description&gt;
	&lt;type&gt;text&lt;/type&gt;
	&lt;mandatory&gt;true&lt;/mandatory&gt;
	&lt;admin&gt;true&lt;/admin&gt;
	&lt;default&gt;Senza titolo&lt;/default&gt;
&lt;/field&gt;</code></pre><br />
<p>Il nodo <strong>default</strong> accetta anche codice php se viene preposto "eval:", ad esempio <strong>&lt;default&gt;eval:time();&lt;/default&gt;</strong>
<p>I campi di tipo <strong>select</strong>, <strong>multiselect</strong> e <strong>checkbox</strong> inoltre, avranno a disposizione il nodo <strong>&lt;options&gt;</strong> per definire le opzioni associate a tale campo, come in questo esempio:</p>
<pre class="prettyprint"><code>&lt;options&gt;
	&lt;option value="T"&gt;Si&lt;/option&gt;
	&lt;option value="F"&gt;No&lt;/option&gt;
&lt;/options&gt;</code></pre>
<br />
<p>In alternativa, &egrave; possibile anche estrarre dinamicamente le options tramite query SQL. Di seguito un esempio:</p>
<pre class="prettyprint"><code>&lt;field id="galleria"&gt;
	&lt;description&gt;Galleria collegata&lt;/description&gt;
	&lt;type&gt;select&lt;/type&gt;
	&lt;sql cache="false"&gt;
		&lt;select&gt;id_record AS value, title AS name&lt;/select&gt;
		&lt;from&gt;records&lt;/from&gt;
		&lt;type&gt;Gallerie&lt;/type&gt;
		&lt;where&gt;published = 1&lt;/where&gt;
		&lt;order_by&gt;name ASC&lt;/order_by&gt;
	&lt;/sql&gt;
&lt;/field&gt;</code></pre><br />
<div class="message info">Impostando la cache attiva, la query verr&agrave; cacheata fino a che non verr&agrave; svuotata la cache dei tipi.</div>
<p>Nota: qui, le clausole WHERE sono utilizzabili solamente su campi fisici della tabella selezionata e non sui campi XML.</p>

<p>&Egrave; possibile anche estrarre le options tramite <strong>eval</strong>, utilizzando il nodo <strong>&lt;custom&gt;</strong>:</p>
<pre class="prettyprint"><code>&lt;field id="lang"&gt;
	&lt;description&gt;Lingua del contenuto&lt;/description&gt;
	&lt;type&gt;select&lt;/type&gt;
	&lt;options&gt;
		&lt;custom&gt;config_item('languages_select');&lt;/custom&gt;
	&lt;/options&gt;
&lt;/field&gt;</code></pre><br />

<p>I campi di tipo <strong>files</strong> e <strong>images</strong> avranno a disposizione anche le seguenti propriet&agrave;:</p>
<pre class="prettyprint"><code>&lt;size&gt;2048&lt;/size&gt;
&lt;mimes&gt;jpg|png|gif&lt;/mimes&gt;
&lt;max&gt;5&lt;/max&gt;</code></pre>
<br />
<p>Rispettivamente, definiscono la dimensione massima dei files caricati in Kb, i mime-types accettati e il numero massimo di files caricabili in questo campo.
Il campo <strong>&lt;mimes&gt;</strong> accetta anche il valore <strong>*</strong> come segnaposto per accettare tutti i mimes.</p>

<p>I campi di tipo <strong>images</strong> avranno a disposizione le propriet&agrave; dei campi files ed anche le seguenti propriet&agrave;:</p>
<pre class="prettyprint"><code>&lt;original&gt;true&lt;/original&gt;
&lt;encrypt_name&gt;true&lt;/encrypt_name&gt;
&lt;resized&gt;640x?&lt;/resized&gt;
&lt;thumbnail&gt;150x100&lt;/thumbnail&gt;</code></pre>
<br />
<p>Il campo <strong>&lt;original&gt;</strong> imposta se salvare l'immagine originale. Il campo <strong>&lt;encrypt_name&gt;</strong> definisce se il nome del file salvato deve essere mantenuto tale.<br />
I campi <strong>&lt;resized&gt;</strong> e <strong>&lt;thumbnail&gt;</strong> impostano le dimensioni dell'immagine da ridimensionare per le due nuove rispettive immagini.<br />
Il campo thumbnail accetta anche l'attributo <strong>preset</strong> relativo al preset da utilizzare nella visualizzazione del thumbnail in amministrazione.<br />
Il marcatore <strong>"?"</strong> pu&ograve; essere utilizzato come misura automatica per mantenere le proporzioni della misura definita.</p>

<p>Di seguito un esempio completo di struttura per definire un tipo di contenuto con un campo di testo, una select ed un campo di caricamento per una singola immagine:</p>
<pre class="prettyprint"><code>&lt;?xml version="1.0" encoding="utf-8"?&gt;
&lt;content>
	&lt;id&gt;1&lt;/id&gt;
	&lt;name&gt;pagine&lt;/name&gt;
	&lt;description&gt;Pagine generiche&lt;/description&gt;
	&lt;tree&gt;true&lt;/tree&gt;
	&lt;parent_types&gt;
		&lt;type&gt;pagine&lt;/type&gt;
	&lt;/parent_types&gt;
	&lt;has_categories&gt;true&lt;/has_categories&gt;
	&lt;has_hierarchies&gt;false&lt;/has_hierarchies&gt;
	&lt;primary_key&gt;id_record&lt;/primary_key&gt;
	&lt;table&gt;records&lt;/table&gt;
	&lt;table_stage&gt;records_stage&lt;/table_stage&gt;

	&lt;fieldset&gt;
		&lt;name&gt;Informazioni&lt;/name&gt;

		&lt;field id="nome_utente" column="true"&gt;
			&lt;description&gt;Il tuo nome&lt;/description&gt;
			&lt;type&gt;text&lt;/type&gt;
			&lt;mandatory&gt;true&lt;/mandatory&gt;
			&lt;length&gt;32&lt;/length&gt;
			&lt;list&gt;true&lt;/list&gt;
		&lt;/field&gt;

		&lt;field id="colore_preferito"&gt;
			&lt;description&gt;Scegli il tuo colore preferito&lt;/description&gt;
			&lt;type&gt;select&lt;/type&gt;
			&lt;default&gt;red&lt;/default&gt;
			&lt;options&gt;
				&lt;option value="red"&gt;Rosso&lt;/option&gt;
				&lt;option value="blue"&gt;Blu&lt;/option&gt;
				&lt;option value="green"&gt;Verde&lt;/option&gt;
			&lt;/options&gt;
			&lt;admin&gt;true&lt;/admin&gt;
		&lt;/field&gt;

		&lt;field id="profile_img"&gt;
			&lt;description&gt;Immagine profilo&lt;/description&gt;
			&lt;type&gt;images&lt;/type&gt;
			&lt;mandatory&gt;true&lt;/mandatory&gt;
			&lt;encrypt_name&gt;false&lt;/encrypt_name&gt;
			&lt;mimes&gt;jpg|gif|png&lt;/mimes&gt;
			&lt;max&gt;1&lt;/max&gt;
			&lt;original&gt;false&lt;/original&gt;
			&lt;resized&gt;150x?&lt;/resized&gt;
			&lt;thumbnail preset="user_profile"&gt;50x50&lt;/thumbnail&gt;
			&lt;list&gt;true&lt;/list&gt;
		&lt;/field&gt;

	&lt;/fieldset&gt;
&lt;/content&gt;</code></pre><br />
<p>Alla creazione di un nuovo tipo di contenuto, lo schema XML associato conterr&agrave; gi&agrave; una struttura di base con alcuni campi dimostrativi, tra cui quelli relativi ai <strong>Meta tags</strong>.</p>

		</div>

		<div class="sidebar_content" id="sb-page-actions">
			<h3>5. Azioni delle pagine</h3>

			<p>Ogni contenuto di tipo albero (da ora in poi, pagine) pu&ograve; avere associata una delle seguenti azioni di visualizzazione:</p>
			<ul>
				<li>Solo testo</li>
				<li>Lista di contenuti</li>
				<li>Azione personalizzata</li>
				<li>Link a pagina esterna</li>
			</ul>
			<p>Nota: tale lista viene compilata con il campo <strong>action</strong> presente nel file XML di definizione dei tipi.<br /><br />
In base all'azione scelta, il rendering della pagina si comporter&agrave; in modo diverso: nel primo caso (solo testo), verr&agrave; visualizzato solamente il testo inserito nel campo con id <strong>contenuto</strong> di tale pagina. Nel secondo caso (<strong>lista di contenuti</strong>), sar&agrave; possibile scegliere il tipo di contenuti da estrarre ed eventuali condizioni SQL WHERE.<br /><br />
Scegliendo "Lista di contenuti", la pagina utilizzer&agrave; il template <strong>list.php</strong> del relativo tipo di contenuto scelto per la renderizzazione. Tale template &egrave; contenuto nella directory <strong>application/views/type_templates/{NomeTipo}/</strong><br /><br />
Scegliendo <strong>azione personalizzata</strong>, la pagina invocher&agrave; l'azione da te definita anzich&egrave; seguire il suo normale percorso di routing. In questo modo potrai creare una azione ad-hoc nel controller delle azioni.<br />
<div class="message info">Le azioni personalizzate, vanno definite nel controller: <u><?php echo $this->config->item('custom_controllers_folder'); ?>actions.php</u></div>
<br />&Egrave; possibile configurare manualmente i comportamenti delle singole azioni agendo sul file responsabile del rendering: <strong><?php echo THEMESPATH; ?>desktop/content_render.php</strong>. Tale file, presenta uno switch-case facilmente configurabile per modificare o implementare nuovi casi per il rendering.</p>
<p>Le azioni personalizzate possono essere di due tipi:</p>
<ul>
	<li><strong>Dispatch</strong> - vengono chiamate dal dispatcher, interrompendo il processo tradizionale di rendering</li>
	<li><strong>Content render</strong> - vengono chiamate nel content_render, ovvero all'interno del corpo della pagina</li>
</ul>
<p>Tale comportamento pu&ograve; essere deciso dalla select sotto a quella relativa al nome dell'azione da chiamare.</p>
<div class="message info">L'azione chiamata, ricever&agrave; come primo parametro il nome dell'oggetto chiamante, che potr&agrave; essere "dispatcher" oppure "content_render".</div>

		</div>

		<div class="sidebar_content" id="sb-template-layout">
			<h3>6. Template e Layout</h3>
			<p>Bancha utilizza un sistema di viste composto da un elemento principale (<strong>template</strong>) che a sua volta renderizza altre sotto viste, tra cui <strong>header</strong>, <strong>footer</strong> e <strong>content_render</strong>.<br /><br />
Questo vuol dire che un sito potrebbe avere anche 10 template diversi per le varie sezioni del sito internet.<br /><br />Ogni pagina, presenta un campo chiamato <strong>view_template</strong> che definisce il template da renderizzare per tale pagina. L'elenco dei templates &egrave; contenuto nella cartella <strong><?php echo THEMESPATH; ?>nometema/views/templates</strong> e di base presenta questi templates:</p>
<ul>
	<li><strong><?php echo THEMESPATH; ?>nometema/views/templates/default.php</strong> - utilizzato di default per le pagine</li>
	<li><strong><?php echo THEMESPATH; ?>nometema/views/templates/home.php</strong> - utilizzato dalla homepage del sito</li>
</ul>
<p>Come potrai vedere, ognuno di questi template contiene del codice HTML dichiarativo e a sua volta sceglie quali altre view renderizzare. Per renderizzare altre view, &egrave; sufficiente utilizzare la funzione nativa <strong>load->view</strong> di Code Igniter. Ecco un esempio di template:</p>
<pre class="prettyprint"><code>&lt;?php $this->load->view('header'); ?&gt;
&lt;?php $this->load->view('content_render'); ?&gt;
&lt;?php $this->load->view('footer'); ?&gt;</code></pre>
<br />
<div class="message info">La parte di layout relativa alle dichiarazioni <strong>html, head, body</strong> &egrave; contenuta nel file <?php echo THEMESPATH; ?>desktop/views/layout.php</div>
<p>Quando definisci altri template, ricorda di inserire una nuova option nel campo <strong>view_template</strong> all'interno del tipo di contenuto (solitamente "Pagine") di modo che venga visualizzato il nuovo template nell'area amministrativa.<br /><br />
Templates e views, dispongono gi&agrave; degli helper <strong>url</strong> e <strong>html</strong> di Code Igniter caricati, quindi potrai gi&agrave; utilizzarli.<br /><br />
Anche i tipi di contenuto semplici (non strutturabili ad albero) presentano delle view per la loro visualizzazione:</p>
<ul>
	<li><strong>application/views/type_templates/{NomeTipo}/detail.php</strong> - utilizzata nel rendering di dettaglio di un singolo contenuto</li>
	<li><strong>application/views/type_templates/{NomeTipo}/list.php</strong> - utilizzata nel rendering di lista di pi&ugrave; contenuti di un singolo tipo.</li>
</ul>
<p>Senza toccare i template originali, puoi duplicare tali files all'interno del tuo tema e verrano utilizzati automaticamente se disponibili.<br />
Ad esempio uno dei due files sopra elencati potr&agrave; essere duplicato nella seguente directory: <strong><?php echo THEMESPATH; ?>desktop/type_templates</strong>.</p>
<p>Nulla ti vieta di definire altri templates per i tipi e utilizzarli in maniere da te definite nel <strong>content_render.php</strong> (contenuto in ogni tema).</p>

		</div>

		<div class="sidebar_content" id="sb-views">
			<h3>7. Temi, view e rendering</h3>
			<p>Il front-end del sito internet, pu&ograve; utilizzare pi&ugrave; temi per differenziare ad esempio il sito <strong>desktop</strong> dalla versone <strong>mobile</strong>.
Nel menu <strong>Gestione >> Impostazioni</strong> troverai la configurazione per scegliere i temi da attivare per entrambe le versioni. Per aggiungere un nuovo tema, dopo averlo posizionato nella directory <strong><?php echo THEMESPATH; ?></strong>, aggiungilo anche alla variabile <strong>WEBSITE INSTALLED THEMES</strong> nel file di configurazione di <?php echo CMS; ?>.<br />
NOTA: Lo switch al tema mobile, verr&agrave; effettuato in automatico nel caso in cui il sito venga visitato da un device mobile.</p>
<p>Per forzare lo switch da un tema all'altro, puoi utilizzare le route speciali <strong>go-desktop</strong> e <strong>go-mobile</strong> in questo modo:</p>
<pre class="prettyprint"><code><?php echo site_url('go-desktop'); ?>

<?php echo site_url('go-mobile'); ?>
</code></pre><br />
			<p>Bancha utilizza la classe <strong>View</strong> per settare degli oggetti nelle viste e renderizzarle.<br />
L'oggetto view, &egrave; presente globalmente accedendo alla variabile "view" di Code Igniter in questo modo: <strong>$this->view</strong>.<br /><br />
Il metodo base per settare un oggetto nella view, &egrave; il <strong>set()</strong>, e va utilizzato in questa maniera:</p>
<pre class="prettyprint"><code>$this->view->set('nome_oggetto', $valore);</code></pre><br />
<p>Dalla view, sar&agrave; possibile accedere agli oggetti impostati utilizzando le variabili semplici di PHP con la chiave impostata.
Nel nostro esempio sopra, dalla view sar&agrave; disponibile la variabile in questo modo:</p>
<pre class="prettyprint"><code>echo $nome_oggetto;</code></pre><br />
<p>Nel caso fosse necessario eliminare una variabile prima del rendering della view, &egrave; possibile utilizzare il metodo <strong>remove()</strong> che accetta come singolo parametro la chiave dell'oggetto da eliminare.<br /><br />
Di seguito, le funzioni utili a renderizzare view, o templates.</p><br />

<p>La classe view, presenta anche delle propriet&agrave; utili al layout quali:</p>
<ul>
	<li><strong>javascript</strong> - array</li>
	<li><strong>css</strong> - array</li>
	<li><strong>title</strong> - stringa</li>
	<li><strong>keywords</strong> - stringa</li>
	<li><strong>description</strong> - stringa</li>
</ul>

<h3>render_template($template_file, $layout = true)</h3>
<p>Renderizza un template presenta nella directory dei templates, utilizzando lo skin in uso (solitamente <strong><?php echo THEMESPATH; ?>nometema/templates/</strong>).
&Egrave; il metodo di rendering pi&ugrave; utilizzato, e viene utilizzata soprattutto dal motore di routing generale del sito per renderizzare la struttura base delle pagine.<br />
Nel caso non si volesse passare per il layout, passare come secondo parametro "false" e verr&agrave; renderizzato solo il template scelto.</p>
<pre class="prettyprint"><code>$this->render_template('default');
//Renderizza il file <?php echo THEMESPATH; ?>nometema/templates/default.php

$this->render_template('home');
//Renderizza la homepage del sito, ovvero il file <?php echo THEMESPATH; ?>desktop/templates/home.php
//passando per il layout: <?php echo THEMESPATH; ?>desktop/layout.php</code></pre><br />

<h3>render($view_file)</h3>
<p>Metodo base per il rendering di una singola vista lato front-end. Utilizza il path relativo allo skin in uso:</p>
<pre class="prettyprint"><code>$this->view->render('pagina');
//Renderizzer&agrave; il file <?php echo THEMESPATH; ?>desktop/pagina.php

$this->view->render('demo/prova');
//Renderizzer&agrave; il file <?php echo THEMESPATH; ?>desktop/demo/prova.php</code></pre><br />

<h3>render_type_template($type_name, $view_file)</h3>
<p>Metodo per renderizzare una vista specifica di un tipo di contenuto. Principalmente viene usata dal <strong>content_render</strong> (views/layout/content_render.php) per renderizzare automaticamente le viste dei tipi di contenuti, ma nulla ci vieta di usarlo a nostro piacimento come segue:</p>
<pre class="prettyprint"><code>$this->view->render_type_template('Prodotti', 'list');
//Renderizzer&agrave; il file application/views/type_templates/Prodotti/list.php

$this->view->render_type_template('Gallerie', 'detail');
//Renderizzer&agrave; il file application/views/type_templates/Gallerie/detail.php</code></pre><br />

<h3>render_layout($view_file)</h3>
<p>Metodo che renderizza un layout utilizzando il path base settato precedentemente.<br />Viene attualmente usato <strong>solamente dall'amministrazione</strong> utilizzando come base "admin/" in modo da renderizzare automaticamente solo le viste figlie di tale path.</p>
<pre class="prettyprint"><code>$this->view->base = 'admin/';
$this->view->render_layout('docs/general');
//Verr&agrave; renderizzato il file application/views/admin/layout/layout.php
//e verranno valorizzate le variabili $view e $content, rispettivamente con
//il nome della view da renderizzare, e i dati impostati con il metodo set().</code></pre>

		</div>

		<div class="sidebar_content" id="sb-extract-records">
			<h3>8. Estrazione di contenuti</h3>
			<p>Utilizzando azioni personalizzate per il rendering delle pagine, potrebbe essere necessario che tu debba estrarre manualmente dei contenuti. Bancha porta avanti la filosofia di Code Igniter, e ti mette a disposizione la classe "Records" per le estrazioni dei record. Tale classe &egrave; gi&agrave; disponibile in qualsiasi punto della tua applicazione, facendo riferimento all'oggetto <strong>$this</strong> di Code Igniter in questo modo:</p>
<pre class="prettyprint"><code>$this->records</code></pre><br />
<p>I metodi a disposizione della classe Records sono molto semplici da utilizzare. Ad esempio, se tu volessi estrarre gli ultimi 20 contenuti di tipo "News", potrai procedere come di seguito:</p>
<pre class="prettyprint"><code>$notizie = $this->records->type('News')->limit(10)->get();</code></pre>
<br />
<p>Riceverai un array di oggetti di tipo <strong>Record</strong> che potrai utilizzare per estrarre le informazioni contenute in base allo schema XML del tipo di contenuto estratto.<br />Ad esempio, stampiamo a video il titolo delle notizie estratte:</p>
<pre class="prettyprint"><code>foreach ($notizie as $notizia)
{
	echo $notizia-><strong>get('title')</strong> . br(1);
}</code></pre><br />
<p>Come avrai visto, i records dispongono della funzione "get" per ottenere uno qualsiasi dei loro valori. Nel caso la chiave da te seleziona non esista, ti verr&agrave; ritornato il valore booleano FALSE anzich&egrave; generare errori.<br />Per ottenere il nome/id del tipo di appartenenza di un record e del record stesso, procedi come segue:</p>
<pre class="prettyprint"><code>echo $record->tipo;
//Mostra: "News"

echo $record->_tipo;
//Mostra: 1

echo $record->id;
//Mostra l'id del record</code></pre><br />
<div class="message info">Per estrarre i documenti associati ad un record, &egrave; sufficiente lanciare la sua funzione <strong>set_documents()</strong> per valorizzare i relativi campi.
In alternativa, &egrave; possibile utilizzare la funzione <strong>documents()</strong> documentata qui sotto per estrarre i documenti al momento dell'estrazione dei records.</div>
<p>La classe records, dispone dei seguenti metodi raffinare le estrazioni:</p>
<ul>
	<li><strong>type($tipo_contenuto)</strong> - definisce il tipo di contenuto da usare per la ricerca</li>
	<li><strong>published($bool)</strong> - definisce se estrarre solo record pubblicati</li>
	<li><strong>language()</strong> - imposta se estrarre solo i contenuti nella lingua in uso (&egrave; possibile passare anche un'altra lingua come parametro della funzione)</li>
	<li><strong>documents($bool)</strong> - definisce se estrarre subito anche i documenti collegati anzich&egrave; usare la funzione <strong>set_documents()</strong> sui singoli record.</li>
	<li><strong>where($field, $value)</strong> - definisce una condizione where (anche su campi non fisici - xml)</li>
	<li><strong>like($field, $value)</strong> - definisce una condizione like (anche su campi non fisici - xml)</li>
	<li><strong>limit($limit, $offset)</strong> - definisce una condizione limit</li>
	<li><strong>order_by($field, $order)</strong> - definisce una condizione di order by</li>
</ul>
<p>E per estrarre i records:</p>
<ul>
	<li><strong>get()</strong> - per estrarre i records con le condizioni scelte. Passando come primo parametro un ID, ritorna solamente il record con tale ID anzich&erave; un array di records.</li>
	<li><strong>count()</strong> - per ottenere solo il numero dei records aventi tali condizioni</li>
	<li><strong>get_first()</strong> - per estrarre solamente un record (ritorna direttamente un oggetto <strong>Record</strong>)</li>
</ul>
<p>Vediamo un altro esempio di estrazione:</p>
<pre class="prettyprint"><code>$num_notizie = $this->records->type('News')
	->like('anno', '%2011')
	->limit(40, 10)
	->published(true)
	->order_by('id_record', 'DESC')
	->count();</code></pre><br />
<p>I records presentano alcuni campi fisici preimpostati utilizzati nelle clausole delle query di estrazione, e visualizzabili tramite la funzione "get":</p>
<ul>
	<li><strong>id_record</strong> - rappresenta l'id interno univoco del record</li>
	<li><strong>uri</strong> - ovvero l'URI univoco di quel record (parte finale dell'URL)</li>
	<li><strong>date_insert</strong> - il timestamp riferito al primo inserimento del record</li>
	<li><strong>date_update</strong> - il timestamp riferito all'ultimo salvataggio del record</li>
	<li><strong>date_publish</strong> - il timestamp riferito alla data di pubblicazione del record</li>
	<li><strong>id_parent</strong> - rappresenta l'id del record padre (solo nei contenuti ad albero)</li>
	<li><strong>title</strong> - rappresenta il titolo del record</li>
	<li><strong>lang</strong> - rappresenta la lingua del record (se presente)</li>
	<li><strong>priority</strong> - numero intero che indica la priorit&agrave; di una pagina nell'albero di menu</li>
	<li><strong>published</strong> - definisce se un record &egrave; pubblicato (0 = bozza, 1 = pubblicato, 2 = differente tra sviluppo e produzione)</li>
	<li><strong>child_count</strong> - presente solo per i record con tipo gerarchico (es. "Menu"), &egrave; un numero intero che indica il numero di record figli.</li>
</ul>
<p>I campi di tipo <strong>date</strong> e <strong>datetime</strong> vengono automaticamente salvati come <strong>timestamps</strong>. Ci&ograve; nonostante, puoi accedere alla data/ora nel formato locale semplicemente aggiungendo un underscore davanti al nome della chiave da ricercare:</p>
<pre class="prettyprint"><code>$record->get('date_publish');
//Mostra: 1237138738

$record->get('_date_publish');
//Mostra '15/06/1987 16:30:00'</code></pre><br />
<p>Durante le estrazioni, come impostazione predefinita verranno selezionati tutti i campi del record.<br />
Per estrarre solamente i campi che nello schema XML hanno attributo <strong>&lt;list&gt;</strong>, utilizza la funzione <strong>set_list()</strong> come segue:</p>
<pre class="prettyprint"><code>$post = $this->records->type('Blog')->set_list(TRUE)->limit(10)->get();</code></pre><br />
<div class="message info">Puoi aggiungere campi fisici ad un record intervenendo sull'attributo <strong>column</strong> di un qualsiasi dei campi all'interno dello schema di un tipo di contenuto.
Dopodich&egrave;, sar&agrave; necessario anche aggiungere tale colonna alla tabella <strong>records</strong> di Bancha con un ALTER-TABLE.</div>
<br />
<h3>Creare e salvare records</h3>
<p>&Egrave; possibile creare, aggiornare ed eliminare records anche in maniera manuale. Di seguito un'esempio:</p>
<pre class="prettyprint"><code>//Creiamo un nuovo record di tipo Blog e ne impostiamo titolo e autore
$post = new Record('Blog');
$post-&gt;('title', 'My first post');
$post-&gt;('author', 'Nicholas');

//Salviamo sul database il post
$id = $this-&gt;records-&gt;save($post);

//Pubblichiamo il nostro post
$this-&gt;records-&gt;publish($id, 'Blog');

//In alternativa alla riga sopra, possiamo utilizzare la funzione set_type()
$this-&gt;records-&gt;set_type('Blog')-&gt;publish($id);</code></pre>
<br />
<div class="warning message">La funzione <strong>set_type()</strong> usata nell'esempio sopra, forza la classe records a utilizzare tabelle e chiavi inerenti al tipo di contenuto passato come parametro.</div>
</div>

		<div class="sidebar_content" id="sb-documents">
			<h3>9. Documenti (files e immagini)</h3>
			<p>Tramite lo schema XML di un tipo di contenuto, &egrave; possibile impostare dei campi di tipo "files" o "images", utili per poter caricare appunto files generici e immagini.
			La differenza sostanziale tra questi due tipi risiede nel fatto che le immagini prevedono delle opzioni di ridimensionamento oltre al mero caricamento del file.</p>
			<p>Per estrarre i documenti di un record, &egrave; sufficiente invocare la sua funzione set_documents() in questa maniera:</p>
<pre class="prettyprint"><code>$record-><strong>set_documents();</strong>
print_r($record->get('lista_immagini');</code></pre><br />
<p>In alternativa, anzich&egrave; chiamare singolarmente la funzione per ogni record, &egrave; sufficiente utilizzare la funzione <strong>documents()</strong> durante l'estrazione dei
records per procedere alla valorizzazione automatica dei documenti:</p>
<pre class="prettyprint"><code>$records = $this->records->type('Menu')-><strong>documents(TRUE)</strong>->get();</code></pre><br />
<div class="message warning">Attenzione: quest'ultimo esempio comporta maggior carico lato database poich&egrave; per ogni record estratto, verr&agrave; effettuata una seconda query per estrarre subito i suoi allegati.</div>
<p>Bancha, mette a disposizione anche una classe di estrazione dei documenti. Per inizializzare la classe, utilizzare la seguente sintassi:</p>
<pre class="prettyprint"><code>$this->load->documents();</code></pre><br />
<p>La classe Documents presenta i seguenti metodi di estrazione (il funzionamento &egrave; simile alla classe Records):</p>
<ul>
	<li><strong>table($table_name)</strong> - definisce la tabella su cui ricercare (solitamente, "records")</li>
	<li><strong>id($table_pkey)</strong> - definisce la chiave primaria di ricerca della tabella sopra indicata</li>
	<li><strong>field($field_name)</strong> - definisce il nome del campo xml (sul tipo di contenuto)</li>
	<li><strong>where($column, $value)</strong> - imposta una condizione where per l'estrazione</li>
	<li><strong>limit($num, $offset)</strong> - definisce il limit-sql per l'estrazione</li>
	<li><strong>get()</strong> - estrae i documenti seguendo le condizioni impostate</li>
</ul>
<p>Ecco un esempio di estrazione:</p>
<pre class="prettyprint"><code>//Carico la classe documents
$this->load->documents();

//Estraggo 10 documenti dal record con id 12 della tabella records
$documenti = $this->documents->table('records')->id(12)->limit(10)->get();
</code></pre><br />
<p>&Egrave; possibile anche utilizzare i metodi interni della classe Documents per salvare/eliminare files:</p>
<ul>
	<li><strong>upload($name, $specs, $save_params)</strong> - salva un file (dalla $_FILES) su file system</li>
	<li><strong>save($data)</strong> - salva il record di un file (documents table)</li>
	<li><strong>resize_image($path, $resize_to, $marker)</strong> ridimensiona una immagine</li>
	<li><strong>update_alt_text($document_id, $alt_text)</strong> - aggiorna il testo alternativo di un record</li>
	<li><strong>delete_by_binds($table, $id)</strong> - elimina i documenti di un record fornendo tabella e id</li>
	<li><strong>delete_by_id($document_id)</strong> - elimina un documento fornendo il suo id</li>
</ul>
		</div>

<div class="sidebar_content" id="sb-trees">
			<h3>10. Alberi di menu</h3>
			<p>
			Per ottenere alberi di menu (solo contenuti di tipo pagina), &egrave; disponibile la seguente classe:</p>
<pre class="prettyprint"><code>$this->tree</code></pre><br />
<p>Il tipo di contenuto da utilizzare come default per la creazione dell'albero del sito, va definito nel file di configurazione di <?php echo CMS; ?> alla voce <strong>DEFAULT TREE TYPE</strong>. Per estrarre l'albero generale del sito, utilizzare la funzione <strong>get_default()</strong> come segue:</p>
<pre class="prettyprint"><code>//Estraggo l'albero generale delle pagine
$this->tree-><strong>get_default()</strong>;</code></pre>
<br />
<p>Per stampare l'albero ottenuto, &egrave; sufficiente richiamare l'helper menu in questo modo:</p>
<pre class="prettyprint"><code>$albero = $this->tree->get_default();

$this->load->helper('menu');
echo <strong>menu</strong>($albero);</code></pre>
<br /><p>L'helper provveder&agrave; in automatico a "flaggare" i vari stati sulle pagine aperte, o eventuali sotto pagine selezionate con le classi "open" e "selected".</p>
<p>Nel caso in cui volessi visualizzare solamente pagine figlie di quella attuale, utilizza la funzione <strong>get_current_branch()</strong> come segue:</p>
<pre class="prettyprint"><code>echo menu($this->tree->get_current_branch());</code></pre>
<br />
<div class="message info"><?php echo CMS; ?> provvede gi&agrave; ad estrarre il tipo di contenuto <strong>"<?php echo implode(', ', $this->config->item('default_tree_types')); ?>"</strong> come albero generale del sito internet.</div>
<p>Puoi anche estrarre diversi alberi di menu secondo le tue esigenze. La classe Tree contiene metodi simili a quanto gi&agrave; visto con la classe Records. Ecco alcuni dei metodi utilizzabili per rifinire le estrazioni:</p>
<ul>
	<li><strong>show_invisibles($bool)</strong> - imposta se estrarre i menu impostati come non visibili</li>
	<li><strong>type($type)</strong> - imposta il tipo di contenuto su cui estrarre l'albero</li>
	<li><strong>type_in($types_array)</strong> - imposta i tipi di contenuto su cui estrarre l'albero</li>
	<li><strong>parent_types($type)</strong> - imposta una ricerca tra tutti i parent types definiti nello schema del tipo scelto</li>
	<li><strong>where($key, $val)</strong> - imposta una clausola where (valida su colonne fisiche della tabella pages e pages_stage)</li>
	<li><strong>exclude_page($id_record)</strong> - esclude una determinata pagina dalla ricerca</li>
	<li><strong>exclude_parent($id_record)</strong> - esclude le pagine che hanno come padre tale pagina</li>
</ul>
<p>E quindi, i metodi per estrarre l'albero:</p>
<ul>
	<li><strong>get()</strong> - metodo classico di estrazione dell'albero secondo i filtri sopra definiti</li>
	<li><strong>get_default($type)</strong> - ottiene un albero del tipo scelto, utilizzando prima il file di cache e poi le estrazioni su DB se il file non &egrave; presente (o scaduto)</li>
	<li><strong>get_linear()</strong> - ottiene un albero lineare (verticale)</li>
	<li><strong>get_linear_dropdown()</strong> - ottiene un albero lineare come array associativo (utile per le select)</li>
</ul>
<p>Esempio di estrazione di un albero di pagine:</p>
<pre class="prettyprint"><code>$albero = $this->tree->type('Menu')
		     ->where('lang', 'it')
		     ->exclude_parent(123)
		     ->get();

echo menu($albero);
</code></pre><br />
<p>Infine, alcuni metodi di utilit&agrave; generale:</p>
<ul>
	<li><strong>get_default_branch($starting_id)</strong> - ritorna un ramo dell'albero di menu principale, partendo dalla pagina con id scelto</li>
	<li><strong>get_current_branch()</strong> - ottiene il ramo attuale dalla pagina/record corrente</li>
	<li><strong>get_branch($pages, $starting_id)</strong> - crea un ramo dell'albero utilizzando l'elenco delle pagine e l'id_record di partenza</li>
	<li><strong>get_page_hierarchy($id_record)</strong> - ottiene la gerarchia di una pagina</li>
	<li><strong>get_type_cachepath($type)</strong> - ottiene la path relativa al file di cache dell'albero di un determinato tipo</li>
	<li><strong>clear_cache($type)</strong> - pulisce i file di cache di un albero (o di quello principale se non viene passato il tipo)</li>
	<li><strong>clear()</strong> - forza il refresh dei dati da DB alla ricerca successiva (normalmente un albero viene cacheato fino alla fine della richiesta)</li>
</ul>

</div>

<div class="sidebar_content" id="sb-caching">
			<h3>11. Caching</h3>
			<p><?php echo CMS; ?> utilizza la cache in 4 differenti modalit&agrave;:</p>


			<h3>Cache dei tipi di contenuto</h3>
			<p>Gli schemi XML dei tipi di contenuto vengono cacheati per garantire un rapido accesso alle definizioni senza il bisogno di effettuare il parsing degli schemi ad ogni richiesta. Per svuotare la cache, &egrave; sufficiente navigare attraverso il menu di amministrazione alla voce <strong>"Gestione" >> "Svuota cache"</strong>. La cache viene svuotata in automatico nel caso venga utilizzata la funzionalit&agrave; di "Modifica schema" dalla vista dei tipi di contenuto, anzich&egrave; intervenire direttamente dal file-system.</p>
			<br />

			<h3>Cache degli alberi di menu</h3>
			<p>Tutti gli alberi di menu estratti, vengono <strong>automaticamente cacheati su file-system</strong> in base al loro <strong>tipo</strong> di contenuto. Verranno cacheati <strong>due files</strong> per tipo: uno per l'albero di stage ed uno per quello di produzione. La cache degli alberi viene automaticamente svuotata quando l'albero viene modificato (ad esempio una pagina cambia indirizzo).</p>
			<br />

			<h3>Cache delle pagine</h3>
			<p>I tipi di contenuto con struttura a pagine, normalmente presentano un campo xml chiamato <strong>page_cache</strong> nella scheda <strong>"Aspetto e azioni"</strong> che rappresenta (nella maschera di inserimento/modifica di una pagina) un campo di input numerico relativo al <strong>numero di minuti</strong> da utilizzare per tenere tale pagina in cache. Per non cacheare la pagina, &egrave; sufficiente impostare tale numero a "0" (zero). La cache di una pagina viene automaticamente svuotata quando la pagina viene modificata, pubblicata o depubblicata.<br /><strong>ATTENZIONE</strong>: la cache delle pagine &egrave; una funzionalit&agrave; estremamente <strong>potente</strong> ma <strong>pericolosa</strong>. Verr&agrave; infatti salvato l'interno rendering finale della pagina e servito all'utente come fosse una <strong>pagina statica</strong> (<em>senza passare dal framework</em>). Utilizzarlo quindi con la dovuta cautela, dato che le uniche variazioni nelle diverse richieste potranno essere solamente modifiche client-side via Javascript.</p>
			<br />

			<h3>Cache dei preset (immagini)</h3>
			<p>Le immagini generate attraverso i preset, vengono automaticamente cacheate sul filesystem in base al preset utilizzato, uno per file. La directory impostata come default per i preset &egrave; <strong>/attach/cache</strong>. Per svuotare la cache dei preset &egrave; sufficiente eliminare tale directory.

</div>

<div class="sidebar_content" id="sb-feeds">
			<h3>12. Feed dei contenuti</h3>
			<p>
Le pagine configurate come <strong>lista di contenuti</strong> presentano due utili esportazioni predefinite: una <strong>xml rss 2.0</strong> ed una di tipo <strong>json</strong>.
			</p>
			<br />
			<h3>feed.xml</h3>
			<p>Aggiungendo al path di una pagina di tipo lista di contenuti il parametro <strong>/feed.xml</strong>, verr&agrave; visualizzato il relativo feed RSS 2.0. Ad esempio:</p>
<pre class="prettyprint"><code>//Pagina lista di contenuti:
http://localhost/lista-articoli

//Relativo feed rss:
http://localhost/lista-articoli/feed.xml</code></pre><br />
<p>Per il popolamento dei campi, vengono usati i parametri title, contenuto e date_update/date_insert dei contenuti. &Egrave; possibile variare tale comportamento agendo nel dispatcher di default del sito (<strong>controllers/libraries/bancha/dispatchers/dispatcher_default.php)</strong>)</p>

<div class="message info">Puoi disattivare la funzionalit&agrave; di esportazione feed di una pagina intervenendo sulla voce "Esportazione feed" presente nella maschera "Aspetto e azioni" di tale pagina. Il campo &egrave; definito nello schema xml dei tipi di contenuto ad albero ed &egrave; chiamato "action_list_has_feed".</div>
<br />
<h3>feed.json</h3>
			<p>In maniera simile a quanto avviene per il feed rss, aggiungendo al path di una pagina di tipo lista di contenuti il parametro <strong>/feed.json</strong>, verr&agrave; visualizzata la relativa esportazione dati in formato JSON. Ad esempio:</p>
<pre class="prettyprint"><code>//Pagina lista di contenuti:
http://localhost/lista-articoli/ultimi-post

//Esportazione dati in formato JSON:
http://localhost/lista-articoli/ultimi-post/feed.json</code></pre><br />

</div>

<div class="sidebar_content" id="sb-triggers">
			<h3>13. Triggers (attivatori)</h3>
			<p>
<?php echo CMS; ?> ti mette a disposizione una serie di funzionalit&agrave; per permettere ai tuoi contenuti di "scatenare" eventi personalizzati in pi&ugrave; occasioni, tra le quali al loro inserimento, aggiornamento, eliminazione, pubblicazione e depubblicazione.
			</p>
			<p>I trigger possono essere chiamati ad esempio per aggiornare un campo di un altro tipo di contenuto. Un tipo di contenuto "Commento", potrebbe ad esempio chiamare il contenuto "Blog" ed aggiornare il campo "numero_commenti" di un suo post con il numero dei commenti figli di quel post.</p>
			<br />
			<h3>Definizione di un trigger</h3>
			<p>Attraverso lo schema di un tipo di contenuto, puoi definire dei trigger come segue:</p>
<pre class="prettyprint"><code>&lt;triggers&gt;
	&lt;trigger on="insert, update, delete" field="id_parent"&gt;
		&lt;sql action="recount" type="Menu" target="child_count" /&gt;
	&lt;/trigger&gt;
	&lt;trigger on="insert"&gt;
		&lt;call action="demotrigger" /&gt;
	&lt;/trigger&gt;
&lt;/triggers&gt;</code></pre><br />
<p>Qui sopra sono stati definiti due trigger:</p>
<ul>
	<li>Il primo trigger, esegue una query sql all'inserimento, aggiornamento ed eliminazione di un record di questo tipo di contenuto. L'azione, sar&agrave; "recount", ovvero conter&agrave; i contenuti del tipo appartenente che hanno come campo "id_parent" il valore inserito. Dopodich&egrave; effettuer&agrave; la query di aggiornamento utilizzando come target la colonna "child_count" del tipo di contenuto "Menu".</li>
	<li>Il secondo trigger, all'inserimento di un record chiamer&agrave; il metodo <strong>demotrigger()</strong> definito nel file relativo ai triggers. Verr&agrave; inoltre passato come primo parametro della funzione, il record oggetto della chiamata.</li>
</ul>
<br />
<h3>Attivatori SQL</h3>
<p>I trigger di tipo <strong>"sql"</strong>, accettano i seguenti attributi nel nodo "trigger":</p>
<ul>
	<li><strong>on</strong> - bind della chiamata (insert, update, delete, ...)</li>
	<li><strong>field</strong> - il campo (e valore) da utilizzare per la query del trigger</li>
</ul>
<p>Ed all'interno, contengono un nodo "sql" con tali attributi:</p>
<ul>
	<li><strong>action</strong> - azione da effettuare. Attualmente i valori disponibili sono <strong>update</strong> e <strong>recount</strong>. Per tenere traccia della cardinalit&agrave; di una relazione 1-n &egrave; consigliabile utilizzare come valore "recount".</li>
	<li><strong>type</strong> - il nome del tipo di contenuto "target" per questa azione</li>
	<li><strong>target</strong> - il nome del campo "target" del tipo di contenuto "target"</li>
	<li><strong>value</strong> - (opzionale - <strong>string|int</strong>) immette l'eventuale valore da utilizzare nella query di update</li>
	<li><strong>escape</strong> - (opzionale - <strong>bool</strong>, default "true") definisce se usare il carattere di escape per il valore del record in questione</li>
</ul>
<p>Esempio:</p>
<pre class="prettyprint"><code>&lt;trigger on="insert" field="id_parent"&gt;
	&lt;sql action="update" type="Blog" target="num_commenti" value="num_commenti+1" escape="false"/&gt;
&lt;/trigger&gt;</code></pre>
<br />
<p>Per le <strong>relazioni 1-n</strong>, ad esempio tra i post di un blog ed i suoi commenti, aggiungere un trigger così definito nello schema xml dei commenti:</p>
<pre class="prettyprint"><code>&lt;trigger on="insert, update, delete" field="post_id"&gt;
	&lt;sql action="recount" type="Blog" target="child_count" /&gt;
&lt;/trigger&gt;</code></pre><br />
<h3>Attivatori con chiamata esterna</h3>
<p>I trigger di tipo <strong>"call"</strong>, accettano i seguenti attributi nel nodo "trigger":</p>
<ul>
	<li><strong>on</strong> - bind della chiamata (insert, update, delete, ...)</li>
</ul>
<p>Ed all'interno, contengono un nodo "call" con tali attributi:</p>
<ul>
	<li><strong>action</strong> - nome del metodo da chiamare. Il metodo deve essere stato definito nel file <strong><?php echo $this->config->item('custom_controllers_folder'); ?>triggers.php</strong> e riceverà come primo parametro il record che esegue la chiamata.</li>
</ul>
<p>Esempio:</p>
<pre class="prettyprint"><code>&lt;trigger on="insert"&gt;
	&lt;call action="publish_on_twitter" /&gt;
&lt;/trigger&gt;</code></pre>
</div>

<div class="sidebar_content" id="sb-image-presets">
			<h3>14. Image presets</h3>
			<p><?php echo CMS; ?> include un sofisticato sistema per la gestione delle operazioni sulle immagini organizzato in <strong>presets</strong>.</p>
			<p>Un preset, &egrave; una lista di operazioni che vengono eseguite su una immagine. Dopo queste operazioni, il file viene scritto su disco e utilizzato
			per le successive richieste, anzich&egrave; effettuare nuovamente tutte le operazioni definite sul preset.</p>
			<p>Questo sistema, ti permette di definire operazioni di lavorazione su immagini anche dopo averle caricate dall'amministrazione.<br />
			I preset utilizzabili, vengono definiti nel file <strong>application/config/image_presets.php</strong> e consistono in un array di operazioni ciascuno.<br />
			Vediamo un preset di esempio:</p>
<pre class="prettyprint"><code>$config['presets']['<strong>ridimensiona</strong>'] = array(
	array(
		'operation' => 'resize',
		'size' => '640x?',
		'ratio' => TRUE,
		'quality' => 70
	)
);</code></pre><br />
<p>Il preset sopra definito, effettuer&agrave; una operazione di resize di una immagine mantenendone le proporzioni e portando la larghezza a 640px. Infine, verr&agrave; scritta
su disco comprimendo il file ad una qualit&agrave; di 70 su 100.</p>
<p>Ora, ammettiamo di avere un file caricato su un tipo di contenuto che abbia il seguente URL (riferendoci al file originale):</p>
<pre class="prettyprint"><code>http://localhost/attach/blog/images/2/my_image.jpg</code></pre><br />
<p>Per utilizzare il preset definito poco sopra, dovremo aggiungere al path del file la directory "cache" ed il nome del preset da applicare in questo modo:</p>
<pre class="prettyprint"><code>http://localhost/attach/<strong><u>cache</u></strong>/blog/images/2/<strong><u>ridimensiona</u></strong>/my_image.jpg</code></pre>
<br />
<p>Per costruire in automatico il path di un preset partendo dall'indirizzo originale del file, utilizza il seguente helper:</p>
<h3>preset_url($image_path, $preset, $append_siteurl)</h3>
			<p>Ritorna il path del preset di una immagine, dato il suo percorso relativo e il preset scelto.<br />
Come terzo parametro booleano, viene scelto se prependere il path del sito (<strong>site_url</strong>) e di default &egrave; impostato a TRUE.</p>
<pre class="prettyprint"><code>echo preset_url('attach/blog/images/1/file.jpg', 'user_profile');
// <?php echo preset_url('attach/blog/images/1/file.jpg', 'user_profile'); ?></code></pre><br />
<div class="message info">La cache delle immagini viene salvata con questa pattern:<br />
attach/cache/&lt;nome_tipo_di_contenuto&gt;/&lt;nome_campo_images&gt;/&lt;id_record&gt;/&lt;preset&gt;/&lt;filename&gt;</div>
<br />
<h3>Preset con pi&ugrave; operazioni</h3>
<p>Un preset pu&ograve; avere definite anche pi&ugrave; operazioni, che verranno effettuate in successione, come in questo caso:</p>
<pre class="prettyprint"><code>$config['presets']['<strong>user_profile</strong>'] = array(
	array(
		'operation' => 'resize',
		'size' => '150x150',
		'fixed' => TRUE,
		'quality' => 100,
		'ratio' => TRUE
	),
	array(
		'operation' => 'crop',
		'size' => '125x125',
		'quality' => 80,
		'x' => 25,
		'y' => 25
	)
);</code></pre><br />
<p>Il preset qui sopra, ridimensiona una immagine ad una grandezza fissa e poi effettua un ritaglio di grandezza 125x125 px.<br />
Simile a poco prima, l'indirizzo della nostra immagine con il preset <strong>user_profile</strong> differir&agrave; dal precedente esempio solo per il nome del preset applicato:</p>
<pre class="prettyprint"><code>http://localhost/attach/<strong>cache</strong>/blog/images/2/<strong><u>user_profile</u></strong>/my_image.jpg</code></pre>
<br />
<h3>Svuotare la cache</h3>
<div class="message warning">Puoi facilmente svuotare la cache delle immagini eliminando la cartella relativa al campo, oppure al tipo di contenuto da azzerare. In alternativa, sentiti libero di eliminare l'intera cartella attach/cache/ dal filesystem.</div>
<br />
<h3>Operazioni definibili</h3>
<p>Una singola operazione pu&ograve; essere dei seguenti tipi:</p>
<ul>
	<li><strong>1. resize</strong> - per ridimensionare immagini</li>
	<li><strong>2. crop</strong> - per ritagliare immagini</li>
</ul>
<br />
<h3>1. Resize (Ridimensionamento)</h3>
<p>I parametri definibili sono:</p>
<ul>
	<li><strong>size</strong> - (string) per specificare la dimensione del ridimensionamento (es. 150x150, 400x?, ?x320)</li>
	<li><strong>fixed</strong> - (bool) per specificare se le dimensioni devono sempre essere uguali o maggiori di quelle impostate</li>
	<li><strong>quality</strong> - (int 0-100) per specificare la qualit&agrave; con cui salvare la nuova immagine generata</li>
	<li><strong>ratio</strong> - (bool) per definire se le proporzioni devono essere rispettate</li>
</ul>
<br />
<h3>2. Crop (Ritaglio)</h3>
<p>I parametri definibili sono:</p>
<ul>
	<li><strong>size</strong> - (string) per specificare la dimensione del ritaglio (es. 150x150, 400x320)</li>
	<li><strong>quality</strong> - (int 0-100) per specificare la qualit&agrave; con cui salvare la nuova immagine generata</li>
	<li><strong>x</strong> - (bool) per definire l'origine del ritaglio lungo l'asse delle x</li>
	<li><strong>y</strong> - (bool) per definire l'origine del ritaglio lungo l'asse delle y</li>
</ul>
</div>

<div class="sidebar_content" id="sb-categories">
			<h3>15. Categorie e Gerarchie</h3>
			<p>Bancha offre principalmente due metodi di differenziazione per i record che fanno parte dello stesso tipo di contenuti: <strong>Categorie</strong> e <strong>Gerarchie</strong>.<br />
			Le categorie appartengono ad uno specifico tipo di conenuto, mentre le gerarchie sono globali e condivise da tutti i tipi di contenuto.</p>

			<div class="message warning">Per abilitare categorie e gerarchie su un tipo di contenuto, è necessario impostare rispettivamente i suoi nodi <u>has_categories</u> e <u>has_hierarchies</u> a <strong>"true"</strong> dal suo schema XML.</div><br />

			<h3>Categorie</h3>
			<p>Ogni tipo di contenuto, pu&ograve; definire infinite categorie a cui i suoi record potranno appartenere.<br />
			Per accedere alla gestione delle categorie di un tipo di contenuto, premi sul link <strong>Gestione categorie</strong> dalla vista dei record di un singolo tipo di contenuto.</p>
			<p>Una volta inserite le categorie, esse possono essere associate ad uno o più record:<br />
			Dalla maschera di modifica di un record, premi sulla sezione <strong>Categorie</strong> sulla sinistra, e successivamente imposta le categorie del record.</p>
			<p>Infine, recati nella maschera di modifica di una pagina avente come azione <strong>Lista di contenuti</strong>, e troverai tra i campi disponibili il campo testuale <strong>Categoria</strong>. In tale campo, puoi inserire tutte le categorie che desideri separate da una virgola ed uno spazio (<strong>, </strong>) in questo modo:</p>
			<pre class="prettyprint"><code>Web, Photo, Illustrations</code></pre><br />

			<h3>Gerarchie</h3>
			<p>A differenza delle categorie, le gerarchie sono definibili globalmente all'interno di <?php echo CMS; ?> e quindi sono utilizzabili da tutti i tipi di contenuti indistintamente. Per iniziare, recati nella pagina <strong>Gestione &gt; Gerarchie</strong>. Qui, potrai inserire tutte le gerarchie che vorrai e, a differenza delle categorie, organizzarle <strong>gerarchicamente</strong>. Ogni gerarchia quindi, pu&ograve; essere "figlia" di un'altra gerarchia.</p>
			<p>In maniera simile a quanto avviene per le categorie, in ogni record sulla parte sinistra troverai la sezione <strong>Gerarchie</strong> che ti consentir&agrave; di associare il record a tutte le gerarchie che desideri.</p>

			<p>Infine, recati nella maschera di modifica di una pagina avente come azione <strong>Lista di contenuti</strong>, e troverai tra i campi disponibili un campo relativo alle gerarchie: potrai quindi selezionare tutte le gerarchie da estrarre durante la lista di contenuti di tale pagina.</p>
			<p>Ecco un esempio di come puoi organizzare le gerarchie del tuo sito internet:</p>
<pre class="prettyprint"><code>- Prodotti
	- Collezione 2011
		- Magliette
			- Mezza manica
			- Maniche lunghe
		- Pantaloni
			- Jeans
	- Collezione 2010
		...
</code></pre>
</div>

	<div class="sidebar_content" id="sb-dispatchers">
			<h3>16. Dispatchers</h3>
			<p>Come forse avrai visto dallo schema MVC presente nella sezione <strong>1. Introduzione a BANCHA</strong>, i dispatchers sono collocati appena sotto la fase di routing dell'applicazione.</p>
			<p>Un dispatcher, è una classe responsabile di un percorso di routing del <strong>front-end</strong> del sito internet.</p>
			<p>Attualmente sono disponibili due dispatchers:</p>
			<ul>
				<li><strong>Default Dispatcher</strong> - il dispatcher generale del front-end.</li>
				<li><strong>Image Dispatchers</strong> - responsabile di generazione e invio delle immagini con i preset.</li>
			</ul>

			<p>Per aggiungere un dispatcher, aggiungilo alla cartella <strong>application/libraries/<?php echo FRNAME; ?>/dispatchers/</strong> chiamandolo <strong>dispatcher_&lt;nome&gt;.php</strong> ed utilizzando come nome della classe <strong>Dispatcher_&lt;nome&gt;</strong>. Dopodich&egrave;, potrai invocare un dispatcher in questo modo:</p>
<pre class="prettyprint"><code>$this->load->dispatcher('nome');

//Ed otterrai l'oggetto:
//$this->dispatcher</code></pre>
<br />
			<h3>Default dispatcher</h3>
			<p>&Egrave; il dispatcher generale del sito internet, ed una volta impostato l'<strong>url da visitare</strong> si preoccupa di ricercare tra le pagine ed i records del database, nonch&egrave; preparare tutto il necessario alla visualizzazione del sito, comprese le liste di contenuti ed i feed del sito.</p>
			<p>Per modificare il comportamento generale del sito, puoi quindi agire su questo dispatcher.<br />
			Solitamente viene chiamato dal controller <strong>Website</strong> all'azione <strong>router</strong>.</p>

<pre class="prettyprint"><code>//Il dispatcher di default, viene invocato ed usato cos&igrave;:
$this->load->dispatcher('default');
$this->dispatcher->start();</code></pre><br />

			<h3>Image dispatcher</h3>
			<p>Questo dispatcher si preoccupa di generare le immagini attraverso i <strong>preset</strong>, salvarle e quindi inviarle al browser. Nel caso in cui una immagine sia gi&agrave; stata generata, questo dispatcher non verr&agrave; invocato in quanto l'immagine sar&agrave; fornita direttamente dal filesystem.</p>
			<p>Di seguito l'esempio di come viene invocato il dispatcher dal controller del front-end del sito internet:</p>
<pre class="prettyprint"><code>$this->load->dispatcher('images');

//Imposto i dati relativi all'immagine da generare
$data = array(
	'type'		=> $type,
	'field'		=> $field,
	'id'		=> $id,
	'preset'	=> $preset,
	'filename'	=> $filename,
	'ext'		=> $extension
);

//Lancio il dispatcher
$this->dispatcher->retrieve($data);</code></pre><br />

<p>Per altre informazioni sui preset applicati alle immagini, leggi la relativa sezione <strong>14. Image presets</strong> nella documentazione.</p>
	</div>

	<div class="sidebar_content" id="sb-helpers">
			<h3>17. Helpers</h3>
			<p>Globalmente nell'applicazione sono disponibili diversi helpers, descritti qui di seguito.</p>

			<h3>site_url($path_to_append)</h3>
			<p>Ritorna il path pubblico del sito, aggiungendone il path passato come parametro (opzionale).</p>
<pre class="prettyprint"><code>echo site_url('hello/world');
// <?php echo site_url('hello/world'); ?></code></pre><br /><br />

			<h3>theme_url($path_to_append)</h3>
			<p>Ritorna il path pubblico del tema attualmente in uso, aggiungendone il path passato come parametro (opzionale).</p>
<pre class="prettyprint"><code>echo theme_url('css/style.css');
// <?php echo theme_url('css/style.css'); ?></code></pre><br /><br />

			<h3>attach_url($file_path)</h3>
			<p>Ritorna il path pubblico di un allegato.</p>
<pre class="prettyprint"><code>echo attach_url('path/to/file.ext');
// <?php echo attach_url('path/to/file.ext'); ?></code></pre><br /><br />

			<h3>preset_url($image_path, $preset, $append_siteurl)</h3>
			<p>Ritorna il path del preset di una immagine, dato il suo percorso relativo e il preset scelto.<br />
Come terzo parametro booleano, viene scelto se prependere il path del sito (<strong>site_url</strong>) e di default &egrave; impostato a TRUE.</p>
<pre class="prettyprint"><code>echo preset_url('attach/blog/images/1/file.jpg', 'user_profile');
// <?php echo preset_url('attach/blog/images/1/file.jpg', 'user_profile'); ?></code></pre><br /><br />

			<h3>getter($url)</h3>
			<p>Effettua una chiamata tramite <strong>cURL</strong> ad un webservice esterno e ritorna il responso.</p>
<pre class="prettyprint"><code>$result = getter('http://getbancha.com/');</code></pre><br /><br />

			<h3>breadcrumbs($array)</h3>
			<p>Stampa le breadcrumbs, dato un array. Necessita di caricare l'helper <strong>breadcrumbs</strong> prima dell'utilizzo.</p>
<pre class="prettyprint"><code>$this->load->helper('breadcrumbs');
echo breadcrumbs($this->tree->breadcrumbs);</code></pre><br /><br />

			<h3>menu($tree, $max_depth, $current_level, $show_in_menu)</h3>
			<p>Stampa la struttura di un albero di menu. Il secondo parametro decide il massimo livello di iterazione, ed il terzo parametro il livello corrente di iterazione.</p>
<pre class="prettyprint"><code>echo menu($this->tree->get_default());</code></pre><br />

	</div>

	<div class="sidebar_content" id="sb-modules">
			<h3>18. Moduli</h3>
			<p>
			<div class="message warning">TODO</div>
			</p>
	</div>

	<div class="sidebar_content" id="sb-settings">
			<h3>19. Impostazioni</h3>
			<p>Attraverso il menu <strong>Gestione >> Impostazioni</strong> puoi trovare diversi settaggi da applicare al tuo sito internet.</p>
			<p>Tali settaggi possono essere ampliati intervenendo sullo schema del file <strong><?php echo $this->config->item('xml_folder'); ?>Settings.xml</strong> e la modalit&agrave; di definizione &egrave; identica a quella di definizione degli schemi dei <strong>tipi di contenuto</strong>. L'unica eccezione &egrave; la mancanza di poter definire i campi files, images e hierarchies.</p>
			<p>Puoi accedere alle impostazioni (settings) da qualsiasi punto dell'MVC di <?php echo CMS; ?> in questo modo:</p>
<pre class="prettyprint"><code>//Carico la classe settings
$this->load->settings();

//Leggo un valore dalle impostazioni
echo $this->settings->get('website_name');</code></pre><br />
	<p>La funzione <strong>get()</strong> accetta come secondo parametro opzionale il nome del modulo da cui "prelevare" la chiave. Il sito internet usa come chiave predefinita <strong>general</strong>, ma nulla vieta di utilizzare altre chiavi.</p>
	<p>I moduli esterni al core dell'applicazione, possono utilizzare la classe settings per salvare le loro impostazioni utilizzando il proprio nome come modulo e cos&igrave; evitare conflitti con le variabili interne dell'applicazione.</p>

	<div class="message info">NOTA: Lato front-end, la classe settings viene gi&agrave; caricata in automatico dal sistema.</div><br />

	<h3>get($key, $module)</h3>
	<p>Legge una variabile dalle impostazioni, utilizzando il modulo richiesto.</p>
<pre class="prettyprint"><code>//Leggo un valore dalle impostazioni del modulo 'general'
echo $this->settings->get('website_name');

//Ed ora, dal modulo di twitter
echo $this->settings->get('username', 'twitter');</code></pre><br />

<h3>set($key, $value, $module)</h3>
	<p>Scrive un valore nelle impostazioni, abbinandolo al modulo impostato come terzo parametro.</p>
<pre class="prettyprint"><code>//Scrivo un valore nel modulo 'general'
echo $this->settings->set('website_name', 'My cool website');

//Ed ora, aggiorno un valore nel modulo di twitter
echo $this->settings->set('username', '@squallstar', twitter');

//Svuoto la cache delle impostazioni
$this->settings->clear_cache();</code></pre><br />

<h3>delete($key, $module)</h3>
	<p>Elimina una variabile dalle impostazioni.</p>
<pre class="prettyprint"><code>//Elimino una variabile dal modulo di twitter
echo $this->settings->delete('username', 'twitter');</code></pre><br />

<h3>clear_cache()</h3>
<p>Nonostante vengano salvate come record fisici su database, le impostazioni vengono cacheate attraverso un file su filesystem. Una volta aggiornate delle chiavi, &egrave; necessario svuotare la cache con questo metodo.

<h3>build_cache();</h3>
<p>Funzione usata internamente dal sistema quando il file di cache non &egrave; presente, provvede a rigenerarlo.</p>

	</div>

<!-- end -->

	</div>

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>

<link href="<?php echo site_url(THEMESPATH); ?>/admin/css/prettify.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo site_url(THEMESPATH); ?>/admin/js/prettify.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	prettyPrint();
});
</script>