<table class="table">
	<thead>
		<tr>
			<th class="col-medium">
				{@form.is.default}
			</th>
			<th class="col-larger">
				{@common.color}
			</th>
			<th>
				{@common.name}
			</th>
		</tr>
	</thead>
	<tbody>
		# START severities #
			<tr>
				<td class="custom-radio">
					<div class="form-field-radio">
						<label class="radio" for="default_severity{severities.ID}">
							<input aria-label="{severities.NAME}" id="default_severity{severities.ID}" type="radio" name="default_severity" value="{severities.ID}"# IF severities.C_IS_DEFAULT # checked="checked"# ENDIF # />
							<span></span>
						</label>
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
	# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
		<tfoot>
			<tr>
				<td>
					<a href="{LINK_DELETE_DEFAULT}" aria-label="{@labels.del_default_value}" data-confirmation="delete-element"><i class="far fa-lg fa-dot-circle error"></i> </a>
				</td>
				<td colspan="2"></td>
			</tr>
		</tfoot>
	# ENDIF #
</table>
