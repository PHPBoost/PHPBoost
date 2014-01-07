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
	# IF C_TYPES #
	<tfoot>
		<tr>
			# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
			<th colspan="2" style="text-align:right;">
				<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'main')}" data-confirmation="{@bugs.actions.confirm.del_default_value}"><i class="fa fa-delete" ></i> {@bugs.labels.del_default_value}</a>
			</th>
			# ENDIF #
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START types #
		<tr>
			<td>
				<input type="radio" name="default_type" value="{types.ID}"# IF types.C_IS_DEFAULT # checked="checked"# ENDIF #>
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="type{types.ID}" value="{types.NAME}">
			</td>
			<td>
				<a href="{types.LINK_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="fa fa-delete" data-confirmation="{@bugs.actions.confirm.del_type}"></a>
			</td>
		</tr>
		# END types #
		# IF NOT C_TYPES #
		<tr> 
			<td colspan="3">
				{@bugs.notice.no_type}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>
