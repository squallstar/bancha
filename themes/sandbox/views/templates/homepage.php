<?php
/**
 * Sandbox theme, homepage template
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2014, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

defined('BANCHA') or exit;

render('header'); ?>

<h1>This is the homepage of your website.</h1>

<!-- Please remove the following lines. They are just an example -->
<p>This script is located here: <strong><?php echo theme_url('views/templates/homepage.php'); ?></strong></p>
<p>Note that this is also a Record of type <strong>Menu</strong> marked as <strong>website homepage</strong> on the settings.</p>

<?php

//The example below shows you how to manually extract records using Bancha's ORM
if (type('Blog')) {

	$posts = find('Blog')->limit(10)->order_by('date_publish', 'DESC')->get(); ?>

	<h2>Some posts</h2>

	<ul>
	<?php
	foreach ($posts as $post)
	{
		?><li><a href="<?php echo semantic_url($post); ?>"><?php echo $post->get('title'); ?></a></li>
		<?php
	}
	?>
	</ul>

<?php
}

render('footer');