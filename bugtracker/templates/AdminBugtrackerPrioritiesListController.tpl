<table id="table4">
	<thead>
		<tr>
			<th>
				{@labels.default}
			</th>
			<th>
				${LangLoader::get_message('form.name', 'common')}
			</th>
		</tr>
	</thead>
	# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
	<tfoot>
		<tr>
			<th colspan="2">
				<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i> {@labels.del_default_value}</a>
			</th>
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START priorities #
		<tr>
			<td>
				<div class="form-field-radio">
					<input id="default_priority{priorities.ID}" type="radio" name="default_priority" value="{priorities.ID}"# IF priorities.C_IS_DEFAULT # checked="checked"# ENDIF # />
					<label for="default_priority{priorities.ID}"></label>
				</div>
			</td>
			<td>
				<input type="text" name="priority{priorities.ID}" value="{priorities.NAME}" />
			</td>
		</tr>
		# END priorities #
	</tbody>
</table>
