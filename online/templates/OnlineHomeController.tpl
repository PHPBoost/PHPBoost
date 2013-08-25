<div class="module_position">
	<div class="module_top_l"></div>
	<div class="module_top_r"></div>
	<div class="module_top">
		<div class="module_top_title">{@online} </div>
	</div>
	<div class="module_contents">
		<table class="module_table">
			<tr>
				<th class="column_login">
					{L_LOGIN}
				</th>
				<th>
					{@online.location}
				</th>
				<th class="column_last_update">
					{@online.last_update}
				</th>
			</tr>
			# START users #
			<tr>
				<td class="row3 text_center">
					<div id="comment-pseudo">
						<a href="{users.U_PROFILE}" class="{users.LEVEL_CLASS}" # IF users.C_GROUP_COLOR # style="color:{users.GROUP_COLOR}" # ENDIF #>{users.PSEUDO}</a>
					</div>
					<div class="comment-level">{users.LEVEL}</div>
					<img src="{users.U_AVATAR}" width="90px" class="comment-avatar" />
				</td>
				<td class="row3">
					<a href="{users.U_LOCATION}">{users.TITLE_LOCATION}</a>
				</td>
				<td class="row3">
					{users.LAST_UPDATE}
				</td>
			</tr>
			# END users #
			# IF NOT C_USERS #
			<tr class="text_center"> 
				<td colspan="3" class="row2">
					{@online.no_user_online}
				</td>
			</tr>
			# ENDIF #
		</table>
		# IF C_PAGINATION #
		<div class="spacer">&nbsp;</div>
		<div class="text_center"># INCLUDE PAGINATION #</div>
		# ENDIF #
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom"></div>
</div>