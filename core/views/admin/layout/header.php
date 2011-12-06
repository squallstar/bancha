<?php
/**
 * Admin header view
 *
 * Header per l'amministrazione
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

$_admin_url = admin_url() . '/';
$segment_2 = $this->uri->segment(2);
$segment_3 = $this->uri->segment(3);
$segment_4 = $this->uri->segment(4);

$tipi_content = array();
$tipi_pages = array();
foreach ($this->content->types() as $tipo) {
	if ($tipo['tree']) {
		$tipi_pages[] = array(
			'name'	=> $tipo['description'],
			'url'	=> $_admin_url . 'pages/type/' . $tipo['name'],
			'acl'	=> 'content|' . $tipo['name'],
			'altsegment' =>  $tipo['name']
		);
	} else {
		$tipi_content[] = array(
			'name'	=> $tipo['description'],
			'url'	=> $_admin_url . 'contents/type/' . $tipo['name'],
			'acl'	=> 'content|' . $tipo['name'],
			'altsegment' =>  $tipo['name']
		);
	}
}

$menu = array(
	array(
		'name'		=> $this->auth->user('full_name'),
		'url'		=> $_admin_url . 'dashboard',
		'segment'	=> 'dashboard',
		'sons'	=> array(
			array(
				'name'	=> _('Back to site'),
				'url'	=> site_url()
			)
		)
	),
	array(
		'name'		=> _('Pages'),
		'url'		=> $_admin_url . 'schemes',
		'segment'	=> 'pages',
		'sons'		=> $tipi_pages
	),
	array(
		'name'		=> _('Contents'),
		'url'		=> $_admin_url . 'schemes',
		'segment'	=> 'contents',
		'sons'		=> $tipi_content
	),
	array(
		'name'		=> _('Users'),
		'url'		=> $_admin_url . 'users',
		'acl'		=> 'users|list',
		'segment'	=> 'users',
		'sons'	=> array(
			array(
				'name'	=> _('Add new user'),
				'url'	=> $_admin_url . 'users/edit',
				'acl'	=> 'users|add',
				'altsegment' => 'edit'
			),
			array(
				'name'	=> _('Users list'),
				'url'	=> $_admin_url . 'users/lista',
				'altsegment' => 'lista'
			),
			array(
				'name'	=> _('Groups and permissions'),
				'url'	=> $_admin_url . 'users/groups',
				'altsegment' => 'groups'
			)
		)
	),
	array(
		'name'		=> _('Manage'),
		'url'		=> '#',
		'segment'	=> 'settings',
		'sons'	=> array(
			array(
				'name'	=> _('Settings'),
				'url'	=> $_admin_url . 'settings',
				'acl'	=> 'settings|manage',
				'segment' => 'settings'
			),
			array(
				'name'	=> _('Content types'),
				'url'	=> $_admin_url . 'schemes',
				'segment' => 'schemes',
				'acl'	=> 'types|manage',
			),
			array(
				'name'	=> _('Modules'),
				'url'	=> $_admin_url . 'modules',
				'segment' => 'modules'
			),
			array(
				'name'	=> _('Themes'),
				'url'	=> $_admin_url . 'themes',
				'segment' => 'themes'
			),
			array(
				'name'	=> _('Hierarchies'),
				'url'	=> $_admin_url . 'hierarchies',
				'segment' => 'hierarchies'
			),
			array(
				'name'	=> _('Last events'),
				'url'	=> $_admin_url . 'dashboard/events',
				'altsegment' => 'events'
			),
			array(
				'name'	=> _('Import/export data'),
				'url'	=> $_admin_url . 'import',
				'segment' => 'import'
			),
			array(
				'name'	=> _('Unit tests'),
				'url'	=> $_admin_url . 'unit_tests',
				'segment' => 'unit_tests'
			),
			array(
				'name'	=> _('Clear cache'),
				'url'	=> $_admin_url . 'schemes/rebuild_cache'
			),
			array(
				'name'	=> _('Logout'),
				'url'	=> $_admin_url . 'auth/logout'
			)
		)
	)
);

?><div id="header">
	
	<a href="<?php echo admin_url(); ?>"><h1 class="logo_img"></h1></a>
	<hr />

	<ul id="nav">
		<?php
		foreach ($menu as $row) {
			if (isset($row['acl'])) {
				list($controller, $action) = explode('|', $row['acl']);
				$available = $this->auth->has_permission($controller, $action);
				if (!$available) continue;
			}

			echo  '<li' . ($segment_2 == $row['segment'] ? ' class="open"' : '') . '>'
				 .'<a href="' . $row['url'] . '">' . $row['name'] . '</a>';

			if (isset($row['sons']) && count($row['sons'])) {
				echo '<ul>';
				foreach ($row['sons'] as $son) {

					if(isset($son['acl'])) {
						list($controller_2, $action_2) = explode('|', $son['acl']);
						$available_2 = $this->auth->has_permission($controller_2, $action_2);
					} else {
						$available_2 = TRUE;
					}
					
					if ($available_2) {
						if (isset($son['altsegment'])) {
							$is_active = $segment_3 == $son['altsegment'] || $segment_4 == $son['altsegment'];
						} else if (isset($son['segment'])) {
							$is_active = $segment_2 == $son['segment'];
						} else {
							$is_active = FALSE;
						}

						echo   '<li' . ($is_active ? ' class="active"' : '') . '>'
					 		  .'<a href="' . $son['url'] . '">' . $son['name'] . '</a>'
					 		  .'</li>';
				 	}
				}
				echo '</ul>';
			}
			echo '</li>';
		
		}
		?>

	</ul>
	<p class="copyright"><a href="http://getbancha.com"><?php echo CMS . ' v' . BANCHA_VERSION; ?></a>
	<br /><a href="http://www.squallstar.it">Squallstar Studio</a> &copy; 2011</p>
</div>