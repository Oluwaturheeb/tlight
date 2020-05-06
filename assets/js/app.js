$(document).ready(function () {
	$('.auth-content input').on({
        'mouseover': function(){
            $(this).prev('label').slideDown(500);
        },
        'keyup': () => {
            $(this).prev('label').slideDown(500);
        }
    });

	$(".auth-links div, .auth a").click(function(e) {
		e.preventDefault();
		var id = $(this).attr("class");

		$('.info').empty();
		$(".auth-content #" + id).show().siblings().hide();
		
		if ($(this).hasClass('active'))
			$(this).removeClass('active')
		else
			$(this).addClass('active').siblings().removeClass('active');
	});

	$.ajaxSetup({
		url: 'auth/request.php',
		type: 'post',
	});

	$(".auth form").submit(function (e) {
		e.preventDefault();
		

		var rule = [
			{
				require: true,
				email: true,
				min: 10,
				error: "Email is required!"
			},
			{
				require: true,
				min: 8,
			},
			{
				require: true,
				error: "Enter the captcha code"
			}, {}, {}
		]

		var info = $('.info');
		
		v.form(this, rule);

		if (!v.check()) {
			info.html(v.thrower()).css({'color': '#b28200', 'font-style': 'oblique'});
		} else {
			$.ajax({
				data: $(this).serialize(),
				beforeSend: () => {
					info.html("Connecting to the server...");
					info.empty();
				},
				success: e => {
					if (e == 'ok') {
						$(this).children('#captcha').empty();
						v.redirect();
					} else {
						var se = e.split(" ");
						if (se[0] == 'change') {
							$('.auth .info').empty();
							$(".auth-content #chpwd").show().siblings().hide();
							$('#chpwd .days').html("Its been <b>" + se[1] + "days</b> since you last change your password!");
						} else if (se[0] == 'cap') {
							$(this).children('#captcha').html(v.captcha(se[1]));
						} else {
							if (e != "Captcha Error!")
								$(this).children('#captcha').empty();
							info.html(e).css({'color': '#d80808', 'font-style': 'oblique'});
						}
					}
				}
			});
		}
	});

	$('.skip').click(function(e) {
		e.preventDefault();

		v.redirect('/index');
	});

	$('.dp-menu').click(() => {
	  $('.dp-link').slideToggle(500);
	});
});