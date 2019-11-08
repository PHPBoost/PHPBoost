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

<div id="bbcode-expanded" class="bbcode expand bkgd-color-op10 bdr-color-op20">
	<div class="bbcode-containers">
		<ul id="bbcode-container-smileys" class="bbcode-container dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_SMILEYS}bb_display_block('1', '{FIELD}');return false;" onmouseover="{DISABLED_SMILEYS}bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);" class="bbcode-hover{AUTH_SMILEYS}" aria-label="{@bbcode.smileys}">
					<i class="fa fa-fw bbcode-icon-smileys" aria-hidden="true"></i>
				</a>
				<div id="bb-block1{FIELD}" class="bbcode-block-container arrow-submenu-color" style="display: none;">
					<ul class="bbcode-block block-submenu-color bbcode-block-smileys" onmouseover="bb_hide_block('1', '{FIELD}', 1);" onmouseout="bb_hide_block('1', '{FIELD}', 0);">
						# START smileys #
							<li>
								<a href="" onclick="insertbbcode('{smileys.CODE}', 'smile', '{FIELD}');bb_hide_block('1', '{FIELD}', 0);return false;" class="bbcode-hover" aria-label="{smileys.CODE}">
									<img src="{smileys.URL}" alt="{smileys.CODE}" aria-hidden="true" class="smiley" />
								</a>
							</li>
						# END smileys #
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-fonts" class="bbcode-container dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_B}insertbbcode('[b]', '[/b]', '{FIELD}');return false;" aria-label="{@bbcode.bold}">
					<i class="fa fa-fw bbcode-icon-bold{AUTH_B}" aria-hidden="true"></i>
				</a>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_I}insertbbcode('[i]', '[/i]', '{FIELD}');return false;" aria-label="{@bbcode.italic}">
					<i class="fa fa-fw bbcode-icon-italic{AUTH_I}" aria-hidden="true"></i>
				</a>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_U}insertbbcode('[u]', '[/u]', '{FIELD}');return false;" aria-label="{@bbcode.underline}">
					<i class="fa fa-fw bbcode-icon-underline{AUTH_U}" aria-hidden="true"></i>
				</a>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_S}insertbbcode('[s]', '[/s]', '{FIELD}');return false;" aria-label="{@bbcode.strike}">
					<i class="fa fa-fw bbcode-icon-strike{AUTH_S}" aria-hidden="true"></i>
				</a>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_COLOR}bbcode_color('5', '{FIELD}', 'color');bb_display_block('5', '{FIELD}');return false;" onmouseout="{DISABLED_COLOR}bb_hide_block('5', '{FIELD}', 0);" aria-label="{@bbcode.color}" class="{AUTH_COLOR}">
					<i class="fa fa-fw bbcode-icon-color" aria-hidden="true"></i>
				</a>
				<div id="bb-block5{FIELD}" class="bbcode-block-container arrow-submenu-color color-picker" style="display: none;">
					<div id="bb-color{FIELD}" class="bbcode-block" onmouseover="bb_hide_block('5', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);">
					</div>
				</div>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_SIZE}bb_display_block('6', '{FIELD}');return false;" onmouseout="{DISABLED_SIZE}bb_hide_block('6', '{FIELD}', 0);" class="bbcode-hover{AUTH_SIZE}" aria-label="{@bbcode.size}">
					<i class="fa fa-fw bbcode-icon-size" aria-hidden="true"></i>
				</a>
				<div id="bb-block6{FIELD}" class="bbcode-block-container arrow-submenu-color" style="display: none;">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-size" onmouseover="bb_hide_block('6', '{FIELD}', 1);" onmouseout="bb_hide_block('6', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=5]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;"> 05 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=10]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;"> 10 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=15]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;"> 15 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=20]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;"> 20 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=25]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;"> 25 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=30]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;"> 30 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=35]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;"> 35 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=40]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;"> 40 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[size=45]', '[/size]', '{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;"> 45 </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_FONT}bb_display_block('10', '{FIELD}');return false;" onmouseout="{DISABLED_FONT}bb_hide_block('10', '{FIELD}', 0);" class="bbcode-hover{AUTH_FONT}" aria-label="{@bbcode.font}">
					<i class="fa fa-fw bbcode-icon-font" aria-hidden="true"></i>
				</a>
				<div id="bb-block10{FIELD}" class="bbcode-block-container arrow-submenu-color" style="display: none;">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-fonts" onmouseover="bb_hide_block('10', '{FIELD}', 1);" onmouseout="bb_hide_block('10', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=andale mono]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: andale mono;">Andale Mono</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=arial]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: arial;">Arial</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=arial black]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: arial black;">Arial Black</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=book antiqua]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: book antiqua;">Book Antiqua</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=comic sans ms]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: comic sans ms;">Comic Sans MS</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=courier new]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: courier new;">Courier New</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=georgia]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: georgia;">Georgia</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=helvetica]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: helvetica;">Helvetica</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=impact]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: impact;">Impact</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=symbol]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: symbol;">Symbol</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=tahoma]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: tahoma;">Tahoma</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=terminal]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: terminal;">Terminal</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=times new roman]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: times new roman;">Times New Roman</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=trebuchet ms]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: trebuchet ms;">Trebuchet MS</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=verdana]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> <span style="font-family: verdana;">Verdana</span> </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=webdings]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> Webdings </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[font=wingdings]', '[/font]', '{FIELD}');bb_hide_block('10', '{FIELD}', 0);return false;"> Wingdings </a></li>
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-titles" class="bbcode-container dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_TITLE}bb_display_block('2', '{FIELD}');return false;" onmouseout="{DISABLED_TITLE}bb_hide_block('2', '{FIELD}', 0);" class="bbcode-hover{AUTH_TITLE}" aria-label="{@bbcode.title}">
					<i class="fa fa-fw bbcode-icon-title" aria-hidden="true"></i>
				</a>
				<div id="bb-block2{FIELD}" class="bbcode-block-container arrow-submenu-color" style="display: none;">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-title" onmouseover="bb_hide_block('2', '{FIELD}', 1);" onmouseout="bb_hide_block('2', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=1]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;"> ${LangLoader::get_message('format_title', 'editor-common')} 1 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=2]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;"> ${LangLoader::get_message('format_title', 'editor-common')} 2 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=3]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;"> ${LangLoader::get_message('format_title', 'editor-common')} 3 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=4]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;"> ${LangLoader::get_message('format_title', 'editor-common')} 4 </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[title=5]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;"> ${LangLoader::get_message('format_title', 'editor-common')} 5 </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover modal-container">
				<a href="" data-trigger data-target="bb-block9{FIELD}" onclick="{DISABLED_LIST}bb_display_block('9', '{FIELD}');return false;" aria-label="{@bbcode.list}">
					<i class="fa fa-fw bbcode-icon-list" aria-hidden="true"></i>
				</a>
				<div id="bb-block9{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel">
						<div class="form-element">
							<label for="bb_list{FIELD}">{@bbcode.lines}</label>
							<div class="form-field">
								<input id="bb_list{FIELD}" type="number" name="bb_list{FIELD}" value="3">
							</div>
						</div>
						<div class="form-element">
							<label for="bb_ordered_list{FIELD}">{@bbcode.ordered.list}</label>
							<div class="form-field">
								<label class="checkbox" for="bb_ordered_list{FIELD}">
									<input id="bb_ordered_list{FIELD}" type="checkbox" name="bb_ordered_list{FIELD}" />
									<span></span>
								</label>
							</div>
						</div>
						<fieldset class="fieldset-submit">
							<div class="fieldset-inset">
								<button class="submit" type="submit" onclick="{DISABLED_LIST}bbcode_list('{FIELD}');bb_hide_block('9', '{FIELD}', 0);return false;">{@bbcode.insert.list}</button>
							</div>
						</fieldset>
					</div>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-blocks" class="bbcode-container dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_BLOCK}bb_display_block('3', '{FIELD}');return false;" onmouseout="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0);" class="bbcode-hover{AUTH_BLOCK}" aria-label="{@bbcode.container}">
					<i class="fa fa-fw bbcode-icon-subtitle" aria-hidden="true"></i>
				</a>
				<div id="bb-block3{FIELD}" class="bbcode-block-container arrow-submenu-color" style="display: none;">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-block" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_BLOCK}insertbbcode('[p]', '[/p]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" aria-label="{@bbcode.container} {@bbcode.paragraph.title}"> {@bbcode.paragraph} </a></li>
						<li><a href="" onclick="{DISABLED_BLOCK}insertbbcode('[container]', '[/container]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" aria-label="{@bbcode.container.title}"> {@bbcode.container} </a></li>
						<li><a href="" onclick="{DISABLED_BLOCK}insertbbcode('[block]', '[/block]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" aria-label="{@bbcode.container} {@bbcode.block.title}"> {@bbcode.block} </a></li>
						<li><a href="" onclick="{DISABLED_BLOCK}bbcode_fieldset('{FIELD}', ${escapejs(@bbcode.fieldset.prompt)});return false;" aria-label="{@bbcode.container} {@bbcode.fieldset.title}"> {@bbcode.fieldset} </a></li>
						<li><a href="" onclick="{DISABLED_BLOCK}bbcode_abbr('{FIELD}', ${escapejs(@bbcode.abbr.prompt)});return false;" aria-label="{@bbcode.container} {@bbcode.abbr.title}"> {@bbcode.abbr} </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_QUOTE}bbcode_quote('{FIELD}', ${escapejs(@bbcode.quote.prompt)});return false;" aria-label="{@bbcode.quote}">
					<i class="fa fa-fw bbcode-icon-quote{AUTH_QUOTE}" aria-hidden="true"></i>
				</a>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_HIDE}bb_display_block('11', '{FIELD}');return false;" onmouseout="{DISABLED_HIDE}bb_hide_block('11', '{FIELD}', 0);" class="bbcode-hover{AUTH_HIDE}" aria-label="{@bbcode.hide}">
					<i class="fa fa-fw bbcode-icon-hide" aria-hidden="true"></i>
				</a>
				<div class="bbcode-block-container arrow-submenu-color" style="display: none;" id="bb-block11{FIELD}">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-hide" onmouseover="bb_hide_block('11', '{FIELD}', 1);" onmouseout="bb_hide_block('11', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[hide]', '[/hide]', '{FIELD}');bb_hide_block('11', '{FIELD}', 0);return false;" aria-label="{@bbcode.hide.all} "> {@bbcode.hide} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[member]', '[/member]', '{FIELD}');bb_hide_block('11', '{FIELD}', 0);return false;" aria-label="{@bbcode.hide.view.member}"> {@bbcode.member} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[moderator]', '[/moderator]', '{FIELD}');bb_hide_block('11', '{FIELD}', 0);return false;" aria-label="{@bbcode.hide.view.moderator}  "> {@bbcode.moderator} </a></li>
					</ul>
				</div>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_STYLE}bb_display_block('4', '{FIELD}');return false;" onmouseout="{DISABLED_STYLE}bb_hide_block('4', '{FIELD}', 0);" class="bbcode-hover{AUTH_STYLE}" aria-label="{@bbcode.style}">
					<i class="fa fa-fw bbcode-icon-style" aria-hidden="true"></i>
				</a>
				<div class="bbcode-block-container arrow-submenu-color" style="display: none;" id="bb-block4{FIELD}">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-message" onmouseover="bb_hide_block('4', '{FIELD}', 1);" onmouseout="bb_hide_block('4', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=success]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" aria-label="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('success', 'main')}"> ${LangLoader::get_message('success', 'main')} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=question]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" aria-label="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('question', 'main')}"> ${LangLoader::get_message('question', 'main')} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=notice]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" aria-label="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('notice', 'main')}"> ${LangLoader::get_message('notice', 'main')} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=warning]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" aria-label="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('warning', 'main')}"> ${LangLoader::get_message('warning', 'main')} </a></li>
						<li><a href="" onclick="{DISABLED_B}insertbbcode('[style=error]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;" aria-label="${LangLoader::get_message('style', 'main')} ${LangLoader::get_message('error', 'main')}"> ${LangLoader::get_message('error', 'main')} </a></li>
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-links" class="bbcode-container dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_URL}bbcode_url('{FIELD}', ${escapejs(@bbcode.url.prompt)});return false;" aria-label="{@bbcode.link}">
					<i class="fa fa-fw bbcode-icon-url{AUTH_URL}" aria-hidden="true"></i>
				</a>
			</li>
		</ul>

		<ul id="bbcode-container-pictures" class="bbcode-container dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover modal-container">
				<a href="" data-trigger data-target="bb-block18{FIELD}" onclick="{DISABLED_lightbox}bb_display_block('18','{FIELD}');return false;" aria-label="{@bbcode.lightbox}">
					<i class="fa fa-fw bbcode-icon-lightbox{AUTH_LIGHTBOX}" aria-hidden="true"></i>
				</a>
				<div id="bb-block18{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel">
						<div class="form-element">
							<label for="bbcode_lightbox{FIELD}">
								{@bbcode.picture.url}
							</label>
							<div class="form-field input-element-button">
								<input id="bbcode_lightbox{FIELD}" type="text" name="bbcode_lightbox{FIELD}" value="" />
								<a href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bbcode_lightbox{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;"><i class="fa fa-cloud-upload"></i></a>
							</div>
						</div>
						<div class="form-element">
							<label for="bbcode_lightbox{FIELD}">
								{@bbcode.thumbnail.width}
							</label>
							<div class="form-field input-element-button">
								<input id="bbcode_lightbox_width{FIELD}" type="number" value="150" name="bbcode_lightbox_width{FIELD}" value="" />
							</div>
						</div>
						<fieldset class="fieldset-submit">
							<div class="fieldset-inset">
								<button class="submit" type="submit" onclick="{DISABLED_FIGURE}bbcode_lightbox('{FIELD}');bb_hide_block('18', '{FIELD}', 0);return false;">{@bbcode.picture.add}</button>
							</div>
						</fieldset>
					</div>
				</div>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover modal-container">
				<a href="" data-trigger data-target="bb-block17{FIELD}" onclick="{DISABLED_FIGURE}bb_display_block('17', '{FIELD}');return false;" class="bbcode-hover{AUTH_FIGURE}" aria-label="{@bbcode.figure}">
					<i class="fa fa-fw  bbcode-icon-image{AUTH_IMG}" aria-hidden="true"></i>
				</a>
				<div id="bb-block17{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel">
						<div class="form-element">
							<label for="bb_figure_img{FIELD}">
								{@bbcode.picture.url}
							</label>
							<div class="form-field input-element-button">
									<input id="bb_figure_img{FIELD}" type="text" name="bb_figure_img{FIELD}" value="" />
									<a href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_figure_img{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;"><i class="fa fa-cloud-upload"></i></a>
							</div>
						</div>
						<div class="form-element">
							<label for="bb_alt_text{FIELD}">
								{@bbcode.picture.alt}
							</label>
							<div class="form-field">
								<input id="bb_figure_alt{FIELD}" type="text" name="bb_figure_alt{FIELD}" />
							</div>
						</div>
						<div class="form-element">
							<label for="bb_alt_text{FIELD}">
								{@bbcode.figure.caption}
							</label>
							<div class="form-field">
								<textarea id="bb_figure_desc{FIELD}" rows="5" cols="33"></textarea>
							</div>
						</div>
						<div class="form-element">
							<label for="bb_picture_width{FIELD}">
								{@bbcode.picture.width}
							</label>
							<div class="form-field">
								<input id="bb_picture_width{FIELD}" type="number" name="bb_picture_width{FIELD}" value="" />
							</div>
						</div>
						<fieldset class="fieldset-submit">
							<div class="fieldset-inset">
								<button class="submit" type="submit" onclick="{DISABLED_FIGURE}bbcode_figure('{FIELD}');bb_hide_block('17', '{FIELD}', 0);return false;">{@bbcode.picture.add}</button>
							</div>
						</fieldset>
					</div>
				</div>
			</li>
		</ul>

		# IF C_UPLOAD_MANAGEMENT #
		<ul id="bbcode-container-upload" class="bbcode-container dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a aria-label="{@bbcode.upload}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
					<i class="fa fa-fw bbcode-icon-upload" aria-hidden="true"></i>
				</a>
			</li>
		</ul>
		# ENDIF #

		<span class="bbcode-backspace"></span>

		<ul id="bbcode-container-fa" class="bbcode-container bbcode-container-more dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_FA}bb_display_block('12', '{FIELD}');return false;" onmouseover="{DISABLED_FA}bb_hide_block('12', '{FIELD}', 1);" onmouseout="bb_hide_block('12', '{FIELD}', 0);" class="bbcode-hover{AUTH_FA}" aria-label="{@bbcode.fa}">
					<i class="fab fa-fw bbcode-icon-fa" aria-hidden="true"></i>
				</a>
				<div id="bb-block12{FIELD}" class="bbcode-block-container arrow-submenu-color" style="display: none;">
					<ul class="bbcode-block block-submenu-color bbcode-block-fa" onmouseover="bb_hide_block('12', '{FIELD}', 1);" onmouseout="bb_hide_block('12', '{FIELD}', 0);">
						# START code_fa #
						<li>
							<a href="" onclick="{DISABLED_FA}insertbbcode('[fa# IF code_fa.C_CUSTOM_PREFIX #={code_fa.PREFIX}# ENDIF #]{code_fa.CODE}[/fa]', '', '{FIELD}');bb_hide_block('12', '{FIELD}', 0);return false;" class="bbcode-hover" aria-label="{code_fa.CODE}">
								<i class="{code_fa.PREFIX} fa-{code_fa.CODE}" aria-hidden="true" aria-label="{code_fa.CODE}"></i>
							</a>
						</li>
						# END code_fa #
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-positions" class="bbcode-container bbcode-container-more dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_ALIGN}bb_display_block('13', '{FIELD}');return false;" onmouseover="{DISABLED_ALIGN}bb_hide_block('13', '{FIELD}', 1);" onmouseout="bb_hide_block('13', '{FIELD}', 0);" class="bbcode-hover{AUTH_ALIGN}" aria-label="{@bbcode.align}">
					<i class="fa fa-fw bbcode-icon-left" aria-hidden="true"></i>
				</a>
				<div class="bbcode-block-container arrow-submenu-color" style="display: none;" id="bb-block13{FIELD}">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-aligns" onmouseover="bb_hide_block('13', '{FIELD}', 1);" onmouseout="bb_hide_block('13', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_ALIGN}insertbbcode('[align=left]', '[/align]', '{FIELD}');return false;" aria-label="{@bbcode.left.title}"> {@bbcode.left} </a></li>
						<li><a href="" onclick="{DISABLED_ALIGN}insertbbcode('[align=center]', '[/align]', '{FIELD}');return false;" aria-label="{@bbcode.center.title}"> {@bbcode.center} </a></li>
						<li><a href="" onclick="{DISABLED_ALIGN}insertbbcode('[align=right]', '[/align]', '{FIELD}');return false;" aria-label="{@bbcode.right.title}"> {@bbcode.right} </a></li>
						<li><a href="" onclick="{DISABLED_ALIGN}insertbbcode('[align=justify]', '[/align]', '{FIELD}');return false;" aria-label="{@bbcode.justify.title}"> {@bbcode.justify} </a></li>
					</ul>
				</div>
			</li>
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_POSITIONS}bb_display_block('14', '{FIELD}');return false;" onmouseover="{DISABLED_POSITIONS}bb_hide_block('14', '{FIELD}', 1);" onmouseout="bb_hide_block('14', '{FIELD}', 0);" class="bbcode-hover{AUTH_POSITIONS}" aria-label="{@bbcode.positions}">
					<i class="fa fa-fw bbcode-icon-indent" aria-hidden="true"></i>
				</a>
				<div class="bbcode-block-container arrow-submenu-color" style="display: none;" id="bb-block14{FIELD}">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-positions" onmouseover="bb_hide_block('14', '{FIELD}', 1);" onmouseout="bb_hide_block('14', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_FLOAT}insertbbcode('[float=left]', '[/float]', '{FIELD}');return false;" aria-label="{@bbcode.float.left.title}" class="{AUTH_ALIGN}"> {@bbcode.float.left} </a></li>
						<li><a href="" onclick="{DISABLED_FLOAT}insertbbcode('[float=right]', '[/float]', '{FIELD}');return false;" aria-label="{@bbcode.float.right.title}" class="{AUTH_ALIGN}"> {@bbcode.float.right} </a></li>
						<li><a href="" onclick="{DISABLED_INDENT}insertbbcode('[indent]', '[/indent]', '{FIELD}');return false;" aria-label="{@bbcode.indent.title}" class="{AUTH_INDENT}"> {@bbcode.indent} </a></li>
					</ul>
				</div>
			</li>
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_TABLE}bb_display_block('7', '{FIELD}');return false;" onmouseover="{DISABLED_TABLE}bb_hide_block('7', '{FIELD}', 1);" class="bbcode-hover{AUTH_TABLE}" aria-label="{@bbcode.table}">
					<i class="fa fa-fw bbcode-icon-table" aria-hidden="true"></i>
				</a>
				<div id="bb-block7{FIELD}" class="bbcode-block-container arrow-submenu-color" style="display: none;">
					<div id="bbtable{FIELD}" class="bbcode-block block-submenu-color bbcode-block-table" onmouseover="bb_hide_block('7', '{FIELD}', 1);" onmouseout="bb_hide_block('7', '{FIELD}', 0);">
						<div class="form-element">
							<label class="smaller" for="bb-lines{FIELD}">{@bbcode.lines}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-lines{FIELD}" id="bb-lines{FIELD}" value="2" class="field-smaller">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb-cols{FIELD}">{@bbcode.cols}</label>
							<div class="form-field">
								<input type="text" maxlength="2" name="bb-cols{FIELD}" id="bb-cols{FIELD}" value="2" class="field-smaller">
							</div>
						</div>
						<div class="form-element">
							<label class="smaller" for="bb-head{FIELD}">{@bbcode.head.add}</label>
							<div class="form-field">
								<label class="checkbox" for="bb-head{FIELD}">
									<input type="checkbox" name="bb-head{FIELD}" id="bb-head{FIELD}" class="field-smaller">
									<span></span>
								</label>

							</div>
						</div>
						<div class="bbcode-form-element-text">
							<a class="small" href="" onclick="{DISABLED_TABLE}bbcode_table('{FIELD}', '{@bbcode.head.table}');bb_hide_block('7', '{FIELD}', 0);return false;">
								<i class="fa fa-fw bbcode-icon-table"></i> {@bbcode.insert.table}
							</a>
						</div>
					</div>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-exp" class="bbcode-container bbcode-container-more dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_SUP}insertbbcode('[sup]', '[/sup]', '{FIELD}');return false;" aria-label="{@bbcode.sup}">
					<i class="fa fa-fw bbcode-icon-sup{AUTH_SUP}" aria-hidden="true"></i>
				</a>
			</li>
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_SUB}insertbbcode('[sub]', '[/sub]', '{FIELD}');return false;" aria-label="{@bbcode.sub}">
					<i class="fa fa-fw bbcode-icon-sub{AUTH_SUB}" aria-hidden="true"></i>
				</a>
			</li>
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_BGCOLOR}bbcode_color('15', '{FIELD}', 'bgcolor');bb_display_block('15', '{FIELD}');return false;" onmouseout="{DISABLED_BGCOLOR}bb_hide_block('15', '{FIELD}', 0);" aria-label="{@bbcode.bgcolor}" class="{AUTH_BGCOLOR}">
					<i class="fa fa-fw bbcode-icon-bgcolor" aria-hidden="true"></i>
				</a>
				<div id="bb-block15{FIELD}" class="bbcode-block-container arrow-submenu-color color-picker" style="display: none;">
					<div id="bb-bgcolor{FIELD}" class="bbcode-block" onmouseover="bb_hide_block('15', '{FIELD}', 1);" onmouseout="bb_hide_block('5', '{FIELD}', 0);"></div>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-anchor" class="bbcode-container bbcode-container-more dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_ANCHOR}bbcode_anchor('{FIELD}', ${escapejs(@bbcode.anchor.prompt)});return false;" aria-label="{@bbcode.anchor}">
					<i class="fa fa-fw bbcode-icon-anchor{AUTH_ANCHOR}" aria-hidden="true"></i>
				</a>
			</li>
		</ul>

		<ul id="bbcode-container-movies" class="bbcode-container bbcode-container-more dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_POSITIONS}bb_display_block('16', '{FIELD}');return false;" onmouseover="{DISABLED_POSITIONS}bb_hide_block('16', '{FIELD}', 1);" onmouseout="bb_hide_block('16', '{FIELD}', 0);" class="bbcode-hover{AUTH_MEDIA}" aria-label="{@bbcode.media}">
					<span class="fa-stack bbcode-icons-stack">
						<i class="fa fa-music fa-stack-1x"></i>
						<i class="fas fa-film fa-stack-1x"></i>
		            </span>
				</a>
				<div class="bbcode-block-container arrow-submenu-color" style="display: none;" id="bb-block16{FIELD}">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-positions" onmouseover="bb_hide_block('16', '{FIELD}', 1);" onmouseout="bb_hide_block('16', '{FIELD}', 0);">
						<li><a href="" onclick="{DISABLED_SOUND}insertbbcode('[sound]', '[/sound]', '{FIELD}');return false;" aria-label="{@bbcode.sound.label}"> {@bbcode.sound} </a></li>
						<li><a href="" onclick="{DISABLED_MOVIE}insertbbcode('[movie=100,100]', '[/movie]', '{FIELD}');return false;" aria-label="{@bbcode.movie.label}"> {@bbcode.movie} </a></li>
						<li><a href="" onclick="{DISABLED_YOUTUBE}insertbbcode('[youtube]', '[/youtube]', '{FIELD}');return false;" aria-label="{@bbcode.youtube.label}"> {@bbcode.youtube} </a></li>
						<li><a href="" onclick="{DISABLED_DAILYMOTION}insertbbcode('[dailymotion]', '[/dailymotion]', '{FIELD}');return false;" aria-label="{@bbcode.dailymotion.label}"> {@bbcode.dailymotion} </a></li>
						<li><a href="" onclick="{DISABLED_VIMEO}insertbbcode('[vimeo]', '[/vimeo]', '{FIELD}');return false;" aria-label="{@bbcode.vimeo.label}"> {@bbcode.vimeo} </a></li>
						<li><a href="" onclick="{DISABLED_SWF}insertbbcode('[swf=425,344]', '[/swf]', '{FIELD}');return false;" aria-label="{@bbcode.swf.label}"> {@bbcode.swf} </a></li>
					</ul>
				</div>
			</li>
		</ul>

		<ul id="bbcode-container-code" class="bbcode-container bbcode-container-more dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_CODE}bb_display_block('8', '{FIELD}');return false;" onmouseout="{DISABLED_CODE}bb_hide_block('8', '{FIELD}', 0);" class="bbcode-hover{AUTH_CODE}" aria-label="{@bbcode.code}">
					<i class="fa fa-fw bbcode-icon-code" aria-hidden="true"></i>
				</a>
				<div id="bb-block8{FIELD}" class="bbcode-block-container arrow-submenu-color" style="display: none;">
					<div class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-code" onmouseover="bb_hide_block('8', '{FIELD}', 1);" onmouseout="bb_hide_block('8', '{FIELD}', 0);">
						<ul>
							<li class="bbcode-code-title bkgd-color-op40"><span>{@bbcode.text}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=text]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Text</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=sql]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">SqL</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=xml]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Xml</a></li>

							<li class="bbcode-code-title bkgd-color-op40"><span>{@bbcode.phpboost.languages}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=bbcode]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">BBCode</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=tpl]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Template</a></li>

							<li class="bbcode-code-title bkgd-color-op40"><span>{@bbcode.web}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=html]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Html</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=css]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Css</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=javascript]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Javascript</a></li>

							<li class="bbcode-code-title bkgd-color-op40"><span>{@bbcode.script}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=php]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">PHP</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=asp]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Asp</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=python]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Python</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=pearl]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Pearl</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=ruby]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Ruby</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=bash]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Bash</a></li>

							<li class="bbcode-code-title bkgd-color-op40"><span>{@bbcode.prog}</span></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=c]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">C</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=cpp]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">C++</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=c#]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">C#</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=d]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">D</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=go]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Go</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=java]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Java</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=pascal]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Pascal</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=delphi]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Delphi</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=fortran]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Fortran</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=vb]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Vb</a></li>
							<li><a href="" onclick="{DISABLED_B}insertbbcode('[code=asm]', '[/code]', '{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">Asm</a></li>
						</ul>
					</div>
				</div>
			</li>

			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_MATH}insertbbcode('[math]', '[/math]', '{FIELD}');return false;" aria-label="{@bbcode.math}">
					<i class="fab fa-fw bbcode-icon-math{AUTH_MATH}" aria-hidden="true"></i>
				</a>
			</li>
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_HTML}insertbbcode('[html]', '[/html]', '{FIELD}');return false;" aria-label="{@bbcode.html}">
					<i class="fab fa-fw bbcode-icon-html{AUTH_HTML}" aria-hidden="true"></i>
				</a>
			</li>
		</ul>

		<ul id="bbcode-container-mail" class="bbcode-container bbcode-container-more dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_MAIL}bbcode_mail('{FIELD}', ${escapejs(@bbcode.mail.prompt)});return false;" aria-label="{@bbcode.mail}">
					<i class="fa fa-fw bbcode-icon-mail{AUTH_MAIL}" aria-hidden="true"></i>
				</a>
			</li>
		</ul>

		<ul id="bbcode-container-feed" class="bbcode-container bbcode-container-more dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_FEED}bbcode_feed('{FIELD}', ${escapejs(@bbcode.feed.prompt)});return false;" aria-label="${escape(@bbcode.feed)}">
					<i class="fa fa-fw bbcode-icon-feed{AUTH_FEED}" aria-hidden="true"></i>
				</a>
			</li>
		</ul>

		<ul id="bbcode-container-help" class="bbcode-container bbcode-container-more dlt-color-op20-after">
			<li class="bbcode-elements bkgd-color-op20-hover">
				<a href="https://www.phpboost.com/wiki/bbcode" aria-label="{@bbcode.help} ${LangLoader::get_message('new.window', 'main')}" target="_blank" rel="noopener">
					<i class="fa fa-fw bbcode-icon-help" aria-hidden="true"></i>
				</a>
			</li>
		</ul>
	</div>

	<div class="bbcode-elements bkgd-color-op20-hover bbcode-elements-more">
		<a href="" aria-label="{@bbcode.more}" onclick="show_bbcode_div('bbcode-container-more');return false;">
			<i class="fa fa-fw bbcode-icon-more bbcode-hover"></i>
		</a>
	</div>

</div>

<script>
<!--
set_bbcode_preference('bbcode-container-more');
-->
</script>
