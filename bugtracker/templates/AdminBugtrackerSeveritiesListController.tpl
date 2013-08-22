<script type="text/javascript">
<!--
function Confirm_del_default_value() {
	return confirm("{@bugs.actions.confirm.del_default_value}");
}

function insert_color(color, field)
{
	document.getElementById(field).value = color;
	document.getElementById(field).style.backgroundColor = color;
}

function bbcode_color_list(field)
{
	var i;
	var br;
	var contents;
	var color = new Array(
	'#000000', '#433026', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
	'#800000', '#ffa500', '#808000', '#008000', '#008080', '#0000ff', '#666699', '#808080',
	'#F04343', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
	'#ffc0cb', '#FFCC00', '#ffff00', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
	'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#CC99FF', '#FFFFFF');
	
	contents = '<table style="border-collapse:collapse;margin:auto;"><tr>';
	for(i = 0; i < 40; i++)
	{
		br = (i+1) % 8;
		br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
		contents += '<td style="padding:2px;"><a onclick="javascript:insert_color(\'' + color[i] + '\', \'' + field + '\');" class="bbcode_hover"><span style="background:' + color[i] + ';padding:0px 4px;border:1px solid #ACA899;">&nbsp;</span></a></td>' + br;
	}
	document.getElementById(field + '_list').innerHTML = contents + '</tr></table>';
}
-->
</script>
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
			<th class="column_color">
				{@bugs.labels.color}
			</th>
			<th>
				{L_NAME}
			</th>
		</tr>
		# START severities #
		<tr class="text_center">
			<td class="row2">
				<input type="radio" name="default_severity" value="{severities.ID}" {severities.IS_DEFAULT}>
			</td>
			<td class="row2">
				<input type="text" size="8" maxlength="7" name="s_color{severities.ID}" id="s_color{severities.ID}" value="{severities.COLOR}" style="background-color:{severities.COLOR};" class="text">
				<a href="javascript:bbcode_color_list('s_color{severities.ID}');bb_display_block('{severities.ID}', '');" onmouseout="bb_hide_block('{severities.ID}', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" /></a>
				<div style="position:relative;z-index:100;display:none;margin-left:85px;" id="bb_block{severities.ID}">
					<div id="s_color{severities.ID}_list" class="bbcode_block" style="background:white;width:150px;" onmouseover="bb_hide_block('{severities.ID}', '', 1);" onmouseout="bb_hide_block('{severities.ID}', '', 0);">
					</div>
				</div>
			</td>
			<td class="row2">
				<input type="text" maxlength="100" size="40" name="severity{severities.ID}" value="{severities.NAME}" class="text">
			</td>
		</tr>
		# END severities #
		# IF C_SEVERITIES #
			# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
		<tr>
			<td colspan="4" class="row3">
				<a href="{LINK_DELETE_DEFAULT}" onclick="javascript:return Confirm_del_default_value();"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" /> {@bugs.labels.del_default_value}</a>
			</td>
		</tr>
			# ENDIF #
		# ELSE #
			<tr class="text_center"> 
				<td colspan="4" class="row2">
					{@bugs.notice.no_severity}
				</td>
			</tr>
		# ENDIF #
	</table>
	<br />
</fieldset>