<?php
/**
 * Default website home template
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

render('header'); ?>

<h1>This is the homepage of your website.</h1>

<p>This script is located in <strong>themes/&lt;themename&gt;/views/templates/home.php</strong></p>

<?php
if (type('Blog')) {

	$posts = find('Blog')->limit(10)->get(); ?>

	<h2>Some posts</h2>

	<?php
	foreach ($posts as $post)
	{
		?><a href="<?php echo semantic_url($post); ?>"><?php echo $post->get('title'); ?></a><?php
	}

}

render('footer');