class Validate {
	constructor() {
		this.success = false;
		this.pass = false;
		this.error = '';
		this.auto = '';
	}

	autoForm (f) {
		//initial f before manipulation so as to use it serialization
		var fi = f;
		if (typeof f === "object") {
			f = $(f).children("div");
		} else {
			f = $(f + " div");
		}
		this.error = this.pass = false;
		// removing the last element because it contains the submit button
		//f.pop();

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
					if (this.empty(inp)) {
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
							if (this.checkBox() === false) {
								this.error = "Select a value from " + fname + "!";
							}
						} else if (type == 'file') {
							var min = inp.attr('min');
							var max = inp.attr('max');
							var fc = this.fileCheck(inp);

							if (fc < min) {
								this.error = "Minimum of " + min + " required!"
							} else if (fc > max){
								this.error = "Maximum of " + max + " required!";
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
					if (this.empty(inp)) {
						this.error = "Select a value from " + fname + "!";
					}
				}
				// validation for textarea
			} else if (t.find("textarea").length) {
				var inp = t.find("textarea");
				var fname = inp.prev("label").html();

				if (inp.attr("data-val")) {
					
				} else {
					if (this.empty(inp)) {
						this.error = fname + "  field cannot be empty!";
					}
				}
			}
			if (this.error) {
				return false;
			}
		});
		
		if (this.error) {
			this.pass = true;
		} else {
			this.auto = $(fi).serialize();
		}
	}
	
	withAuto (info = '.info', msg = {ok: "Success!"}, r = '') {
		info = $(info);
		var ppt = {
			data: this.auto,
			beforeSend: () => {
				info.html('Connecting to the server...');
			},
			success: e => {
				if (e == 'ok') {
					info.html(msg.ok);
					this.redirect(r);
				} else {
					if (msg.err) {
						info.html(msg.err);
					} else {
						if (msg.err) {
							info.html(msg.err);
						} else {
							info.html(e)
						}
					}
				}
			}
		}
		$.ajax(ppt);
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

	captcha(str) {
		var inp = "<div><div class='captcha'> " + str + "</div><input name='captcha' placeholder='Robot?' type='text' class='input-line'></div>";
		return inp;
	}

	getInput(input) {
		return $(input).val();
	}

	empty(handler, c = false) {

		if (c) {
			if (handler == undefined || handler == null || handler.length == 0) {
				return true;
			} else {
				return false;
			}
		} else {
			var i = this.getInput(handler);

			if (i == null || i == undefined) {
				return true;
			} else if (i.trim().length == 0) {
				return true;
			} else {
				return false;
			}
		}
	}

	checkBox() {
		if (this.empty($('input:checkbox:checked').val(), true)) {
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
		try{
		var f = $(field).get(0).files.length;
}catch(e){
	this.dError(e, true);
}
		if (f) {
			return f;
		}
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

	/*uniqueData(sup = [], handler, err = '') {
	    var table = sup[0];
	    var col = sup[1];
	    var check = this.getInput(sup[2]);
	    var handler = $(handler);

	    $.ajax({
	        data: {
	            1: table,
	            2: col,
	            3: check
	        },
	        success: e => {
	            if (e == 'ok') {
	                handler.html('')
	            } else {
	                handler.html(err)
	            }
	        }
	    });
	}*/

	connect(form, info, msg = {}, load = '', r = '') {
		// stop();
		var info = $(info);
		var ppt = {
			data: $(form).serialize(),
			beforeSend: () => {
				info.html(load + 'Connecting to the server...');
			},
			success: e => {
				alert(e);
				if (e == 'ok') {
					info.html(msg.ok);
					this.redirect(r);
				} else {
					if (msg.error) {
						info.html(msg.error);
					} else {
						if (msg.error) {
							info.html(msg.error);
						} else {
							info.html(e)
						}
					}
				}
			}
		}
		$.ajax(ppt);
	}
}
var v = new Validate();

try {
	v
} catch (e) {
	// statements
	$("body").html(e)
}