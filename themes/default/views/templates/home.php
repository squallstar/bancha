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
<?php /*
####################### PICASA CALL ####################
$config_picasa = array ('username' => 'kikkovolley');
$picasa = $this->load->module('picasa', $config_picasa);
$picasa->getGallery()->render();
########################################################*/


########################## STAZIONE METEO NAVEVE #################
?>
	<h2>Stazione Meteo @Naveve - Malcesine - Garda Lake - Verona - Italy</h2>
	<div>
		<img alt="Velocit&agrave; Vento" style="width:200px;" src="http://www.kitemalcesine.it/meteo/WindSpeed.gif">
		<img alt="Cronologia Velocit&agrave; Vento" src="http://www.kitemalcesine.it/meteo/WindSpeedHistory.gif">
	</div>
	<div>
		<img alt="Direzione vento" style="width:200px;" src="http://www.kitemalcesine.it/meteo/WindDirection.gif">
		<img alt="Cronologia Direzione Vento" src="http://www.kitemalcesine.it/meteo/WindDirectionHistory.gif">
	</div>
<?php ################################################### ?>
	<ul> 
		<li>
			<a href="#">
				<span>Read about this project</span>
				<img src="<?php echo theme_url('widgets/600x300.gif'); ?>" alt="" />
			</a>
		</li>  
		<li>
			<a href="#">
				<span>Read about this project</span>
				<img src="<?php echo theme_url('widgets/600x300.gif'); ?>" alt="" />
			</a>	
		</li>  
		<li>
			<a href="#">
				<span>Read about this project</span>
				<img src="<?php echo theme_url('widgets/600x300.gif'); ?>" alt="" />
			</a>
		</li>  
		<li>
			<a href="#">
				<span>Read about this project</span>
				<img src="<?php echo theme_url('widgets/600x300.gif'); ?>" alt="" />
			</a>
		</li>  
		<li>
			<a href="#">
				<span>Read about this project</span>
				<img src="<?php echo theme_url('widgets/600x300.gif'); ?>" alt="" />
			</a>
		</li>  
	</ul> 
</div>
<div class="hr grid_12 clearfix">&nbsp;</div>
	
<!-- Caption Line -->
<h2 class="grid_12 caption clearfix"><?php echo $this->settings->get('website_claim'); ?></h2>

<div class="hr grid_12 clearfix quicknavhr">&nbsp;</div>
<div id="quicknav" class="grid_12">
	<a class="quicknavgrid_3 quicknav alpha" href="#">
			<h4 class="title ">Recent Work</h4>
			<p>Cras vestibulum lorem et dui mollis sed posuere leo semper. </p>
			<p style="text-align:center;"><img alt="" src="<?php echo theme_url('widgets/Art_Artdesigner.lv.png'); ?>" /></p>
	</a>
	<a class="quicknavgrid_3 quicknav" href="#">
			<h4 class="title ">Learn about us</h4>
			<p>Cras vestibulum lorem et dui mollis sed posuere leo semper. </p>
			<p style="text-align:center;"><img alt="" src="<?php echo theme_url('widgets/info.png'); ?>" /></p>
	</a>
	<a class="quicknavgrid_3 quicknav" href="#">
			<h4 class="title ">Read our blog</h4>
			<p>Cras vestibulum lorem et dui mollis sed posuere leo semper. </p>
			<p style="text-align:center;"><img alt="" src="<?php echo theme_url('widgets/Blog_Artdesigner.lv.png'); ?>" /></p>
	</a>
	<a class="quicknavgrid_3 quicknav" href="#">
			<h4 class="title ">Follow on Twitter</h4>
			<p>Cras vestibulum lorem et dui mollis sed posuere leo semper. </p>
			<p style="text-align:center;"><img alt="" src="<?php echo theme_url('widgets/hungry_bird.png'); ?>" /></p>
	</a>
</div>
<div class="hr grid_12 clearfix">&nbsp;</div>

<script type="text/javascript">   
  $(document).ready(function() {
    $('#featured ul').roundabout({
      easing: 'easeOutInCirc',
      duration: 600
    });
  });
</script>

<?php
$this->view->render('footer');