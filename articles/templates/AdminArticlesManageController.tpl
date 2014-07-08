<table>
	<caption>{@articles_management}</caption>
	<thead>
		<tr>
			<th></th>
			<th>
				<a href="{U_SORT_TITLE_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('form.title', 'common')}
				<a href="{U_SORT_TITLE_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_CATEGORY_ASC}" class="fa fa-table-sort-up"></a>
				${LangLoader::get_message('category', 'categories-common')}
				<a href="{U_SORT_CATEGORY_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_AUTHOR_ASC}" class="fa fa-table-sort-up"></a>
				{@admin.articles.sort_field.author}
				<a href="{U_SORT_AUTHOR_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_DATE_ASC}" class="fa fa-table-sort-up"></a>
				{@admin.articles.sort_field.date}
				<a href="{U_SORT_DATE_DESC}" class="fa fa-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_PUBLISHED_ASC}" class="fa fa-table-sort-up"></a>
				{@admin.articles.sort_field.published}
				<a href="{U_SORT_PUBLISHED_DESC}" class="fa fa-table-sort-down"></a>
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
		# START articles #
		<tr>
			<td> 
				<a href="{articles.U_EDIT_ARTICLE}" title="{@articles.edit}" class="fa fa-edit"></a>
				<a href="{articles.U_DELETE_ARTICLE}" title="{@articles.delete}" class="fa fa-delete" data-confirmation="delete-element"></a>
			</td>
			<td class="left">
				<a href="{articles.U_ARTICLE}">{articles.TITLE}</a>
			</td>
			<td> 
				<a href="{articles.U_CATEGORY}">{articles.CATEGORY_NAME}</a>
			</td>
			<td> 
				# IF articles.C_AUTHOR_EXIST #<a href="{articles.U_AUTHOR}" class="{articles.USER_LEVEL_CLASS}" # IF articles.C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a># ELSE #{articles.PSEUDO}# ENDIF #
			</td>
			<td>
				{articles.DATE_SHORT}
			</td>
			<td>
				# IF articles.C_PUBLISHED #${LangLoader::get_message('yes', 'main')}# ELSE #${LangLoader::get_message('no', 'main')}# ENDIF #
				<br />
				<span class="smaller">
				# IF articles.C_PUBLISHING_START_AND_END_DATE #
				${LangLoader::get_message('from_date', 'main')} {articles.PUBLISHING_START_DATE}<br />
				${LangLoader::get_message('to_date', 'main')} {articles.PUBLISHING_END_DATE}
				# ELSE #
					# IF articles.C_PUBLISHING_START_DATE #
						{articles.PUBLISHING_START_DATE}
					# ELSE #
						# IF articles.C_PUBLISHING_END_DATE #
							${LangLoader::get_message('until', 'main')} {articles.PUBLISHING_END_DATE}
						# ENDIF #
					# ENDIF #
				# ENDIF #
				</span>
			</td>
		</tr>
		# END articles #
		# IF NOT C_ARTICLES #
		<tr> 
			<td colspan="6">
				{@articles.no_article}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>