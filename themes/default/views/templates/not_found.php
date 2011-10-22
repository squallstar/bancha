<?php
/**
 * 404 Page not found template
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->view->render('header');

?>
<div class="grid_12">
	<h1 class="page_title">Page not found</h1>
	<div class="hr dotted clearfix">&nbsp;</div>
	<div class="body">
	<?php echo _("We can't find the page you're looking for. Check out our website for help, or maybe you should try heading to home."); ?>
	<br /><br />
	</div>
</div>
<div class="grid_12 clearfix">&nbsp;</div>

<?php
$this->view->render('footer');