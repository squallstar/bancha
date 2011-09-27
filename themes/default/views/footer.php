<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="hr grid_12 clearfix">&nbsp;</div>
		
<p class="grid_12 footer clearfix">
	<span class="float"><b>&copy; Copyright 2011 - Squallstar</b> - Page generated in {elapsed_time} seconds. - 
		<?php
		foreach ($this->lang->languages as $language => $val)
		{
			echo '<a href="'.site_url('change-language/'.$language).'">'.$val['description'].'</a>&nbsp; ';
		}
		?>	
	</span>
	<a class="float right" href="#">top</a>
</p>