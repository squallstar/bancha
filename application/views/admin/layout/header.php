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

$tipi_content = array();
$tipi_pages = array();
foreach ($this->content->types() as $tipo) {
	if ($tipo['tree']) {
		$tipi_pages[] = $tipo;
	} else {
		$tipi_content[] = $tipo;
	}
}

?><div id="header">
	<div class="hdrl"></div>
	<div class="hdrr"></div>

	<h1><a href="<?php echo admin_url(); ?>"><?php echo CMS; ?></a></h1>

	<ul id="nav">
		<li class="<?php echo $this->uri->segment(2) == 'dashboard' ? 'active' : ''; ?>">
			<a href="<?php echo admin_url('dashboard/'); ?>"><?php echo _('Dashboard'); ?></a>
		</li>
		<li class="<?php echo $this->uri->segment(2) == 'contents' ? 'active' : ''; ?>">
			<a href="<?php echo admin_url('contents/'); ?>"><?php echo _('Contents'); ?></a>
			<ul>
				<?php
				foreach ($tipi_content as $tipo) {
					if ($this->auth->has_permission('content', $tipo['name'])) {
				?>
				<li><a href="<?php echo admin_url('contents/type/'.$tipo['name']); ?>"><?php echo $tipo['description']; ?></a></li>
				<?php }
				} ?>
			</ul>
		</li>
		<li class="<?php echo $this->uri->segment(2) == 'pages' ? 'active' : ''; ?>">
			<a href="<?php echo admin_url('pages/'); ?>"><?php echo _('Pages'); ?></a>
			<ul>
				<?php
				foreach ($tipi_pages as $tipo) {
					if ($this->auth->has_permission('content', $tipo['name'])) {
				?>
				<li><a href="<?php echo admin_url('pages/type/'.$tipo['name']); ?>"><?php echo $tipo['description']; ?></a></li>
				<?php }
				} ?>
			</ul>
		</li>
		<li class="<?php echo $this->uri->segment(2) == 'users' ? 'active' : ''; ?>">
			<a href="<?php echo admin_url('users/'); ?>"><?php echo _('Users'); ?></a>
			<ul>
				<li><a href="<?php echo admin_url('users/edit'); ?>"><?php echo _('Add new user'); ?></a></li>
				<li><a href="<?php echo admin_url('users/lista'); ?>"><?php echo _('Users list'); ?></a></li>
				<li><a href="<?php echo admin_url('users/groups'); ?>"><?php echo _('Groups and permissions'); ?></a></li>
			</ul>
		</li>
		<li class="<?php echo $this->uri->segment(2) == 'modules' ? 'active' : ''; ?>">
			<a href="<?php echo admin_url('modules'); ?>"><?php echo _('Modules'); ?></a>
		</li>
		<li class="<?php echo $this->uri->segment(2) == 'manage' ? 'active' : ''; ?>">
			<a href="#" style="cursor:default"><?php echo _('Manage'); ?></a>
			<ul>
				<li class="<?php echo $this->uri->segment(2) == 'docs' ? 'active' : ''; ?>">
					<a href="<?php echo admin_url('docs'); ?>"><?php echo _('Documentation'); ?></a>
				</li>
				<li><a href="<?php echo admin_url('hierarchies'); ?>"><?php echo _('Hierarchies'); ?></a></li>
				<li><a href="<?php echo admin_url('import'); ?>"><?php echo _('Import/export data'); ?></a></li>
				<li><a href="<?php echo admin_url('unit_tests'); ?>"><?php echo _('Unit tests'); ?></a></li>
				<li><a href="<?php echo admin_url('contents/renew_cache'); ?>"><?php echo _('Clear cache'); ?></a></li>

			</ul>
		</li>

	</ul>
	<p class="user"><?php echo $this->auth->user('full_name'); ?> | <a href="<?php echo site_url(); ?>"><?php echo _('Back to site'); ?></a> | <a href="<?php echo admin_url('auth/logout'); ?>"><?php echo _('Logout'); ?></a></p>
</div>