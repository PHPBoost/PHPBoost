/* #### Constraints #### */
function integerIntervalValidator(value, lbound, rbound)
{
	var prev_value = value;
	var value = parseInt(value);
	if (value != prev_value || value < lbound || value > rbound)
	{
		return false;
	}
	return true;
}

function integerMinValidator(value, lbound)
{
	var prev_value = value;
	var value = parseInt(value);
	if (value != prev_value || value < lbound)
	{
		return false;
	}
	return true;
}

function integerMaxValidator(value, rbound)
{
	var prev_value = value;
	var value = parseInt(value);
	if (value != prev_value || value > rbound)
	{
		return false;
	}
	return true;
}

function lengthIntervalValidator(value, lbound, rbound)
{
	var value = value.length;
	if (value < lbound || value > rbound)
	{
		return false;
	}
	return true;
}

function lengthMinValidator(value, lbound)
{
	var value = value.length;
	if (value < lbound)
	{
		return false;
	}
	return true;
}

function lengthMaxValidator(value, rbound)
{
	var value = value.length;
	if (value > rbound)
	{
		return false;
	}
	return true;
}

function notEmptyFormFieldValidator(field_id, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		
		if (parseInt(value) != 0 && (value == null || value == '' || value == false))
		{
			return message;
		}
	}
	return '';
}

function regexFormFieldValidator(field_id, regexPattern, options, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			regex = new RegExp(regexPattern, options);
			if (!regex.test(value))
			{
				return message;
			}
		}
	}
	return '';
}

function integerIntervalFormFieldValidator(field_id, lbound, rbound, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			if (!integerIntervalValidator(value, lbound, rbound))
			{
				return message;
			}
		}
	}
	return '';
}

function integerMinFormFieldValidator(field_id, lbound, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			if (!integerMinValidator(value, lbound))
			{
				return message;
			}
		}
	}
	return '';
}

function integerMaxFormFieldValidator(field_id, rbound, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			if (!integerMaxValidator(value, rbound))
			{
				return message;
			}
		}
	}
	return '';
}

function lengthIntervalFormFieldValidator(field_id, lbound, rbound, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			if (!lengthIntervalValidator(value, lbound, rbound))
			{
				return message;
			}
		}
	}
	return '';
}

function lengthMinFormFieldValidator(field_id, lbound, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			if (!lengthMinValidator(value, lbound))
			{
				return message;
			}
		}
	}
	return '';
}

function lengthMaxFormFieldValidator(field_id, rbound, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value !== '')
		{
			if (!lengthMaxValidator(value, rbound))
			{
				return message;
			}
		}
	}
	return '';
}

function maxSizeFilePickerFormFieldValidator(field_id, max_size, message)
{
	var field = HTMLForms.getField(field_id);
	if (field && jQuery("#" + field.getHTMLId())[0].files[0])
	{
		var value = jQuery("#" + field.getHTMLId())[0].files[0].size;
		if (value !== '')
		{
			if (!integerMaxValidator(value, max_size))
			{
				return message;
			}
		}
	}
	return '';
}

function DisplayNameExistValidator(field_id, message, user_id)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		var error = '';

		jQuery.ajax({
			url: PATH_TO_ROOT + "/kernel/framework/ajax/user_xmlhttprequest.php",
			type: "post",
			async: false,
			data: {display_name : value, user_id : user_id, token : TOKEN},
			success: function(returnData){
				if (returnData == 1)
				{
					error = message;
				}
			}
		});
		return error;
	}
	return '';
}

function LoginExistValidator(field_id, message, user_id)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		var error = '';

		jQuery.ajax({
			url: PATH_TO_ROOT + "/kernel/framework/ajax/user_xmlhttprequest.php",
			type: "post",
			async: false,
			data: {login : value, user_id : user_id, token : TOKEN},
			success: function(returnData){
				if (returnData == 1)
				{
					error = message;
				}
			}
		});
		return error;
	}
	return '';
}

function MailExistValidator(field_id, message, user_id)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		var error = '';

		jQuery.ajax({
			url: PATH_TO_ROOT + "/kernel/framework/ajax/user_xmlhttprequest.php",
			type: "post",
			async: false,
			data: {mail : value, user_id : user_id, token : TOKEN},
			success: function(returnData){
				if (returnData == 1)
				{
					error = message;
				}
			}
		});
		return error;
	}
	return '';
}

function UserExistValidator(field_id, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		var error = '';
		if (value != '')
		{
			jQuery.ajax({
				url: PATH_TO_ROOT + "/kernel/framework/ajax/user_xmlhttprequest.php",
				type: "post",
				async: false,
				data: {display_name : value, token : TOKEN},
				success: function(returnData){
					if (returnData != 1)
					{
						error = message;
					}
				}
			});
		}
		return error;
	}
	return '';
}

function UrlExistsValidator(field_id, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		var error = '';
		if (value != '')
		{
			jQuery.ajax({
				url: PATH_TO_ROOT + "/kernel/framework/ajax/dispatcher.php?url=/url_validation/",
				type: "post",
				dataType: "json",
				async: false,
				data: {url_to_check : value, token : TOKEN},
				success: function(returnData){
					if (returnData.is_valid != 1)
					{
						error = message;
					}
				}
			});
		}
		return error;
	}
	return '';
}

/* #### Multiple Field Constraints #### */
function equalityFormFieldValidator(field_id, field_id2, message)
{
	var field1 = HTMLForms.getField(field_id);
	var field2 = HTMLForms.getField(field_id2);
	if (field1 && field2) {
		if (field1.getValue() != field2.getValue() && field1.getValue() !== '' && field2.getValue() !== '') {
			return message;
		}
	}
	return "";
}

function inequalityFormFieldValidator(field_id, field_id2, message)
{
	var field1 = HTMLForms.getField(field_id);
	var field2 = HTMLForms.getField(field_id2);
	if (field1 && field2) {
		if (field1.getValue() == field2.getValue() && field1.getValue() !== '' && field2.getValue() !== '') {
			return message;
		}
	}
	return "";
}

function inclusionFormFieldValidator(field_id, field_id2, message)
{
	var field1 = HTMLForms.getField(field_id);
	var field2 = HTMLForms.getField(field_id2);
	if (field1 && field2) {
		if (field1.getValue() == field2.getValue() && field1.getValue() !== '' && field2.getValue() !== '' && field2.getValue().toLowerCase().indexOf(field1.getValue().toLowerCase()) >= 0) {
			return message;
		}
	}
	return "";
}