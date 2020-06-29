$(document).ready(function () {
	/* default js */
	$('.auth-content input').on({
        'mouseover': function(){
            $(this).prev('label').slideDown(500);
        },
        'keyup': () => {
            $(this).prev('label').slideDown(500);
        }
    });

	$(".auth a, .auth-links div").click(function(e) {
		e.preventDefault();
		var id = $(this).attr("class");

		$('.info').empty();
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
		$(".auth-content #" + id).show().siblings("div").hide();
	});
	
	// logins
	
	$.ajaxSetup({
		url: 'request',
		dataType: 'json',
		type: 'post'
	})

	$(".auth form").submit(function (e) {
		e.preventDefault();

		var info = $(this).children('div').children('.info');
		v.autoForm(this);

		if (!v.check()) {
			info.html(v.thrower()).css({'color': '#b28200', 'font-style': 'oblique'});
		} else {
			$.ajax({
				data: v.auto,
				beforeSend: () => {
					info.html("Connecting to the server...");
				},
				success: e => {
					if (e.msg == 'ok') {
						$(this).children('#captcha').empty();
						info.html('You are logged!').css({"color": "#36a509"});
						// change the redirect location url to any location of your choosing
						v.redirect(e.type);
					} else if (e.msg == 'captcha') {
						$(this).children('#captcha').html(e.captcha);
					} else if (e.msg == "change") {
						$('.auth .info').empty();
						$(".auth-content #chpwd").show().siblings().hide();
						$('#chpwd .days').html("Its been <b>" + e.days + "days</b> since you last change your password!");
					} else {
						info.html(e.msg).css({'color': '#d80808', 'font-style': 'oblique'});
					}
				},
				error: (e) => {
					info.html(e);
				}
			});
		}
	});

	$('.skip').click(function(e) {
		e.preventDefault();

		v.redirect('/index');
	});
	
	/* ends here */
});