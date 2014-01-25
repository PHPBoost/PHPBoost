# IF C_HISTORY #
<table>
	<thead>
		<tr>
			<th>
				{@bugs.labels.fields.updater_id}
			</th>
			<th>
				{@bugs.labels.fields.updated_field}
			</th>
			<th>
				{@bugs.labels.fields.old_value}
			</th>
			<th>
				{@bugs.labels.fields.new_value}
			</th>
			<th>
				{@bugs.labels.fields.update_date}
			</th>
			<th>
				{@bugs.labels.fields.change_comment}
			</th>
		</tr>
	</thead>
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<th colspan="6"># INCLUDE PAGINATION #</th>
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START history #
		<tr> 
			<td> 
				# IF history.UPDATER #<a href="{history.U_UPDATER_PROFILE}" class="small {history.UPDATER_LEVEL_CLASS}" # IF history.C_UPDATER_GROUP_COLOR # style="color:{history.UPDATER_GROUP_COLOR}" # ENDIF #>{history.UPDATER}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF #
			</td>
			<td> 
				{history.UPDATED_FIELD}
			</td>
			<td> 
				{history.OLD_VALUE}
			</td>
			<td> 
				{history.NEW_VALUE}
			</td>
			<td>
				# IF C_IS_DATE_FORM_SHORT #{history.UPDATE_DATE_SHORT}# ELSE #{history.UPDATE_DATE}# ENDIF #
			</td>
			<td>
				{history.COMMENT}
			</td>
		</tr>
		# END history #
	</tbody>
</table>
# ELSE #
<div class="message-helper notice">
<i class="fa fa-notice"></i>
<div class="message-helper-content">{@bugs.notice.no_history}</div>
</div>
# ENDIF #
