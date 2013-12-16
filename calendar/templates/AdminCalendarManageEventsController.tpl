<table>
	<thead>
		<tr>
			<th class="column_title">
				<a href="{U_SORT_TITLE_ASC}" class="icon-table-sort-up"></a>
				${LangLoader::get_message('form.title', 'common')}
				<a href="{U_SORT_TITLE_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_CATEGORY_ASC}" class="icon-table-sort-up"></a>
				${LangLoader::get_message('category', 'categories-common')}
				<a href="{U_SORT_CATEGORY_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_AUTHOR_ASC}" class="icon-table-sort-up"></a>
				{@calendar.labels.created_by}
				<a href="{U_SORT_AUTHOR_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				<a href="{U_SORT_DATE_ASC}" class="icon-table-sort-up"></a>
				{@calendar.labels.date}
				<a href="{U_SORT_DATE_DESC}" class="icon-table-sort-down"></a>
			</th>
			<th>
				{@calendar.titles.repetion}
			</th>
			<th>
				{@calendar.labels.approved}
			</th>
			<th>
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
		# START event #
		<tr> 
			<td class="text_left">
				<a href="{event.U_LINK}">{event.TITLE}</a>
			</td>
			<td> 
				<a href="{event.U_CATEGORY}"# IF event.CATEGORY_COLOR # style="color:{event.CATEGORY_COLOR}"# ENDIF #>{event.CATEGORY_NAME}</a>
			</td>
			<td> 
				<a href="{event.U_AUTHOR_PROFILE}" class="small_link {event.AUTHOR_LEVEL_CLASS}" # IF event.C_AUTHOR_GROUP_COLOR # style="color:{event.AUTHOR_GROUP_COLOR}"# ENDIF #>{event.AUTHOR}</a>
			</td>
			<td>
				${LangLoader::get_message('from_date', 'main')} {event.START_DATE}<br />
				${LangLoader::get_message('to_date', 'main')} {event.END_DATE}
			</td>
			<td>
				# IF event.C_BELONGS_TO_A_SERIE #{event.REPEAT_TYPE} - {event.REPEAT_NUMBER} {@calendar.labels.repeat_times}# ELSE #${LangLoader::get_message('no', 'main')}# ENDIF #
			</td>
			<td>
				# IF event.C_APPROVED #${LangLoader::get_message('yes', 'main')}# ELSE #${LangLoader::get_message('no', 'main')}# ENDIF #
			</td>
			<td> 
				<a href="{event.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
				<a href="{event.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete"# IF NOT event.C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #></a>
			</td>
		</tr>
		# END event #
		# IF NOT C_EVENTS #
		<tr> 
			<td colspan="6">
				{@calendar.notice.no_event}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>
