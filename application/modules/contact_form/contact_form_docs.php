<?php
/**
 * Contact form Module Documentation
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

 ?><div class="sidebar">
	<ul class="sidemenu">
		<li><a href="#sb-intro">Contact form Module</a></li>
	</ul>
<p>
	Author: Nicholas Valbusa<br />
	Package: Bancha
</p>
</div>

<div class="sidebar_content" id="sb-intro">
	<h3>Using the Contact form module</h3>
	<p>This module, displays a contact form and sends also an e-mail to the website administrator when the form will be submitted. Such as many libraries of CodeIgniter, to config the module just pass an associative array as second parameter of the function <strong>module</strong>.</p>
	<p>Use it as in the example below:</p>
<code>&lt;?php

$config = array(
	'action'	=&gt; 'email',
	'from'		=&gt; 'noreply@example.org',
	'to'		=&gt; 'support@example.org',
	'subject'	=&gt; 'New request received'
);

echo $this-&gt;load-&gt;module('contact_form', $config)-&gt;render();
?&gt;</code>

</div>