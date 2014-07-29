		<script src="{PATH_TO_ROOT}/kernel/lib/js/bottom.js"></script>
		<!--[if lt IE 9]>
		<script async src="{PATH_TO_ROOT}/kernel/lib/js/html5shiv/html5shiv.js"></script>
		<![endif]-->
		<script>
		<!-- 
			$$('[data-confirmation]').each(function(a) {
				var data_confirmation = a.readAttribute('data-confirmation');
				
				if (data_confirmation == 'delete-element')
					var message = ${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))};
				else
					var message = data_confirmation;

				a.onclick = function () { return confirm(message); }
			}); 
		-->
		</script>