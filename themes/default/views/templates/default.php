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
<div class="grid_8">
	<?php $this->view->render('content_render'); ?>
</div>

<div class="grid_4">
		
			<h4>And...</h4>
			<div class="hr dotted clearfix">&nbsp;</div>

			<?php 
			$branch = $this->tree->get_current_branch();
			if ($branch) {
				echo menu($branch);
				echo '<div class="hr dotted clearfix">&nbsp;</div>';
			}
			 ?>

			<?php echo $this->blocks->load('sidebar'); ?>

			<?php echo $this->blocks->load('sidebar2'); ?>

			<dl class="history"> 
				<dt>2010</dt> 
				<dd>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel diam id mauris accumsan egestas. Sed sed lorem. Integer id mi vel sapien fermentum vehicula. Pellentesque vitae lacus a sem posuere fringilla. Vestibulum dolor.</dd> 
			</dl>
		</div>

<div class="hr grid_12 clearfix">&nbsp;</div>
<?php
$this->view->render('footer');