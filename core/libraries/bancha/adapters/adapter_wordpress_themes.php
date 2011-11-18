<?php
/**
 * Wordpress Themes to Bancha, Adapter Class
 *
 * A library that enables Bancha support on WP themes
 * Porting is currently under development - only for internal use
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

$GLOBALS['B'] = & get_instance();

Class Adapter_wordpress_themes
{
	private $CI;

	public $post;
	public $posts;
	public $current_post = -1;
	public $post_count;
	public $in_the_loop = FALSE;
	public $max_num_pages;

	public function __construct()
	{
		$this->CI = & $GLOBALS['B'];
		$GLOBALS['DATA'] = array();

		$this->max_num_pages = $this->CI->config->item('records_per_page');

		$this->dispatch();
	}

	public function dispatch()
	{
		$GLOBALS['wp_query'] = $this;
		global $DATA;

		$page = $this->CI->view->get('page');

		if ($page->get('action') == 'list')
		{
			$this->posts = $page->get('records');
			$this->post_count = count($this->posts);
		}

		//Wp uses $post globally
		$this->post = $this->CI->view->get('record');
		if ($this->post)
		{
			$this->post->ID = & $this->post->id;
			$this->CI->view->set('post', $this->post);
			$DATA['post'] = $this->post;
		}

		$DATA['wp_query'] = $this;

		//Starts the render
		$this->CI->view->render('index', $DATA);
	}

	function the_post()
	{
		global $post;
		$this->in_the_loop = true;

		if ($this->current_post == -1 ) {
			$post = $this->next_post();
			//setup_postdata($post);
		}
	}

	function next_post() {
		$this->current_post++;
		$this->post = $this->posts[$this->current_post];
		return $this->post;
	}

	function have_posts()
	{
		if ($this->current_post + 1 < $this->post_count)
		{
			return true;
		} elseif ($this->current_post + 1 == $this->post_count && $this->post_count > 0)
		{
            $this->rewind_posts();
		}
		$this->in_the_loop = false;
		return false;
	}

	function rewind_posts()
	{
		$this->current_post = -1;
		if ($this->post_count > 0)
		{
			$this->post = $this->posts[0];
		}
	}
}

/* ================================ WP FUNCTIONS ================================ */

/**
 * Renders the header
 */
function get_header()
{
	$GLOBALS['B']->view->render('header', $GLOBALS['DATA']);
}

/**
 * Renders the footer
 */
function get_footer()
{
	$GLOBALS['B']->view->render('footer', $GLOBALS['DATA']);
}

/**
 * Renders the sidebar
 */
function get_sidebar()
{
	return '';
}

/**
 * Builds up a set of html attributes containing the text direction and language information for the page.
 * @param $doctype
 */
function language_attributes($doctype = '')
{
	echo 'lang="'.$GLOBALS['B']->lang->current_language.'"';
}

/**
 * Displays information about your blog
 * @param $show
 */
function bloginfo($show)
{
	echo get_bloginfo($show);
}

/**
 * Returns information about your site which can then be used elsewhere in your PHP code
 * @param $show
 * @param $filter
 */
function get_bloginfo($show, $filter = '')
{
	switch ($show)
	{
		case 'charset':
			return 'utf-8';break;
		case 'html_type':
			return 'text/html';break;
		case 'name':
			return $GLOBALS['B']->settings->get('website_title');break;
		case 'stylesheet_url':
			return theme_url('views/style.css');

		default:
			return '';
	}
}

/**
 * Displays or returns the title of the page.
 * @param $sep
 * @param $echo
 * @param $seplocation
 */
function wp_title($sep, $echo = FALSE, $seplocation = 'right')
{
	if ($echo)
	{
		echo $GLOBALS['B']->view->title;
	} else {
		return $GLOBALS['B']->view->title;
	}
}

/**
 * Checks whether a page is singular post or a list
 * @return bool
 */
function is_singular()
{
	global $B;
	if (count($GLOBALS['wp_query']->posts))
	{
		return TRUE;
	}/* else if ($page = $B->view->get('page'))
	{
		if ($page->get('action') != 'list')
		{
			return TRUE;
		}
	}*/
	return FALSE;
}

/**
 * A safe way of getting values for a named option from the options database table
 * TODO
 * @param $show
 * @param $default
 */
function get_option($show = '', $default = FALSE)
{
	return $default;
}

/**
 * A safe way of adding javascripts to a generated page.
 * @param $handle
 * @param $src
 * @param $deps
 * @param $ver
 * @param $in_footer
 */
function wp_enqueue_script($handle, $src = '', $deps = array(), $ver = '', $in_footer = FALSE)
{
	return '';
}

/**
 * Returns the theme url
 */
function get_template_directory_uri()
{
	return theme_url('views');
}

/**
 * Fire the 'wp_head' action.
 * TODO
 */
function wp_head()
{
	return '';
}

/**
 * Fire the 'wp_footer' action.
 * TODO
 */
function wp_footer()
{
	return '';
}

/**
 * This function gives the body element different classes.
 * TODO
 * @param $class
 */
function body_class($class = '')
{
	return 'class="home blog"';
}

/**
 * WP Navigator menu
 * @param $args
 */
function wp_nav_menu($args = array())
{
	echo '<div class="menu-page-menu-container">'.menu($GLOBALS['B']->view->get('tree'), 2).'</div>';
}

/**
 * The home_url template tag retrieves the home url for the current site
 * @param string $path
 * @param string $scheme
 */
function home_url($path = '', $scheme = '')
{
	return site_url($path, FALSE);
}

/**
 * Encodes < > & " ' (less than, greater than, ampersand, double quote, single quote)
 * @param $text
 */
function esc_attr($text)
{
	return str_replace(array('<', '>', '"', "'"), array('&lt;', '&gt;', '&quot;', '&quot;'), $text);
}

/**
 * Checks a theme's support for a given theme feature.
 * @param $feature
 */
function current_theme_supports($feature)
{
	switch ($feature)
	{
		case 'custom-header':
		case 'custom-background':
		case 'widgets':
			return FALSE;
	}
	return TRUE;
}

/**
 * Returns a boolean if a post has a Post Thumbnail attached (true) or not (false).
 * TODO
 * @param $post_id
 */
function has_post_thumbnail($post_id)
{
	return FALSE;
}

/**
 * Retrieve header image for custom header.
 * TODO
 */
function get_header_image()
{
	return '';
}

/**
 * Checks whether a submenu exists
 * TODO
 * @param $submenu
 */
function is_nav_menu($submenu = '')
{
	return '';
}

/**
 * This function checks to see if the current WordPress query has any results to loop over.
 * This is a boolean function, meaning it returns either TRUE or FALSE.
 */
function have_posts()
{
	return $GLOBALS['wp_query']->have_posts();
}

/**
 * Gets the next post
 */
function the_post()
{
	return $GLOBALS['wp_query']->the_post();
}

/**
 * Prints a link to the next set of posts within the current query.
 * TODO
 * @param $label
 * @param $max_pages
 */
function next_posts_link($label , $max_pages = 10)
{
	return '';
}

/**
 * Prints a link to the next set of posts within the current query.
 * TODO
 * @param $label
 * @param $max_pages
 */
function previous_posts_link($label , $max_pages = 10)
{
	return '';
}


/**
 * TODO
 * @param $first
 * @param $second
 */
function __($first = '', $second = '')
{
	return '';
}

function _e($text, $domain = '')
{
	echo _($text);
}