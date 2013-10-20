<table>
	<thead>
		<tr>
			<th class="column_default">
				{@bugs.labels.default}
			</th>
			<th>
				${LangLoader::get_message('name', 'main')}
			</th>
		</tr>
	</thead>
	<tfoot>
		# IF C_PRIORITIES #
			# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
		<tr>
			<th colspan="3">
				<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'main')}" class="pbt-icon-delete" data-confirmation="{@bugs.actions.confirm.del_default_value}">{@bugs.labels.del_default_value}</a>
			</th>
		</tr>
			# ENDIF #
		# ENDIF #
	</tfoot>
	<tbody>
		# START priorities #
		<tr>
			<td>
				<input type="radio" name="default_priority" value="{priorities.ID}"# IF priorities.C_IS_DEFAULT # checked="checked"# ENDIF #>
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="priority{priorities.ID}" value="{priorities.NAME}" class="text">
			</td>
		</tr>
		# END priorities #
		# IF NOT C_PRIORITIES #
		<tr> 
			<td colspan="3">
				{@bugs.notice.no_priority}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>
