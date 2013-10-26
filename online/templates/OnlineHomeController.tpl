# IF IS_ADMIN #
	<menu class="dynamic_menu right">
		<ul>
			<li><a><i class="icon-cog"></i></a>
				<ul>
					<li>
						<a href="${relative_url(OnlineUrlBuilder::configuration())}" title="${i18n('admin.online-config')}">${i18n('admin.online-config')}</a>
					</li>
				</ul>
			</li>
		</ul>
	</menu>
# ENDIF #

<section>
	<header>
		<h1>{@online}</h1>
	</header>
	<table>
		<thead>
			<tr>
				<th class="column_login">
					${LangLoader::get_message('pseudo', 'main')}
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
					<img src="{users.U_AVATAR}" width="90px"/>
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