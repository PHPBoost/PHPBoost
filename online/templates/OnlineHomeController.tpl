<section id="module-online">
	<header>
		<h1>{@online}</h1>
	</header>
	<table id="table">
		<thead>
			<tr>
				<th>
					${LangLoader::get_message('form.name', 'common')}
				</th>
				<th>
					{@online.location}
				</th>
				<th class="column-last-update">
					{@online.last_update}
				</th>
			</tr>
		</thead>
		# IF C_PAGINATION #
		<tfoot>
			<tr>
				<th colspan="3"># INCLUDE PAGINATION #</th>
			</tr>
		</tfoot>
		# ENDIF #
		<tbody>
			# START users #
			<tr>
				<td>
					<a href="{users.U_PROFILE}" class="{users.LEVEL_CLASS}" # IF users.C_GROUP_COLOR # style="color:{users.GROUP_COLOR}" # ENDIF #>{users.PSEUDO}</a>
					<div>{users.LEVEL}</div>
					# IF users.C_AVATAR #<img src="{users.U_AVATAR}" class="message-avatar" alt="${LangLoader::get_message('avatar', 'user-common')}" /># ENDIF #
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
					${LangLoader::get_message('no_item_now', 'common')}
				</td>
			</tr>
			# ENDIF #
		</tbody>
	</table>
	<footer></footer>
</section>
