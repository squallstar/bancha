<?php
/**
 * Sharer Module Documentation
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011-2012, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

?><div class="sidebar">
	<ul class="sidemenu">
		<li><a href="#sb-intro">Using the module</a></li>
		<li><a href="#sb-facebook">Facebook usage</a></li>
		<li><a href="#sb-twitter">Twitter usage</a></li>
	</ul>
<p>
	Author: Nicholas Valbusa<br />
	Package: Bancha
</p>
</div>

<div class="sidebar_content" id="sb-intro">
	<h3>Using the sharer module</h3>
	<p>This module is useful to gets the sharing URL for Facebook/Twitter or to renders a sharing button.</p>
	<p>Before all, just made an instance of the sharer module:</p>
<code><strong>$sharer</strong> = $this-&gt;load-&gt;module('sharer');</code>
<br />
<p>Then, if you want to display the sharing button for Facebook:</p>
<code>//Renders the Facebook sharing button
echo $sharer-&gt;type('facebook')-&gt;url('http://example.org')-&gt;title('Share on Facebook')-&gt;render();</code>
<br />
<p>If you do not set the <strong>title</strong> or the <strong>url</strong>, the module will use the <strong>current page meta title</strong> and <strong>current url</strong> (via the view object).</p>

<p>The example below, displays the sharing link for Twitter:</p>
<code>$link = $sharer-&gt;type('twitter')-&gt;via('@squallstar')-&gt;message('This is cool')-&gt;get_link();</code>
<br />
<p>As you saw, the main rendering functions are two:</p>
<ul>
	<li><strong>render()</strong> - that returns the xhtml of the rendered the sharing button</li>
	<li><strong>get_link()</strong> - that returns just the sharing URL</li>
</ul>

</div>

<div class="sidebar_content" id="sb-facebook">
	<h3>Using the sharer for Facebook</h3>
	<p>Here are the available functions for the Facebook sharer:</p>
<ul>
	<li><strong>url($string)</strong> - The URL you want to share. By default is set to che current page url.</li>
	<li><strong>title($string)</strong> - The title of the page. If not set, the sharer will use the view title ($this->view->title)</li>
</ul>
<p>Example of usage:</p>
<code>$sharer = $this-&gt;load-&gt;module('sharer');

$sharer-&gt;type('facebook');
$sharer-&gt;url('http://www.google.it');
$sharer-&gt;title('Google Homepage');
echo $sharer-&gt;render();

//Functions can be chained:
echo $sharer-&gt;type('facebook')-&gt;url('http://www.google.it')-&gt;title('Google Homepage')-&gt;render();</code>
</code>
<br />
<p>If you want just the sharing URL, instead of the <strong>render()</strong> function, use this one:</p>
<code>echo $sharer-&gt;type('facebook')-&gt;url('http://www.google.it')-&gt;title('Google Homepage')-&gt;<strong>get_link()</strong>;</code>
</div>

<div class="sidebar_content" id="sb-twitter">
	<h3>Using the sharer for Twitter</h3>
	<p>Here are the available functions for the Twitter sharer:</p>
<ul>
	<li><strong>url($string)</strong> - The URL you want to share. By default is set to che current page url.</li>
	<li><strong>message($string)</strong> - the message of the tweet (use 100-120 characters)</li>
		<li><strong>via($string)</strong> - the username subject of the tweet (example: via @squallstar)</li>
	<li><strong>hashtags($string)</strong> - the hashtags of the tweet (example: #hello, #world)</li>
</ul>
<p>Example of usage:</p>
<code>$sharer = $this-&gt;load-&gt;module('sharer');

$sharer-&gt;type('twitter');
$sharer-&gt;url('http://www.google.it');
$sharer-&gt;via('@squallstar');
$sharer-&gt;message('This website is cool!');
echo $sharer-&gt;render();

//Functions can be chained:
echo $sharer-&gt;type('twitter')-&gt;url('http://www.google.it')-&gt;message('This is cool!')-&gt;render();</code>
</code>
<br />
<p>If you want just the sharing URL, instead of the <strong>render()</strong> function, use this one:</p>
<code>echo $sharer-&gt;type('twitter')-&gt;url('http://www.google.it')-&gt;message('Cool!')-&gt;<strong>get_link()</strong>;</code>
</div>