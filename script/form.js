/*
 * form plugin script
 */

// init form plugin script
function form_init() {

	update();

	// add events
	// bind event to input changes
	jQuery('.form_input')
		.keyup(function (e) {
			update();
		});

	// bind event to selections
	jQuery('.form_select,.form_checkbox,.form_radio')
		.change(function (e) {
			update();
		});

}


// update mandatory and submit buttons, hide
function update() {

	update_content();

	var nodes = jQuery("[mandatory]");
	var obj;

	// iterate nodes
	jQuery.each(nodes, function (k, v) {

		obj = jQuery(v);

		switch (v.nodeName) {

			case "SELECT":
				update_select(obj);
				break;

			case "INPUT":
				update_input(obj);
				break;
		}
	});

	update_hide();
	form_check_submit();
}


// update input fields
function update_input(obj) {

	var type = jQuery(obj).attr("type");
	var val = jQuery(obj).val();
	var check = jQuery(obj).attr("check");

	switch (type) {

		case "text":
			update_input_text(obj, val, check);
			break;

		case "checkbox":
			update_input_checkbox(obj, val, check);
			break;

		case "radio":
			update_input_radio(obj, val, check);
			break;
	}
}

// update input type="text"
function update_input_text(obj, val, check) {
	
	var check_ary;
	var reg;
	
	if (check) {

		check_ary = check.split(":");

		// set valid on check function
		switch (check_ary[0]) {

			// minimal character count
			case "count":

				if (val.length >= check_ary[1]) {
					jQuery(obj)
						.removeClass("form_mandatory")
						.addClass("form_mand_ok");
				}

				else {
					jQuery(obj)
						.removeClass("form_mand_ok")
						.addClass("form_mandatory");
				}
				break;


			// regex match
			case "regex":

				reg = val.match(check_ary[1]);						

				if (reg != null) {
					jQuery(obj)
						.removeClass("form_mandatory")
						.addClass("form_mand_ok");
				}

				else {
					jQuery(obj)
						.removeClass("form_mand_ok")
						.addClass("form_mandatory");
				}
				break;
		}
	}
}

// update input type="checkbox"
function update_input_checkbox(obj, val, check) {

	// check mandatory
	if (obj.attr("mandatory")) {

		if (obj.attr('checked') !== undefined) {

			jQuery(obj)
				.removeClass("form_mandatory")
				.addClass("form_mand_ok");
		}

		else {

			jQuery(obj)
				.removeClass("form_mand_ok")
				.addClass("form_mandatory");
		}
	}
}


// update input type="radio"
function update_input_radio(obj, val, check) {

}


// update content
function update_content() {

	var nodes = jQuery("[ajax]");
	var obj;

	// iterate nodes
	jQuery.each(nodes, function (k, v) {

		obj = jQuery(v);

		switch (v.nodeName) {

			case "SELECT":
				update_sel_content(obj);
				break;

			case "INPUT":
				break;
		}
	});
}


// update select content via ajax
function update_sel_content(obj) {

	var ajax = jQuery(obj).attr("ajax"); // ajax api string
	var ajaxvalue; // ajax value field name
	var ajaxVal = (obj.attr("ajaxVal") ? obj.attr("ajaxVal") : false); // ajax variable values
	var update = false;
	var variables;
	var val;

	// dynamic update parameter found
	if (ajax) {
		
		// find variables format $varname
		variables = ajax.match(/\$([^\@\,\^]+)/gm);

		// variables found
		if (variables.length > 0) {

			// iterate values
			jQuery.each(variables, function (k, v) {
				
				// get form field value by variable name
				val = jQuery("[name='_form_" + v.substring(1) + "']").val();

				// value found
				if (val) {

					// check for new values
					if (ajaxVal) {
						
						// split multiple values
						ajaxVal = ajaxVal.split("|");
						
						// value has changed
						if (val != ajaxVal[k]) {
							update = true;
						}
					}

					// update ajax attribute string
					ajax = ajax.replace(v, val);

					// add value
					if (obj.attr("ajaxVal")) {
						obj.attr("ajaxVal", obj.attr("ajaxVal") + "|" + val);
					}

					// set value
					else {
						obj.attr("ajaxVal", val);
					}
				}
			});


			// no stored values or values changed
			// fetch values via ajax
			if (!ajaxVal || update) {


				// make ajax call
				jQuery.ajax({
					"url": "?source=" + ajax,
					"dataType": "json",
					"success": function(result) {

						// add options to select
						if (result != "") {

							// remove options
							obj.empty();
							obj.removeAttr("disabled");
							
							// add empty first option
							obj.append("<option></option>");

							ajaxvalue = obj.attr("ajaxvalue");

							jQuery.each(result, function () {
								obj.append("<option value=\"" + this[ajaxvalue] + "\">" + this.name + "</option>");
							})
						}

						// no data > disable
						else {
							// remove options
							obj.empty();
							obj.removeAttr("ajaxVal");
							obj.attr("disabled", "disabled");
						}

						update_select(obj);
					}
				});
			}
		}
	}
}


// update select fields
function update_select(obj) {

	// select = jQuery('select[name="filmblock"]').attr("fields");
	var sel = obj.children('option:selected');

	// has children
	if (sel.length) {

		if (sel[0].value) {
			jQuery(obj)
				.removeClass("form_mandatory")
				.addClass("form_mand_ok");
		}

		else {
			jQuery(obj)
				.removeClass("form_mand_ok")
				.addClass("form_mandatory");
		}
	}
}


// update hidden blocks
function update_hide() {

	var hides = jQuery('[hide]');

	// iterate tags with hide attributes
	jQuery.each(hides, function (k, v) {
		set_hide(jQuery(v).attr("hide"));
	})
}


// check if area with name has all mandatory data
function set_hide(name) {

	var source = jQuery("[hide='"+name+"']");
	var nodes = jQuery("[cond='"+name+"'][mandatory]");

	if (nodes.length == jQuery(".form_mand_ok[cond='"+name+"']").length) {
		jQuery(source).show();
	}

	else {
		jQuery(source).hide();
	}
}


// insert data into form
function form_insert_data(fields, values) {

	var fields = fields.split("|");
	var values = values.split("|");

	// iterate fields
	jQuery.each(values, function (idx) {

		jQuery('*[name="'+fields[idx]+'"]')
			.attr("value", values[idx])
			.removeClass("form_mandatory")
			.addClass("form_mand_ok");
	})
}


function form_check_submit() {

	// check all mandatory
	// enable submit
	if(jQuery('.form_mandatory:visible').length == 0) {
		jQuery('input[type="submit"]')
			.removeAttr("disabled","disabled");
	}

	// disable submit
	else {
		jQuery('input[type="submit"]')
			.attr("disabled","disabled");
	}
	
}