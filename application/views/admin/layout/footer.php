<?php
/**
 * Admin footer view
 *
 * Footer per l'amministrazione
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

?><div id="footer">
	<p class="right"><a href="<?php echo admin_url('dashboard/welcome/it'); ?>">Italiano</a> - <a href="<?php echo admin_url('dashboard/welcome/en'); ?>">English</a> - <?php echo CMS; ?> CMS - v<?php echo BANCHA_VERSION; ?></p>
	<p class="left"><a href="#">Squallstar Studio</a> &copy;2011 - <?php echo $this->lang->_trans('Page rendered in %n seconds using %m of memory.', array('n'=>'{elapsed_time}', 'm' => '{memory_usage}'));?></p>
</div>