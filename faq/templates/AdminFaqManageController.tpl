<table>
	<caption>{@faq.management}</caption>
	<thead>
		<tr>
			<th></th>
			<th>
				<a href="{U_SORT_QUESTION_ASC}" class="fa fa-table-sort-up"></a>
				{@faq.form.question}
				<a href="{U_SORT_Question_DESC}" class="fa fa-table-sort-down"></a>
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
				${LangLoader::get_message('form.approved', 'common')}
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
		# START questions #
			<tr>
				<td>
					<a href="{questions.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
					<a href="{questions.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
				</td>
				<td class="left">
					<a href="{questions.U_LINK}">{questions.QUESTION}</a>
				</td>
				<td>
					<a href="{questions.U_CATEGORY}">{questions.CATEGORY_NAME}</a>
				</td>
				<td>
					# IF questions.C_AUTHOR_EXIST #<a href="{questions.U_AUTHOR_PROFILE}" class="{questions.USER_LEVEL_CLASS}" # IF questions.C_USER_GROUP_COLOR # style="color:{questions.USER_GROUP_COLOR}"# ENDIF #>{questions.PSEUDO}</a># ELSE #{questions.PSEUDO}# ENDIF #
				</td>
				<td>
					{questions.DATE}
				</td>
				<td>
					# IF questions.C_APPROVED #${LangLoader::get_message('yes', 'common')}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #
				</td>
			</tr>
		# END questions #
		# IF NOT C_QUESTIONS #
		<tr> 
			<td colspan="6">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>