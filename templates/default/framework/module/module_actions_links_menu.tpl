# IF C_DISPLAY #
<menu id="cssmenu-module-{ID}" class="cssmenu cssmenu-right cssmenu-actionslinks">
	<ul class="hidden">
		# START element #
			# INCLUDE element.ELEMENT #
		# END element #
	</ul>
</menu>
<script>
	jQuery("#cssmenu-module-${escape(ID)}").menumaker({
		title: "${LangLoader::get_message('content.menus.actions', 'admin-links-common')} {MODULE_NAME}",
		format: "multitoggle",
		breakpoint: 768,
		actionslinks: true
	});
	jQuery(document).ready(function() {
		jQuery("#cssmenu-module-${escape(ID)} ul").removeClass('hidden');
	});
</script>
# ENDIF #