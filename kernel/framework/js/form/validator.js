/* #### Utils #### */
function displayFormFieldOnsubmitValidatorMessage(field_id)
{
	alert('test' + field_id);
}
function displayFormFieldOnblurValidatorMessage(field_id)
{
	alert('test' + field_id);
}


/* #### Onsubmit validator #### */
/**
 * @desc validate not empty field
 * @return bool  
 */
function notEmptyFormFieldOnsubmitValidator(field_id, message)
{
	if (document.getElementById(field_id).value == '')
	{
		displayFormFieldOnsubmitValidatorMessage(message);
	}		
}


/* #### Onblur validator #### */
function regexFormFieldOnblurValidator(field_id, regexPattern)
{
	value = document.getElementById(field_id).value;
	
	regex = new RegExp(regexPattern, "i");
	if (!regex.test(value))
	{
		displayFormFieldOnblurValidatorMessage(field_id);
	}
}

function integerIntervalFormFieldOnblurValidator(field_id, lboundary, rboundary)
{
	value = document.getElementById(field_id).value;
	if (value < lboundary || value > rboundary)
	{
		displayFormFieldOnblurValidatorMessage(field_id);
	}
}

