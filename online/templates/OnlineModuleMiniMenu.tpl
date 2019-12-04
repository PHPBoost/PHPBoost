<div class="cell-body">
	<div class="cell-content">
		${TextHelper::lcfirst(LangLoader::get_message('online', 'common', 'online'))} :
		# IF C_DISPLAY_ROBOTS #
			<span class="small pinned warning">{TOTAL_ROBOT_CONNECTED} {L_ROBOT}</span>
		# ENDIF #
		<span class="small pinned visitor">{TOTAL_VISITOR_CONNECTED} {L_VISITOR}</span>
		<span class="small pinned member">{TOTAL_MEMBER_CONNECTED} {L_MEMBER}</span>
		<span class="small pinned moderator">{TOTAL_MODERATOR_CONNECTED} {L_MODO}</span>
		<span class="small pinned administrator">{TOTAL_ADMINISTRATOR_CONNECTED} {L_ADMIN}</span>

		<div class="online-users-container">
			# START users #
				# IF users.C_ROBOT #
					<span class="{users.LEVEL_CLASS}">{users.PSEUDO}</span>
				# ELSE #
					<a href="{users.U_PROFILE}" class="{users.LEVEL_CLASS} online-user" # IF users.C_GROUP_COLOR # style="color:{users.GROUP_COLOR}" # ENDIF #>{users.PSEUDO}</a>
				# ENDIF #
			# END users #
		</div>

	</div>
	<div class="cell-content align-center">
		<p>{L_TOTAL} : {TOTAL_USERS_CONNECTED}</p>
		# IF C_MORE_USERS #
			<a class="button small" href="${relative_url(OnlineUrlBuilder::home())}">{TOTAL_USERS_CONNECTED} {L_USERS_ONLINE}</a>
		# ENDIF #
	</div>
</div>
