class UI {
	toggleActive (obj) {
		$(obj).addClass('active').siblings().removeClass('active');
	}

	tabToggle (obj, cls = 'tab-items') {
		var id = $(obj).attr('href');
		$('.' + cls).children(id).show().siblings().hide();
	}
	
	checkboxToggle (div) {
		$(div).click(function() {
		if ($(this).children('input').prop('checked')){
			$(this).children('input').prop('checked', false);
		} else {
			$(this).children('input').prop('checked', true);
			$(this).siblings().children('input').prop('checked', false);
			}
		});
	}
}

let UI = new UI();