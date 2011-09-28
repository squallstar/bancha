<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

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