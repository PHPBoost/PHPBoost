# IF C_DISPLAY #
<menu id="cssmenu-{ID}" class="cssmenu cssmenu-right cssmenu-actionslinks">
	<ul>
		# START element #
			# INCLUDE element.ELEMENT #
		# END element #
	</ul>
</menu>
<script>
	jQuery("#cssmenu-${escape(ID)}").menumaker({
		title: "${LangLoader::get_message('content.menus.actions', 'admin-links-common')} {MODULE_NAME}",
		format: "multitoggle",
		breakpoint: 980,
		actionlink: true
	});
</script>
# ENDIF #