<?php
/**
 * Default website home template
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

render('header'); ?>

<h1>This is the homepage of your website.</h1>

<p>This script is located here: <strong>themes/<?php echo $this->view->theme; ?>/views/templates/homepage.php</strong></p>
<p>Note that this is also a Record of type <strong>Menu</strong> marked as <strong>website homepage</strong> on the settings.</p>

<?php
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