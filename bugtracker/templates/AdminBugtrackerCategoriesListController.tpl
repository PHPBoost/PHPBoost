<table>
	<thead>
		<tr>
			<th class="column_default">
				{@bugs.labels.default}
			</th>
			<th>
				${LangLoader::get_message('name', 'main')}
			</th>
			<th class="column_delete">
				${LangLoader::get_message('delete', 'main')}
			</th>
		</tr>
	</thead>
	<tfoot>
		# IF C_CATEGORIES #
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
		# START categories #
		<tr>
			<td>
				<input type="radio" name="default_category" value="{categories.ID}"# IF categories.C_IS_DEFAULT # checked="checked"# ENDIF #>
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="category{categories.ID}" value="{categories.NAME}" class="text">
			</td>
			<td>
				<a href="{categories.LINK_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="pbt-icon-delete" data-confirmation="{@bugs.actions.confirm.del_category}"></a>
			</td>
		</tr>
		# END categories #
		# IF NOT C_CATEGORIES #
		<tr> 
			<td colspan="3">
				{@bugs.notice.no_category}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>
