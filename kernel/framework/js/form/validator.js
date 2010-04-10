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
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
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
	var field = HTMLForms.getField(field_id);
	if (field)
	{
		var value = field.getValue();
		if (!integerIntervalValidator(value, lbound, rbound))
		{
			return message;
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
		if (!lengthIntervalValidator(value, lbound, rbound))
		{
			return message;
		}
	}
	return '';
}

/* #### Multiple Field Constraints #### */
function equalityFormFieldValidator(object_field, field_id, field_id_equality, message)
{
	// TODO refactor, i think the object_field attribute is useless
	// TODO refactor use HTMLField.getValue() to obtain fields values.
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
