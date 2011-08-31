<?php
/**
 * Base Layout
 *
 * Layout base per la renderizzazione del sito
 *
 * @package		Milk
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

?><!DOCTYPE html>
<html lang="<?php echo $this->lang->current_language; ?>">
	<head>
		<title><?php echo ($this->view->title ? $this->view->title . ' - ' : '') . _('Website title'); ?></title>
		<?php echo meta(array(
			array('Content-type', 'text/html; charset=utf-8', 'equiv'),
			array('name' => 'description', 'content' => $this->view->description),
			array('name' => 'keywords', 'content' => $this->view->keywords)
		));

		foreach ($this->view->css as $css) {
			echo link_tag('css/'.$css);
		}

		if ($this->view->has_feed)
		{
			echo link_tag(current_url().'/feed.xml', 'alternate', 'application/rss+xml', 'My RSS Feed');
		}

		foreach ($this->view->javascript as $js) {
   			?><script type="text/javascript" src="<?php echo site_url('js/'.$js); ?>"></script><?php
   		}
   		?>

	</head>
	<body>
		<div id="wrapper">
			<?php $this->view->render($_template_file); ?>
		</div>
	</body>
</html>