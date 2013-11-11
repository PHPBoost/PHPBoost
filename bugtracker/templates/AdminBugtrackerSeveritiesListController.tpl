<table>
	<thead>
		<tr>
			<th class="column_default">
				{@bugs.labels.default}
			</th>
			<th class="column_color">
				{@bugs.labels.color}
			</th>
			<th>
				${LangLoader::get_message('name', 'main')}
			</th>
		</tr>
	</thead>
	<tfoot>
	# IF C_SEVERITIES #
		# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
	<tr>
		<th colspan="4">
			<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="{@bugs.actions.confirm.del_default_value}">{@bugs.labels.del_default_value}</a>
		</th>
	</tr>
		# ENDIF #
	# ENDIF #
	</tfoot>
	<tbody>
		# START severities #
		<tr>
			<td>
				<input type="radio" name="default_severity" value="{severities.ID}"# IF severities.C_IS_DEFAULT # checked="checked"# ENDIF #>
			</td>
			<td>
				# INCLUDE severities.COLOR #
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="severity{severities.ID}" value="{severities.NAME}" class="text">
			</td>
		</tr>
		# END severities #
		# IF NOT C_SEVERITIES #
		<tr> 
			<td colspan="4">
				{@bugs.notice.no_severity}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>
