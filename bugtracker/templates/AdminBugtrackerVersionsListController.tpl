<script type="text/javascript">
<!--
function display_default_version_radio(version_id)
{
	if (document.getElementById('detected_in' + version_id).checked)
		document.getElementById('default_version' + version_id).style.display = ""
	else
	{
		document.getElementById('default_version' + version_id).style.display = "none";
		document.getElementById('default_version' + version_id).checked = "";
	}
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
					{@bugs.labels.version_name}
				</th>
				<th>
					{@bugs.labels.fields.version_release_date}
				</th>
				<th class="column_version_detected">
					{@bugs.labels.fields.version_detected}
				</th>
				<th class="column_delete">
					{L_DELETE}
				</th>
			</tr>
		</thead>
		<tfoot>
			# IF C_VERSIONS #
				# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
			<tr>
				<th colspan="5">
					<a href="{LINK_DELETE_DEFAULT}" title="{L_DELETE}" class="pbt-icon-delete" data-confirmation="{@bugs.actions.confirm.del_version}"></a>
				</th>
			</tr>
				# ENDIF #
			# ELSE #
				<tr> 
					<th colspan="5">
						{@bugs.notice.no_version}
					</th>
				</tr>
			# ENDIF #
		</tfoot>
		<tbody>
			# START versions #
			<tr>
				<td>
					<input type="radio" id="default_version{versions.ID}" name="default_version" value="{versions.ID}" {versions.IS_DEFAULT} {versions.DISPLAY_DEFAULT}>
				</td>
				<td>
					<input type="text" maxlength="100" size="40" name="version{versions.ID}" value="{versions.NAME}" class="text">
				</td>
				<td>
					<input type="text" maxlength="10" size="11" id="release_date{versions.ID}" name="release_date{versions.ID}" value="{versions.RELEASE_DATE}" class="text" onclick="if(this.value == '00/00/0000') this.value = '';" onblur="if(this.value == '') this.value = '00/00/0000';">
				</td> 
				<td>
					<input type="checkbox" id="detected_in{versions.ID}" name="detected_in{versions.ID}" onclick="javascript:display_default_version_radio('{versions.ID}');" {versions.DETECTED_IN}>
				</td> 
				<td>
					<a href="{versions.LINK_DELETE}" title="{L_DELETE}" class="pbt-icon-delete" data-confirmation="{@bugs.actions.confirm.del_default_value}"></a>
				</td>
			</tr>
			# END versions #
		</tbody>
	</table>
</fieldset>
