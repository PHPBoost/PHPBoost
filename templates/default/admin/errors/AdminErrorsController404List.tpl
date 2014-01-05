	# IF C_ERRORS #
	<form action="{U_CLEAR_404_ERRORS}" method="post" class="fieldset-content">
		<fieldset>
			<legend>{@clear_list}</legend>
			<div class="form-element">
				<label>{@clear_list} <span class="field-description">{@clear_list_explain}</span></label>
				<div class="form-field"><label><button type="submit" name="clear" data-confirmation="{@404_errors_clear_confirmation}" value="true">{@clear_404_list}</button></label></div>
			</div>
		</fieldset>
		<input type="hidden" name="token" value="{TOKEN}">
	</form>
	<div class="spacer">&nbsp;</div>
	# ENDIF #
	<table>
		<caption>{@404_list}</caption>
		# IF C_ERRORS #
		<thead>
			<tr>
				<th>{@404_error_requested_url}</th>
				<th>{@404_error_from_url}</th>
				<th style="width:50px;">{@404_error_times}</th>
				<th style="width:50px;">{@404_error_delete}</th>
			</tr>
		</thead>
		<tbody>
			# START errors #
			<tr>
				<td><a href="{errors.REQUESTED_URL}" title="{@404_error_requested_url}">{errors.REQUESTED_URL}</a></td>
				<td><a href="{errors.FROM_URL}" title="{@404_error_from_url}">{errors.FROM_URL}</a></td>
				<td class="center">{errors.TIMES}</td>
				<td class="center">
					<a href="{errors.U_DELETE}" title="{@404_error_delete}" class="fa fa-delete" data-confirmation="delete-element"></a>
				</td>
			</tr>
			# END errors #
		</tbody>
		# ELSE #
		<tbody>
			<tr>
				<td>{@404_no_error}</td>
			</tr>
		</tbody>
		# ENDIF #
	</table>
