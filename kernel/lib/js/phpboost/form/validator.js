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
	var error_message = '';
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var getInputsElements = $(field.formId).getInputs('', field.getHTMLId());
		if (getInputsElements.size() > 1)
		{
			var number_elements_not_complete = 0;
			getInputsElements.each(function(element) {
				if (element.type == 'checkbox' || element.type == 'radio')
				{
					number_elements_not_complete = number_elements_not_complete + 1;
				}
				else
				{
					if (element.value == null || element.value == '')
					{
						number_elements_not_complete = number_elements_not_complete + 1;
					}
				}
			});
			
			if (getInputsElements.size() <= number_elements_not_complete)
			{
				error_message = message;
			}
		}
		else
		{
			getInputsElements.each(function(element) {
				if (element.type == 'checkbox' || element.type == 'radio')
				{
					if (!element.checked)
					{
						error_message = message;
					}
				}
				else
				{
					if (element.value == null || element.value == '')
					{
						error_message = message;
					}
				}
			});
		}
		return error_message;
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

function LoginExistValidator(field_id, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		new Ajax.Request(
			PATH_TO_ROOT + "/member/register_xmlhttprequest.php",
			{
				method: 'post',
				parameters: {login : value, token : TOKEN},
				onSuccess: function(transport) {
					if (transport.responseText == '1')
					{
						field.displayErrorMessage(message);
					}
					else
					{
						field.displaySuccessMessage();
					}
				},
			}
		);
	}
	return '';
}

function MailExistValidator(field_id, message)
{
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		new Ajax.Request(
			PATH_TO_ROOT + "/member/register_xmlhttprequest.php",
			{
				method: 'post',
				parameters: {mail : value, token : TOKEN},
				onSuccess: function(transport) {
					if (transport.responseText == '1')
					{
						field.displayErrorMessage(message);
					}
					else
					{
						field.displaySuccessMessage();
					}
				},
			}
		);
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