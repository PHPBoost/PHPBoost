<form method="post" class="fieldset_content">
			<table class="module_table">
				<tr> 
					<th colspan="5">
						{L_MANAGEMENT_EXTENDED_FIELDS}
					</th>
				</tr>
				<tr style="text-align:center;">
					<td class="row1" style="width:8%">
					</td>
					<td class="row1">
						{L_NAME}
					</td>
					<td class="row1" style="width:20%">
						{L_POSITION}
					</td>
					<td class="row1" style="width:20%">
						{L_REQUIRED}
					</td>
					<td class="row1" style="width:20%">
						{L_DISPLAY}
					</td>
				</tr>
				
				# START list_extended_fields #
				<tr style="text-align:center;"> 
					<td class="row1"> 
						<a href="{list_extended_fields.EDIT_LINK}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" /></a>
						<a href="{list_extended_fields.DELETE_LINK}" onclick="javascript:return Confirm_delete_field();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a>
					</td>
					<td class="row2">
						<span id="e{list_extended_fields.ID}"></span>
						{list_extended_fields.NAME}
					</td>				
					<td class="row2">
						{list_extended_fields.TOP}
						{list_extended_fields.BOTTOM}
					</td>
					<td class="row2">
						{list_extended_fields.L_REQUIRED}
					</td>
					<td class="row2">
						{list_extended_fields.L_DISPLAY}
					</td>
				</tr>
				# END list_extended_fields #
			</table>
			<input type="hidden" name="token" value="{TOKEN}" />
		</form>
		<script type="text/javascript">
		<!--
		function Confirm_delete_field() {
			return confirm("{L_ALERT_DELETE_FIELD}");
		}
		-->
		</script>