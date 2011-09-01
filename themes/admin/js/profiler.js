var _profiler_is_open = false;

function _show_profiler()
{
	if (!_profiler_is_open)
	{
		$('#milk_profiler_content').slideDown(500, function(){
			$('#milk_profiler_preview').animate({
				top : '48px'
			}, 500);
		});
		
		_profiler_is_open = true;
	}
	else {
		$('#milk_profiler_preview').animate({
			top : '0px'
		}, 500, function(){
			$('#milk_profiler_content').slideUp();
		});
		_profiler_is_open = false;
	}
}

function _show_ciprofiler()
{
	$('#milk_profiler_ci').slideToggle();
}