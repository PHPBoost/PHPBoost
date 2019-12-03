# IF C_HISTORY #
<table class="table">
	<thead>
		<tr>
			<th>
				{@labels.fields.updater_id}
			</th>
			<th>
				{@labels.fields.updated_field}
			</th>
			<th>
				{@labels.fields.old_value}
			</th>
			<th>
				{@labels.fields.new_value}
			</th>
			<th>
				{@labels.fields.update_date}
			</th>
			<th>
				{@labels.fields.change_comment}
			</th>
		</tr>
	</thead>
	<tbody>
		# START history #
		<tr>
			<td>
				# IF history.C_UPDATER_EXIST #<a href="{history.U_UPDATER_PROFILE}" class="{history.UPDATER_LEVEL_CLASS}" # IF history.C_UPDATER_GROUP_COLOR # style="color:{history.UPDATER_GROUP_COLOR}" # ENDIF #>{history.UPDATER}</a># ELSE #{history.UPDATER}# ENDIF #
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
				{history.UPDATE_DATE_FULL}
			</td>
			<td>
				{history.COMMENT}
			</td>
		</tr>
		# END history #
	</tbody>
	# IF C_PAGINATION #
	<tfoot>
		<tr>
			<td colspan="6"># INCLUDE PAGINATION #</td>
		</tr>
	</tfoot>
	# ENDIF #
</table>
# ELSE #
<div class="message-helper bgc notice">{@notice.no_history}</div>
# ENDIF #
