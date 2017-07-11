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
		alert("${LangLoader::get_message('require_text', 'main')}");
}
-->
</script>
<script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode.js"></script>
# ENDIF #

<div id="loading-preview-{FIELD}" class="loading-preview-container" style="display: none;">
	<div class="loading-preview">
		<i class="fa fa-spinner fa-2x fa-spin"></i>
	</div>
</div>

<div id="xmlhttprequest-preview{FIELD}" class="xmlhttprequest-preview" style="display: none;"></div>

<div id="bbcode-expanded" class="bbcode expand">
	<div class="bbcode-containers">
		<ul id="bbcode-container-smileys" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_SMILEYS}bb_display_block('1', '{FIELD}');return false;" onmouseover="{DISABLED_SMILEYS}bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);" class="bbcode-hover{AUTH_SMILEYS}" title="{@bb_smileys}">
					<i class="fa bbcode-icon-smileys"></i>
				</a>
				<div id="bb-block1{FIELD}" class="bbcode-block-container" style="display: none;">
					<ul class="bbcode-block bbcode-block-smileys" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);">
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
				<a href="" class="fa bbcode-icon-bold{AUTH_B}" onclick="{DISABLED_B}insertbbcode('[b]', '[/b]', '{FIELD}');return false;" title="{@bb_bold}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-italic{AUTH_I}" onclick="{DISABLED_I}insertbbcode('[i]', '[/i]', '{FIELD}');return false;" title="{@bb_italic}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-underline{AUTH_U}" onclick="{DISABLED_U}insertbbcode('[u]', '[/u]', '{FIELD}');return false;" title="{@bb_underline}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-strike{AUTH_S}" onclick="{DISABLED_S}insertbbcode('[s]', '[/s]', '{FIELD}');return false;" title="{@bb_strike}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_COLOR}bbcode_color('5', '{FIELD}', 'color');bb_display_block('5', '{FIELD}');return false;" onmouseout="{DISABLED_COLOR}bb_hide_block('5', '{FIELD}', 0);" title="{@bb_color}" class="{AUTH_COLOR}">
					<i class="fa bbcode-icon-color"></i>
				</a>
				<div id="bb-block5{FIELD}" class="bbcode-block-container color-picker" style="display: none;">
					<div id="bb-color{FIELD}" class="bbcode-block" onmouseover="bb_hide_block('5', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);">
					</div>
				</div>
			</li>
			
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_SIZE}bb_display_block('6', '{FIELD}');return false;" onmouseout="{DISABLED_SIZE}bb_hide_block('6', '{FIELD}', 0);" class="bbcode-hover{AUTH_SIZE}" title="{@bb_size}">
					<i class="fa bbcode-icon-size"></i>
				</a>
				<div id="bb-block6{FIELD}" class="bbcode-block-container" style="display: none;">
					<ul class="bbcode-block bbcode-block-list bbcode-block-size" onmouseover="bb_hide_block('6', '{FIELD}', 1);" onmouseout="bb_hide_block('6', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=5]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_size', 'editor-common')} 05"> 05 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=10]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_size', 'editor-common')} 10"> 10 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=15]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_size', 'editor-common')} 15"> 15 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=20]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_size', 'editor-common')} 20"> 20 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=25]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_size', 'editor-common')} 25"> 25 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=30]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_size', 'editor-common')} 30"> 30 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=35]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_size', 'editor-common')} 35"> 35 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=40]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_size', 'editor-common')} 40"> 40 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=45]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_size', 'editor-common')} 45"> 45 </a></li>
					</ul>
				</div>
			</li>
			
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_FONT}bb_display_block('10', '{FIELD}');return false;" onmouseout="{DISABLED_FONT}bb_hide_block('10', '{FIELD}', 0);" class="bbcode-hover{AUTH_FONT}" title="{@bb_font}">
					<i class="fa bbcode-icon-font"></i>
				</a>
				<div id="bb-block10{FIELD}" class="bbcode-block-container" style="display: none;">
					<ul class="bbcode-block bbcode-block-list bbcode-block-fonts" onmouseover="bb_hide_block('10', '{FIELD}', 1);" onmouseout="bb_hide_block('10', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=andale mono]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Andale Mono"> <span style="font-family: andale mono;">Andale Mono</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=arial]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Arial"> <span style="font-family: arial;">Arial</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=arial black]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Arial Black"> <span style="font-family: arial black;">Arial Black</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=book antiqua]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Book Antiqua"> <span style="font-family: book antiqua;">Book Antiqua</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=comic sans ms]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Comic Sans MS"> <span style="font-family: comic sans ms;">Comic Sans MS</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=courier new]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Courier New"> <span style="font-family: courier new;">Courier New</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=georgia]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Georgia"> <span style="font-family: georgia;">Georgia</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=helvetica]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Helvetica"> <span style="font-family: helvetica;">Helvetica</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=impact]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Impact"> <span style="font-family: impact;">Impact</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=symbol]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Symbol"> <span style="font-family: symbol;">Symbol</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=tahoma]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Tahoma"> <span style="font-family: tahoma;">Tahoma</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=terminal]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Terminal"> <span style="font-family: terminal;">Terminal</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=times new roman]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Times New Roman"> <span style="font-family: times new roman;">Times New Roman</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=trebuchet ms]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Trebuchet MS"> <span style="font-family: trebuchet ms;">Trebuchet MS</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=verdana]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Verdana"> <span style="font-family: verdana;">Verdana</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=webdings]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Webdings"> Webdings </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=wingdings]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_font', 'editor-common')} Wingdings"> Wingdings </a></li>
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-titles" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_TITLE}bb_display_block('2', '{FIELD}');return false;" onmouseout="{DISABLED_TITLE}bb_hide_block('2', '{FIELD}', 0);" class="bbcode-hover{AUTH_TITLE}" title="{@bb_title}">
					<i class="fa bbcode-icon-title"></i>
				</a>
				<div id="bb-block2{FIELD}" class="bbcode-block-container" style="display: none;">
					<ul class="bbcode-block bbcode-block-list bbcode-block-title" onmouseover="bb_hide_block('2', '{FIELD}', 1);" onmouseout="bb_hide_block('2', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=1]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_title', 'editor-common')} 1"> ${LangLoader::get_message('format_title', 'editor-common')} 1 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=2]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_title', 'editor-common')} 2"> ${LangLoader::get_message('format_title', 'editor-common')} 2 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=3]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_title', 'editor-common')} 3"> ${LangLoader::get_message('format_title', 'editor-common')} 3 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=4]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_title', 'editor-common')} 4"> ${LangLoader::get_message('format_title', 'editor-common')} 4 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=5]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;" title="${LangLoader::get_message('format_title', 'editor-common')} 5"> ${LangLoader::get_message('format_title', 'editor-common')} 5 </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_LIST}bb_display_block('9', '{FIELD}');return false;" onmouseout="{DISABLED_LIST}bb_hide_block('9', '{FIELD}', 0);" class="bbcode-hover{AUTH_LIST}" title="{@bb_list}">
					<i class="fa bbcode-icon-list"></i>
				</a>
				<div id="bb-block9{FIELD}" class="bbcode-block-container" style="display: none;">
					<div class="bbcode-block bbcode-block-ul" onmouseover="bb_hide_block('9', '{FIELD}', 1);" onmouseout="bb_hide_block('9', '{FIELD}', 0);">
						<div class="form-element">
							<label class="smaller" for="bb_list{FIELD}">{@lines}</label>
							<div class="form-field">
								<input id="bb_list{FIELD}" class="field-smaller" size="3" type="text" name="bb_list{FIELD}" maxlength="3" value="3">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb_ordered_list{FIELD}">{@ordered_list}</label>
							<div class="form-field">
								<input id="bb_ordered_list{FIELD}" type="checkbox" name="bb_ordered_list{FIELD}" >
							</div>
						</div>
						<div class="bbcode-form-element-text">
							<a class="small" href="" onclick="{DISABLED_LIST}bbcode_list('{FIELD}');bb_hide_block('9', '{FIELD}', 0);return false;">
								<i class="fa bbcode-icon-list valign-middle" title="{@bb_list}"></i> {@insert_list}
							</a>
						</div>
					</div>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-blocks" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_BLOCK}bb_display_block('3', '{FIELD}');return false;" onmouseout="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0);" class="bbcode-hover{AUTH_BLOCK}" title="{@bb_container}">
					<i class="fa bbcode-icon-subtitle"></i>
				</a>
				<div id="bb-block3{FIELD}" class="bbcode-block-container" style="display: none;">
					<ul class="bbcode-block bbcode-block-list bbcode-block-block" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_BLOCK}insertbbcode('[p]', '[/p]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" title="{@bb_container} {@bb_paragraph_title}"> {@bb_paragraph} </a></li>
						<li><a href="" onclick="{DISABLED_BLOCK}insertbbcode('[block]', '[/block]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" title="{@bb_container} {@bb_block_title}"> {@bb_block} </a></li>
						<li><a href="" onclick="{DISABLED_BLOCK}bbcode_fieldset('{FIELD}', ${escapejs(@bb_fieldset_prompt)});return false;" title="{@bb_container} {@bb_fieldset_title}"> {@bb_fieldset} </a></li>
						<li><a href="" onclick="{DISABLED_BLOCK}bbcode_abbr('{FIELD}', ${escapejs(@bb_abbr_prompt)});return false;" title="{@bb_container} {@bb_abbr_title}"> {@bb_abbr} </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-quote{AUTH_QUOTE}" onclick="{DISABLED_QUOTE}insertbbcode('[quote]', '[/quote]', '{FIELD}');return false;" title="{@bb_quote}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_HIDE}bb_display_block('11', '{FIELD}');return false;" onmouseout="{DISABLED_HIDE}bb_hide_block('11', '{FIELD}', 0);" class="bbcode-hover{AUTH_HIDE}" title="{@bb_hide}">
					<i class="fa bbcode-icon-hide"></i>
				</a>
				<div class="bbcode-block-container" style="display: none;" id="bb-block11{FIELD}">
					<ul class="bbcode-block bbcode-block-list bbcode-block-hide" onmouseover="bb_hide_block('11', '{FIELD}', 1);" onmouseout="bb_hide_block('11', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[hide]', '[/hide]', '{FIELD}');bb_hide_block('11', '{FIELD}', 0);return false;" title="{@bb_hide_all} "> {@bb_hide} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[member]', '[/member]', '{FIELD}');bb_hide_block('11', '{FIELD}', 0);return false;" title="{@bb_hide_view_member}"> {@bb_member} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[moderator]', '[/moderator]', '{FIELD}');bb_hide_block('11', '{FIELD}', 0);return false;" title="{@bb_hide_view_moderator}  "> {@bb_moderator} </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_STYLE}bb_display_block('4', '{FIELD}');return false;" onmouseout="{DISABLED_STYLE}bb_hide_block('4', '{FIELD}', 0);" class="bbcode-hover{AUTH_STYLE}" title="{@bb_style}">
					<i class="fa bbcode-icon-style"></i>
				</a>
				<div class="bbcode-block-container" style="display: none;" id="bb-block4{FIELD}">
					<ul class="bbcode-block bbcode-block-list bbcode-block-message" onmouseover="bb_hide_block('4', '{FIELD}', 1);" onmouseout="bb_hide_block('4', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=success]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('success', 'main')}"> ${LangLoader::get_message('success', 'main')} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=question]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('question', 'main')}"> ${LangLoader::get_message('question', 'main')} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=notice]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('notice', 'main')}"> ${LangLoader::get_message('notice', 'main')} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=warning]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('warning', 'main')}"> ${LangLoader::get_message('warning', 'main')} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=error]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" title="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('error', 'main')}"> ${LangLoader::get_message('error', 'main')} </a></li>
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-links" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-url{AUTH_URL}" onclick="{DISABLED_URL}bbcode_url('{FIELD}', ${escapejs(@bb_url_prompt)});return false;" title="{@bb_link}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-pictures" class="bbcode-container">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-image{AUTH_IMG}" onclick="{DISABLED_IMG}insertbbcode('[img]', '[/img]', '{FIELD}');return false;" title="{@bb_picture}"></a>
			</li>

			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-lightbox{AUTH_LIGHTBOX}" onclick="{DISABLED_lightbox}bbcode_lightbox('{FIELD}', ${escapejs(@bb_url_prompt)});return false;" title="{@bb_lightbox}"></a>
			</li>
		</ul>

		# IF C_UPLOAD_MANAGEMENT #
		<ul id="bbcode-container-upload" class="bbcode-container">
			<li class="bbcode-elements">
				<a title="{@bb_upload}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=720,resizable=yes,scrollbars=yes');return false;">
					<i class="fa bbcode-icon-upload"></i>
				</a>
			</li>
		</ul>
		# ENDIF #
		
		<span class="bbcode-backspace"><br /></span>

		<ul id="bbcode-container-fa" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_FA}bb_display_block('12', '{FIELD}');return false;" onmouseover="{DISABLED_FA}bb_hide_block('12', '{FIELD}', 1);" onmouseout="bb_hide_block('12', '{FIELD}', 0);" class="bbcode-hover{AUTH_FA}" title="{@bb_fa}">
					<i class="fa bbcode-icon-fa"></i>
				</a>
				<div id="bb-block12{FIELD}" class="bbcode-block-container" style="display: none;">
					<ul class="bbcode-block bbcode-block-fa" onmouseover="bb_hide_block('12', '{FIELD}', 1);" onmouseout="bb_hide_block('12', '{FIELD}', 0);">
						# START code_fa #
						<li>
							<a href="" onclick="{DISABLED_FA}insertbbcode('[fa]{code_fa.CODE}[/fa]', '', '{FIELD}');bb_hide_block('12', '{FIELD}', 0);return false;" class="bbcode-hover" title="{code_fa.CODE}"><i class="fa fa-{code_fa.CODE}"></i></a>
						</li>
						# END code_fa #
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-positions" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_ALIGN}bb_display_block('13', '{FIELD}');return false;" onmouseover="{DISABLED_ALIGN}bb_hide_block('13', '{FIELD}', 1);" onmouseout="bb_hide_block('13', '{FIELD}', 0);" class="bbcode-hover{AUTH_ALIGN}" title="{@bb_align}">
					<i class="fa bbcode-icon-left"></i>
				</a>
				<div class="bbcode-block-container" style="display: none;" id="bb-block13{FIELD}">
					<ul class="bbcode-block bbcode-block-list bbcode-block-aligns" onmouseover="bb_hide_block('13', '{FIELD}', 1);" onmouseout="bb_hide_block('13', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_ALIGN}insertbbcode('[align=left]', '[/align]', '{FIELD}');return false;" title="{@bb_left_title}"> {@bb_left} </a></li>
						<li><a href="" onclick="{DISABLED_ALIGN}insertbbcode('[align=center]', '[/align]', '{FIELD}');return false;" title="{@bb_center_title}"> {@bb_center} </a></li>
						<li><a href="" onclick="{DISABLED_ALIGN}insertbbcode('[align=right]', '[/align]', '{FIELD}');return false;" title="{@bb_left_title}"> {@bb_right} </a></li>
						<li><a href="" onclick="{DISABLED_ALIGN}insertbbcode('[align=justify]', '[/align]', '{FIELD}');return false;" title="{@bb_justify_title}"> {@bb_justify} </a></li>
					</ul>
				</div>
			</li>
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_POSITIONS}bb_display_block('14', '{FIELD}');return false;" onmouseover="{DISABLED_POSITIONS}bb_hide_block('14', '{FIELD}', 1);" onmouseout="bb_hide_block('14', '{FIELD}', 0);" class="bbcode-hover{AUTH_POSITIONS}" title="{@bb_positions}">
					<i class="fa bbcode-icon-indent"></i>
				</a>
				<div class="bbcode-block-container" style="display: none;" id="bb-block14{FIELD}">
					<ul class="bbcode-block bbcode-block-list bbcode-block-positions" onmouseover="bb_hide_block('14', '{FIELD}', 1);" onmouseout="bb_hide_block('14', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_FLOAT}insertbbcode('[float=left]', '[/float]', '{FIELD}');return false;" title="{@bb_float_left_title}" class="{AUTH_ALIGN}"> {@bb_float_left} </a></li>
						<li><a href="" onclick="{DISABLED_FLOAT}insertbbcode('[float=right]', '[/float]', '{FIELD}');return false;" title="{@bb_float_right_title}" class="{AUTH_ALIGN}"> {@bb_float_right} </a></li>
						<li><a href="" onclick="{DISABLED_INDENT}insertbbcode('[indent]', '[/indent]', '{FIELD}');return false;" title="{@bb_indent_title}" class="{AUTH_INDENT}"> {@bb_indent} </a></li>
					</ul>
				</div>
			</li>
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_TABLE}bb_display_block('7', '{FIELD}');return false;" onmouseover="{DISABLED_TABLE}bb_hide_block('7', '{FIELD}', 1);" class="bbcode-hover{AUTH_TABLE}" title="{@bb_table}">
					<i class="fa bbcode-icon-table"></i>
				</a>
				<div id="bb-block7{FIELD}" class="bbcode-block-container" style="display: none;">
					<div id="bbtable{FIELD}" class="bbcode-block bbcode-block-table" onmouseover="bb_hide_block('7', '{FIELD}', 1);" onmouseout="bb_hide_block('7', '{FIELD}', 0);">
						<div class="form-element">
							<label class="smaller" for="bb-lines{FIELD}">{@lines}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-lines{FIELD}" id="bb-lines{FIELD}" value="2" class="field-smaller">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb-cols{FIELD}">{@cols}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-cols{FIELD}" id="bb-cols{FIELD}" value="2" class="field-smaller">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb-head{FIELD}">{@head_add}</label>
							<div class="form-field">
								<input type="checkbox" name="bb-head{FIELD}" id="bb-head{FIELD}" class="field-smaller">
							</div>
						</div>
						<div class="bbcode-form-element-text">
							<a class="small" href="" onclick="{DISABLED_TABLE}bbcode_table('{FIELD}', '{@head_table}');bb_hide_block('7', '{FIELD}', 0);return false;">
								<i class="fa bbcode-icon-table" title="{@bb_table}"></i> {@insert_table}
							</a>
						</div>
					</div>
				</div>
			</li>
		</ul>
		
		<ul id="bbcode-container-exp" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-sup{AUTH_SUP}" onclick="{DISABLED_SUP}insertbbcode('[sup]', '[/sup]', '{FIELD}');return false;" title="{@bb_sup}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-sub{AUTH_SUB}" onclick="{DISABLED_SUB}insertbbcode('[sub]', '[/sub]', '{FIELD}');return false;" title="{@bb_sub}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_BGCOLOR}bbcode_color('15', '{FIELD}', 'bgcolor');bb_display_block('15', '{FIELD}');return false;" onmouseout="{DISABLED_BGCOLOR}bb_hide_block('15', '{FIELD}', 0);" title="{@bb_bgcolor}" class="{AUTH_BGCOLOR}">
					<i class="fa bbcode-icon-bgcolor"></i>
				</a>
				<div id="bb-block15{FIELD}" class="bbcode-block-container color-picker" style="display: none;">
					<div id="bb-bgcolor{FIELD}" class="bbcode-block" onmouseover="bb_hide_block('15', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);">
					</div>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-anchor" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-anchor{AUTH_ANCHOR}" onclick="{DISABLED_ANCHOR}bbcode_anchor('{FIELD}', ${escapejs(@bb_anchor_prompt)});return false;" title="{@bb_anchor}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-movies" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-flash{AUTH_SWF}" onclick="{DISABLED_SWF}insertbbcode('[swf=425,344]', '[/swf]', '{FIELD}');return false;" title="{@bb_swf}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-movie{AUTH_MOVIE}" onclick="{DISABLED_MOVIE}insertbbcode('[movie=100,100]', '[/movie]', '{FIELD}');return false;" title="{@bb_movie}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-youtube{AUTH_YOUTUBE}" onclick="{DISABLED_YOUTUBE}insertbbcode('[youtube]', '[/youtube]', '{FIELD}');return false;" title="{@bb_youtube}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-sound{AUTH_SOUND}" onclick="{DISABLED_SOUND}insertbbcode('[sound]', '[/sound]', '{FIELD}');return false;" title="{@bb_sound}"></a>
			</li>
		</ul>
		
		<ul id="bbcode-container-code" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" onclick="{DISABLED_CODE}bb_display_block('8', '{FIELD}');return false;" onmouseout="{DISABLED_CODE}bb_hide_block('8', '{FIELD}', 0);" class="bbcode-hover{AUTH_CODE}" title="{@bb_code}">
					<i class="fa bbcode-icon-code"></i>
				</a>
				<div id="bb-block8{FIELD}" class="bbcode-block-container" style="display: none;">
					<div class="bbcode-block bbcode-block-list bbcode-block-code" onmouseover="bb_hide_block('8', '{FIELD}', 1);" onmouseout="bb_hide_block('8', '{FIELD}', 0);">
						<ul>
							<li class="bbcode-code-title"><span>{@bb_text}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=text]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} text">Text</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=sql]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} sql">SqL</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=xml]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} xml">Xml</a></li>

							<li class="bbcode-code-title"><span>{@phpboost_languages}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=bbcode]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} bbcode">BBCode</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=tpl]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} template">Template</a></li>

							<li class="bbcode-code-title"><span>{@bb_script}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=php]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} php">PHP</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=asp]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} asp">Asp</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=python]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} python">Python</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=pearl]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} text">Pearl</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=ruby]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} ruby">Ruby</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=bash]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} bash">Bash</a></li>

							<li class="bbcode-code-title"><span>{@bb_web}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=html]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} html">Html</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=css]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} css">Css</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=javascript]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;" title="{L_CODE} javascript">Javascript</a></li>

							<li class="bbcode-code-title"><span>{@bb_prog}</span></li>
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
				<a href="" class="fa bbcode-icon-math{AUTH_MATH}" onclick="{DISABLED_MATH}insertbbcode('[math]', '[/math]', '{FIELD}');return false;" title="{@bb_math}"></a>
			</li>
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-html{AUTH_HTML}" onclick="{DISABLED_HTML}insertbbcode('[html]', '[/html]', '{FIELD}');return false;" title="{@bb_html}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-mail" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-mail{AUTH_MAIL}" onclick="{DISABLED_MAIL}bbcode_mail('{FIELD}', ${escapejs(@bb_mail_prompt)});return false;" title="{@bb_mail}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-feed" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="" class="fa bbcode-icon-feed{AUTH_FEED}" onclick="{DISABLED_FEED}bbcode_feed('{FIELD}', ${escapejs(@bb_feed_prompt)});return false;" title="${escape(@bb_feed)}"></a>
			</li>
		</ul>

		<ul id="bbcode-container-help" class="bbcode-container bbcode-container-more">
			<li class="bbcode-elements">
				<a href="https://www.phpboost.com/wiki/bbcode" title="{@bb_help}" target="_blank" rel="noopener">
					<i class="fa bbcode-icon-help"></i>
				</a>
			</li>
		</ul>
	</div>

	<div class="bbcode-elements bbcode-elements-more">
		<a href="" title="{@bb_more}" onclick="show_bbcode_div('bbcode-container-more');return false;">
			<i class="fa bbcode-icon-more bbcode-hover"></i>
		</a>
	</div>

</div>

<script>
<!--
set_bbcode_preference('bbcode-container-more');
-->
</script>
