# START messages #
	<div id="shoutbox-message-{messages.ID}" class="shoutbox-message-container">
		# IF C_DISPLAY_DATE #<span class="pinned question small">{messages.DATE}</span># ENDIF #
		<span class="shoutbox-message">
			# IF messages.C_DELETE #
				<a href="#" onclick="shoutbox_delete_message({messages.ID});return false;" aria-label="${LangLoader::get_message('delete', 'common')}" id="delete_{messages.ID}"><i class="fa fa-times" aria-hidden="true"></i></a>
			# ENDIF #
			# IF messages.C_AUTHOR_EXIST #
				<a href="{messages.U_AUTHOR_PROFILE}" class="shoutbox-message-author {messages.USER_LEVEL_CLASS}" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>{messages.PSEUDO}</a>
			# ELSE #
				<span class="shoutbox-message-author visitor">{messages.PSEUDO}</span>
			# ENDIF #
			 : <span class="shoutbox-message-content">{messages.CONTENTS}</span>
		</span>
	</div>
# END messages #
