<?php
/**
 * Admin footer view
 *
 * Footer per l'amministrazione
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

//$this->output->enable_profiler();

?><div id="footer">
	<p class="right">Milk Content Management System - v<?php echo MILK_VERSION; ?></p>
	<p class="left"><a href="#">Squallstar Studio</a> &copy;2011 - <?php echo $this->lang->_trans('Page rendered in %n seconds using %m of memory.', array('n'=>'{elapsed_time}', 'm' => '{memory_usage}'));?></p>
</div>