
function form_mail_init() {


	// disable submit on init
	jQuery('input[type="submit"]')
		.attr("disabled","disabled");
	

	// hide optional fields
	jQuery('*[optional]')
		.hide();


	jQuery("*[mandatory]")
		.addClass("form_mail_mandatory");


	// listen to form changes
	jQuery('.form_mail_cell')

		.change(function(e) {

			// set mandatory field green
			if(jQuery(this).attr("mandatory")) {
				jQuery(this)
					.removeClass("form_mail_mandatory")
					.addClass("form_mail_mand_ok")
					.attr("done");

				jQuery(this)
					.siblings()
						.removeClass("form_mail_mandatory")
						.addClass("form_mail_mand_ok");
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


			form_mail_check_submit();
	});


	// listen to input change
	jQuery('.form_mail_input')

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
								.removeClass("form_mail_mandatory")
								.addClass("form_mail_mand_ok");
						}

						else {
							jQuery(this)
								.removeClass("form_mail_mand_ok")
								.addClass("form_mail_mandatory");
						}
						break;


					// regex match
					case "regex":

						reg = val.match(check_ary[1]);						

						if (reg != null) {
							jQuery(this)
								.removeClass("form_mail_mandatory")
								.addClass("form_mail_mand_ok");
						}

						else {
							jQuery(this)
								.removeClass("form_mail_mand_ok")
								.addClass("form_mail_mandatory");
						}
						break;
				}
			}
			form_mail_check_submit();
		});


	// hide film lists
	form_mail_hide_lists();

	// bind event on film list selection
	jQuery('select[name="filmblock"]')

		.change(function (e) {

			select = jQuery('select[name="filmblock"]').attr("fields");
			sel = jQuery('select[name="filmblock"] option:selected').text();


			// add group field data to form
			form_mail_insert_data(select, sel);

			form_mail_show_list(sel);
		});
}


function form_mail_hide_lists() {

	if (jQuery('.selector').length) {
		jQuery('[name^="fm_filmlist"]').hide();
		jQuery('#form_mail_form').hide();
	}
}


function form_mail_show_list(n) {

	// hide form
	form_mail_hide_lists();

	// select film list
	jQuery('[name="fm_filmlist_' + n + '"]')
		.show()
		.change(function (e) {

			data = jQuery(this);

			form_mail_insert_data(data.find("select").attr("fields"), data.find("select option:selected").attr("value"));

			jQuery('#form_mail_form').show();
		});
}


// insert data into form
function form_mail_insert_data(fields, values) {

	fields = fields.split("|");
	values = values.split("|");

	// iterate fields
	jQuery.each(values, function (idx) {

		jQuery('*[name="'+fields[idx]+'"]')
			.attr("value", values[idx])
			.removeClass("form_mail_mandatory")
			.addClass("form_mail_mand_ok");

	})
}


function form_mail_check_submit() {

	// check all mandatory
	// enable submit
	if(jQuery('.form_mail_mandatory:visible').length == 0) {
		jQuery('input[type="submit"]')
			.removeAttr("disabled","disabled");
	}

	// disable submit
	else {
		jQuery('input[type="submit"]')
			.attr("disabled","disabled");
	}
	
}