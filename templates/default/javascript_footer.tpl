		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/global.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/lightbox/lightbox.js"></script>
		<!--[if lt IE 9]>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/html5shiv/html5shiv.js"></script>
		<![endif]-->
		<script type="text/javascript">
		<!-- 
			$$('[data-confirmation]').each(function(a) {
				var data_confirmation = a.readAttribute('data-confirmation');
				
				if (data_confirmation == 'delete-element')
					var message = ${escapejs(LangLoader::get_message('confirm.delete', 'errors-common'))};
				else
					var message = data_confirmation;

				a.onclick = function () { return confirm(message); }
			}); 
		-->
		</script>