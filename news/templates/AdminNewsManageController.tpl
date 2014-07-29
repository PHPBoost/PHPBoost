<table>
	<caption>{@news.management}</caption>
	<thead>
		<tr>
			<th></th>
			<th>
				<a href="{U_SORT_NAME_ASC}" class="fa fa-table-sort-up"></a>
				{@news.form.name}
				<a href="{U_SORT_NAME_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_CATEGORY_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('category', 'categories-common')}
				<a href="{U_SORT_CATEGORY_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_AUTHOR_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('pseudo', 'main')}
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
		# START news #
			<tr>
				<td>
					<a href="{news.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="fa fa-edit"></a>
					<a href="{news.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="delete-element"></a>
				</td>
				<td class="left">
					<a href="{news.U_LINK}">{news.NAME}</a>
				</td>
				<td>
					<a href="{news.U_CATEGORY}">{news.CATEGORY_NAME}</a>
				</td>
				<td>
					# IF news.C_AUTHOR_EXIST #<a href="{news.U_AUTHOR_PROFILE}" class="{news.USER_LEVEL_CLASS}" # IF news.C_USER_GROUP_COLOR # style="color:{news.USER_GROUP_COLOR}"# ENDIF #>{news.PSEUDO}</a># ELSE #{news.PSEUDO}# ENDIF #
				</td>
				<td>
					{news.DATE}
				</td>
				<td>
					{news.STATUS}
				</td>
			</tr>
		# END news #
		# IF NOT C_NEWS #
		<tr> 
			<td colspan="6">
				{@news.message.no_items}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>