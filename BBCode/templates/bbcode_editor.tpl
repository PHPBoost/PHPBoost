# IF C_EDITOR_NOT_ALREADY_INCLUDED #
<script>
<!--
function XMLHttpRequest_preview(field)
{
	if( XMLHttpRequest_preview.arguments.length == 0 )
		field = ${escapejs(FIELD)};

	var contents = jQuery('#' + field).val();
	var preview_field = 'xmlhttprequest-preview' + field;

	if( contents != "" )
	{
		jQuery("#" + preview_field).slideDown(500);

		jQuery('#loading-preview-' + field).show();

		jQuery.ajax({
			url: PATH_TO_ROOT + "/kernel/framework/ajax/content_xmlhttprequest.php",
			type: "post",
			data: {
				token: '{TOKEN}',
				path_to_root: '{PHP_PATH_TO_ROOT}',
				editor: 'BBCode',
				page_path: '{PAGE_PATH}',
				contents: contents,
				ftags: '{FORBIDDEN_TAGS}'
			},
			success: function(returnData){
				jQuery('#' + preview_field).html(returnData);
				jQuery('#loading-preview-' + field).hide();
			}
		});
	}
	else
		alert("{L_REQUIRE_TEXT}");
}
-->
</script>
<script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode.js"></script>
# ENDIF #

<div id="loading-preview-{FIELD}" class="loading-preview-container" style="display:none;">
	<div class="loading-preview">
		<i class="fa fa-spinner fa-2x fa-spin"></i>
	</div>
</div>

<div id="xmlhttprequest-preview{FIELD}" class="xmlhttprequest-preview" style="display:none;"></div>

<div id="bbcode-expanded" class="bbcode expand">
	<div class="bbcode-containers">
		<ul id="bbcode-container-smileys" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" onclick="bb_display_block('1', '{FIELD}');return false;" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_SMILEYS}">
					<i class="fa bbcode-icon-smileys" {AUTH_SMILEYS}></i>
				</a>
				<div id="bb-block1{FIELD}" class="bbcode-block-container" style="display:none;">
					<ul class="bbcode-block block-smileys" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);">
						# START smileys #
							<li>
							<a href="" onclick="insertbbcode('{smileys.CODE}', 'smile', '{FIELD}');bb_hide_block('1', '{FIELD}', 0);return false;" class="bbcode-hover" title="{smileys.CODE}"><img src="{smileys.URL}" title="{smileys.CODE}" alt="{smileys.CODE}"></a>
							</li>
						# END smileys #
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-fonts" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-bold" {AUTH_B} onclick="{DISABLED_B}insertbbcode('[b]', '[/b]', '{FIELD}');return false;" title="{L_BB_BOLD}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-italic" {AUTH_I} onclick="{DISABLED_I}insertbbcode('[i]', '[/i]', '{FIELD}');return false;" title="{L_BB_ITALIC}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-underline" {AUTH_U} onclick="{DISABLED_U}insertbbcode('[u]', '[/u]', '{FIELD}');return false;" title="{L_BB_UNDERLINE}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-strike" {AUTH_S} onclick="{DISABLED_S}insertbbcode('[s]', '[/s]', '{FIELD}');return false;" title="{L_BB_STRIKE}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_COLOR}bbcode_color('5', '{FIELD}');bb_display_block('5', '{FIELD}');return false;" onmouseout="{DISABLED_COLOR}bb_hide_block('5', '{FIELD}', 0);" title="{L_BB_COLOR}">
					<i class="fa bbcode-icon-color" {AUTH_COLOR}></i>
				</a>
				<div id="bb-block5{FIELD}" class="bbcode-block-container color-picker" style="display:none;">
					<div id="bbcolor{FIELD}" class="bbcode-block" onmouseover="bb_hide_block('5', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);">
					</div>
				</div>
			</li>
			
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_SIZE}bb_display_block('6', '{FIELD}');return false;" onmouseout="{DISABLED_SIZE}bb_hide_block('6', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_SIZE}">
					<i class="fa bbcode-icon-size" {AUTH_SIZE}></i>
				</a>
				<div id="bb-block6{FIELD}" class="bbcode-block-container" style="display:none;">
					<ul class="bbcode-block bbcode-block-list" style="width: 40px;" onmouseover="bb_hide_block('6', '{FIELD}', 1);" onmouseout="bb_hide_block('6', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=5]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="{L_SIZE} 05"> 05 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=10]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="{L_SIZE} 10"> 10 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=15]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="{L_SIZE} 15"> 15 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=20]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="{L_SIZE} 20"> 20 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=25]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="{L_SIZE} 25"> 25 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=30]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="{L_SIZE} 30"> 30 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=35]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="{L_SIZE} 35"> 35 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=40]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="{L_SIZE} 40"> 40 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=45]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="{L_SIZE} 45"> 45 </a></li>
					</ul>
				</div>
			</li>
			
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_FONT}bb_display_block('10', '{FIELD}');return false;" onmouseout="{DISABLED_FONT}bb_hide_block('10', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_FONT}">
					<i class="fa bbcode-icon-font" {AUTH_FONT}></i>
				</a>
				<div id="bb-block10{FIELD}" class="bbcode-block-container" style="display:none;">
					<ul class="bbcode-block bbcode-block-list bbcode-block-fonts" onmouseover="bb_hide_block('10', '{FIELD}', 1);" onmouseout="bb_hide_block('10', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=andale mono]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Andale Mono"> <span style="font-family: andale mono;">Andale Mono</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=arial]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Arial"> <span style="font-family: arial;">Arial</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=arial black]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Arial Black"> <span style="font-family: arial black;">Arial Black</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=book antiqua]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Book Antiqua"> <span style="font-family: book antiqua;">Book Antiqua</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=comic sans ms]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Comic Sans MS"> <span style="font-family: comic sans ms;">Comic Sans MS</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=courier new]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Courier New"> <span style="font-family: courier new;">Courier New</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=georgia]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Georgia"> <span style="font-family: georgia;">Georgia</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=helvetica]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Helvetica"> <span style="font-family: helvetica;">Helvetica</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=impact]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Impact"> <span style="font-family: impact;">Impact</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=symbol]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Symbol"> <span style="font-family: symbol;">Symbol</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=tahoma]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Tahoma"> <span style="font-family: tahoma;">Tahoma</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=terminal]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Terminal"> <span style="font-family: terminal;">Terminal</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=times new roman]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Times New Roman"> <span style="font-family: times new roman;">Times New Roman</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=trebuchet ms]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Trebuchet MS"> <span style="font-family: trebuchet ms;">Trebuchet MS</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=verdana]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Verdana"> <span style="font-family: verdana;">Verdana</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=webdings]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Webdings"> Webdings </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=wingdings]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="{L_FONT} Wingdings"> Wingdings </a></li>
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-titles" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_TITLE}bb_display_block('2', '{FIELD}');return false;" onmouseout="{DISABLED_TITLE}bb_hide_block('2', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_TITLE}">
					<i class="fa bbcode-icon-title" {AUTH_TITLE}></i>
				</a>
				<div id="bb-block2{FIELD}" class="bbcode-block-container" style="display:none;">
					<ul class="bbcode-block bbcode-block-list bbcode-block-title" onmouseover="bb_hide_block('2', '{FIELD}', 1);" onmouseout="bb_hide_block('2', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=1]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="{L_TITLE} 1"> {L_TITLE} 1 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=2]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="{L_TITLE} 2"> {L_TITLE} 2 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=3]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="{L_TITLE} 3"> {L_TITLE} 3 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=4]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="{L_TITLE} 4"> {L_TITLE} 4 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=5]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="{L_TITLE} 5"> {L_TITLE} 5 </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_LIST}bb_display_block('9', '{FIELD}');return false;" onmouseout="{DISABLED_LIST}bb_hide_block('9', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_LIST}">
					<i class="fa bbcode-icon-list" {AUTH_LIST}></i>
				</a>
				<div id="bb-block9{FIELD}" class="bbcode-block-container" style="display:none;">
					<div class="bbcode-block bbcode-block-ul" onmouseover="bb_hide_block('9', '{FIELD}', 1);" onmouseout="bb_hide_block('9', '{FIELD}', 0);">
						<div class="form-element">
							<label class="smaller" for="bb_list{FIELD}">{L_LINES}</label>
							<div class="form-field">
								<input id="bb_list{FIELD}" class="field-smaller" size="3" type="text" name="bb_list{FIELD}" maxlength="3" value="3">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb_ordered_list{FIELD}">{L_ORDERED_LIST}</label>
							<div class="form-field">
								<input id="bb_ordered_list{FIELD}" type="checkbox" name="bb_ordered_list{FIELD}" >
							</div>
						</div>
						<div class="bbcode-form-element-text">
							<a class="small" href="" onclick="{DISABLED_LIST}bbcode_list('{FIELD}');bb_hide_block('9', '{FIELD}', 0);return false;">
								<i class="fa bbcode-icon-list valign-middle" title="{L_BB_LIST}"></i> {L_INSERT_LIST}
							</a>
						</div>
					</div>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-blocks" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_BLOCK}bb_display_block('3', '{FIELD}');return false;" onmouseout="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_CONTAINER}">
					<i class="fa bbcode-icon-subtitle" {AUTH_BLOCK}></i>
				</a>
				<div id="bb-block3{FIELD}" class="bbcode-block-container" style="display:none;">
					<ul class="bbcode-block bbcode-block-list bbcode-block-block" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[block]', '[/block]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" title="{L_CONTAINER} {L_BLOCK}"> {L_BLOCK} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[fieldset]', '[/fieldset]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" title="{L_CONTAINER} {L_FIELDSET}"> {L_FIELDSET} </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-quote" {AUTH_QUOTE} onclick="{DISABLED_QUOTE}insertbbcode('[quote]', '[/quote]', '{FIELD}');return false;" title="{L_BB_QUOTE}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-hide" {AUTH_HIDE} onclick="{DISABLED_HIDE}insertbbcode('[hide]', '[/hide]', '{FIELD}');return false;" title="{L_BB_HIDE}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_STYLE}bb_display_block('4', '{FIELD}');return false;" onmouseout="{DISABLED_STYLE}bb_hide_block('4', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_STYLE}">
					<i class="fa bbcode-icon-style" {AUTH_STYLE}></i>
				</a>
				<div class="bbcode-block-container" style="display:none;" id="bb-block4{FIELD}">
					<ul class="bbcode-block bbcode-block-list bbcode-block-message" onmouseover="bb_hide_block('4', '{FIELD}', 1);" onmouseout="bb_hide_block('4', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=success] ', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="{L_SUCCESS} "> {L_SUCCESS}  </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=question]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="{L_QUESTION}"> {L_QUESTION} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=notice]  ', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="{L_NOTICE}  "> {L_NOTICE}   </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=warning] ', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="{L_WARNING} "> {L_WARNING}  </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=error]   ', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="{L_ERROR}   "> {L_ERROR}    </a></li>
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-links" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-url" {AUTH_URL} onclick="{DISABLED_URL}bbcode_url('{FIELD}', ${escapejs(L_URL_PROMPT)});return false;" title="{L_BB_URL}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-pictures" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-image" {AUTH_IMG} onclick="{DISABLED_IMG}insertbbcode('[img]', '[/img]', '{FIELD}');return false;" title="{L_BB_IMAGE}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-lightbox" {AUTH_LIGHTBOX} onclick="{DISABLED_lightbox}bbcode_lightbox('{FIELD}', ${escapejs(L_URL_PROMPT)});return false;" title="{L_BB_LIGHTBOX}"></a>
			</li>
		</ul>

		# IF C_UPLOAD_MANAGEMENT #
		<ul id="bbcode-container-upload" class="bbcode-container">
			<li class="bbcode-elements">
				<a title="{L_BB_UPLOAD}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=720,resizable=yes,scrollbars=yes');return false;">
					<i class="fa bbcode-icon-upload"></i>
				</a>
			</li>
		</ul>
		# ENDIF #
		
		<span class="bbcode-backspace"><br /></span>

		<ul id="bbcode-container-aligns" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-left" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=left]', '[/align]', '{FIELD}');return false;" title="{L_BB_LEFT}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-center" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=center]', '[/align]', '{FIELD}');return false;" title="{L_BB_CENTER}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-right" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=right]', '[/align]', '{FIELD}');return false;" title="{L_BB_RIGHT}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-justify" {AUTH_ALIGN} onclick="{DISABLED_ALIGN}insertbbcode('[align=justify]', '[/align]', '{FIELD}');return false;" title="{L_BB_JUSTIFY}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-positions" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-float-left" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=left]', '[/float]', '{FIELD}');return false;" title="{L_BB_FLOAT_LEFT}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-float-right" {AUTH_FLOAT} onclick="{DISABLED_FLOAT}insertbbcode('[float=right]', '[/float]', '{FIELD}');return false;" title="{L_BB_FLOAT_RIGHT}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-indent" {AUTH_INDENT} onclick="{DISABLED_INDENT}insertbbcode('[indent]', '[/indent]', '{FIELD}');return false;" title="{L_BB_INDENT}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_TABLE}bb_display_block('7', '{FIELD}');return false;" onmouseover="{DISABLED_TABLE}bb_hide_block('7', '{FIELD}', 1);" class="bbcode-hover" title="{L_BB_TABLE}">
					<i class="fa bbcode-icon-table" {AUTH_TABLE}></i>
				</a>
				<div id="bb-block7{FIELD}" class="bbcode-block-container" style="display:none;">
					<div id="bbtable{FIELD}" class="bbcode-block bbcode-block-table" onmouseover="bb_hide_block('7', '{FIELD}', 1);" onmouseout="bb_hide_block('7', '{FIELD}', 0);">
						<div class="form-element">
							<label class="smaller" for="bb-lines{FIELD}">{L_LINES}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-lines{FIELD}" id="bb-lines{FIELD}" value="2" class="field-smaller">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb-cols{FIELD}">{L_COLS}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-cols{FIELD}" id="bb-cols{FIELD}" value="2" class="field-smaller">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb-head{FIELD}">{L_ADD_HEAD}</label>
							<div class="form-field">
								<input type="checkbox" name="bb-head{FIELD}" id="bb-head{FIELD}" class="field-smaller">
							</div>
						</div>
						<div class="bbcode-form-element-text">
							<a class="small" href="" onclick="{DISABLED_TABLE}bbcode_table('{FIELD}', '{L_TABLE_HEAD}');bb_hide_block('7', '{FIELD}', 0);return false;">
								<i class="fa bbcode-icon-table" title="{L_BB_TABLE}"></i> {L_INSERT_TABLE}
							</a>
						</div>
					</div>
				</div>
			</li>
		</ul>
		
		<ul id="bbcode-container-exp" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-sup" {AUTH_SUP} onclick="{DISABLED_SUP}insertbbcode('[sup]', '[/sup]', '{FIELD}');return false;" title="{L_BB_SUP}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-sub" {AUTH_SUB} onclick="{DISABLED_SUB}insertbbcode('[sub]', '[/sub]', '{FIELD}');return false;" title="{L_BB_SUB}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-anchor" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-anchor" {AUTH_ANCHOR} onclick="{DISABLED_ANCHOR}bbcode_anchor('{FIELD}', ${escapejs(L_ANCHOR_PROMPT)});return false;" title="{L_BB_ANCHOR}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-movies" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-flash" {AUTH_SWF} onclick="{DISABLED_SWF}insertbbcode('[swf=425,344]', '[/swf]', '{FIELD}');return false;" title="{L_BB_SWF}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-movie" {AUTH_MOVIE} onclick="{DISABLED_MOVIE}insertbbcode('[movie=100,100]', '[/movie]', '{FIELD}');return false;" title="{L_BB_MOVIE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-youtube" {AUTH_YOUTUBE} onclick="{DISABLED_YOUTUBE}insertbbcode('[youtube]', '[/youtube]', '{FIELD}');return false;" title="{L_BB_YOUTUBE}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-sound" {AUTH_SOUND} onclick="{DISABLED_SOUND}insertbbcode('[sound]', '[/sound]', '{FIELD}');return false;" title="{L_BB_SOUND}"></a>
			</li>
		</ul>
		
		<ul id="bbcode-container-code" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_CODE}bb_display_block('8', '{FIELD}');return false;" onmouseout="{DISABLED_CODE}bb_hide_block('8', '{FIELD}', 0);" class="bbcode-hover" title="{L_BB_CODE}">
					<i class="fa bbcode-icon-code" {AUTH_CODE}></i>
				</a>
				<div id="bb-block8{FIELD}" class="bbcode-block-container" style="display:none;">
					<div class="bbcode-block bbcode-block-list bbcode-block-code" onmouseover="bb_hide_block('8', '{FIELD}', 1);" onmouseout="bb_hide_block('8', '{FIELD}', 0);">
						<ul>
							<li class="bbcode-code-title"><span>{L_TEXT}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=text]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} text">Text</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=sql]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} sql">SqL</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=xml]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} xml">Xml</a></li>

							<li class="bbcode-code-title"><span>{L_PHPBOOST_LANGUAGES}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=bbcode]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} bbcode">BBCode</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=tpl]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} template">Template</a></li>

							<li class="bbcode-code-title"><span>{L_SCRIPT}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=php]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} php">PHP</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=asp]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} asp">Asp</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=python]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} python">Python</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=pearl]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} text">Pearl</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=ruby]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} ruby">Ruby</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=bash]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} bash">Bash</a></li>

							<li class="bbcode-code-title"><span>{L_WEB}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=html]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} html">Html</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=css]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} css">Css</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=javascript]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} javascript">Javascript</a></li>

							<li class="bbcode-code-title"><span>{L_PROG}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=c]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} c">C</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=cpp]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} c++">C++</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=c#]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} c#">C#</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=d]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} d">D</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=go]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} go">Go</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=java]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} java">Java</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=pascal]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} pascal">Pascal</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=delphi]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} delphi">Delphi</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=fortran]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} fortran">Fortran</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=vb]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} vb">Vb</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=asm]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} asm">Asm</a></li>
						</ul>
					</div>
				</div>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-math" {AUTH_MATH} onclick="{DISABLED_MATH}insertbbcode('[math]', '[/math]', '{FIELD}');return false;" title="{L_BB_MATH}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-html" {AUTH_HTML} onclick="{DISABLED_HTML}insertbbcode('[html]', '[/html]', '{FIELD}');return false;" title="{L_BB_HTML}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-help" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="https://www.phpboost.com/wiki/bbcode" title="{L_BB_HELP}" target="_blank">
					<i class="fa bbcode-icon-help"></i>
				</a>
			</li>
		</ul>
	</div>

	<div class="bbcode-elements bbcode-elements-more">
		<a href="" title="{L_BB_MORE}" onclick="show_bbcode_div('bbcode-container-more');return false;">
			<i class="fa bbcode-icon-more bbcode-hover"></i>
		</a>
	</div>

</div>

<script>
<!--
set_bbcode_preference('bbcode-container-more');
-->
</script>
