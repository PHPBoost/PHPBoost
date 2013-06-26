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
	<table style="margin:4px;margin-left:auto;margin-right:auto;">
		<tr>
			<td>
				<table class="bbcode">
					<tr>
						<td style="padding:1px;">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
	
							<div style="position:relative;z-index:100;margin-left:-50px;float:left;display:none;" id="bb_block1{FIELD}">
								<div class="bbcode_block" style="width:130px;" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);">
									# START smileys #
									<a onclick="insertbbcode('{smileys.CODE}', 'smile', '{FIELD}');" class="bbcode_hover" title="{smileys.CODE}"><img src="{smileys.URL}" style="height:{smileys.HEIGHT};width:{smileys.WIDTH};"></a>{smileys.END_LINE}
									# END smileys #
									# IF C_BBCODE_SMILEY_MORE #
									<br />
									<a style="font-size: 10px;" href="#" onclick="window.open('{PATH_TO_ROOT}/BBCode/formatting/smileys.php?field={FIELD}', '{L_SMILEY}', 'height=550,width=650,resizable=yes,scrollbars=yes');return false;">{L_ALL_SMILEY}</a>
									# ENDIF #
								</div>
							</div>
							<a href="javascript:bb_display_block('1', '{FIELD}');" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_SMILEYS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/smileys.png"  {AUTH_SMILEYS} alt="{L_BB_SMILEYS}" /></a>
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/bold.png" class="bbcode_hover" {AUTH_B} onclick="{DISABLED_B}insertbbcode('[b]', '[/b]', '{FIELD}');" alt="{L_BB_BOLD}" title="{L_BB_BOLD}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/italic.png" class="bbcode_hover" {AUTH_I} onclick="{DISABLED_I}insertbbcode('[i]', '[/i]', '{FIELD}');" alt="{L_BB_ITALIC}" title="{L_BB_ITALIC}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/underline.png" class="bbcode_hover" {AUTH_U} onclick="{DISABLED_U}insertbbcode('[u]', '[/u]', '{FIELD}');" alt="{L_BB_UNDERLINE}" title="{L_BB_UNDERLINE}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/strike.png" class="bbcode_hover" {AUTH_S} onclick="{DISABLED_S}insertbbcode('[s]', '[/s]', '{FIELD}');" alt="{L_BB_STRIKE}" title="{L_BB_STRIKE}" />
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
							
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
							<a href="javascript:{DISABLED_TITLE}bb_display_block('2', '{FIELD}');" onmouseout="{DISABLED_TITLE}bb_hide_block('2', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_TITLE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/title.png" {AUTH_TITLE} alt="{L_BB_TITLE}" /></a>
							
							<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block3{FIELD}">
								<div style="margin-left:135px;" class="bbcode_block" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);">
									<select id="blocks{FIELD}" onchange="insertbbcode_select2('blocks', '{FIELD}')">
										<option value="" selected="selected" disabled="disabled">{L_CONTAINER}</option>
										<option value="block">{L_BLOCK}</option>
										<option value="fieldset">{L_FIELDSET}</option>
									</select>	
								</div>
							</div>
							<a href="javascript:{DISABLED_BLOCK}bb_display_block('3', '{FIELD}');" onmouseout="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_CONTAINER}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/subtitle.png" {AUTH_BLOCK} alt="{L_BB_CONTAINER}" /></a>
							
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
							<a href="javascript:{DISABLED_STYLE}bb_display_block('4', '{FIELD}');" onmouseout="{DISABLED_STYLE}bb_hide_block('4', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_STYLE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/style.png" {AUTH_STYLE} alt="{L_BB_STYLE}" /></a>
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/url.png" class="bbcode_hover" {AUTH_URL} onclick="{DISABLED_URL}bbcode_url('{FIELD}', '{L_URL_PROMPT}');" alt="{L_BB_URL}" title="{L_BB_URL}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/image.png" class="bbcode_hover" {AUTH_IMG} onclick="{DISABLED_IMG}insertbbcode('[img]', '[/img]', '{FIELD}');" alt="{L_BB_IMAGE}" title="{L_BB_IMAGE}" />			
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/quote.png" class="bbcode_hover" {AUTH_QUOTE} onclick="{DISABLED_QUOTE}insertbbcode('[quote]', '[/quote]', '{FIELD}');" alt="{L_BB_QUOTE}" title="{L_BB_QUOTE}" />		
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/hide.png" class="bbcode_hover" {AUTH_HIDE} onclick="{DISABLED_HIDE}insertbbcode('[hide]', '[/hide]', '{FIELD}');" alt="{L_BB_HIDE}" title="{L_BB_HIDE}" />	
							
							<div style="position:relative;z-index:100;float:right;display:none;" id="bb_block9{FIELD}">
								<div class="bbcode_block" style="margin-left:-220px;width:180px;" onmouseover="bb_hide_block('9', '{FIELD}', 1);" onmouseout="bb_hide_block('9', '{FIELD}', 0);">
									<p><label style="font-size:10px;font-weight:normal">* {L_LINES} <input size="3" type="text" class="text" name="bb_list{FIELD}" id="bb_list{FIELD}" maxlength="3" value="3" /></label></p>
									<p><label style="font-size:10px;font-weight:normal">{L_ORDERED_LIST} <input size="3" type="checkbox" name="bb_ordered_list{FIELD}" id="bb_ordered_list{FIELD}" /></label></p>
									<p style="text-align:center;"><a class="small_link" href="javascript:bbcode_list('{FIELD}');"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/list.png" alt="{L_BB_LIST}" title="{L_BB_LIST}" class="valign_middle" /> {L_INSERT_LIST}</a></p>
								</div>
							</div>
							<a href="javascript:{DISABLED_LIST}bb_display_block('9', '{FIELD}');" onmouseout="{DISABLED_LIST}bb_hide_block('9', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_LIST}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/list.png" {AUTH_LIST} alt="{L_BB_LIST}" title="{L_BB_LIST}" /></a>
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
							
							<div style="position:relative;z-index:100;float:right;display:none;" id="bb_block5{FIELD}">
								<div id="bbcolor{FIELD}" class="bbcode_block" style="margin-left:-170px;background:white;" onmouseover="bb_hide_block('5', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);">
								</div>
							</div>
							<a href="javascript:{DISABLED_COLOR}bbcode_color('{FIELD}');{DISABLED_COLOR}bb_display_block('5', '{FIELD}');" onmouseout="{DISABLED_COLOR}bb_hide_block('5', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_COLOR}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/color.png" {AUTH_COLOR} alt="{L_BB_COLOR}" /></a>					
							
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
							<a href="javascript:{DISABLED_SIZE}bb_display_block('6', '{FIELD}');" onmouseout="{DISABLED_SIZE}bb_hide_block('6', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_SIZE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/size.png" {AUTH_SIZE} alt="{L_BB_SIZE}" /></a>			
	
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
							&nbsp;
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/minus.png" style="cursor: pointer;cursor:hand;" onclick="textarea_resize('{FIELD}', -100, 'height');textarea_resize('xmlhttprequest_preview', -100, 'height');" alt="{L_BB_SMALL}" title="{L_BB_SMALL}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/plus.png" style="cursor: pointer;cursor:hand;" onclick="textarea_resize('{FIELD}', 100, 'height');textarea_resize('xmlhttprequest_preview', 100, 'height');" alt="{L_BB_LARGE}" title="{L_BB_LARGE}" />
	
							<a onclick="show_bbcode_div('bbcode_more{FIELD}', 1);"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/more.png" title="{L_BB_MORE}" alt="{L_BB_MORE}" class="bbcode_hover" /></a>
						</td>
					</tr>	
				</table>
				<table class="bbcode2" id="bbcode_more{FIELD}">
					<tr>
						<td style="width:100%;padding:1px;">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/left.png" class="bbcode_hover" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=left]', '[/align]', '{FIELD}');" alt="{L_BB_LEFT}" title="{L_BB_LEFT}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/center.png" class="bbcode_hover" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=center]', '[/align]', '{FIELD}');" alt="{L_BB_CENTER}" title="{L_BB_CENTER}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/right.png" class="bbcode_hover" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=right]', '[/align]', '{FIELD}');" alt="{L_BB_RIGHT}" title="{L_BB_RIGHT}" />	
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/justify.png" class="bbcode_hover" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=justify]', '[/align]', '{FIELD}');" alt="{L_BB_JUSTIFY}" title="{L_BB_JUSTIFY}" />	
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/float_left.png" class="bbcode_hover" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=left]', '[/float]', '{FIELD}');" alt="{L_BB_FLOAT_LEFT}" title="{L_BB_FLOAT_LEFT}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/float_right.png" class="bbcode_hover" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=right]', '[/float]', '{FIELD}');" alt="{L_BB_FLOAT_RIGHT}" title="{L_BB_FLOAT_RIGHT}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/sup.png" class="bbcode_hover" {AUTH_SUP} onclick="{DISABLED_SUP}insertbbcode('[sup]', '[/sup]', '{FIELD}');" alt="{L_BB_SUP}" title="{L_BB_SUP}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/sub.png" class="bbcode_hover" {AUTH_SUB} onclick="{DISABLED_SUB}insertbbcode('[sub]', '[/sub]', '{FIELD}');" alt="{L_BB_SUB}" title="{L_BB_SUB}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/indent.png" class="bbcode_hover" {AUTH_INDENT} onclick="{DISABLED_INDENT}insertbbcode('[indent]', '[/indent]', '{FIELD}');" alt="{L_BB_INDENT}" title="{L_BB_INDENT}" />
							
							<div style="position:relative;z-index:100;float:left;display:none;" id="bb_block7{FIELD}">
								<div id="bbtable{FIELD}" class="bbcode_block" style="margin-left:130px;width:180px;" onmouseover="bb_hide_block('7', '{FIELD}', 1);" onmouseout="bb_hide_block('7', '{FIELD}', 0);">
									<p><label style="font-size:10px;font-weight:normal">* {L_LINES} <input size="3" type="text" class="text" name="bb_lines{FIELD}" id="bb_lines{FIELD}" maxlength="3" value="2" /></label></p>
									<p><label style="font-size:10px;font-weight:normal">* {L_COLS} <input size="3" type="text" class="text" name="bb_cols{FIELD}" id="bb_cols{FIELD}" maxlength="3" value="2" /></label></p>
									<p><label style="font-size:10px;font-weight:normal">{L_ADD_HEAD} <input size="3" type="checkbox" name="bb_head{FIELD}" id="bb_head{FIELD}" /></label></p>
									<p style="text-align:center;"><a class="small_link" href="javascript:{DISABLED_TABLE}bbcode_table('{FIELD}');"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/table.png" alt="{L_BB_TABLE}" title="{L_BB_TABLE}" class="valign_middle" /> {L_INSERT_TABLE}</a></p>
								</div>
							</div>
							<a href="javascript:{DISABLED_TABLE}bb_display_block('7', '{FIELD}');" onmouseover="{DISABLED_TABLE}bb_hide_block('7', '{FIELD}', 1);" class="bbcode_hover" title="{L_BB_TABLE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/table.png" {AUTH_TABLE} alt="{L_BB_TABLE}" title="{L_BB_TABLE}" /></a>   
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/flash.png" class="bbcode_hover" {AUTH_SWF} onclick="{DISABLED_SWF}insertbbcode('[swf=425,344]', '[/swf]', '{FIELD}');" alt="{L_BB_SWF}" title="{L_BB_SWF}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/movie.png" class="bbcode_hover" {AUTH_MOVIE} onclick="{DISABLED_MOVIE}insertbbcode('[movie=100,100]', '[/movie]', '{FIELD}');" alt="{L_BB_MOVIE}" title="{L_BB_MOVIE}" />
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/sound.png" class="bbcode_hover" {AUTH_SOUND} onclick="{DISABLED_SOUND}insertbbcode('[sound]', '[/sound]', '{FIELD}');" alt="{L_BB_SOUND}" title="{L_BB_SOUND}" />
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
							
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
							<a href="javascript:{DISABLED_CODE}bb_display_block('8', '{FIELD}');" onmouseout="{DISABLED_CODE}bb_hide_block('8', '{FIELD}', 0);" class="bbcode_hover" title="{L_BB_CODE}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/code.png" {AUTH_CODE} alt="{L_BB_CODE}" /></a>
							
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/math.png" class="bbcode_hover" {AUTH_MATH} onclick="{DISABLED_MATH}insertbbcode('[math]', '[/math]', '{FIELD}');" alt="{L_BB_MATH}" title="{L_BB_MATH}" />	
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/html.png" class="bbcode_hover" {AUTH_HTML} onclick="{DISABLED_HTML}insertbbcode('[html]', '[/html]', '{FIELD}');" alt="{L_BB_HTML}" title="{L_BB_HTML}" />
						</td>
						<td style="width:3px;">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/separate.png" alt="" />
						</td>
						<td style="padding:0px 2px;width:22px;">
							<a href="http://www.phpboost.com/wiki/bbcode" title="{L_BB_HELP}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/form/help.png" alt="{L_BB_HELP}" /></a>
						</td>
					</tr>	
				</table>
			</td>
			<td style="vertical-align:top;padding-left:8px;padding-top:5px;">
				# IF C_UPLOAD_MANAGEMENT #
				<a title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=720,resizable=yes,scrollbars=yes');return false;"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/upload/files_add.png" alt="" /></a>
				# ENDIF #
			</td>
		</tr>
	</table>
			
	<script type="text/javascript">
	<!--
	set_bbcode_preference('bbcode_more{FIELD}');
	-->
	</script>