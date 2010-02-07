/* #### Affichage #### */
function displayFormFieldOnsubmitValidatorMessage(message)
{
	message = message.replace(/&quot;/g, '"');
	message = message.replace(/&amp;/g,'&');
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
		$('onblurContainerResponse' + field_id).innerHTML = 
			'<img src="' + PATH_TO_ROOT + '/templates/' + THEME + '/images/processed_mini.png" alt="" class="valign_middle" />';
		Effect.Appear('onblurContainerResponse' + field_id, { duration: 0.2 });
		
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


/* #### Onblur validator #### */
function nonEmptyFormFieldValidator(field_id, message)
{
	if ($(field_id))
	{
		if ($F(field_id) == '')
		{
			return message;
		}
	}
	return '';
}

function regexFormFieldValidator(field_id, regexPattern, options, message)
{
	if ($(field_id))
	{
		value = $F(field_id);
		regex = new RegExp(regexPattern, options);
		if (!regex.test(value))
		{
			return message;
		}
	}
	return '';
}

function integerIntervalFormFieldValidator(field_id, lbound, rbound, message)
{
	if ($(field_id))
	{
		if (!integerIntervalValidator($F(field_id), lbound, rbound))
		{
			return message;
		}
	}
	return '';
}

function lengthFormFieldValidator(field_id, lbound, rbound, message)
{
	if ($(field_id))
	{
		if (!lengthIntervalValidator($F(field_id), lbound, rbound))
		{
			return message;
		}
	}
	return '';
}

/* #### Multiple Field Constraints #### */
function equalityFormFieldValidator(object_field, field_id, field_id_equality, message)
{
	var answer = '';
	if ($(field_id) && $(field_id_equality))
	{
		if (($F(field_id) != '' && $F(field_id_equality) != '') && $F(field_id) != $F(field_id_equality))
		{
			answer = message;
		}
	}
	if (object_field.id == field_id_equality)
	{
		if ($F(field_id) != '')
		{
			return Array(field_id, answer);
		}
	}
	else
	{
		if ($F(field_id_equality) != '')
		{
			return Array(field_id_equality, answer);
		}
	}
	return Array('', '');
}


/* #### Validation functions #### */
function formFieldConstraintsOnblurValidation(othis, constraints)
{
	var message = '';
	var field_id = '';

	has_constraint = constraints.length;
	for (var i = 0; i < has_constraint; i++)
	{
		message = constraints[i];
		if (message != '')
		{
			break;
		}
	}
	if (message instanceof Array) //Multiple constraints
	{
		field_id = message[0];
		message = message[1];
		if (message == '') //All validations checked.
		{
			clearFormFieldOnblurValidatorMessage(othis.id);
			clearFormFieldOnblurValidatorMessage(field_id);
		}
		else
		{
			displayFormFieldOnblurValidatorMessage(field_id, message);
		}
	}
	else
	{
		if (message == '') //All validations checked.
		{
			clearFormFieldOnblurValidatorMessage(othis.id);
		}
		else
		{
			displayFormFieldOnblurValidatorMessage(othis.id, message);
		}
	}
}

function formFieldConstraintsOnsubmitValidation(constraints)
{
	has_constraint = constraints.length;
	for (var i = 0; i < has_constraint; i++)
	{
		if (constraints[i] instanceof Array) //Multiple constraints
		{
			if (constraints[i][1] != '')
			{
				displayFormFieldOnsubmitValidatorMessage(constraints[i][1]);
				return false;
			}
		}
		else
		{
			if (constraints[i] != '')
			{
				displayFormFieldOnsubmitValidatorMessage(constraints[i]);
				return false;
			}
		}
	}
	return true;
}
