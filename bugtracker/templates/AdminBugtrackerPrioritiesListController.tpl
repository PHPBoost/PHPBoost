<script type="text/javascript">
<!--
function Confirm_del_default_value() {
	return confirm("{@bugs.actions.confirm.del_default_value}");
}
-->
</script>
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
			</tr>
		</thead>
		<tfoot>
			# IF C_PRIORITIES #
				# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
			<tr>
				<th colspan="3">
					<a href="{LINK_DELETE_DEFAULT}" onclick="javascript:return Confirm_del_default_value();" title="{L_DELETE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="" /> {@bugs.labels.del_default_value}</a>
				</th>
			</tr>
				# ENDIF #
			# ELSE #
			<tr> 
				<th colspan="3">
					{@bugs.notice.no_priority}
				</th>
			</tr>
			# ENDIF #
		</tfoot>
		<tbody>
			# START priorities #
			<tr>
				<td>
					<input type="radio" name="default_priority" value="{priorities.ID}" {priorities.IS_DEFAULT}>
				</td>
				<td>
					<input type="text" maxlength="100" size="40" name="priority{priorities.ID}" value="{priorities.NAME}" class="text">
				</td>
			</tr>
			# END priorities #
		</tbody>
	</table>
</fieldset>
