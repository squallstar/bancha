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
		$('form').attr('action', $('form').attr('action') + window.location.hash);
	
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
	actions : {
		record_act : function() {
			var val = $('select[name=action]').val();
			var list_fields = '.field-action_list_type, .field-action_list_categories, .field-action_list_limit, '
							+ '.field-action_list_order_by, .field-action_list_where, .field-action_list_has_feed ';
			var action_fields = '.field-action_custom_name';
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
	}
}

jQuery.extend(DateInput.DEFAULT_OPTS, {
	  month_names: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
	  short_month_names: ["Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"],
	  short_day_names: ["Dom", "Lun", "Mar", "Mer", "Gio", "Ven", "Sab"],
	  stringToDate: function(string) {
		    var matches;
		    if (matches = string.match(/^(\d{2,2})\/(\d{2,2})\/(\d{4,4})$/)) {
		      return new Date(matches[3], matches[2] - 1, matches[1]);
		    } else {
		      return null;
		    };
		  },

		  dateToString: function(date) {
		    var month = (date.getMonth() + 1).toString();
		    var dom = date.getDate().toString();
		    if (month.length == 1) month = "0" + month;
		    if (dom.length == 1) dom = "0" + dom;
		    return dom + "/" + month + "/" + date.getFullYear();
		  }
	});