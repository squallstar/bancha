<?php
/**
 * Bancha configuration
 *
 * Configurazione generica del framework
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* LANGUAGES
 * Configurazione delle lingue utilizzabili
 */
$config['languages'] = array(
	'it' => array(
		'name'			=> 'italian',
		'locale'		=> 'it_IT',
		'description'	=> 'Italiano',
		'date_format'	=> 'd/m/Y'
	),
	'en' => array(
		'name'			=> 'english',
		'locale'		=> 'en_US',
		'description'	=> 'English',
		'date_format'	=> 'Y-m-d'
	)
);

/*
 * BANCHA CMS VERSION
 * Versione del framework
 */
define('BANCHA_VERSION', '0.6.6');

/*
 * NOME DEL CMS
 */
define('CMS', 'BANCHA');

/*
 * WEBSITE THEMES
 * Temi da utilizzare. La prima si riferisce alla versione desktop, la seconda alla versione mobile (se presente)
 * I nomi sono relativi alle directory presenti in themes/
 */
$config['website_themes'] = array(
	'desktop'	=> 'desktop',
	'mobile'	=> 'desktop'
);

/*
* RECORDS PER PAGE
* Il numero di record per pagina estratti in amministrazione nelle aree dei records.
*/
$config['records_per_page'] = 10;

/* FRAMEWORK PATH
 * Costante di definizione del path delle librerie di Bancha
 */
define('FRPATH', APPPATH . 'libraries' . DIRECTORY_SEPARATOR . FRNAME . DIRECTORY_SEPARATOR);

/*
 * XML FOLDER
 * Directory che conterrà gli XML relativi ai tipi di contenuto
 */
$config['xml_folder'] = APPPATH . 'xml' . DIRECTORY_SEPARATOR;

/*
 * MODULES FOLDER
 */
$config['modules_folder'] = APPPATH . 'modules' . DIRECTORY_SEPARATOR;

/*
 * CACHE FOLDER
 */
$config['fr_cache_folder'] = APPPATH . 'cache' . DIRECTORY_SEPARATOR . '_' . FRNAME. DIRECTORY_SEPARATOR;

/*
 * TYPES CACHE FILE
 * Posizione del file che contiene la cache relativa ai tipi di contenuto
 */
$config['types_cache_folder'] = $config['fr_cache_folder'] . 'content.types';

/*
* TYPES CACHE FILE
* Posizione del file che contiene la cache relativa all'albero generale del sito
* La pseudovariabile {name} conterrà il nome del tipo cacheato
*/
$config['tree_cache_folder'] = $config['fr_cache_folder'] . '{name}.tree';

/*
 * TEMPLATES DIRECTORY
 * Directory che contiene i template di default di Bancha. Vengono usati come base per
 * quelli creati da zero.
 */
$config['templates_folder'] = FRPATH . 'templates' . DIRECTORY_SEPARATOR;

/*
* CUSTOM CONTROLLERS DIRECTORY
* Directory che contiene i controller personalizzati
*/
$config['custom_controllers_folder'] = APPPATH . 'controllers' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR;

/*
 * ATTACH MAIN DIRECTORY
 * Cartella che contiene gli allegati caricati
 */
$config['attach_folder'] = FCPATH . 'attach' . DIRECTORY_SEPARATOR;

/*
 * ATTACH MAIN DIRECTORY
 * Path pubblico per accedere alla cartella degli allegati qui sopra
 * (senza il path del sito)
 */
$config['attach_out_folder'] = 'attach/';

/*
 * STRIP WEBSITE URL
 * Se impostato a true, verra' rimosso l'indirizzo del sito da tutti i campi salvati nei records
 * e' utile per popolare il sito in sviluppo e portarlo in produzione senza che rimangono i vecchi url
 * nei campi (ad esempio nelle textarea)
 */
$config['strip_website_url'] = TRUE;

/*
 * FEED URI
 * Segmento dell'url da utilizzare per i feed
 */
$config['feed_uri'] = array('feed.xml', 'feed.json');

/*
 * DOCUMENTS SELECT FIELDS
 */
$config['documents_select_fields'] = array(
	'id_document', 'name', 'path', 'size', 'width', 'height', 'resized_path', 'thumb_path', 'alt_text',
	'bind_id', 'bind_table', 'bind_field', 'mime', 'priority'
);

/*
 * WEBSITE VIEWS FOLDER
 */
$config['website_views_folder'] = 'website/';

/*
 * VIEWS TEMPLATES FOLDER
 * Directory che contiene i template dei tipi di contenuti
 * (path relativo alla directory delle view)
 */
$config['views_templates_folder'] = $config['website_views_folder'] . 'type_templates/';

/*
 * DEFAULT VIEW TEMPLATE
 * Nome del template da utilizzare come default quando una pagina non lo specifica
 * o quando il file non viene trovato. Il file deve trovarsi nella directory
 * application/views/layout/templates/ e deve avere estensione .php
 */
$config['default_view_template'] = 'default';

/*
 * VIEW TEMPLATES TO COPY
 * Nomi dei template .php da copiare nelle views alla creazione di un tipo
 * Tali files devono trovarsi nella directory type_templates delle librerie di Bancha
 */
$config['view_templates_to_copy'] = array('detail', 'list');

/*
 * VIEWS ABSOLUTE TEMPLATES FOLDER
 * Directory assoluta che contiene i template dei tipi di contenuti
 */
$config['views_absolute_templates_folder'] = APPPATH . 'views' . DIRECTORY_SEPARATOR . $config['views_templates_folder'];

/*
 * RESTRICTED FIELD NAMES
 * Contiene i nomi NON utilizzabili come chiave dei campi negli xml
 * di definizione dei tipi di contenuto
 */
$config['restricted_field_names'] = array(
	'categories', 'xml'
);

/*
 * DEFAULT TREE TYPE
 * I tipi di contenuto da utilizzare per il menu principale del sito.
 * E' possibile utilizzarne più di uno per avere più contenuti come unico albero
 */
$config['default_tree_types'] = array('Menu');

/*
 * DELETE DEAD RECORDS
 * Imposta se eliminare i record quando perdono la referenza al tipo di contenuto.
 * (prevalentemente quando viene eliminato un tipo di contenuto).
 */
$config['delete_dead_records'] = FALSE;

/*
 * RECORD COLUMNS
 * Colonne fisiche presenti nella tabella records
 * Dopo aver fatto un ALTER-TABLE, aggiungere qui il nome della colonna e il campo relativo
 * verrà salvato sulla colonna anzichè nell'xml generico.
 *
 * Viene usato dal router quando estrae un contenuto come ultimo parametro dell'url
 */

$config['record_columns'] = array(
	'id_record',
	'id_type',
	'lang',
	'uri',
	'title',
	'date_update',
	'date_insert',
	'date_publish',
	'xml',
	'id_parent',
	'show_in_menu',
	'published',
	'priority'
);


/*
 * RECORD NOT LIVE COLUMNS
 * Indicare le colonne addizionali presenti solo nella tabella records_stage
 */
$config['record_not_live_columns'] = array(
	'published'
);

/*
 * PAGE EXTRACT COLUMNS
 * Indicare le colonne da estrarre nelle pagine per gli alberi di menu (tabella pages)
 */
$config['page_extract_columns'] = array(
	'id_record', 'uri', 'full_uri', 'title', 'id_parent', 'show_in_menu', 'priority'
);

/*
 * TREE LINEAR FIELDS
 * Campi che vengono estratti dalla tree per costruire l'albero dei record un tipo di contenuto
 * E' possibile aggiungere colonne fisiche della tabella records per ottenere più campi
 */
$config['tree_linear_fields'] = array('id_record', 'title', 'uri');

/*
 * RECORD SELECT (TREE) FIELDS
 * Campi addizionali che vengono estratti per i tipi di contenuto non semplici (tree)
 * Utilizzare solo colonne fisiche della tabella records
 */
$config['record_select_tree_fields'] = array('id_parent');

