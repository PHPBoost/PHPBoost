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

			jQuery.ajax({
				url: '${relative_url(BugtrackerUrlBuilder::check_status_changed())}',
				type: "post",
				async: false,
				data: {id : bug_id, status : value, old_status : bug_status, token : '{TOKEN}'},
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