# IF C_DISPLAY #
<li # IF C_HAS_SUB_LINK # class="has-sub"# ENDIF #>
	<a href="{U_LINK}" class="cssmenu-title"><img src="{IMG}" alt="{NAME}"/> {NAME}</a>
	# IF C_HAS_SUB_LINK #
	<ul class="level-2">
		# START element #
			# INCLUDE element.ELEMENT #
		# END element #
	</ul>
	# ENDIF #
</li>
# ENDIF #
