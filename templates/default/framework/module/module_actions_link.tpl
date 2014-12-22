<li # IF C_HAS_SUB_LINK #class="extend"# ENDIF #><a href="{U_LINK}">{NAME}</a>
	# IF C_HAS_SUB_LINK #
	<ul>
		# START element #
			# INCLUDE element.ELEMENT #
		# END element #
	</ul>
	# ENDIF #
</li>