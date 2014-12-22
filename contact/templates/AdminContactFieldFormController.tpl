# INCLUDE FORM #
<script>
<!--
	Event.observe(window, 'load', function() {
		{JS_EVENT_SELECT_TYPE}
	});
	
	function ContactFieldExistValidator(message, field_id)
	{
		var field = HTMLForms.getField('name');
		if (field)
		{
			var value = field.getValue();
			var error = '';
			new Ajax.Request(
				'${relative_url(ContactUrlBuilder::check_field_name())}',
				{
					method: 'post',
					asynchronous: false,
					parameters: {id : field_id, name : value},
					onSuccess: function(transport) {
						if (transport.responseText == '1')
						{
							error = message;
						}
						else
						{
							error = '';
						}
					}
				}
			);
			return error;
		}
		return '';
	}
-->
</script>