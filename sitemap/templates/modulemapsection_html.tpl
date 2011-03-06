		# IF C_SECTION_NAME_IS_STRING #
		{SECTION_NAME} {L_LEVEL} {DEPTH}
		# ENDIF #
		
		# IF C_SECTION_NAME_IS_LINK #
		{LINK_CODE}
		# ENDIF #
		<ul class="bb_ul">
		# START children #
		<li>{children.CHILD_CODE}</li>
		# END children #
		</ul>