<?php
/**
 * Frontend Helper
 *
 * Some utilities for the website front-end
 * The following functions are well-documented on the official
 * documenation available here: http://docs.getbancha.com
 * ----------------------------------------
 * Please do not change the functions below.
 * Instead, feel free to copy and rename them.
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
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

function settings($name, $namespace = 'General')
{
	global $B;
	return $B->settings->get($name, $namespace);
}

function type($type_name)
{
	global $B;
	return $B->content->type($type_name);
}

function content_render()
{
	global $B;
	$B->load->view('content_render');
}

function load_helper($name='')
{
	global $B;
	$B->load->helper($name);
}

function page($what='')
{
	global $page;
	if (!isset($page)) return FALSE;
	if ($what == '') return $page;
	return $page->get($what);
}

function tree($which='')
{
	global $B;

	if (is_numeric($which))
	{
		return $B->tree->get_default_branch($which);
	}

	switch($which)
	{
		case '':
		case 'default':
			$tree = $B->view->get('tree');
			if (!$tree) {
				return $B->tree->get_default();
			}
			return $tree;

		case 'current':
			return $B->tree->get_current_branch();
		
		case 'breadcrumbs':
			return $B->tree->breadcrumbs;
	}
}

function record($what='')
{
	global $record;
	if (!isset($record)) return FALSE;
	if ($what == '') return $record;
	return $record->get($what);
}

function records()
{
	global $page;
	if (!isset($page)) return FALSE;
	$records = & $page->get('records');
	return is_array($records) && count($records) ? $records : FALSE;
}

function have_records()
{
	global $page;
	if (!isset($page)) return FALSE;
	$records = & $page->get('records');
	return is_array($records) && count($records);
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

function module($name, $config = array())
{
	global $B;
	return $B->load->module($name, $config);
}

function find($type = '')
{
	global $B;
	return $type =! '' ? $B->records->type($type) : $B->records;
}

function categories($type = '')
{
	global $B;
	if (!isset($B->categories))
	{
		$B->load->categories();
	}
	if ($type != '') return $B->categories->type($type);
	return $B->categories;
}

function related_records($record, $limit = 5)
{
	if (! $record instanceof Record) return array();

	$record_categories = categories()->get_record_categories($record->id);

	$records_ids = categories()->get_records_for_categories($record_categories);

	$ids_to_extract = array();
	foreach ($records_ids as $record_id) {
		if ($record_id != $record->id && count($ids_to_extract) <= $limit) {
			$ids_to_extract[] = $record_id;
		}
	}
	if (!count($ids_to_extract)) return array();

	return find($record->_tipo)->id_in($ids_to_extract)->limit($limit)->get();
}

function page_author()
{
	global $B;
	return $B->view->author;
}

function page_keywords()
{
	global $B;
	return $B->view->keywords;
}

function page_description()
{
	global $B;
	return $B->view->description;
}

function pagination()
{
	global $B;
	if (isset($B->pagination))
	{
		return $B->pagination->create_links();
	}
}

function languages($sep = '&nbsp;')
{
	global $B;
	$langs = $B->settings->get('website_active_languages');
	$all_langs = & $B->lang->languages;
	if (is_array($langs) && count($langs))
	{
		foreach ($langs as $lang)
		{
			if (isset($all_langs[$lang])) {
				echo '<a href="'.site_url('change-language/'.$lang, FALSE).'">'.$all_langs[$lang]['description'].'</a>' . $sep;
			}
		}
	}
}

function language()
{
	global $B;
	return $B->lang->current_language;
}