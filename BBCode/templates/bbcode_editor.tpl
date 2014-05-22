<script>
<!--
var displayed = new Array();
displayed[${escapejs(FIELD)}] = false;
function XMLHttpRequest_preview(field)
{
	if( XMLHttpRequest_preview.arguments.length == 0 )
		field = ${escapejs(FIELD)};

	var contents = $(field).value;
	var preview_field = 'xmlhttprequest-preview' + field;
	
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
		<i class="fa fa-spinner fa-2x fa-spin"></i>
	</div>
</div>

<div style="display:none;" class="xmlhttprequest-preview" id="xmlhttprequest-preview{FIELD}"></div>

# IF C_EDITOR_NOT_ALREADY_INCLUDED #
	<script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode.js"></script>
# ENDIF #

<div class="bbcode expand">
	# IF C_UPLOAD_MANAGEMENT #
		<div class="bbcode-upload" style="float:right;">
			<a title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=720,resizable=yes,scrollbars=yes');return false;">
				<i class="fa bbcode-icon-upload"></i>
			</a>
		</div>
	# ENDIF #		
	
	<div class="bbcode-containers">
		<ul class="bbcode-container">
			<li class="bbcode-elements">
				<a href="javascript:bb_display_block('1', '{FIELD}');" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_SMILEYS}">
					<i class="fa bbcode-icon-smileys" {AUTH_SMILEYS}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block1{FIELD}">
					<div class="bbcode-block" style="width:140px;" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);">
						# START smileys #
							<a href="" onclick="insertbbcode('{smileys.CODE}', 'smile', '{FIELD}');return false;" class="bbcode-hover" title="{smileys.CODE}"><img src="{smileys.URL}" alt="{smileys.CODE}"></a>{smileys.END_LINE}
						# END smileys #
						# IF C_BBCODE_SMILEY_MORE #
							<br /><br />
							<a href="" onclick="window.open('{PATH_TO_ROOT}/BBCode/formatting/smileys.php?field={FIELD}', '{L_SMILEY}', 'height=550,width=650,resizable=yes,scrollbars=yes');return false;" style="font-size: 10px;">{L_ALL_SMILEY}</a>
						# ENDIF #
					</div>
				</div>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-bold" {AUTH_B} onclick="{DISABLED_B}insertbbcode('[b]', '[/b]', '{FIELD}');return false;" title="{L_BB_BOLD}"></a>
				<a href="" class="fa bbcode-icon-italic" {AUTH_I} onclick="{DISABLED_I}insertbbcode('[i]', '[/i]', '{FIELD}');return false;" title="{L_BB_ITALIC}"></a>
				<a href="" class="fa bbcode-icon-underline" {AUTH_U} onclick="{DISABLED_U}insertbbcode('[u]', '[/u]', '{FIELD}');return false;" title="{L_BB_UNDERLINE}"></a>
				<a href="" class="fa bbcode-icon-strike" {AUTH_S} onclick="{DISABLED_S}insertbbcode('[s]', '[/s]', '{FIELD}');return false;" title="{L_BB_STRIKE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_TITLE}bb_display_block('2', '{FIELD}');" onmouseout="{DISABLED_TITLE}bb_hide_block('2', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_TITLE}">
					<i class="fa bbcode-icon-title" {AUTH_TITLE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block2{FIELD}">
					<div class="bbcode-block" style="margin-left:100px;" onmouseover="bb_hide_block('2', '{FIELD}', 1);" onmouseout="bb_hide_block('2', '{FIELD}', 0);">
						<select id="title{FIELD}" onchange="insertbbcode_select('title', '[/title]', '{FIELD}')">
							<option value="" selected="selected" disabled="disabled">{L_TITLE}</option>
							<option value="1">{L_TITLE}1</option>
							<option value="2">{L_TITLE}2</option>
							<option value="3">{L_TITLE}3</option>
							<option value="4">{L_TITLE}4</option>
						</select>
					</div>
				</div>
					
				<a href="javascript:{DISABLED_BLOCK}bb_display_block('3', '{FIELD}');" onmouseout="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_CONTAINER}">
					<i class="fa bbcode-icon-subtitle" {AUTH_BLOCK}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block3{FIELD}">
					<div class="bbcode-block" style="margin-left:110px;" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);">
						<select id="blocks{FIELD}" onchange="insertbbcode_select2('blocks', '{FIELD}')">
							<option value="" selected="selected" disabled="disabled">{L_CONTAINER}</option>
							<option value="block">{L_BLOCK}</option>
							<option value="fieldset">{L_FIELDSET}</option>
						</select>
					</div>
				</div>
				
				<a href="javascript:{DISABLED_STYLE}bb_display_block('4', '{FIELD}');" onmouseout="{DISABLED_STYLE}bb_hide_block('4', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_STYLE}">
					<i class="fa bbcode-icon-style" {AUTH_STYLE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block4{FIELD}">
					<div class="bbcode-block" style="margin-left:120px;" onmouseover="bb_hide_block('4', '{FIELD}', 1);" onmouseout="bb_hide_block('4', '{FIELD}', 0);">
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
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-url" {AUTH_URL} onclick="{DISABLED_URL}bbcode_url('{FIELD}', ${escapejs(L_URL_PROMPT)});return false;" title="{L_BB_URL}"></a>
				<a href="" class="fa bbcode-icon-image" {AUTH_IMG} onclick="{DISABLED_IMG}insertbbcode('[img]', '[/img]', '{FIELD}');return false;" title="{L_BB_IMAGE}"></a>
				<a href="" class="fa bbcode-icon-lightbox" {AUTH_LIGHTBOX} onclick="{DISABLED_lightbox}bbcode_lightbox('{FIELD}', ${escapejs(L_URL_PROMPT)});return false;" title="{L_BB_LIGHTBOX}"></a> 
				<a href="" class="fa bbcode-icon-quote" {AUTH_QUOTE} onclick="{DISABLED_QUOTE}insertbbcode('[quote]', '[/quote]', '{FIELD}');return false;" title="{L_BB_QUOTE}"></a>
				<a href="" class="fa bbcode-icon-hide" {AUTH_HIDE} onclick="{DISABLED_HIDE}insertbbcode('[hide]', '[/hide]', '{FIELD}');return false;" title="{L_BB_HIDE}"></a>
				
				<a href="javascript:{DISABLED_LIST}bb_display_block('9', '{FIELD}');" onmouseout="{DISABLED_LIST}bb_hide_block('9', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_LIST}">
					<i class="fa bbcode-icon-list" {AUTH_LIST}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block9{FIELD}">
					<div class="bbcode-block" style="margin-left:260px;" onmouseover="bb_hide_block('9', '{FIELD}', 1);" onmouseout="bb_hide_block('9', '{FIELD}', 0);">
						<p><label style="font-size:10px;font-weight:normal">* {L_LINES} <input size="3" type="text" name="bb_list{FIELD}" id="bb_list{FIELD}" maxlength="3" value="3"></label></p>
						<p><label style="font-size:10px;font-weight:normal">{L_ORDERED_LIST} <input size="3" type="checkbox" name="bb_ordered_list{FIELD}" id="bb_ordered_list{FIELD}"></label></p>
						<p style="text-align:center;">
							<a class="small" href="javascript:bbcode_list('{FIELD}');">
								<i class="fa bbcode-icon-list valign-middle" title="{L_BB_LIST}"></i> {L_INSERT_LIST}
							</a>
						</p>
					</div>
				</div>
			</li>
			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_COLOR}bbcode_color('{FIELD}');{DISABLED_COLOR}bb_display_block('5', '{FIELD}');" onmouseout="{DISABLED_COLOR}bb_hide_block('5', '{FIELD}', 0);" title="{L_BB_COLOR}">
					<i class="fa bbcode-icon-color" {AUTH_COLOR}></i>
				</a>
				<div class="bbcode-block-container color-picker" style="display:none;" id="bb-block5{FIELD}">
					<div id="bbcolor{FIELD}" class="bbcode-block" style="margin-left:250px;left:0px;" onmouseover="bb_hide_block('5', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);">
					</div>
				</div>
				
				<a href="javascript:{DISABLED_SIZE}bb_display_block('6', '{FIELD}');" onmouseout="{DISABLED_SIZE}bb_hide_block('6', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_SIZE}">
					<i class="fa bbcode-icon-size" {AUTH_SIZE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block6{FIELD}">
					<div class="bbcode-block" style="margin-left:340px;" onmouseover="bb_hide_block('6', '{FIELD}', 1);" onmouseout="bb_hide_block('6', '{FIELD}', 0);">
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
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-minus" onclick="textarea_resize('{FIELD}', -100, 'height');textarea_resize('xmlhttprequest-preview', -100, 'height');return false;" title="{L_BB_SMALL}"></a>
				<a href="" class="fa bbcode-icon-plus" onclick="textarea_resize('{FIELD}', 100, 'height');textarea_resize('xmlhttprequest-preview', 100, 'height');return false;" title="{L_BB_LARGE}"></a>
				<!-- bbcode-more button */ --> 
				<a href="" title="{L_BB_MORE}" onclick="show_bbcode_div('bbcode_more{FIELD}', 1);return false;" style="display:inline-block; float:right;">
					<i class="fa bbcode-icon-more bbcode-hover"></i>
				</a>
			</li>
		</ul>
		
		<ul class="bbcode-container bbcode-more" id="bbcode_more{FIELD}">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-left" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=left]', '[/align]', '{FIELD}');return false;" title="{L_BB_LEFT}"></a>
				<a href="" class="fa bbcode-icon-center" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=center]', '[/align]', '{FIELD}');return false;" title="{L_BB_CENTER}"></a>
				<a href="" class="fa bbcode-icon-right" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=right]', '[/align]', '{FIELD}');return false;" title="{L_BB_RIGHT}"></a>
				<a href="" class="fa bbcode-icon-justify" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=justify]', '[/align]', '{FIELD}');return false;" title="{L_BB_JUSTIFY}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-float-left" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=left]', '[/float]', '{FIELD}');return false;" title="{L_BB_FLOAT_LEFT}"></a>
				<a href="" class="fa bbcode-icon-float-right" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=right]', '[/float]', '{FIELD}');return false;" title="{L_BB_FLOAT_RIGHT}"></a>
				<a href="" class="fa bbcode-icon-sup" {AUTH_SUP} onclick="{DISABLED_SUP}insertbbcode('[sup]', '[/sup]', '{FIELD}');return false;" title="{L_BB_SUP}"></a>
				<a href="" class="fa bbcode-icon-sub" {AUTH_SUB} onclick="{DISABLED_SUB}insertbbcode('[sub]', '[/sub]', '{FIELD}');return false;" title="{L_BB_SUB}"></a>
				<a href="" class="fa bbcode-icon-indent" {AUTH_INDENT} onclick="{DISABLED_INDENT}insertbbcode('[indent]', '[/indent]', '{FIELD}');return false;" title="{L_BB_INDENT}"></a>
				<a href="" class="fa bbcode-icon-anchor" {AUTH_ANCHOR} onclick="{DISABLED_ANCHOR}bbcode_anchor('{FIELD}', ${escapejs(L_ANCHOR_PROMPT)});return false;" title="{L_BB_ANCHOR}"></a>
				
				<a href="javascript:{DISABLED_TABLE}bb_display_block('7', '{FIELD}');" onmouseover="{DISABLED_TABLE}bb_hide_block('7', '{FIELD}', 1);" class="bbcode-hover" title="{L_BB_TABLE}">
					<i class="fa bbcode-icon-table" {AUTH_TABLE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block7{FIELD}">
					<div id="bbtable{FIELD}" class="bbcode-block" style="margin-left:130px;width:160px;" onmouseover="bb_hide_block('7', '{FIELD}', 1);" onmouseout="bb_hide_block('7', '{FIELD}', 0);">			
						<div class="form-element">
							<label class="smaller" for="bb_lines{FIELD}">* {L_LINES}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-lines{FIELD}" id="bb-lines{FIELD}" value="2" class="field-smaller" data-cip-id="bb_lines{FIELD}">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb_cols{FIELD}">* {L_COLS}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-cols{FIELD}" id="bb-cols{FIELD}" value="2" class="field-smaller" data-cip-id="bb_cols{FIELD}">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb_head{FIELD}">{L_ADD_HEAD}</label>
							<div class="form-field">
								<input type="checkbox" name="bb-head{FIELD}" id="bb-head{FIELD}" class="field-smaller" data-cip-id="bb_head{FIELD}">
							</div>
						</div>
						<div class="bbcode-form-element-text">
							<a class="small" href="javascript:{DISABLED_TABLE}bbcode_table('{FIELD}', '{L_TABLE_HEAD}');">
								<i class="fa bbcode-icon-table" title="{L_BB_TABLE}"></i> {L_INSERT_TABLE}
							</a>
						</div>
					</div>
				</div>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-flash" {AUTH_SWF} onclick="{DISABLED_SWF}insertbbcode('[swf=425,344]', '[/swf]', '{FIELD}');return false;" title="{L_BB_SWF}"></a>
				<a href="" class="fa bbcode-icon-movie" {AUTH_MOVIE} onclick="{DISABLED_MOVIE}insertbbcode('[movie=100,100]', '[/movie]', '{FIELD}');return false;" title="{L_BB_MOVIE}"></a>
				<a href="" class="fa bbcode-icon-youtube" {AUTH_YOUTUBE} onclick="{DISABLED_YOUTUBE}insertbbcode('[youtube]', '[/youtube]', '{FIELD}');return false;" title="{L_BB_YOUTUBE}"></a>
				<a href="" class="fa bbcode-icon-sound" {AUTH_SOUND} onclick="{DISABLED_SOUND}insertbbcode('[sound]', '[/sound]', '{FIELD}');return false;" title="{L_BB_SOUND}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="javascript:{DISABLED_CODE}bb_display_block('8', '{FIELD}');" onmouseout="{DISABLED_CODE}bb_hide_block('8', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_CODE}">
					<i class="fa bbcode-icon-code" {AUTH_CODE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block8{FIELD}">
					<div class="bbcode-block" style="margin-left:220px;" onmouseover="bb_hide_block('8', '{FIELD}', 1);" onmouseout="bb_hide_block('8', '{FIELD}', 0);">
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

				<a href="" class="fa bbcode-icon-math" {AUTH_MATH} onclick="{DISABLED_MATH}insertbbcode('[math]', '[/math]', '{FIELD}');return false;" title="{L_BB_MATH}"></a>	
				<a href="" class="fa bbcode-icon-html" {AUTH_HTML} onclick="{DISABLED_HTML}insertbbcode('[html]', '[/html]', '{FIELD}');return false;" title="{L_BB_HTML}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="http://www.phpboost.com/wiki/bbcode" title="{L_BB_HELP}">
					<i class="fa bbcode-icon-help"></i>
				</a>
			</li>
		</ul>
	</div>
</div>


<script>
<!--
set_bbcode_preference('bbcode_more{FIELD}');
-->
</script>