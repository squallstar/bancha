<?php if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<footer>
	<div id="footer">
		<p>Pagina generata in {elapsed_time} secondi.
		<?php
		foreach ($this->settings->get('website_active_languages') as $_lang)
		{
			echo '<a href="'.site_url('change-language/'.$_lang, FALSE).'">'.$this->lang->languages[$_lang]['description'].'</a>&nbsp; ';
		}
		?>	
		</p>
	</div>
</footer>