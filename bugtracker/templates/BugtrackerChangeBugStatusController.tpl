# INCLUDE MSG #
# INCLUDE FORM #
<script>
<!--
	function BugtrackerStatusChangedValidator(message, bug_id, bug_status)
	{
		var field = HTMLForms.getField('status');
		if (field)
		{
			var value = field.getValue();
			var error = '';
			new Ajax.Request(
				'${relative_url(BugtrackerUrlBuilder::check_status_changed())}',
				{
					method: 'post',
					asynchronous: false,
					parameters: {id : bug_id, status : value, old_status : bug_status},
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