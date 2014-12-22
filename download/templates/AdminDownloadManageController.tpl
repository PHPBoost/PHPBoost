<table>
	<caption>{@download.management}</caption>
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
		# START downloadfiles #
			<tr>
				<td>
					<a href="{downloadfiles.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
					<a href="{downloadfiles.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
				</td>
				<td class="left">
					<a href="{downloadfiles.U_LINK}">{downloadfiles.NAME}</a>
				</td>
				<td>
					<a href="{downloadfiles.U_CATEGORY}">{downloadfiles.CATEGORY_NAME}</a>
				</td>
				<td>
					# IF downloadfiles.C_AUTHOR_EXIST #<a href="{downloadfiles.U_AUTHOR_PROFILE}" class="{downloadfiles.USER_LEVEL_CLASS}" # IF downloadfiles.C_USER_GROUP_COLOR # style="color:{downloadfiles.USER_GROUP_COLOR}"# ENDIF #>{downloadfiles.PSEUDO}</a># ELSE #{downloadfiles.PSEUDO}# ENDIF #
				</td>
				<td>
					{downloadfiles.DATE}
				</td>
				<td>
					{downloadfiles.STATUS}
				</td>
			</tr>
		# END downloadfiles #
		# IF NOT C_FILES #
		<tr> 
			<td colspan="6">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>