<?php
/**
 * Wordpress Themes Adapter - Layout dispatcher
 *
 * 1) Copy the WP theme under Bancha themes/ directory
 * 2) Move all the PHP files in a new folder called "views"
 * 3) Copy the content of this file in a new file named "layout.php" into the folder "views" that you just created
 * 4) Enjoy!
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

$this->load->adapter('wordpress_themes', 'wp');