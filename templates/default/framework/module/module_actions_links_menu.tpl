# IF C_DISPLAY #
<menu id="cssmenu-{ID}" class="cssmenu cssmenu-right cssmenu-treelinks">
	<ul>
		# START element #
			# INCLUDE element.ELEMENT #
		# END element #
	</ul>
</menu>
<script type="text/javascript">
    $("#cssmenu-${escape(ID)}").menumaker({
        title: "{TITLE}",
        format: "multitoggle",
        breakpoint: 980,
        actionlink: true
    });
</script>
# ENDIF #