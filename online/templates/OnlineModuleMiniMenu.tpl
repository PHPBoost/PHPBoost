<span class="smaller">{TOTAL_VISITOR_CONNECTED} {L_VISITOR}, {TOTAL_MEMBER_CONNECTED} {L_MEMBER}, {TOTAL_MODERATOR_CONNECTED} {L_MODO}, {TOTAL_ADMINISTRATOR_CONNECTED} {L_ADMIN} ${TextHelper::lowercase_first(LangLoader::get_message('online', 'common', 'online'))}.</span>

<div class="online-users-container">
	# START users #
		<a href="{users.U_PROFILE}" class="{users.LEVEL_CLASS} online-user" # IF users.C_GROUP_COLOR # style="color:{users.GROUP_COLOR}" # ENDIF #>{users.PSEUDO}</a>
	# END users #
</div>

# IF C_MORE_USERS #
<div class="spacer"></div>
<a href="${relative_url(OnlineUrlBuilder::home())}">{TOTAL_USERS_CONNECTED} {L_USERS_ONLINE}</a>
# ENDIF #

<div class="smaller nbr-connected-user-container">
	{L_TOTAL} : {TOTAL_USERS_CONNECTED}
</div>
