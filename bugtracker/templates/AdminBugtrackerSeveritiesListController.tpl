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
	# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
	<tfoot>
	<tr>
		<th colspan="3">
			<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'main')}" data-confirmation="{@bugs.actions.confirm.del_default_value}"><i class="fa fa-delete" ></i> {@bugs.labels.del_default_value}</a>
		</th>
	</tr>
	</tfoot>
	# ENDIF #
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
				<input type="text" maxlength="100" size="40" name="severity{severities.ID}" value="{severities.NAME}">
			</td>
		</tr>
		# END severities #
	</tbody>
</table>
