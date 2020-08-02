class Validate {
	constructor() {
		this.success = this.pass = true;
		this.error = this.auto = '';
	}

	autoForm (f) {
		if (typeof f != "object") {
			this.error('this method expects the (this) object as argument!')
		} else {
			// set the error and the pass value to false to avoid the method giving error all the time;
			this.error = '';
			this.pass = true;

			var ftype = $(f).attr("enctype");
			var fi = f;
			f = $(f).children("div");

			/*removing the last element because it contains the submit button
			i tot i would'nt make senese cus sometimes i used to hide some hidden input there
			console.log(f);
			f.pop();*/

			f.each((i, tag) => {
				var t = $(tag);

				// validation for input
				if (t.find("input").length) {
					var inp = t.find("input");
					var fname = inp.prev("label").html();
					var type = inp.attr('type');
					// this check if there is dataval attr

					if (inp.attr("data-val")) {
						// this.form()
					} else {
						if (this.empty(inp, true)) {
							this.error = fname +" field cannot be empty!";
							if (type == 'file')
								this.error = "Select a file to upload in " + fname + "!";
						} else {
							if (type == "number" || type == "tel") {
								if (isNaN(this.getInput(inp))) {
									this.error = 'Enter a numeric value for ' + fname + '!';
								}
							} else if (type == "email") {
								if (this.getInput(inp).indexOf('.') == -1 || this.getInput(inp).indexOf('@') == -1) {
									this.error = 'Kindly provide a valid email address!';
								}
							} else if (type == "checkbox") {
								fname = "boxes";
								if (this.checkBox() === false) {
									this.error = "Select a value from the " + fname + "!";
								}
							} else if (type == 'file') {
								var min = inp.attr('min');
								var max = inp.attr('max');
								var fc = this.fileCheck(inp);

								if (fc < min) {
									this.error = "Minimum of " + min + " files is required!"
								} else if (fc > max){
									this.error = "Maximum of " + max + " files exceeded!";
								}
								return false;
							}
						}
					}
					// validation for select
				} else if (t.find("select").length) {
					var inp = t.find("select");
					var fname = inp.prev("label").html();

					if (inp.attr("data-val")) {
						
					} else {
						if (this.empty(inp, true)) {
							this.error = "Select a value from " + fname + "!";
						}
					}
					// validation for textarea
				} else if (t.find("textarea").length) {
					var inp = t.find("textarea");
					var fname = inp.prev("label").html();

					if (inp.attr("data-val")) {
						
					} else {
						if (this.empty(inp, true)) {
							this.error = fname + "  field cannot be empty!";
						}
					}
				}
				if (this.error) {
					return false;
				}
			});

			if (this.error) {
				this.pass = false;
			} else {
				// check the form enctype here;
				if (this.empty(ftype))
					this.auto = $(fi).serialize();
				else
					this.auto = new FormData(fi);
				
			}
		}
	}

	// this method accepts only json from the server

	withAuto (info = '.info', msg = {ok: "Success!", data: ".result"}, r = '') {
		var ppt = {
			data: this.auto,
			beforeSend: () => {
				if (info != "alert")
					info.html('Connecting to the server...');
				else
					alert('Connecting to the server...');

				if (msg.data) {
					$(msg.data).empty();
				}
			},
			success: e => {
				if (e.msg == 'ok') {
					// to display notty
					if (info != 'alert')
						info.html(msg.ok).css({"color": "#36a509"});
					else 
						alert(msg.ok);
					// ends
					if (e.data) {
						info.empty();
						$(msg.data).html(e.data);
					} else {
						this.redirect(r);
					}
				} else {
					if (msg.err) {
						if (info != 'alert')
							info.html(msg.err).css({'color': '#d80808', 'font-style': 'oblique'});
						else 
							alert(msg.err);
					} else {
						if (info != 'alert')
							info.html(e.msg).css({'color': '#d80808', 'font-style': 'oblique'});
						else
							alert(e.msg);
					}
				}
			},
			error: e => {info.html(JSON.stringify(e)).css({'color': '#d80808', 'font-style': 'oblique'})}
		}

		if (typeof v.auto == 'string')
			$.ajax(ppt);
		else
			$.ajax(ppt = {...ppt, contentType: false, processData: false, cache: false});
	}

	form(id, rules) {
		var f = $(id).serializeArray();
		this.error = this.pass = false;

		f.forEach((d, i) => {
			var rule = Object.keys(rules[i]);
			var val = Object.values(rules[i]);
			rule.forEach((r, c) => {

				if (this.error) {
					return this.error;
				} else {
					switch (r) {
						case "require":
							if (this.empty(d.value, true)) {
								this.error = d.name + ' field can not be empty!';
							}
							break;
						case "email":
							if (d.value.indexOf('.') == -1 || d.value.indexOf('@') == -1) {
								this.error = 'Kindly provide a valid email address!';
							}
							break;
						case "number":
							if (isNaN(d.value)) {
								this.error = 'Enter a numeric value for ' + d.name + '!';
							}
							break;
						case "wordcount":
							var word = d.value.split(' ');
							if (val[c] > word.length) {
								this.error = 'At least ' + val[c] + ' word is required for ' + d.name;
							}
							break;
						case "min":
							if (d.value.length < val[c]) {
								this.error = 'Minimum of ' + val[c] + ' chars is required for ' + d.name;
							}
							break;
						case "max":
							if (d.value.lengt > val[c]) {
								this.error = 'Minimum of ' + val[c] + ' chars is required for ' + d.name;
							}
							break;
						case "match":
							if (!this.checkMatch(d.value, val[c])) {
								this.error = "Password do not match!";
							}
							break;
						case "checkbox":
							if (this.checkBox() === false) {
								this.error = 'Select a value!';
							}
							break;
						case "file":
							if (!this.fileCheck(d.value)) {
								this.error = 'Kindly, select a file!';
							}
							break;
						case "fileMin":
							if (val[c] > this.fileCheck(d.value)) {
								this.error = 'Minimum of ' + val[c] + ' files is required!';
							}
							break;
						case "fileMax":
							if (this.fileCheck(d.value) > val[c]) {
								this.error = 'Maximum of ' + val[c] + ' files exceeded!';
							}
							break;
					}
				}
			});
		});
		if (this.error == "") {
			this.pass = true;
		}

	}

	capFirst(str) {
		return str.replace(str, str[0].toUpperCase());
	}

	captcha () {
		var inp = "<div class='id'><label style='display: none !important'>captcha</label><input type='text' name='captcha' placeholder='Enter captcha' class='input-line'></div>";
		return inp;
	}

	getInput(input) {
		return $(input).val();
	}

	empty(handler, c = false) {
		if (c) {
			var i = this.getInput(handler);

			if (typeof i == 'string') {
				if (i == null || i == undefined) {
					return true;
				} else if (i.trim().length == 0) {
					return true;
				} else {
					return false;
				}
			} else {
				i.forEach( e => {
					if (e == null || e == undefined) {
						return true;
					} else if (e.trim().length == 0) {
						return true;
					} else {
						return false;
					}
				});
			}
		} else {
			if (handler == undefined || handler == null || handler.length == 0) {
				return true;
			} else {
				return false;
			}
		}
	}

	checkBox() {
		if (this.empty($('input:checkbox:checked').val())) {
			return false;
		} else {
			return true;
		}
	}
	
	redirect(loc = '') {
		if (loc === '') {
			setTimeout(() => {
				location.reload();
			}, 1000);
		} else {
			setTimeout(() => {
				location = loc;
			}, 1000);
		}
	}

	store(value, key) {
		var store = localStorage;
		if (typeof value === 'string') {
			switch (key) {
				case 'rm':
					store.removeItem(value);
					break;
				case 'get':
					return store.getItem(value);
			}
		} else {
			switch (key) {
				case 'set':
					store.setItem(value[0], value[1]);
					break;
			}
		}
	}

	checkMatch(field, toMatch) {
		var field = this.getInput(field);
		var toMatch = this.getInput(toMatch);

		if (field === toMatch) {
			return true;
		}
		return false;
	}

	fileCheck(field) {
		var f = $(field).get(0).files.length;
		if (f)
			return f;
		return false;
	}

	thrower() {
		return this.error;
	}

	check() {
		return this.pass;
	}

	dError(val, c = false) {
		if (c) {
			alert(JSON.stringify(val));
		} else {
			try {
				val
			} catch (e) {
				this.dError(e, true);
			}
		}
	}
}
var v = new Validate();