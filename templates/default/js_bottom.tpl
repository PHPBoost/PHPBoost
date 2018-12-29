<script src="{PATH_TO_ROOT}/kernel/lib/js/lightcase/lightcase.js"></script>

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
		jQuery('a[rel^=lightbox]').attr('data-rel', 'lightcase:collection');
		jQuery('a[data-lightbox^=formatter]').attr('data-rel', 'lightcase:collection');
		jQuery('a[data-rel^=lightcase]').lightcase({
			labels : {
				'errorMessage'    : ${escapejs(LangLoader::get_message('element.unexist', 'status-messages-common'))},
				'sequenceInfo.of' : ' ' + ${escapejs(TextHelper::lcfirst(LangLoader::get_message('of', 'common')))} + ' ',
				'close'           : ${escapejs(LangLoader::get_message('close', 'main'))},
				'navigator.prev'  : ${escapejs(LangLoader::get_message('previous', 'common'))},
				'navigator.next'  : ${escapejs(LangLoader::get_message('next', 'common'))},
				'navigator.play'  : ${escapejs(LangLoader::get_message('play', 'common'))},
				'navigator.pause' : ${escapejs(LangLoader::get_message('pause', 'common'))}
			},
			maxHeight: window.innerHeight,
			maxWidth: window.innerWidth,
			shrinkFactor: 0.85
		});
	});

	jQuery('#table').basictable();
	jQuery('#table2').basictable();
	jQuery('#table3').basictable();
	jQuery('#table4').basictable();
	jQuery('#table5').basictable();

	jQuery(function() {
		jQuery(".lined textarea").linedtextarea();
	});
-->
</script>

# IF C_COOKIEBAR_ENABLED #
<script src="{PATH_TO_ROOT}/user/templates/js/cookiebar.js"></script>
# ENDIF #
