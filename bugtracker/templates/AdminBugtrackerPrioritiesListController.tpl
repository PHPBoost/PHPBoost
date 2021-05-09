<table class="table">
	<thead>
		<tr>
			<th class="col-medium">
				{@form.is.default}
			</th>
			<th>
				{@common.name}
			</th>
		</tr>
	</thead>
	<tbody>
		# START priorities #
			<tr>
				<td class="custom-radio">
					<div class="form-field-radio">
						<label class="radio" for="default_priority{priorities.ID}">
							<input aria-label="{priorities.NAME}" id="default_priority{priorities.ID}" type="radio" name="default_priority" value="{priorities.ID}"# IF priorities.C_IS_DEFAULT # checked="checked"# ENDIF # />
							<span></span>
						</label>
					</div>
				</td>
				<td>
					<input type="text" name="priority{priorities.ID}" value="{priorities.NAME}" />
				</td>
			</tr>
		# END priorities #
	</tbody>
	# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
		<tfoot>
			<tr>
				<td>
					<a href="{LINK_DELETE_DEFAULT}" aria-label="{@labels.del_default_value}" data-confirmation="delete-element"><i class="far fa-lg fa-dot-circle error"></i></a>
				</td>
				<td></td>
			</tr>
		</tfoot>
	# ENDIF #
</table>
