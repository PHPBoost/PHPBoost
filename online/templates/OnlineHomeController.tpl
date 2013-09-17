<section>
	<header>
		<h1>{@online}</h1>
	</header>
	<div class="content">
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
			<tr class="center"> 
				<td colspan="3" class="row2">
					{@online.no_user_online}
				</td>
			</tr>
			# ENDIF #
		</table>
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>