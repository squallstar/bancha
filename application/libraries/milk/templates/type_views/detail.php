<?php
/**
 * {name} Detail View
 *
 * Vista di dettaglio di tipo {name}
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 *
 */

if(!defined('BASEPATH')) exit('No direct script access allowed');

echo '<div class="details"><h1>'.$page->get('title').'</h1>'.
	 '<p class="info">'.menu($this->tree->get_current_branch()).'</p></div>'.
	 '<div class="body">';

if (isset($record) && $record instanceof Record) {
	?>

	<h1><?php echo $record->get('title'); ?></h1>

	<div class="text"><?php echo $record->get('contenuto'); ?></div>

	<?php
}

echo '</div><div class="clear"></div>';