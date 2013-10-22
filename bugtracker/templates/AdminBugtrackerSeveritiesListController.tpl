<script type="text/javascript">
<!--
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
	'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#E3007B', '#FFFFFF');
	
	contents = '<table><tr>';
	for(i = 0; i < 40; i++)
	{
		br = (i+1) % 8;
		br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
		contents += '<td><a style="background:' + color[i] + ';" onclick="javascript:insert_color(\'' + color[i] + '\', \'' + field + '\');"></a></td>' + br;
	}
	document.getElementById(field + '_list').innerHTML = contents + '</tr></table>';
}
-->
</script>
<table>
	<thead>
		<tr>
			<th class="column_default">
				{@bugs.labels.default}
			</th>
			<th class="column_color">
				{@bugs.labels.color}
			</th>
			<th>
				${LangLoader::get_message('name', 'main')}
			</th>
		</tr>
	</thead>
	<tfoot>
	# IF C_SEVERITIES #
		# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
	<tr>
		<th colspan="4">
			<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'main')}" class="pbt-icon-delete" data-confirmation="{@bugs.actions.confirm.del_default_value}">{@bugs.labels.del_default_value}</a>
		</th>
	</tr>
		# ENDIF #
	# ENDIF #
	</tfoot>
	<tbody>
		# START severities #
		<tr>
			<td>
				<input type="radio" name="default_severity" value="{severities.ID}"# IF severities.C_IS_DEFAULT # checked="checked"# ENDIF #>
			</td>
			<td>
				<input type="text" size="8" maxlength="7" name="s_color{severities.ID}" id="s_color{severities.ID}" value="{severities.COLOR}" style="background-color:{severities.COLOR};" class="text">
				<a href="javascript:bbcode_color_list('s_color{severities.ID}');bb_display_block('{severities.ID}', '');" onmouseout="bb_hide_block('{severities.ID}', '', 0);" class="bbcode_hover"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/color.png" alt="" class="valign_middle" /></a>
				<div class="color_picker" style="display:none;" id="bb_block{severities.ID}">
					<div id="s_color{severities.ID}_list" class="bbcode_block" onmouseover="bb_hide_block('{severities.ID}', '', 1);" onmouseout="bb_hide_block('{severities.ID}', '', 0);">
					</div>
				</div>
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="severity{severities.ID}" value="{severities.NAME}" class="text">
			</td>
		</tr>
		# END severities #
		# IF NOT C_SEVERITIES #
		<tr> 
			<td colspan="4">
				{@bugs.notice.no_severity}
			</td>
		</tr>
		# ENDIF #
	</tbody>
</table>
