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
				echo '<div class="body">'.$page->get('contenuto').'</div><div class="clear"></div>';
				break;

			case 'single':
				//Singolo record
				if (isset($record) && $record instanceof Record) {
					$this->view->render_type_template($record->tipo, 'detail');
				}
				break;

			case 'list':
				$records = & $page->get('records');

				if ($records && count($records)) {
					//Ottengo il tipo del primo record
					$record = $records[0];
					$this->view->render_type_template($record->tipo, 'list');
				}

		}
	}

}

?></div>