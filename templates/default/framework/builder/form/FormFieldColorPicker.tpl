<script>
<!--		
function ${escape(NAME)}insert_color(color, field)
{
	document.getElementById(field).value = color;
	document.getElementById(field).style.backgroundColor = color;
}

function ${escape(NAME)}bbcode_color(field)
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
		contents += '<td><a style="background:' + color[i] + ';" onclick="javascript:${escape(NAME)}insert_color(\'' + color[i] + '\', \'' + field + '\');"></a></td>' + br;
	}
	document.getElementById(field + '_list').innerHTML = contents + '</tr></table>';
}
-->
</script>

<input type="text" size="8" maxlength="7" name="${escape(NAME)}" id="${escape(HTML_ID)}" value="${escape(VALUE)}" style="background-color:${escape(VALUE)};" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_HIDDEN # style="display:none;" # ENDIF #>
<a href="javascript:${escape(NAME)}bbcode_color('${escape(HTML_ID)}');bb_display_block('${escape(HTML_ID)}', '');" onmouseout="bb_hide_block('${escape(HTML_ID)}', '', 0);">
	<img src="{PATH_TO_ROOT}/templates/default/images/color.png" alt="" class="valign-middle" />
</a>
<div class="color-picker" style="display:none;" id="bb-block${escape(HTML_ID)}">
	<div id="${escape(HTML_ID)}_list" class="bbcode-block" onmouseover="bb_hide_block('${escape(HTML_ID)}', '', 1);" onmouseout="bb_hide_block('${escape(HTML_ID)}', '', 0);">
	</div>
</div>

# INCLUDE ADD_FIELD_JS #