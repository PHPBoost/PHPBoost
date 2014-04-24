<script>
<!--
function insert_severity_color(color, field)
{
	document.getElementById(field).value = color;
	document.getElementById(field).style.backgroundColor = color;
}

function severity_bbcode_color(field)
{
	var i;
	var br;
	var contents;
	var color = new Array(
	'#000000', '#433026', '#333300', '#003300', '#003366', '#000080', '#333399', '#333333',
	'#800000', '#FFA500', '#808000', '#008000', '#008080', '#0000FF', '#666699', '#808080',
	'#F04343', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
	'#FFC0CB', '#FFCC00', '#FFFF00', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
	'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#E3007B', '#FFFFFF');
	
	contents = '<table><tr>';
	for(i = 0; i < 40; i++)
	{
		br = (i+1) % 8;
		br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
		contents += '<td><a style="background:' + color[i] + ';" onclick="javascript:insert_severity_color(\'' + color[i] + '\', \'' + field + '\');"></a></td>' + br;
	}
	document.getElementById(field + '_list').innerHTML = contents + '</tr></table>';
}
-->
</script>

<table>
	<thead>
		<tr>
			<th class="small_column">
				{@labels.default}
			</th>
			<th class="medium_column">
				{@labels.color}
			</th>
			<th>
				${LangLoader::get_message('name', 'main')}
			</th>
		</tr>
	</thead>
	# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
	<tfoot>
	<tr>
		<th colspan="3">
			<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'main')}" data-confirmation="{@actions.confirm.del_default_value}"><i class="fa fa-delete"></i> {@labels.del_default_value}</a>
		</th>
	</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		# START severities #
		<tr>
			<td>
				<input type="radio" name="default_severity" value="{severities.ID}"# IF severities.C_IS_DEFAULT # checked="checked"# ENDIF # />
			</td>
			<td>
				<input type="text" size="8" maxlength="7" name="color{severities.ID}" id="color{severities.ID}" value="{severities.COLOR}" style="background-color:{severities.COLOR};" />
				<a href="javascript:severity_bbcode_color('color{severities.ID}');bb_display_block('color{severities.ID}', '');" onmouseout="bb_hide_block('color{severities.ID}', '', 0);">
					<img src="{PATH_TO_ROOT}/templates/default/images/color.png" alt="" class="valign-middle" />
				</a>
				<div class="color-picker" style="display:none;" id="bb-blockcolor{severities.ID}">
					<div id="color{severities.ID}_list" class="bbcode-block" style="left:132px;bottom:27px;" onmouseover="bb_hide_block('color{severities.ID}', '', 1);" onmouseout="bb_hide_block('color{severities.ID}', '', 0);">
					</div>
				</div>
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="severity{severities.ID}" value="{severities.NAME}" />
			</td>
		</tr>
		# END severities #
	</tbody>
</table>
