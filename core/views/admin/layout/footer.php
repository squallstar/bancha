<?php
/**
 * Admin footer view
 *
 * Footer per l'amministrazione
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

?><div id="footer">
	<p class="right"><?php

		foreach ($this->lang->languages as $language => $val)
		{
			echo '<a href="'.admin_url('dashboard/welcome/'.$language).'">'.$val['description'].'</a> - ';
		}

		echo CMS;
		?> CMS - v<?php echo BANCHA_VERSION; ?></p>
	<p class="left"><a href="#">Squallstar Studio</a> &copy;2011 - <?php echo $this->lang->_trans('Page rendered in %n seconds using %m of memory.', array('n'=>'{elapsed_time}', 'm' => '{memory_usage}'));?></p>
</div>