		<script src="{PATH_TO_ROOT}/kernel/lib/js/bottom.js"></script>
		<!--[if lt IE 9]>
		<script async src="{PATH_TO_ROOT}/kernel/lib/js/html5shiv/html5shiv.js"></script>
		<![endif]-->
		<script>
		<!-- 
			jQuery('[data-confirmation]').each(function() {
				data_confirmation = jQuery(this).attr('data-confirmation');

				if (data_confirmation == 'delete-element')
					var message = ${escapejs(LangLoader::get_message('confirm.delete', 'status-messages-common'))};
				else
					var message = data_confirmation;

				this.onclick = function () { return confirm(message); }
			});
			
			jQuery(document).ready(function() {
				jQuery('a[data-lightbox^=formatter]').attr('data-rel', 'lightcase:collection');
				jQuery('a[data-rel^=lightcase]').lightcase();
			});
		-->
		</script>