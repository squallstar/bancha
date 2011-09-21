<div class="block small left">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo $this->lang->_trans('Welcome back, %u', array('u'=>$this->auth->user('full_name')))?></h2>
	</div>		<!-- .block_head ends -->



	<div class="block_content">

		<p>Hello world</p>

	</div>		<!-- .block_content ends -->

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>		<!-- .block.small.left ends -->


<div class="block small right">

	<div class="block_head">
		<div class="bheadl"></div>
		<div class="bheadr"></div>

		<h2><?php echo _('Last events'); ?></h2>
	</div>		<!-- .block_head ends -->



	<div class="block_content">
	<br />
		<ul>
			<?php foreach ($events as $event) {

				list($date, $time) = explode(' ', date('d/m/Y H:i:s', $event->event_date));
				$str_time = ($date == date('d/m/Y') ? _('Today') : $date) . ' ' . $this->lang->_trans('at %time', array('time'=>$time));

				echo '<li>';

				if ($event->content_type)
				{
					$tipo = $this->content->type($event->content_type);
					if (isset($tipo['name']))
					{
						$link = admin_url('contents/edit_record/'.$tipo['name'].'/'.$event->content_id);
					} else {
						$tipo['name'] = _('Unknown');
						$link = '#';
					}
				} else {
					$tipo['name'] = _('Unknown');
					$link = '#';
				}

				switch ($event->event)
				{
					case 'update':
						echo $str_time.' '.$this->lang->_trans('%u updated the content %c of type %t.', array(
							'u'	=> $event->user_name,
							'c'	=> '<a href="'.$link.'">'.$event->content_name.'</a>',
							't'	=> $tipo['name']
						));
						break;

					case 'insert':
						echo $str_time.' '.$this->lang->_trans('%u created a new content of type %t named %c.',
						array(
							'u'	=> $event->user_name,
							'c'	=> '<a href="'.$link.'">'.$event->content_name.'</a>',
							't'	=> $tipo['name']
						));
						break;

					case 'publish':
						echo $str_time.' '.$this->lang->_trans('%u published the content %c of type %t.',
						array(
							'u'	=> $event->user_name,
							'c'	=> '<a href="'.$link.'">'.$event->content_name.'</a>',
							't'	=> $tipo['name']
						));
						break;

					case 'depublish':
						echo $str_time.' '.$this->lang->_trans('%u depublished the content %c.',
						array(
							'u'	=> $event->user_name,
							'c'	=> '<strong>['.$event->content_name.']</strong>'
						));
						break;

					case 'install':
						echo $str_time.' '.$this->lang->_trans('%u installed %c.',
						array(
							'u'	=> $event->user_name,
							'c'	=> '<strong>['.$event->content_name.']</strong>'
						));
						break;

					case 'login':
						echo $str_time.' '.$this->lang->_trans('%u login.', array('u'	=> $event->user_name));
						break;

					case 'logout':
						echo $str_time.' '.$this->lang->_trans('%u logout.', array('u'	=> $event->user_name));
						break;

				}
				echo '</li>';

			} ?>
		</ul>

	</div>		<!-- .block_content ends -->

	<div class="bendl"></div>
	<div class="bendr"></div>

</div>		<!-- .block.small.right ends -->