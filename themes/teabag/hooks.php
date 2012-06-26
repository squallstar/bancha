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
 * You can use this hook to perform operations at the end of the script
 */
function hook_ondestruct() {
	//echo 'Hello world';
}

/**
 * Administration logo override
 * @return string
 */
function hook_admin_logo() {
	//return '<img src="' . theme_url('img/mylogo.png') . '" />';
}

/**
 * Administration html, called just before </head>
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

/**
 * Administration html, called just before </body>
 * @return string
 */
function hook_admin_html_body_closure() {
	//return '<script type="text/javascript" src="..."></script>';
}