<?php
/**
 * Frontend Helper
 *
 * Some utilities for the website front-end
 * ----------------------------------------
 * Please do not change the functions below.
 * Instead, feel free to copy and rename them.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

function render($who)
{
	global $B;
	$B->load->view($who);
}

function template()
{
	global $B;
	$B->load->view($B->view->get('_template_file'));
}

function settings($name, $area = 'General')
{
	global $B;
	return $B->settings->get($name, $area);
}

function content_render()
{
	global $B;
	$B->load->view('content_render');
}

function page($what='')
{
	global $page;
	if (!isset($page)) return FALSE;
	if ($what == '') return $page;
	return $page->get($what);
}

function page_feed()
{
	global $B;
	if ($B->view->has_feed)
    {
      echo link_tag(current_url().'/feed.xml', 'alternate', 'application/rss+xml', page('title') . ' - Feed');
    }
}

function page_css()
{
	$css = page('view_css');
	if ($css)
	{
		echo '<style type="text/css">' . $css . '</style>';
	}
}

function page_js()
{
	$js = page('view_js');
   	if ($js)
   	{
   		echo '<script type="text/javascript">' . $js . '</script>';
   	}
}

function title($sep = ' - ')
{
	global $B;
	return $B->view->title ? $B->view->title . $sep : '';
}

function module($name)
{
	global $B;
	return $B->load->module($name);
}

function records($type = '')
{
	global $B;
	return $type =! '' ? $B->records->type($type) : $B->records;
}