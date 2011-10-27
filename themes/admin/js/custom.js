$(function () {

	// Preload images
	$.preloadCssImages();



	// CSS tweaks
	$('#header #nav li:last').addClass('nobg');
	$('.block_head ul').each(function() { $('li:first', this).addClass('nobg'); });
	//$('.block form input[type=file]').addClass('file');



	// Web stats
	$('table.stats').each(function() {

		if($(this).attr('rel')) {
			var statsType = $(this).attr('rel');
		} else {
			var statsType = 'area';
		}

		var chart_width = ($(this).parent('div').width()) - 60;


		if(statsType == 'line' || statsType == 'pie') {
			$(this).hide().visualize({
				type: statsType,	// 'bar', 'area', 'pie', 'line'
				width: chart_width,
				height: '240px',
				colors: ['#6fb9e8', '#ec8526', '#9dc453', '#ddd74c'],

				lineDots: 'double',
				interaction: true,
				multiHover: 5,
				tooltip: true,
				tooltiphtml: function(data) {
					var html ='';
					for(var i=0; i<data.point.length; i++){
						html += '<p class="chart_tooltip"><strong>'+data.point[i].value+'</strong> '+data.point[i].yLabels[0]+'</p>';
					}
					return html;
				}
			});
		} else {
			$(this).hide().visualize({
				type: statsType,	// 'bar', 'area', 'pie', 'line'
				width: chart_width,
				height: '240px',
				colors: ['#6fb9e8', '#ec8526', '#9dc453', '#ddd74c']
			});
		}
	});



	// Sort table
	$("table.sortable").tablesorter({
		headers: { 0: { sorter: false}, 5: {sorter: false} },		// Disabled on the 1st and 6th columns
		widgets: ['zebra']
	});

	$('.block table tr th.header').css('cursor', 'pointer');



	// Check / uncheck all checkboxes
	$('.check_all').click(function() {
		$(this).parents('form').find('input:checkbox').attr('checked', $(this).is(':checked'));
	});



	// Set WYSIWYG editor
	$('.wysiwyg').wysiwyg({css: site_url+"themes/admin/css/wysiwyg.css", brIE: false });

	$('.wysiwyg').wysiwyg("addControl",
		    "controlName",
		    {
		        icon: "/themes/admin/widgets/cal.jpg",
		        exec:  function() { alert('Hello World'); }
		    }
		);


	// Modal boxes - to all links with rel="facebox"
	//$('a[rel*=facebox]').facebox()



	// Messages
	$('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
	$('.block .message .close').hover(
		function() { $(this).addClass('hover'); },
		function() { $(this).removeClass('hover'); }
	);

	$('.block .message .close').click(function() {
		$(this).parent().fadeOut('slow', function() { $(this).remove(); });
	});

	// Form select styling
	$("form select.styled").select_skin();

	// Tabs
	$(".tab_content").hide();
	$("ul.tabs li:first-child").addClass("active").show();
	$(".block").find(".tab_content:first").show();

	$("ul.tabs li").click(function() {
		$(this).parent().find('li').removeClass("active");
		$(this).addClass("active");
		$(this).parents('.block').find(".tab_content").hide();

		var activeTab = $(this).find("a").attr("href");
		$(activeTab).show();

		// refresh visualize for IE
		$(activeTab).find('.visualize').trigger('visualizeRefresh');

		return false;
	});



	// Sidebar Tabs
	$(".sidebar_content").hide();

	if(window.location.hash && window.location.hash.match('sb')) {

		$("ul.sidemenu li a[href="+window.location.hash+"]").parent().addClass("active").show();
		$(".block .sidebar_content#"+window.location.hash).show();
	} else {

		$("ul.sidemenu li:first-child").addClass("active").show();
		$(".block .sidebar_content:first").show();
	}

	$("ul.sidemenu li").click(function() {

		var activeTab = $(this).find("a").attr("href");
		window.location.hash = activeTab;

		$(this).parent().find('li').removeClass("active");
		$(this).addClass("active");
		$(this).parents('.block').find(".sidebar_content").hide();
		$(activeTab).show();
		return false;
	});



	// Block search
	$('.block .block_head form .text').bind('click', function() { $(this).attr('value', ''); });



	// Image actions menu
	$('ul.imglist li').hover(
		function() { $(this).find('ul').css('display', 'none').fadeIn('fast').css('display', 'block'); },
		function() { $(this).find('ul').fadeOut(100); }
	);



	// Image delete confirmation
	$('ul.imglist .delete a').click(function() {
		if (confirm("Are you sure you want to delete this image?")) {
			return true;
		} else {
			return false;
		}
	});

	// File upload
	if ($('#fileupload').length) {
		new AjaxUpload('fileupload', {
			action: 'upload-handler.php',
			autoSubmit: true,
			name: 'userfile',
			responseType: 'text/html',
			onSubmit : function(file , ext) {
					$('.fileupload #uploadmsg').addClass('loading').text('Uploading...');
					this.disable();
				},
			onComplete : function(file, response) {
					$('.fileupload #uploadmsg').removeClass('loading').text(response);
					this.enable();
				}
		});
	}



	// Date picker
	$('input.date_picker').date_input();



	// Navigation dropdown fix for IE6
	if(jQuery.browser.version.substr(0,1) < 7) {
		$('#header #nav li').hover(
			function() { $(this).addClass('iehover'); },
			function() { $(this).removeClass('iehover'); }
		);
	}


	// IE6 PNG fix
	$(document).pngFix();

});

var bancha = {
	_priority : 0,
	load : function(url) {
		$.get(admin_url + url, function(data) {
			$('#content_wrapper').html(data);
		});
	},
	preset_url : function(path, preset, append) {
		//Prototype: attach/cache/type/field/id/preset/name.ext
		if (!append || append === 'undefined') {
			append = false;
		}
		var tmp = path.split('/');
		var i = tmp.length-1;
		path = 'attach/cache/' + tmp[i-3] + '/' + tmp[i-2] + '/' + tmp[i-1] + '/' + preset + '/' + tmp[i];
		return (append ? site_url : '') + path;
	},
	remove : {
		document : function(self, e) {
			$.post(admin_url+'ajax/delete_document', {document_id : e});
			$(self).closest('tr').fadeOut(200);
			var pr = $(self).closest('table').prev('div.limit.hidden');
			pr.removeClass('hidden');
			pr.prev('span.limit').fadeOut(200);
			return false;
		}
	},
	add_form_hash : function() {
		var attr = $('form').attr('action').split('#');
		$('form').attr('action', attr[0] + window.location.hash);
		return true;
	},
	sort_priority : function (event, ui) {
		var rows = $('tbody tr', $(ui.item[0]).closest('.sortable'));
		bancha._priority = rows.length;
		rows.each(function() {
			$('.tbl-priority', this).val(bancha._priority);
			bancha._priority--;
		});
	},
	check : {
		uri: function(e) {
			clearInterval(document._to);
			document._to=setTimeout(function(){bancha.check._triggers.uri(e);},1000);
		},
		_triggers : {
			uri : function(e) {
				$.post(admin_url+'ajax/can_use_uri', {uri : $(e).val(), id_record : $("input[name=id]").val()}, function(data) {
					if (data) {
						$(e).after('<div class="message errormsg">'+data+'</div>');
					} else {
						$('div.errormsg').remove();
					}
				});
			}
		}
	},
	tab_textarea : function(selector) {
		$(selector).keypress(function (e) {
		    if (e.keyCode == 9) {
		        var myValue = "\t";
		        var startPos = this.selectionStart;
		        var endPos = this.selectionEnd;
		        var scrollTop = this.scrollTop;
		        this.value = this.value.substring(0, startPos) + myValue + this.value.substring(endPos,this.value.length);
		        this.focus();
		        this.selectionStart = startPos + myValue.length;
		        this.selectionEnd = startPos + myValue.length;
		        this.scrollTop = scrollTop;

		        e.preventDefault();
		    }
		});
	},
	actions : {
		record_act : function() {
			var val = $('select[name=action]').val();
			var list_fields = '.field-action_list_type, .field-action_list_categories, .field-action_list_limit, '
							+ '.field-action_list_order_by, .field-action_list_where, .field-action_list_has_feed, .field-action_list_hierarchies ';
			var action_fields = '.field-action_custom_name, .field-action_custom_mode';
			var link_fields = '.field-action_link_url';

			switch (val) {
				case 'text':
					$(list_fields).hide(200);
					$(action_fields).hide(200);
					$(link_fields).hide(200);
					break;

				case 'list':
					$(list_fields).show(200);
					$(action_fields).hide(200);
					$(link_fields).hide(200);
					$(link_fields).hide(200, function(){
						//Temporary fix
						$('.cmf-skinned-text').css('height', '20px');
					});
					break;

				case 'action':
					$(action_fields).show(200);
					$(list_fields).hide(200);
					$(link_fields).hide(200);
					break;

				case 'link':
					$(link_fields).show(200);
					$(list_fields).hide(200);
					$(action_fields).hide(200);
					break;
			}
		}
	},
	blocks : {
		_last_section : false,
		set_section : function(which) {
			bancha.blocks._last_section = which;
		},
		save_section : function(el) {
			var this_block = bancha.blocks._last_section;
			$(el + ' form').append('<input type="hidden" name="block" value ="'+this_block+'" />');
			var values = $(el + ' form').serialize();
			values = values + '&theme=' + $('#add_section').attr('data-theme') + '&template='
				   + $('#add_section').attr('data-template');
			$('#cboxClose').click();
			$.post(admin_url + 'themes/add_section', values, function(data) {

				$(data).insertBefore($('.theme_block[data-name="'+this_block+'"]').children().last());

				$('#add_section input[type=text], #add_section select, #add_section textarea').val('');
				$('form input[name=block]').remove();
				bancha.blocks.load_sortable();
			});
		},
		delete_section : function(which) {
			var pos = $(which).parent('.section').attr('data-pos');
			var block = $(which).parent('.section').parent('.theme_block').attr('data-name');
			window.location.href = current_url + '?delete_section=' + pos + '&block=' + block;
		},
		load_sortable : function() {
			$('.theme_block').sortable({
				stop: function(event, ui) {
					bancha.blocks.sorted(event, ui);
				}
			});
		},
		sorted : function(event, ui) {
			var block = ui.item.parent('.theme_block');
			var block_name = block.attr('data-name');
			var str = '&theme=' + $('#add_section').attr('data-theme') + '&template='
					+ $('#add_section').attr('data-template'); + '&block=' + block;

			var str = '&theme=' + $('#add_section').attr('data-theme') + '&template='
				   + $('#add_section').attr('data-template') + '&block=' + block_name;

			$('.section', block).each(function(index) {
				str = str + '&' + index + '=' + $(this).attr('data-pos');
			});
			$.post(admin_url + 'themes/reorder_block', str, function(data) {
				
			});
		}
	}
}

jQuery.extend(DateInput.DEFAULT_OPTS, {
  	/*month_names: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
  	short_month_names: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
  	short_day_names: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],*/
  	stringToDate: function(string) {
	    var matches;
	    if (local_date_format == 'd/m/Y') {
		    if (matches = string.match(/^(\d{2,2})\/(\d{2,2})\/(\d{4,4})$/)) {
		      return new Date(matches[3], matches[2] - 1, matches[1]);
		    } else {
		      return null;
		    };
		} else {
			 if (matches = string.match(/^(\d{4,4})\/(\d{2,2})\/(\d{2,2})$/)) {
		      return new Date(matches[1], matches[2] - 1, matches[3]);
		    } else {
		      return null;
		    };
		}
	},

	  dateToString: function(date) {
	    var month = (date.getMonth() + 1).toString();
	    var dom = date.getDate().toString();
	    if (month.length == 1) month = "0" + month;
	    if (dom.length == 1) dom = "0" + dom;
	    if (local_date_format == 'd/m/Y') {
	    	return dom + "/" + month + "/" + date.getFullYear();
		} else {
			return date.getFullYear() + "-" + month + "-" + dom;
		}
	}
});