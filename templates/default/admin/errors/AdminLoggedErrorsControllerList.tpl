	# IF C_ERRORS #
	<form action="{U_CLEAR_LOGGED_ERRORS}" method="post" class="fieldset-content">
		<fieldset>
			<legend>{@clear_list}</legend>
			<div class="form-element">
				<label>{@clear_list} <span class="field-description">{@clear_list_explain}</span></label>
				<div class="form-field"><label><button type="submit" class="submit" name="clear" data-confirmation="{@logged_errors_clear_confirmation}" value="true">{@clear_list}</button></label></div>
			</div>
		</fieldset>
		<input type="hidden" name="token" value="{TOKEN}">
	</form>
	<div class="spacer">&nbsp;</div>
	# ENDIF #
	<table class="table-fixed">
		<caption>{@logged_errors_list}</caption>
		# IF C_ERRORS #
		<thead>
			<tr> 
				<th>${LangLoader::get_message('description', 'main')}</th>
				<th style="width:85px;">${LangLoader::get_message('date', 'date-common')} </th>
			</tr>
		</thead>
		# IF C_PAGINATION #
		<tfoot>
			<tr>
				<th colspan="2">
					# INCLUDE PAGINATION #
				</th>
			</tr>
		</tfoot>
		# ENDIF #
		<tbody>
			# START errors #
			<tr>
				<td> 
					<div class="{errors.CLASS}">
						<strong>{errors.ERROR_TYPE} : </strong>{errors.ERROR_MESSAGE}<br /><br /><br />
						<em>{errors.ERROR_STACKTRACE}</em>
					</div>
				</td>
				<td>
					{errors.DATE}
				</td>
			</tr>
			# END errors #
		</tbody>
		# ENDIF #
	</table>
	# IF NOT C_ERRORS #
	<div class="success message-helper-small">{@no_error}</div>
	# ENDIF #