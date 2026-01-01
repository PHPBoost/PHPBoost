# INCLUDE CONTENT #
<script>
	jQuery(document).ready(function() {
		{JS_EVENT_SELECT_TYPE}
	});

	function ContactFieldExistValidator(message, field_id)
	{
		var field = HTMLForms.getField('name');
		var result = '';

		if (field)
		{
			var value = field.getValue();

			jQuery.ajax({
				url: '${relative_url(ContactUrlBuilder::check_field_name())}',
				type: "post",
				dataType: "json",
				async: false,
				data: {'id' : field_id, 'name' : value, 'token' : '{TOKEN}'},
				success: function(returnData){
					if (returnData.result == 1)
						result = message;
				}
			});
		}
		return result;
	}
</script>
