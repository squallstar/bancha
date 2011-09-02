<?php
/**
 * 404 Page not found template
 *
 * Template per l'errore 404 - Pagina non trovata
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->view->render('header');

?>

<div class="post">
	<div class="details"><h1>404 - Pagina non trovata</h1></div>
	<div class="body">
	La pagina che stavi cercando non esiste oppure &egrave; stata rimossa
	</div>

	<div class="clear"></div>
</div>

<?php
$this->view->render('footer');