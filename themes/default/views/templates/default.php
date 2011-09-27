<?php
/**
 * Default website (generic page) template
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
	<?php $this->view->render('content_render'); ?>
</div>
<div class="hr grid_12 clearfix">&nbsp;</div>
<?php
$this->view->render('footer');