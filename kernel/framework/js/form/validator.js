/* #### Affichage #### */
function displayFormFieldOnsubmitValidatorMessage(message)
{
	alert(message);
}
function displayFormFieldOnblurValidatorMessage(field_id, message)
{
	if ($('onblurContainerResponse' + field_id) && $('onblurMesssageResponse' + field_id))
	{
		$('onblurContainerResponse' + field_id).innerHTML = 
		'<img src="' + PATH_TO_ROOT + '/templates/' + THEME + '/images/forbidden_mini.png" alt="" class="valign_middle" />';
		$('onblurMesssageResponse' + field_id).innerHTML = message;
	
		Effect.Appear('onblurContainerResponse' + field_id, { duration: 0.5 });
		Effect.Appear('onblurMesssageResponse' + field_id, { duration: 0.5 });
	}
}
function clearFormFieldOnblurValidatorMessage(field_id)
{
	if ($('onblurContainerResponse' + field_id))
	{
		Effect.Fade('onblurContainerResponse' + field_id, { duration: 0.2 });
		Effect.Fade('onblurMesssageResponse' + field_id, { duration: 0.2 });
	}
}

/* #### Outils #### */
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

/* #### Onsubmit validator #### */
function regexFormFieldOnsubmitValidator(field_id, regexPattern, options, message)
{
	if ($(field_id))
	{
		value = $F(field_id);
		regex = new RegExp(regexPattern, options);
		if (!regex.test(value))
		{
			displayFormFieldOnsubmitValidatorMessage(message);
			return false;
		}
	}
	return true;
}

/**
 * @desc validate not empty field
 * @return bool  
 */
function notEmptyFormFieldOnsubmitValidator(field_id, message)
{
	if ($(field_id))
	{
		if ($F(field_id) == '')
		{
			displayFormFieldOnsubmitValidatorMessage(message);
			return false;
		}
	}
	return true;
}
function integerIntervalFormFieldOnsubmitValidator(field_id, lbound, rbound, message)
{
	if ($(field_id))
	{
		if (!integerIntervalValidator($F(field_id), lbound, rbound))
		{
			displayFormFieldOnsubmitValidatorMessage(message);
			return false;
		}
	}
	return true;
}
function lengthFormFieldOnsubmitValidator(field_id, lbound, rbound, message)
{
	if ($(field_id))
	{
		if (!lengthIntervalValidator($F(field_id), lbound, rbound))
		{
			displayFormFieldOnsubmitValidatorMessage(message);
			return false;
		}
	}
	return true;
}


/* #### Onblur validator #### */
function nonEmptyFormFieldOnblurValidator(field_id, message)
{
	if ($(field_id))
	{
		if ($F(field_id) == '')
		{
			displayFormFieldOnblurValidatorMessage(field_id, message);
			return false;
		}
	}
	clearFormFieldOnblurValidatorMessage(field_id);
	return true;
}

function regexFormFieldOnblurValidator(field_id, regexPattern, options, message)
{
	if ($(field_id))
	{
		value = $F(field_id);
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
	if ($(field_id))
	{
		if (!integerIntervalValidator($F(field_id), lbound, rbound))
		{
			displayFormFieldOnblurValidatorMessage(field_id, message);
			return false;
		}
	}
	clearFormFieldOnblurValidatorMessage(field_id);
	return true;
}

function lengthFormFieldOnblurValidator(field_id, lbound, rbound, message)
{
	if ($(field_id))
	{
		if (!lengthIntervalValidator($F(field_id), lbound, rbound))
		{
			displayFormFieldOnblurValidatorMessage(field_id, message);
			return false;
		}
	}
	clearFormFieldOnblurValidatorMessage(field_id);
	return true;
}



function equalityFormFieldOnblurValidator(field_id, field_id_equality, message)
{
	if ($(field_id) && $(field_id_equality))
	{
		if ($F(field_id) != $F(field_id_equality))
		{
			displayFormFieldOnblurValidatorMessage(field_id, message);
			return false;
		}
	}
	clearFormFieldOnblurValidatorMessage(field_id);
	return true;
}



