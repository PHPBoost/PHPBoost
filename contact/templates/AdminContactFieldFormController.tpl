# INCLUDE FORM #
<script>
<!--
	jQuery(document).ready(function() {
		{JS_EVENT_SELECT_TYPE}
	});
	
	function ContactFieldExistValidator(message, field_id)
	{
		var field = HTMLForms.getField('name');
		if (field)
		{
			var value = field.getValue();
			var error = '';

			jQuery.ajax({
				url: '${relative_url(ContactUrlBuilder::check_field_name())}',
				type: "post",
				async : false,
				data: {id : field_id, name : value},
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
-->
</script>