<script type="text/javascript">
<!--
var displayed = new Array();
displayed[${escapejs(FIELD)}] = false;
function XMLHttpRequest_preview(field)
{
	if( XMLHttpRequest_preview.arguments.length == 0 )
		field = ${escapejs(FIELD)};

	var contents = $(field).value;
	var preview_field = 'xmlhttprequest_preview' + field;
	
	if( contents != "" )
	{
		if( !displayed[field] ) 
			Effect.BlindDown(preview_field, { duration: 0.5 });
		
		var loading = $('loading_preview' + field);
		if( loading )
			loading.style.display = 'block';
		displayed[field] = true;

		new Ajax.Request(
			'{PATH_TO_ROOT}/kernel/framework/ajax/content_xmlhttprequest.php',
			{
				method: 'post',
				parameters: {
					token: '{TOKEN}',
					path_to_root: '{PHP_PATH_TO_ROOT}',
					editor: 'BBCode',
					page_path: '{PAGE_PATH}',  
					contents: contents,
					ftags: '{FORBIDDEN_TAGS}'
				 },
				onSuccess: function(response)
				{
					$(preview_field).update(response.responseText);
					if( loading )
						loading.style.display = 'none';
				}
			}
		);
	}
	else
		alert("{L_REQUIRE_TEXT}");
}		
-->
</script>
<div style="position:relative;display:none;" id="loading_preview{FIELD}">
	<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;">
		<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading.gif" alt="" />
	</div>
</div>
<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_preview{FIELD}"></div>

	# IF C_EDITOR_NOT_ALREADY_INCLUDED #
		<script type="text/javascript" src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode.js"></script>
	# ENDIF #

	<div class="bbcode expand">
		<div class="bbcode-container">
			<i class="bbcode-icon-separate"></i>
	
			<div style="position:relative;z-index:100;margin-left:-50px;float:left;display:none;" id="bb_block1{FIELD}">
				<div class="bbcode_block" style="width:140px;" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);">
					# START smileys #
					<a onclick="insertbbcode('{smileys.CODE}', 'smile', '{FIELD}');" class="bbcode_hover" title="{smileys.CODE}"><img src="{smileys.URL}" style="height:{smileys.HEIGHT}px;width:{smileys.WIDTH}px;" alt="{smileys.CODE}"></a>{smileys.END_LINE}
					# END smileys #
					# IF C_BBCODE_SMILEY_MORE #
					<br />
					<a style="font-size: 10px;" href="#" onclick="window.open('{PATH_TO_ROOT}/BBCode/formatting/smileys.php?field={FIELD}', '{L_SMILEY}', 'height=550,width=650,resizable=yes,scrollbars=yes');return false;">{L_ALL_SMILEY}</a>
					# ENDIF #
				</div>
			</div>
			<a href="javascript:bb_display_block('1', '{FIELD}');" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_SMILEYS}">
				<i class="bbcode-icon-smileys" {AUTH_SMILEYS}></i>
			</a>
			
			<i class="bbcode-icon-separate"></i>
			
			<i class="bbcode-icon-bold" {AUTH_B} onclick="{DISABLED_B}insertbbcode('[b]', '[/b]', '{FIELD}');" title="{L_BB_BOLD}"></i>
			<i class="bbcode-icon-italic" {AUTH_I} onclick="{DISABLED_I}insertbbcode('[i]', '[/i]', '{FIELD}');" title="{L_BB_ITALIC}"></i>
			<i class="bbcode-icon-underline" {AUTH_U} onclick="{DISABLED_U}insertbbcode('[u]', '[/u]', '{FIELD}');" title="{L_BB_UNDERLINE}"></i>
			<i class="bbcode-icon-strike" {AUTH_S} onclick="{DISABLED_S}insertbbcode('[s]', '[/s]', '{FIELD}');" title="{L_BB_STRIKE}"></i>
			
			<i class="bbcode-icon-separate"></i>
			
			<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block2{FIELD}">
				<div style="margin-left:110px;" class="bbcode_block" onmouseover="bb_hide_block('2', '{FIELD}', 1);" onmouseout="bb_hide_block('2', '{FIELD}', 0);">
					<select id="title{FIELD}" onchange="insertbbcode_select('title', '[/title]', '{FIELD}')">
						<option value="" selected="selected" disabled="disabled">{L_TITLE}</option>
						<option value="1">{L_TITLE}1</option>
						<option value="2">{L_TITLE}2</option>
						<option value="3">{L_TITLE}3</option>
						<option value="4">{L_TITLE}4</option>
					</select>	
				</div>
			</div>
			<a href="javascript:{DISABLED_TITLE}bb_display_block('2', '{FIELD}');" onmouseout="{DISABLED_TITLE}bb_hide_block('2', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_TITLE}">
				<i class="bbcode-icon-title" {AUTH_TITLE}></i>
			</a>
			
			<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block3{FIELD}">
				<div style="margin-left:135px;" class="bbcode_block" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);">
					<select id="blocks{FIELD}" onchange="insertbbcode_select2('blocks', '{FIELD}')">
						<option value="" selected="selected" disabled="disabled">{L_CONTAINER}</option>
						<option value="block">{L_BLOCK}</option>
						<option value="fieldset">{L_FIELDSET}</option>
					</select>	
				</div>
			</div>
			<a href="javascript:{DISABLED_BLOCK}bb_display_block('3', '{FIELD}');" onmouseout="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_CONTAINER}">
				<i class="bbcode-icon-subtitle" {AUTH_BLOCK}></i>
			</a>
			
			<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block4{FIELD}">
				<div style="margin-left:160px;" class="bbcode_block" onmouseover="bb_hide_block('4', '{FIELD}', 1);" onmouseout="bb_hide_block('4', '{FIELD}', 0);">
					<select id="style{FIELD}" onchange="insertbbcode_select('style', '[/style]', '{FIELD}')">
						<option value="" selected="selected" disabled="disabled">{L_STYLE}</option>
						<option value="success">{L_SUCCESS}</option>
						<option value="question">{L_QUESTION}</option>
						<option value="notice">{L_NOTICE}</option>
						<option value="warning">{L_WARNING}</option>
						<option value="error">{L_ERROR}</option>
					</select>	
				</div>
			</div>
			<a href="javascript:{DISABLED_STYLE}bb_display_block('4', '{FIELD}');" onmouseout="{DISABLED_STYLE}bb_hide_block('4', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_STYLE}">
				<i class="bbcode-icon-style" {AUTH_STYLE}></i>
			</a>
			
			<i class="bbcode-icon-separate"></i>
			
			<i class="bbcode-icon-url" {AUTH_URL} onclick="{DISABLED_URL}bbcode_url('{FIELD}', '{L_URL_PROMPT}');" title="{L_BB_URL}"></i>
			<i class="bbcode-icon-image" {AUTH_IMG} onclick="{DISABLED_IMG}insertbbcode('[img]', '[/img]', '{FIELD}');" title="{L_BB_IMAGE}"></i>
			<i class="bbcode-icon-quote" {AUTH_QUOTE} onclick="{DISABLED_QUOTE}insertbbcode('[quote]', '[/quote]', '{FIELD}');" title="{L_BB_QUOTE}"></i>
			<i class="bbcode-icon-hide" {AUTH_HIDE} onclick="{DISABLED_HIDE}insertbbcode('[hide]', '[/hide]', '{FIELD}');" title="{L_BB_HIDE}"></i>
			
			<div style="position:relative;z-index:100;float:right;display:none;" id="bb_block9{FIELD}">
				<div class="bbcode_block" style="margin-left:-220px;width:180px;" onmouseover="bb_hide_block('9', '{FIELD}', 1);" onmouseout="bb_hide_block('9', '{FIELD}', 0);">
					<p><label style="font-size:10px;font-weight:normal">* {L_LINES} <input size="3" type="text" class="text" name="bb_list{FIELD}" id="bb_list{FIELD}" maxlength="3" value="3"></label></p>
					<p><label style="font-size:10px;font-weight:normal">{L_ORDERED_LIST} <input size="3" type="checkbox" name="bb_ordered_list{FIELD}" id="bb_ordered_list{FIELD}"></label></p>
					<p style="text-align:center;"><a class="small" href="javascript:bbcode_list('{FIELD}');">
						<i class="bbcode-icon-list" title="{L_BB_LIST}" class="valign_middle"></i> {L_INSERT_LIST}
					</a></p>
				</div>
			</div>
			<a href="javascript:{DISABLED_LIST}bb_display_block('9', '{FIELD}');" onmouseout="{DISABLED_LIST}bb_hide_block('9', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_LIST}">
				<i class="bbcode-icon-list" {AUTH_LIST}></i>
			</a>
			
			<i class="bbcode-icon-separate"></i>
			
			<div class="color_picker" style="display:none;" id="bb_block5{FIELD}">
				<div id="bbcolor{FIELD}" class="bbcode_block" onmouseover="bb_hide_block('5', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);">
				</div>
			</div>
			<a href="javascript:{DISABLED_COLOR}bbcode_color('{FIELD}');{DISABLED_COLOR}bb_display_block('5', '{FIELD}');" onmouseout="{DISABLED_COLOR}bb_hide_block('5', '{FIELD}', 0);" title="{L_BB_COLOR}">
				<i class="bbcode-icon-color" {AUTH_COLOR}></i>
			</a>				
			
			<div style="position:relative;z-index:100;margin-left:-70px;float:right;display:none;" id="bb_block6{FIELD}">
				<div style="margin-left:-120px;" class="bbcode_block" onmouseover="bb_hide_block('6', '{FIELD}', 1);" onmouseout="bb_hide_block('6', '{FIELD}', 0);">
					<select id="size{FIELD}" onchange="insertbbcode_select('size', '[/size]', '{FIELD}')">
						<option value="" selected="selected" disabled="disabled">{L_SIZE}</option>
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="15">15</option>
						<option value="20">20</option>
						<option value="25">25</option>
						<option value="30">30</option>
						<option value="35">35</option>
						<option value="40">40</option>
						<option value="45">45</option>
					</select>
				</div>
			</div>
			<a href="javascript:{DISABLED_SIZE}bb_display_block('6', '{FIELD}');" onmouseout="{DISABLED_SIZE}bb_hide_block('6', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_SIZE}">
				<i class="bbcode-icon-size" {AUTH_SIZE}></i>
			</a>			

			<i class="bbcode-icon-separate"></i>
			&nbsp;
			<i class="bbcode-icon-minus" style="cursor: pointer;cursor:hand;" onclick="textarea_resize('{FIELD}', -100, 'height');textarea_resize('xmlhttprequest_preview', -100, 'height');" title="{L_BB_SMALL}"></i>
			<i class="bbcode-icon-plus" style="cursor: pointer;cursor:hand;" onclick="textarea_resize('{FIELD}', 100, 'height');textarea_resize('xmlhttprequest_preview', 100, 'height');" title="{L_BB_LARGE}"></i>

			<a onclick="show_bbcode_div('bbcode_more{FIELD}', 1);"><i class="bbcode-icon-more" title="{L_BB_MORE}" class="bbcode_hover"></i></a>
		</div>
		# IF C_UPLOAD_MANAGEMENT #
			<div class="bbcode-upload">
				<a title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=720,resizable=yes,scrollbars=yes');return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="" /></a>
			</div>
		# ENDIF #
		<div class="bbcode-container" id="bbcode_more{FIELD}">
			<i class="bbcode-icon-separate"></i>
			
			<i class="bbcode-icon-left" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=left]', '[/align]', '{FIELD}');" title="{L_BB_LEFT}"></i>
			<i class="bbcode-icon-center" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=center]', '[/align]', '{FIELD}');" title="{L_BB_CENTER}"></i>
			<i class="bbcode-icon-right" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=right]', '[/align]', '{FIELD}');" title="{L_BB_RIGHT}"></i>
			<i class="bbcode-icon-justify" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=justify]', '[/align]', '{FIELD}');" title="{L_BB_JUSTIFY}"></i>
			<i class="bbcode-icon-separate" alt=""></i>
			
			<i class="bbcode-icon-float_left" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=left]', '[/float]', '{FIELD}');" title="{L_BB_FLOAT_LEFT}"></i>
			<i class="bbcode-icon-float_right" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=right]', '[/float]', '{FIELD}');" title="{L_BB_FLOAT_RIGHT}"></i>
			<i class="bbcode-icon-sup" {AUTH_SUP} onclick="{DISABLED_SUP}insertbbcode('[sup]', '[/sup]', '{FIELD}');" title="{L_BB_SUP}"></i>
			<i class="bbcode-icon-sub" {AUTH_SUB} onclick="{DISABLED_SUB}insertbbcode('[sub]', '[/sub]', '{FIELD}');" title="{L_BB_SUB}"></i>
			<i class="bbcode-icon-indent" {AUTH_INDENT} onclick="{DISABLED_INDENT}insertbbcode('[indent]', '[/indent]', '{FIELD}');" title="{L_BB_INDENT}"></i>
			
			<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block7{FIELD}">
				<div id="bbtable{FIELD}" class="bbcode_block" style="margin-left:130px;width:180px;" onmouseover="bb_hide_block('7', '{FIELD}', 1);" onmouseout="bb_hide_block('7', '{FIELD}', 0);">
					<p><label style="font-size:10px;font-weight:normal">* {L_LINES} <input size="3" type="text" class="text" name="bb_lines{FIELD}" id="bb_lines{FIELD}" maxlength="3" value="2"></label></p>
					<p><label style="font-size:10px;font-weight:normal">* {L_COLS} <input size="3" type="text" class="text" name="bb_cols{FIELD}" id="bb_cols{FIELD}" maxlength="3" value="2"></label></p>
					<p><label style="font-size:10px;font-weight:normal">{L_ADD_HEAD} <input size="3" type="checkbox" name="bb_head{FIELD}" id="bb_head{FIELD}"></label></p>
					<p style="text-align:center;"><a class="small" href="javascript:{DISABLED_TABLE}bbcode_table('{FIELD}');">
						<i class="bbcode-icon-table" title="{L_BB_TABLE}"></i>
					{L_INSERT_TABLE}</a></p>
				</div>
			</div>
			<a href="javascript:{DISABLED_TABLE}bb_display_block('7', '{FIELD}');" onmouseover="{DISABLED_TABLE}bb_hide_block('7', '{FIELD}', 1);" class="bbcode_hover" title="{L_BB_TABLE}">
				<i class="bbcode-icon-table" {AUTH_TABLE}></i>
			</a>   
			
			<i class="bbcode-icon-separate"></i>
			
			<i class="bbcode-icon-flash" {AUTH_SWF} onclick="{DISABLED_SWF}insertbbcode('[swf=425,344]', '[/swf]', '{FIELD}');" title="{L_BB_SWF}"></i>
			<i class="bbcode-icon-movie" {AUTH_MOVIE} onclick="{DISABLED_MOVIE}insertbbcode('[movie=100,100]', '[/movie]', '{FIELD}');"title="{L_BB_MOVIE}"></i>
			<i class="bbcode-icon-sound" {AUTH_SOUND} onclick="{DISABLED_SOUND}insertbbcode('[sound]', '[/sound]', '{FIELD}');" title="{L_BB_SOUND}"></i>
			
			<i class="bbcode-icon-separate"></i>
			
			<div style="position:relative;z-index:100;margin-left:-70px;float:right;display:none;" id="bb_block8{FIELD}">
				<div style="margin-left:-120px;" class="bbcode_block" onmouseover="bb_hide_block('8', '{FIELD}', 1);" onmouseout="bb_hide_block('8', '{FIELD}', 0);">
					<select id="code{FIELD}" onchange="insertbbcode_select('code', '[/code]', '{FIELD}')">
						<option value="" selected="selected" disabled="disabled">{L_CODE}</option>
						<optgroup label="{L_TEXT}">
							<option value="text">Text</option>
							<option value="sql">Sql</option>
							<option value="xml">Xml</option>										
						</optgroup>
						<optgroup label="{L_PHPBOOST_LANGUAGES}">
							<option value="bbcode">BBCode</option>
							<option value="tpl">Template</option>
						</optgroup>
						<optgroup label="{L_SCRIPT}">
							<option value="php">PHP</option>
							<option value="asp">Asp</option>
							<option value="python">Python</option>
							<option value="perl">Perl</option>
							<option value="ruby">Ruby</option>
							<option value="bash">Bash</option>
						</optgroup>
						<optgroup label="{L_WEB}">	
							<option value="html">Html</option>
							<option value="css">Css</option>
							<option value="javascript">Javascript</option>
						</optgroup>
						<optgroup label="{L_PROG}">
							<option value="c">C</option>
							<option value="cpp">C++</option>
							<option value="c#">C#</option>
							<option value="d">D</option>
							<option value="java">Java</option>
							<option value="pascal">Pascal</option>
							<option value="delphi">Delphi</option>
							<option value="fortran">Fortran</option>
							<option value="vb">Vb</option>
							<option value="asm">Asm</option>
						</optgroup>
					</select>
				</div>
			</div>
			<a href="javascript:{DISABLED_CODE}bb_display_block('8', '{FIELD}');" onmouseout="{DISABLED_CODE}bb_hide_block('8', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_CODE}">
				<i class="bbcode-icon-code" {AUTH_CODE}></i>
			</a>
			
			<i class="bbcode-icon-math" {AUTH_MATH} onclick="{DISABLED_MATH}insertbbcode('[math]', '[/math]', '{FIELD}');" title="{L_BB_MATH}"></i>	
			<i class="bbcode-icon-html" {AUTH_HTML} onclick="{DISABLED_HTML}insertbbcode('[html]', '[/html]', '{FIELD}');" title="{L_BB_HTML}"></i>

			<i class="bbcode-icon-separate"></i>

			<a href="http://phpboost.com/wiki/bbcode" title="{L_BB_HELP}"><i class="bbcode-icon-help"></i></a>
		</div>
	</div>
	
	<script type="text/javascript">
	<!--
	set_bbcode_preference('bbcode_more{FIELD}');
	-->
	</script>