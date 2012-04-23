var _profiler_is_open = false;

function _show_profiler()
{
	if (!_profiler_is_open)
	{
		$('#bancha_profiler_content').slideDown(500, function(){
			$('#bancha_profiler_preview').animate({
				top : '48px'
			}, 500);
		});

		_profiler_is_open = true;
	}
	else {
		$('#bancha_profiler_preview').animate({
			top : '0px'
		}, 500, function(){
			$('#bancha_profiler_content').slideUp();
		});
		_profiler_is_open = false;
	}
	return false;
}

function _show_ciprofiler()
{
	$('#bancha_profiler_ci').slideToggle();
}