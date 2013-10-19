# INCLUDE ADD_FIELDSET_JS #
<fieldset id="${escape(ID)}" # IF C_DISABLED # style="display:none;" # ENDIF # # IF CSS_CLASS # class="{CSS_CLASS}" # ENDIF #>
	<legend>{L_FORMTITLE}</legend>
	# START elements #
		# INCLUDE elements.ELEMENT #
	# END elements #
	<table>
		<thead>
			<tr>
				<th class="column_default">
					{@bugs.labels.default}
				</th>
				<th>
					{L_NAME}
				</th>
				<th class="column_delete">
					{L_DELETE}
				</th>
			</tr>
		</thead>
		<tfoot>
			# IF C_CATEGORIES #
				# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
			<tr>
				<th colspan="3">
					<a href="{LINK_DELETE_DEFAULT}" title="{L_DELETE}" class="pbt-icon-delete" data-confirmation="{@bugs.actions.confirm.del_default_value}">{@bugs.labels.del_default_value}</a>
				</th>
			</tr>
				# ENDIF #
			# ELSE #
			<tr> 
				<th colspan="3">
					{@bugs.notice.no_category}
				</th>
			</tr>
			# ENDIF #
		</tfoot>
		<tbody>
			# START categories #
			<tr>
				<td>
					<input type="radio" name="default_category" value="{categories.ID}" {categories.IS_DEFAULT}>
				</td>
				<td>
					<input type="text" maxlength="100" size="40" name="category{categories.ID}" value="{categories.NAME}" class="text">
				</td>
				<td>
					<a href="{categories.LINK_DELETE}" class="pbt-icon-delete" data-confirmation="{@bugs.actions.confirm.del_category}"></a>
				</td>
			</tr>
			# END categories #
		</tbody>
	</table>
</fieldset>
