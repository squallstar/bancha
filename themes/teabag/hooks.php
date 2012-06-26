<?php
/**
 * Theme Hooks
 *
 * Please declare only functions that you actually use.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

/**
 * Called as soon as the view class is loaded
 */
function hook_onconstruct() {
	//echo 'Hello world';
}

/**
 * Called when the controller is destructed
 */
function hook_ondestruct() {
	//echo 'Hello world';
}

/**
 * Administration logo override
 * @return string
 */
function hook_admin_logo() {
	//return 'Hello world';
}

/**
 * Administration html head declarations
 * @return string
 */
function hook_admin_html_head() {
	//return '<link rel="stylesheet" href="..." type="text/css" />';
}
/**
 * Administration html body class
 * @return string
 */
function hook_admin_html_body_class() {
	//return 'some-class';
}