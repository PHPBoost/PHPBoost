<script type="text/javascript">
<!--
function Confirm_del_default_value() {
	return confirm("{@bugs.actions.confirm.del_default_value}");
}
-->
# INCLUDE ADD_FIELDSET_JS #
<fieldset id="${escape(ID)}" # IF C_DISABLED # style="display:none;" # ENDIF # # IF CSS_CLASS # class="{CSS_CLASS}" # ENDIF #>
	<legend>{L_FORMTITLE}</legend>
	# START elements #
		# INCLUDE elements.ELEMENT #
	# END elements #
	<table class="module_table">
		<tr class="text_center">
			<th class="column_default">
				{@bugs.labels.default}
			</th>
			<th>
				{L_NAME}
			</th>
		</tr>
		# START priorities #
		<tr class="text_center">
			<td class="row2">
				<input type="radio" name="default_priority" value="{priorities.ID}" {priorities.IS_DEFAULT}>
			</td>
			<td class="row2">
				<input type="text" maxlength="100" size="40" name="priority{priorities.ID}" value="{priorities.NAME}" class="text">
			</td>
		</tr>
		# END priorities #
		# IF C_PRIORITIES #
			# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
		<tr>
			<td colspan="3" class="row3">
				<a href="{LINK_DELETE_DEFAULT}" onclick="javascript:return Confirm_del_default_value();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /> {@bugs.labels.del_default_value}</a>
			</td>
		</tr>
			# ENDIF #
		# ELSE #
		<tr class="text_center"> 
			<td colspan="3" class="row2">
				{@bugs.notice.no_priority}
			</td>
		</tr>
		# ENDIF #
		
	</table>
	<br />
</fieldset>