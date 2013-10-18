		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/scriptaculous/scriptaculous.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/global.js"></script>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/lightbox/lightbox.js"></script>
		<!--[if lt IE 9]>
		<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/html5shiv/html5shiv.js"></script>
		<![endif]-->
		<script type="text/javascript">
		<!-- 
			$$('.delete:not([data-confirmation=false]').each(function(a) {
				var message = ${escapejs(LangLoader::get_message('confirm.delete', 'errors-common'))};
				var data_message = a.readAttribute('data-message');
				if (data_message != null)
					var message = data_message;
				a.onclick = function () { return confirm(message); }
			}); 
		-->
		</script>