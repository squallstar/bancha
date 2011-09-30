<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?><div id="main" role="main"><?php
if (isset($page) && $page->is_page()) {

	$action = $page->get('action');
	if ($action) {
		
		$this->load->helper('breadcrumbs');
		echo _('You are here: ').breadcrumbs($this->tree->breadcrumbs).br(1);
		
		switch ($action) {

			case 'text':
				echo '<div class="details"><h1>'.$page->get('title').'</h1>'.
					 '<p class="info">'.menu($this->tree->get_current_branch()).'</p>'.
					 '</div>';
				echo '<div class="body"'.$this->view->live_tags('content', $page).'>'.$page->get('content').'</div><div class="clear"></div>';
				break;

			case 'single':
				//Single record
				if (isset($record) && $record instanceof Record) {
					$this->view->render_type_template($record->tipo, 'detail');
				}
				break;

			case 'list':
				$records = & $page->get('records');

				if ($records && count($records)) {
					//We use the content type of the first record as template
					$record = $records[0];
					$this->view->render_type_template($record->tipo, 'list');
				}
				break;
			
			case 'action_render':
				$class = $page->get('_action_class');
				if ($class)
				{
					$method = $page->get('action_custom_name');
					$class->$method('content_render');
				}
				break;

		}
	}

}

?></div>