/* #### Utils #### */
function displayFormFieldOnsubmitValidatorMessage(message)
{
	alert(message);
}
function displayFormFieldOnblurValidatorMessage(field_id, message)
{
	document.getElementById('onblurContainerResponse' + field_id).innerHTML = 
		'<img src="' + PATH_TO_ROOT + '/templates/' + THEME + '/images/forbidden_mini.png" alt="" class="valign_middle" />';
	document.getElementById('onblurMesssageResponse' + field_id).innerHTML = message;
}
function clearFormFieldOnblurValidatorMessage(field_id)
{
	document.getElementById('onblurContainerResponse' + field_id).innerHTML = '';
	document.getElementById('onblurMesssageResponse' + field_id).innerHTML = '';
}

function integerIntervalValidator(value, lbound, rbound)
{
	value = parseInt(value);
	if (isNaN(value) || value < lbound || value > rbound)
	{
		return false;
	}
	return true;
}

/* #### Onsubmit validator #### */
/**
 * @desc validate not empty field
 * @return bool  
 */
function notEmptyFormFieldOnsubmitValidator(field_id, message)
{
	if (document.getElementById(field_id))
	{
		if (document.getElementById(field_id).value == '')
		{
			displayFormFieldOnsubmitValidatorMessage(message);
			return false;
		}
	}
	return true;
}
function integerIntervalFormFieldOnsubmitValidator(field_id, lbound, rbound, message)
{
	if (document.getElementById(field_id))
	{
		if (!integerIntervalValidator(document.getElementById(field_id).value, lbound, rbound))
		{
			displayFormFieldOnsubmitValidatorMessage(message);
			return false;
		}
	}
	return true;
}


/* #### Onblur validator #### */
function regexFormFieldOnblurValidator(field_id, regexPattern, options, message)
{
	if (document.getElementById(field_id))
	{
		value = document.getElementById(field_id).value;
		regex = new RegExp(regexPattern, options);
		if (!regex.test(value))
		{
			displayFormFieldOnblurValidatorMessage(field_id, message);
			return false;
		}
	}
	clearFormFieldOnblurValidatorMessage(field_id);
	return true;
}

function integerIntervalFormFieldOnblurValidator(field_id, lbound, rbound, message)
{
	if (document.getElementById(field_id))
	{
		if (!integerIntervalValidator(document.getElementById(field_id).value, lbound, rbound))
		{
			displayFormFieldOnblurValidatorMessage(field_id, message);
			return false;
		}
	}
	clearFormFieldOnblurValidatorMessage(field_id);
	return true;
}

