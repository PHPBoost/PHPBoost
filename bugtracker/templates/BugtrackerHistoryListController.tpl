<table class="module_table">
	<tr class="center">
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
	# START history #
	<tr class="center"> 
		<td class="row2"> 
			# IF history.UPDATER #<a href="{history.U_UPDATER_PROFILE}" class="small_link {history.UPDATER_LEVEL_CLASS}" # IF history.C_UPDATER_GROUP_COLOR # style="color:{history.UPDATER_GROUP_COLOR}" # ENDIF #>{history.UPDATER}</a># ELSE #{L_GUEST}# ENDIF #
		</td>
		<td class="row2"> 
			{history.UPDATED_FIELD}
		</td>
		<td class="row2"> 
			{history.OLD_VALUE}
		</td>
		<td class="row2"> 
			{history.NEW_VALUE}
		</td>	
		<td class="row2">
			{history.DATE}
		</td>
		<td class="row2">
			{history.COMMENT}
		</td>
	</tr>
	# END history #
	# IF NOT C_HISTORY #
	<div class="center"># INCLUDE PAGINATION #</div>
	<tr class="center"> 
		<td colspan="6" class="row2">
			{@bugs.notice.no_history}
		</td>
	</tr>
	# ENDIF #
</table>

# IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #

<div class="spacer">&nbsp;</div>

<div class="center">
	<strong><a href="javascript:history.back(1);" title="${escape(RETURN_NAME)}">${escape(RETURN_NAME)}</a></strong>
</div>
