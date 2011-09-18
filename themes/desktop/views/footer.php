<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="col">
	<h3><a href="#">Ut enim risus rhoncus</a></h3>
	<p>Quisque consectetur odio ut sem semper commodo. Maecenas iaculis leo a ligula euismod condimentum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
	<p>&not; <a href="#">read more</a></p>
</div>
<div class="col">
	<h3><a href="#">Maecenas iaculis leo</a></h3>
	<p>Quisque consectetur odio ut sem semper commodo. Maecenas iaculis leo a ligula euismod condimentum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. </p>
	<p>&not; <a href="#">read more</a></p>
</div>
<div class="col last">
	<h3><a href="#">Quisque consectetur odio</a></h3>
	<p>Quisque consectetur odio ut sem semper commodo. Maecenas iaculis leo a ligula euismod condimentum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
	<p>&not; <a href="#">read more</a></p>
</div>
<footer>
	<div id="footer">
		<p>Pagina generata in {elapsed_time} secondi.
		<?php
		
		foreach ($this->lang->languages as $language => $val)
		{
			echo '<a href="'.site_url('change-language/'.$language).'">'.$val['description'].'</a>&nbsp; ';
		}
		?>
		</p>
	</div>
</footer>