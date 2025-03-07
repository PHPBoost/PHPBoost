<div class="cell-body">
	<div class="cell-content">
		<p>
			{REGISTERED_USERS_NUMBER} # IF C_SEVERAL_REGISTERED_USERS #{@stats.registered.members}# ELSE #{@stats.registered.member}# ENDIF #
		</p>
		<p>
			<span class="pinned small">{@stats.last.member}:</span>
			<a href="{U_LAST_USER_PROFILE}" class="{LAST_USER_LEVEL_CLASS} offload"# IF C_LAST_USER_GROUP_COLOR # style="color:{LAST_USER_GROUP_COLOR}" # ENDIF #>{LAST_USER_DISPLAY_NAME}</a>
		</p>
		</div>
	<div class="cell-content align-center"><a href="{PATH_TO_ROOT}/stats/" class="button small offload">{@stats.more.stats}</a></div>
</div>
