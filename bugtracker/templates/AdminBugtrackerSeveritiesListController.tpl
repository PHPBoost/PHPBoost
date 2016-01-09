<table id="table3">
	<thead>
		<tr>
			<th>
				{@labels.default}
			</th>
			<th class="medium-column">
				{@labels.color}
			</th>
			<th>
				${LangLoader::get_message('form.name', 'common')}
			</th>
		</tr>
	</thead>
	# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
	<tfoot>
	<tr>
		<th colspan="3">
			<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i> {@labels.del_default_value}</a>
		</th>
	</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START severities #
		<tr>
			<td>
				<div class="form-field-radio">
					<input id="default_severity{severities.ID}" type="radio" name="default_severity" value="{severities.ID}"# IF severities.C_IS_DEFAULT # checked="checked"# ENDIF # />
					<label for="default_severity{severities.ID}"></label>
				</div>
			</td>
			<td>
				<input type="color" name="color{severities.ID}" id="color{severities.ID}" value="{severities.COLOR}" />
			</td>
			<td>
				<input type="text" name="severity{severities.ID}" value="{severities.NAME}" />
			</td>
		</tr>
		# END severities #
	</tbody>
</table>
