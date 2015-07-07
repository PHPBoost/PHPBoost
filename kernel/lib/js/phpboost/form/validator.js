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

function lengthIntervalValidator(value, lbound, rbound)
{
	var value = value.length;
	if (value < lbound || value > rbound)
	{
		return false;
	}
	return true;
}

function nonEmptyFormFieldValidator(field_id, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (value == null || value == '' || value == false)
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

function lengthFormFieldValidator(field_id, lbound, rbound, message)
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
			async : false,
			data: {login : value, user_id : user_id, token : TOKEN},
			success: function(returnData){
				if (returnData == 1)
				{
					error = message;
				}
				else
				{
					error = '';
				}
			},
			error: function(e){
				alert(e);
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
			async : false,
			data: {mail : value, user_id : user_id, token : TOKEN},
			success: function(returnData){
				if (returnData == 1)
				{
					error = message;
				}
				else
				{
					error = '';
				}
			},
			error: function(e){
				alert(e);
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
				async : false,
				data: {display_name : value, token : TOKEN},
				success: function(returnData){
					if (returnData != 1)
					{
						error = message;
					}
					else
					{
						error = '';
					}
				},
				error: function(e){
					alert(e);
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