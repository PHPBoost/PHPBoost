<div class="admin_form">
	# INCLUDE FORM #
</div>

<table>
	<thead>
		<tr>
			<th class="column_title">
				{@calendar.labels.title}
			</th>
			<th>
				{@calendar.labels.category}
			</th>
			<th>
				{@calendar.labels.created_by}
			</th>
			<th>
				{@calendar.labels.date}
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
		# START events #
		<tr> 
			<td class="text_left">
				<a href="{events.U_LINK}">{events.SHORT_TITLE}</a>
			</td>
			<td> 
				<a href="{events.U_CATEGORY}"# IF events.CATEGORY_COLOR # style="color:{events.CATEGORY_COLOR}"# ENDIF #>{events.CATEGORY_NAME}</a>
			</td>
			<td> 
				<a href="{events.U_AUTHOR_PROFILE}" class="small_link {events.AUTHOR_LEVEL_CLASS}" # IF events.C_AUTHOR_GROUP_COLOR # style="color:{events.AUTHOR_GROUP_COLOR}"# ENDIF #>{events.AUTHOR}</a>
			</td>
			<td>
				{events.DATE}
			</td>
			<td>
				# IF events.C_APPROVED #${LangLoader::get_message('yes', 'main')}# ELSE #${LangLoader::get_message('no', 'main')}# ENDIF #
			</td>
			<td> 
				<a href="{events.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="pbt-icon-edit"></a>
				<a href="{events.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
			</td>
		</tr>
		# END events #
		# IF NOT C_EVENTS #
		<tr> 
			<td colspan="6">
				{@calendar.notice.no_event}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>
