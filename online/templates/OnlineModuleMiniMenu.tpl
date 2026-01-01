<div class="cell-list">
	<ul>
		# IF C_DISPLAY_ROBOTS #
			<li class="li-stretch">
				<span class="small pinned warning">{@user.robots}</span>
				<span>{ROBOTS_NUMBER}</span>
			</li>
		# ENDIF #
		<li class="li-stretch">
			<span class="small pinned visitor">{@user.guests}</span>
			<span>{VISITORS_NUMBER}</span>
		</li>

		<li class="li-stretch">
			<span class="small pinned member">{@user.members}</span>
			<span>{MEMBERS_NUMBER}</span>
		</li>

		<li class="li-stretch">
			<span class="small pinned moderator">{@user.moderators}</span>
			<span>{MODERATORS_NUMBER}</span>
		</li>

		<li class="li-stretch">
			<span class="small pinned administrator">{@user.administrators}</span>
			<span>{ADMINISTRATORS_NUMBER}</span>
		</li>

		<li class="align-center">
			{@common.total} : {USERS_NUMBER}
		</li>
	</ul>
</div>
<div class="cell-body">
	<div class="cell-content align-center">
		<a
		class="button small"
		href="${relative_url(OnlineUrlBuilder::home())}"
		aria-label="# START items #<span# IF NOT C_ROBOT # class='{items.USER_LEVEL_CLASS}'# ENDIF ## IF items.C_USER_GROUP_COLOR # style='color: {items.USER_GROUP_COLOR}'# ENDIF #>{items.USER_DISPLAY_NAME}</span># IF C_SEVERAL_USERS #<br /># ENDIF ## IF C_MORE_USERS #...# ENDIF ## END items #">
			{@online.who.is}
		</a>
	</div>
</div>
