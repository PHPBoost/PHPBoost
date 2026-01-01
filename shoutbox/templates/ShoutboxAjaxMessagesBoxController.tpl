# START messages #
	<div id="shoutbox-message-{messages.ID}" class="shoutbox-message">
		# IF messages.C_DELETE #
			<a href="#" onclick="shoutbox_delete_message({messages.ID});return false;" aria-label="{@common.delete}" id="delete_{messages.ID}"><i class="far fa-trash-alt error" aria-hidden="true"></i></a>
		# ENDIF #
		# IF messages.C_AUTHOR_EXISTS #
			<a itemprop="author" href="{messages.U_AUTHOR_PROFILE}" class="shoutbox-message-author {messages.USER_LEVEL_CLASS} offload" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>{messages.PSEUDO}</a>
		# ELSE #
			<span class="text-italic visitor">{messages.PSEUDO}</span>
		# ENDIF #
		# IF C_DISPLAY_DATE #<span class="text-italic question small">({messages.DATE_DELAY})</span># ENDIF # : <span class="shoutbox-message-content">{messages.CONTENT}</span>
	</div>
# END messages #
