<li# IF C_HAS_SUB_LINK # class="has-sub"# ENDIF #>
	<a href="{U_LINK}" class="cssmenu-title offload# IF C_HAS_CSS_CLASS # {CSS_CLASS}# ENDIF #"><span>{NAME}</span></a>
	# IF C_HAS_SUB_LINK #
		<ul class="level-1">
			# START element #
				# INCLUDE element.ELEMENT #
			# END element #
		</ul>
	# ENDIF #
</li>
