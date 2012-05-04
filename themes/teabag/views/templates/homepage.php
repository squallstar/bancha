<?php
/**
 * Sandbox theme, homepage template
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

defined('BANCHA') or exit;

render('header'); ?>

<div class="container">
	<div class="row clearfix">
		<div class="grid_12">
<h1>Welcome! This is the homepage of your website.</h1>
<hr />
		</div>
	</div>
</div>


<?php

//The example below shows you how to manually extract records using Bancha's ORM
if (type('Blog')) {

	$posts = find('Blog')->limit(10)->order_by('date_publish', 'DESC')->get(); ?>

	<div class="container">
		<div class="row clearfix">
			<div class="grid_12 margin_bottom_30">
				<h2>Latest blog posts</h2>
				<ul>
				<?php
				foreach ($posts as $post)
				{
					?>
					<li>
						<h3><?php echo $post->get('title'); ?></h3>
						<a href="<?php echo semantic_url($post); ?>">View detail &rarr;</a>
					</li>
					<?php
				}
				?>
				</ul>
			</div>
		</div>
	</div>

<?php
}

render('footer');