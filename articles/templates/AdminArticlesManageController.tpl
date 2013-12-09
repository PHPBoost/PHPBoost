<table>
	<thead>
		<tr>
			<th class="column_title">
				<a href="{U_SORT_TITLE_ASC}" class="icon-table-sort-up"></a>
				{@admin.articles.sort_field.title}
				<a href="{U_SORT_TITLE_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_CATEGORY_ASC}" class="icon-table-sort-up"></a>
				{@admin.articles.sort_field.cat}
				<a href="{U_SORT_CATEGORY_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_AUTHOR_ASC}" class="icon-table-sort-up"></a>
				{@admin.articles.sort_field.author}
				<a href="{U_SORT_AUTHOR_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_DATE_ASC}" class="icon-table-sort-up"></a>
				{@admin.articles.sort_field.date}
				<a href="{U_SORT_DATE_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_PUBLISHED_DESC}" class="icon-table-sort-up"></a>
				{@admin.articles.sort_field.published}	
				<a href="{U_SORT_PUBLISHED_DESC}" class="icon-table-sort-down"></a>
			</th>
		</tr>
	</thead>
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<th colspan="5">
				# INCLUDE PAGINATION #
			</th>
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START articles #
		<tr> 
			<td class="text_left">
				<a href="{articles.U_ARTICLE}">{articles.L_TITLE}</a>
			</td>
			<td> 
				<a href="{articles.U_CATEGORY}">{articles.L_CATEGORY}</a>
			</td>
			<td> 
				<a href="{articles.U_AUTHOR}" class="small_link {articles.USER_LEVEL_CLASS}" # IF articles.C_USER_GROUP_COLOR # style="color:{articles.USER_GROUP_COLOR}"# ENDIF #>{articles.PSEUDO}</a>
			</td>		
			<td>
				{articles.DATE}
			</td>
			<td>
				{articles.PUBLISHED} 
				<br />
				<span class="smaller">{articles.PUBLISHED_DATE}</span>
			</td>
			<td> 
				<a href="{articles.U_EDIT_ARTICLE}" title="{@articles.edit}" class="icon-edit"></a>
			</td>
			<td>
				<a href="{articles.U_DELETE_ARTICLE}" title="{@articles.delete}" class="icon-delete" data-confirmation="delete-element"></a>
			</td>
		</tr>
		# END articles #
		# IF C_NO_ARTICLES #
		<tr> 
			<td colspan="5">
				{@articles.no_article}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>