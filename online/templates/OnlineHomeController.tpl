<section>
	<header>
		<h1>{@online}</h1>
	</header>
	<table>
		<thead>
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
		</thead>
		# IF C_PAGINATION #
		<tfoot>
			<tr>
				<th colspan="3">
					<span class="inline">${LangLoader::get_message('page', 'main')} : </span># INCLUDE PAGINATION #
				</th>
			</tr>
		</tfoot>
		# ENDIF #
		<tbody>
			# START users #
			<tr>
				<td>
					<div id="comment-pseudo">
						<a href="{users.U_PROFILE}" class="{users.LEVEL_CLASS}" # IF users.C_GROUP_COLOR # style="color:{users.GROUP_COLOR}" # ENDIF #>{users.PSEUDO}</a>
					</div>
					<div class="comment-level">{users.LEVEL}</div>
					<img src="{users.U_AVATAR}" width="90px" class="comment-avatar" />
				</td>
				<td>
					<a href="{users.U_LOCATION}">{users.TITLE_LOCATION}</a>
				</td>
				<td>
					{users.LAST_UPDATE}
				</td>
			</tr>
			# END users #
			# IF NOT C_USERS #
			<tr> 
				<td colspan="3">
					{@online.no_user_online}
				</td>
			</tr>
			# ENDIF #
		</tbody>
	</table>
	<footer></footer>
</section>