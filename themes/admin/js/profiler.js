var _profiler_is_open = false;
var _livemode_last_color = false;

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
}

function _show_ciprofiler()
{
	$('#bancha_profiler_ci').slideToggle();
}

$(document).ready(function() {
	/* (ONLY AVAILABLE IN DEVELOPMENT BRANCH)
	$('*[data-mode="edit"]').click(function() {
		_livemode.start_editor(this);
	}).hover(function() {
		_livemode_last_color = $(this).css('background-color');
		$(this).css('background-color', '#fcfdef');
	}, function() {
		$(this).css('background-color', _livemode_last_color);
	});
	*/
});

var _livemode = {
	_objects : new Array(),
	start_editor : function(el) {
		var obj = $(el);

		if (jQuery.inArray(obj.attr('data-field'), _livemode._objects)) {
			return;
		}

		_livemode._objects[obj.attr('data-field')] = true;
		var field_type = obj.attr('data-fieldtype');
		var input_type;
		switch (field_type) {
			case 'text':
			case 'number':
				input_type = 'input';
				break;
			case 'textarea':
			case 'textarea_full':
				input_type = 'textarea';
				break;
		}
		var val = obj.html();
		if (field_type == 'input') {
			obj.html('<input type="text" name="" value="'+val+'" />');
		} else if (field_type == 'textarea') {
			obj.html('<textarea name="" >'+val+'</textarea>');
		}
	}
}