
function form_init() {



	jQuery('[hide]')
		.hide();


	// disable submit on init
	jQuery('input[type="submit"]')
		.attr("disabled","disabled");
	

	// hide optional fields
	jQuery('*[optional]')
		.hide();


	jQuery("*[mandatory]")
		.addClass("form_mandatory");


	// listen to form changes
	jQuery('.form_cell')

		.change(function(e) {

			// set mandatory field green
			if(jQuery(this).attr("mandatory")) {
				jQuery(this)
					.removeClass("form_mandatory")
					.addClass("form_mand_ok")
					.attr("done");

				jQuery(this)
					.siblings()
						.removeClass("form_mandatory")
						.addClass("form_mand_ok");
			}

			// enable optional fields
			radio_val = jQuery(this).find('input[type="radio"]:checked').val();
			formid = jQuery(this).attr('formid');
			optional = radio_val + '@' + formid;


			// enable optional fields
			if(jQuery('*[optional="' + optional + '"]').length) {
				jQuery('*[optional="' + optional + '"]').show();
			}

			// disable optional fields
			else {
				jQuery('*[optional$="@' + formid + '"]').hide();
			}


			form_check_submit();
	});


	// listen to input change
	jQuery('.form_input')

		.keyup(function (e) {

			type = jQuery(this).attr("name");
			val = jQuery(this).val();
			check = jQuery(this).attr("check");


			if (check) {

				check_ary = check.split(":");

				// set valid on check function
				switch (check_ary[0]) {

					// minimal character count
					case "count":

						if (val.length >= check_ary[1]) {
							jQuery(this)
								.removeClass("form_mandatory")
								.addClass("form_mand_ok");
						}

						else {
							jQuery(this)
								.removeClass("form_mand_ok")
								.addClass("form_mandatory");
						}
						break;


					// regex match
					case "regex":

						reg = val.match(check_ary[1]);						

						if (reg != null) {
							jQuery(this)
								.removeClass("form_mandatory")
								.addClass("form_mand_ok");
						}

						else {
							jQuery(this)
								.removeClass("form_mand_ok")
								.addClass("form_mandatory");
						}
						break;
				}
			}
			form_check_submit();
		});


	// bind event on film list selection
	jQuery('select')

		.change(function (e) {

			// select = jQuery('select[name="filmblock"]').attr("fields");
			sel = jQuery('select option:selected');

console.log(sel);

			// // add group field data to form
			// form_insert_data(select, sel);

			update_hide();
		});


	update_hide();
}


function update_hide() {

	var hides = jQuery('[hide]');

	jQuery.each(hides, function (k, v) {
		has_data(jQuery(v).attr("hide"));
	})
}


// check if area with name has all data
function has_data(name) {

}


function get_data(name) {

}


// insert data into form
function form_insert_data(fields, values) {

	fields = fields.split("|");
	values = values.split("|");

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