<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/bbcode.js"></script>
<script type="text/javascript">
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
	'#800000', '#ffa500', '#808000', '#008000', '#008080', '#0000ff', '#666699', '#808080',
	'#F04343', '#FF9900', '#99CC00', '#339966', '#33CCCC', '#3366FF', '#800080', '#ACA899',
	'#ffc0cb', '#FFCC00', '#ffff00', '#00FF00', '#00FFFF', '#00CCFF', '#993366', '#C0C0C0',
	'#FF99CC', '#FFCC99', '#FFFF99', '#CCFFCC', '#CCFFFF', '#CC99FF', '#CC99FF', '#FFFFFF');
	
	contents = '<table style="border-collapse:collapse;margin:auto;"><tr>';
	for(i = 0; i < 40; i++)
	{
		br = (i+1) % 8;
		br = (br == 0 && i != 0 && i < 39) ? '</tr><tr>' : '';
		contents += '<td style="padding:2px;"><a onclick="javascript:${escape(NAME)}insert_color(\'' + color[i] + '\', \'' + field + '\');" class="bbcode_hover"><span style="background:' + color[i] + ';padding:0px 4px;border:1px solid #ACA899;">&nbsp;</span></a></td>' + br;
	}
	document.getElementById(field + '_list').innerHTML = contents + '</tr></table>';
}
-->
</script>

<input type="text" size="8" maxlength="7" name="${escape(NAME)}" id="${escape(ID)}" value="${escape(VALUE)}" style="background-color:${escape(VALUE)};" class="text" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_HIDDEN # style="display:none;" # ENDIF # />
<a href="javascript:${escape(NAME)}bbcode_color('${escape(ID)}');bb_display_block('${escape(ID)}', '');" onmouseout="bb_hide_block('${escape(ID)}', '', 0);" class="bbcode_hover">
	<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" alt="" class="valign_middle" />
</a>
<div style="position:relative;z-index:100;display:none;margin-left:75px;" id="bb_block${escape(ID)}">
	<div id="${escape(ID)}_list" class="bbcode_block" onmouseover="bb_hide_block('${escape(ID)}', '', 1);" onmouseout="bb_hide_block('${escape(ID)}', '', 0);">
	</div>
</div>

# INCLUDE ADD_FIELD_JS #