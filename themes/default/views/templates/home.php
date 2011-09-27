<?php
/**
 * Default website home template
 *
 * @package		Bancha
 * @author		Nicholas Valbusa - info@squallstar.it - @squallstar
 * @copyright	Copyright (c) 2011, Squallstar
 * @license		GNU/GPL (General Public License)
 * @link		http://squallstar.it
 *
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$this->view->render('header');

?>
<div id="featured" class="clearfix grid_12">
	<ul> 
		<li>
			<a href="portfolio_single.html">
				<span>Read about this project</span>
				<img src="images/600x300.gif" alt="" />
			</a>
		</li>  
		<li>
			<a href="portfolio_single.html">
				<span>Read about this project</span>
				<img src="images/600x300.gif" alt="" />
			</a>	
		</li>  
		<li>
			<a href="portfolio_single.html">
				<span>Read about this project</span>
				<img src="images/600x300.gif" alt="" />
			</a>
		</li>  
		<li>
			<a href="portfolio_single.html">
				<span>Read about this project</span>
				<img src="images/600x300.gif" alt="" />
			</a>
		</li>  
		<li>
			<a href="portfolio_single.html">
				<span>Read about this project</span>
				<img src="images/600x300.gif" alt="" />
			</a>
		</li>  
	</ul> 
</div>
<div class="hr grid_12 clearfix">&nbsp;</div>
	
<!-- Caption Line -->
<h2 class="grid_12 caption clearfix">Welcome! This is <span>Aurelius</span>, a slick, professional <u>Business</u> &amp; <u>Portfolio</u> theme built to engage the user in your work.</h2>

<div class="hr grid_12 clearfix quicknavhr">&nbsp;</div>
<div id="quicknav" class="grid_12">
	<a class="quicknavgrid_3 quicknav alpha" href="portfolio.html">
			<h4 class="title ">Recent Work</h4>
			<p>Cras vestibulum lorem et dui mollis sed posuere leo semper. </p>
			<p style="text-align:center;"><img alt="" src="images/Art_Artdesigner.lv.png" /></p>
		
	</a>
	<a class="quicknavgrid_3 quicknav" href="about.html">
			<h4 class="title ">Learn about us</h4>
			<p>Cras vestibulum lorem et dui mollis sed posuere leo semper. </p>
			<p style="text-align:center;"><img alt="" src="images/info.png" /></p>
		
	</a>
	<a class="quicknavgrid_3 quicknav" href="blog.html">
			<h4 class="title ">Read our blog</h4>
			<p>Cras vestibulum lorem et dui mollis sed posuere leo semper. </p>
			<p style="text-align:center;"><img alt="" src="images/Blog_Artdesigner.lv.png" /></p>
		
	</a>
	<a class="quicknavgrid_3 quicknav" href="#">
			<h4 class="title ">Follow on Twitter</h4>
			<p>Cras vestibulum lorem et dui mollis sed posuere leo semper. </p>
			<p style="text-align:center;"><img alt="" src="images/hungry_bird.png" /></p>
	</a>
</div>
<div class="hr grid_12 clearfix">&nbsp;</div>

<?php
$this->view->render('footer');