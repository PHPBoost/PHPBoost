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
		title: "{TITLE} {ID}",
		format: "multitoggle",
		breakpoint: 980,
		actionlink: true
	});
</script>
# ENDIF #