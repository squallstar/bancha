<?php
/**
 * 404 Page not found template
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

render('header');

?>

<h1>Page not found</h1>

<p>The page does not exists.</p>

<?php
content_render();

render('footer');