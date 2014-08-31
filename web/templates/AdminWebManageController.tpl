<table>
	<caption>{@web.management}</caption>
	<thead>
		<tr>
			<th></th>
			<th>
				<a href="{U_SORT_NAME_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('form.name', 'common')}
				<a href="{U_SORT_NAME_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_CATEGORY_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('category', 'categories-common')}
				<a href="{U_SORT_CATEGORY_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_AUTHOR_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('author', 'common')}
				<a href="{U_SORT_AUTHOR_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_DATE_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('form.date.creation', 'common')}
				<a href="{U_SORT_DATE_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				${LangLoader::get_message('form.approbation', 'common')}
			</th>
		</tr>
	</thead>
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<th colspan="6">
				# INCLUDE PAGINATION #
			</th>
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START weblinks #
			<tr>
				<td>
					<a href="{weblinks.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
					<a href="{weblinks.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
				</td>
				<td class="left">
					<a href="{weblinks.U_LINK}">{weblinks.NAME}</a>
				</td>
				<td>
					<a href="{weblinks.U_CATEGORY}">{weblinks.CATEGORY_NAME}</a>
				</td>
				<td>
					# IF weblinks.C_AUTHOR_EXIST #<a href="{weblinks.U_AUTHOR_PROFILE}" class="{weblinks.USER_LEVEL_CLASS}" # IF weblinks.C_USER_GROUP_COLOR # style="color:{weblinks.USER_GROUP_COLOR}"# ENDIF #>{weblinks.PSEUDO}</a># ELSE #{weblinks.PSEUDO}# ENDIF #
				</td>
				<td>
					{weblinks.DATE}
				</td>
				<td>
					{weblinks.STATUS}
				</td>
			</tr>
		# END weblinks #
		# IF NOT C_WEBLINKS #
		<tr> 
			<td colspan="6">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>