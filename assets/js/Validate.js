class Validate {
    constructor() {
        this.success = false;
        this.pass = false;
        this.error = '';
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
    
    captcha (str) {
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
        var f = $(field).get(0).files.length;

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

    dError(val, t = 'b') {
        switch (t) {
            case 'a':
                alert(JSON.stringify(val));
                break;
            case 'b':
                try {
                    val
                } catch (e) {
                    alert(e);
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
            success: e => {alert(e);
                if (e == 'ok') {
                    info.html(msg.ok);
                    this.redirect(r);
                } else {
                    if (msg.error) {
                        info.html(msg.error);
                    } else {
                        if(msg.error) {
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
} catch(e) {
    // statements
    $("body").html(e)
}