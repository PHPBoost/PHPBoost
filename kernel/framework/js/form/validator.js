/* #### Utils #### */
function displayFormFieldOnsubmitValidatorMessage(message)
{
	alert(message);
}
function displayFormFieldOnblurValidatorMessage(message)
{
	alert(message);
}

function integerIntervalValidator(value, lboundary, rboundary)
{
	value = parseInt(value);
	if (isNaN(value) || value < lboundary || value > rboundary)
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
function integerIntervalFormFieldOnsubmitValidator(field_id, lboundary, rboundary, message)
{
	if (document.getElementById(field_id))
	{
		if (!integerIntervalValidator(document.getElementById(field_id).value, lboundary, rboundary))
		{
			displayFormFieldOnsubmitValidatorMessage(message);
			return false;
		}
	}
	return true;
}


/* #### Onblur validator #### */
function regexFormFieldOnblurValidator(field_id, regexPattern, message)
{
	if (document.getElementById(field_id))
	{
		value = document.getElementById(field_id).value;
		regex = new RegExp(regexPattern, "i");
		if (!regex.test(value))
		{
			displayFormFieldOnblurValidatorMessage(message);
			return false;
		}
	}
	return true;
}

function integerIntervalFormFieldOnblurValidator(field_id, lboundary, rboundary, message)
{
	if (document.getElementById(field_id))
	{
		if (!integerIntervalValidator(document.getElementById(field_id).value, lboundary, rboundary))
		{
			displayFormFieldOnblurValidatorMessage(message);
			return false;
		}
	}
	return true;
}

