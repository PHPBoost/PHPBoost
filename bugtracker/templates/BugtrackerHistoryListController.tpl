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
				# IF history.UPDATER #<a href="{history.U_UPDATER_PROFILE}" class="small {history.UPDATER_LEVEL_CLASS}" # IF history.C_UPDATER_GROUP_COLOR # style="color:{history.UPDATER_GROUP_COLOR}" # ENDIF #>{history.UPDATER}</a># ELSE #{L_GUEST}# ENDIF #
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
				{history.DATE}
			</td>
			<td>
				{history.COMMENT}
			</td>
		</tr>
		# END history #
		# IF NOT C_HISTORY #
		<tr> 
			<td colspan="6">
				{@bugs.notice.no_history}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>



<div class="spacer">&nbsp;</div>

<div class="center">
	<strong><a href="javascript:history.back(1);" title="${escape(RETURN_NAME)}">${escape(RETURN_NAME)}</a></strong>
</div>
