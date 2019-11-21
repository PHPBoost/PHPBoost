<table class="table">
	# IF C_SUBSCRIBERS #
	<thead>
		<tr>
			<th>
				<a href="{SORT_NAME_TOP}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
				{@subscribers.pseudo}
				<a href="{SORT_NAME_BOTTOM}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
			</th>
			<th>
				<a href="{SORT_MAIL_TOP}" aria-label="${LangLoader::get_message('sort.asc', 'common')}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
				{@subscribers.mail}
				<a href="{SORT_MAIL_BOTTOM}" aria-label="${LangLoader::get_message('sort.desc', 'common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
			</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		# START subscribers_list #
		<tr>
			<td>
				# IF subscribers_list.C_MEMBER #<a href="{subscribers_list.U_USER_PROFILE}">{subscribers_list.NAME}</a># ELSE #{subscribers_list.NAME}# ENDIF #
			</td>
			<td>
				{subscribers_list.MAIL}
			</td>
			<td>
				# IF subscribers_list.C_AUTH_MODO #
					# IF subscribers_list.C_EDIT #
					<a href="{subscribers_list.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
					# ENDIF #
					<a href="{subscribers_list.U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-trash-alt" aria-hidden="true"></i></a>
				# ENDIF #
			</td>
		</tr>
		# END subscribers_list #
	</tbody>
	# ELSE #
	<tbody>
		<tr>
			<td colspan="3">
				<span class="text-strong">{@subscribers.no_users}</span>
			</td>
		</tr>
	</tbody>
	# ENDIF #
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<td colspan="3">
				# INCLUDE PAGINATION #
			</td>
		</tr>
	</tfoot>
	# ENDIF #
</table>
