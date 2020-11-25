<select name="${escape(NAME)}" id="${escape(HTML_ID)}" class="# IF C_SELECT_TO_LIST #select-to-list # ENDIF #${escape(CSS_CLASS)}" # IF C_DISABLED # disabled="disabled" # ENDIF # >
	# START options #
		# INCLUDE options.OPTION #
	# END options #
</select>
