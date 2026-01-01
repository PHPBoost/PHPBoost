# IF C_DISPLAY #
	<nav id="cssmenu-module-{ID}" class="cssmenu cssmenu-right cssmenu-actionslinks">
		<ul class="level-0 hidden">
			# START element #
				# INCLUDE element.ELEMENT #
			# END element #
		</ul>
	</nav>
	<script>
		jQuery("#cssmenu-module-${escape(ID)}").menumaker({
			title: ${escapejs(LangLoader::get_message('menu.tree.links', 'menu-lang'))} + " {MODULE_NAME}",
			format: "multitoggle",
			actionslinks: true,
			breakpoint: 768
		});
		jQuery(document).ready(function() {
			jQuery("#cssmenu-module-${escape(ID)} ul").removeClass('hidden');
		});
	</script>
# ENDIF #
