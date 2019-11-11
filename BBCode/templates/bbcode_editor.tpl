# IF C_EDITOR_NOT_ALREADY_INCLUDED #
	<script>
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
		<ul id="bbcode-container-format" class="bbcode-container dlt-color-op20-after modal-container cell-flex cell-modal">
			<li id="format-bold" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_B}insertbbcode('[b]', '[/b]', '{FIELD}');return false;" aria-label="{@bbcode.bold}">
					<i class="fa fa-fw bbcode-icon-bold{AUTH_B}" aria-hidden="true"></i>
				</a>
			</li>
			<li id="format-italic" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_I}insertbbcode('[i]', '[/i]', '{FIELD}');return false;" aria-label="{@bbcode.italic}">
					<i class="fa fa-fw bbcode-icon-italic{AUTH_I}" aria-hidden="true"></i>
				</a>
			</li>
			<li id="format-underline" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_U}insertbbcode('[u]', '[/u]', '{FIELD}');return false;" aria-label="{@bbcode.underline}">
					<i class="fa fa-fw bbcode-icon-underline{AUTH_U}" aria-hidden="true"></i>
				</a>
			</li>
			<li id="format-strike" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_S}insertbbcode('[s]', '[/s]', '{FIELD}');return false;" aria-label="{@bbcode.strike}">
					<i class="fa fa-fw bbcode-icon-strike{AUTH_S}" aria-hidden="true"></i>
				</a>
			</li>
			<li id="format-color" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block5{FIELD}" href="" onclick="{DISABLED_COLOR}bbcode_color('5', '{FIELD}', 'color');bb_display_block('5', '{FIELD}');return false;" aria-label="{@bbcode.color}" class="{AUTH_COLOR}">
					<i class="fa fa-fw bbcode-icon-color" aria-hidden="true"></i>
				</a>
				<div id="bb-block5{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.color}</div>
						</div>
						<div id="bb-color{FIELD}" class="cell-table color-table"></div>
					</div>
				</div>
			</li>
			<li id="format-bg-color" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block15{FIELD}" href="" onclick="{DISABLED_BGCOLOR}bbcode_color('15', '{FIELD}', 'bgcolor');bb_display_block('15', '{FIELD}');return false;" aria-label="{@bbcode.bgcolor}" class="{AUTH_BGCOLOR}">
					<i class="fa fa-fw bbcode-icon-bgcolor" aria-hidden="true"></i>
				</a>
				<div id="bb-block15{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.bgcolor}</div>
						</div>
						<div id="bb-bgcolor{FIELD}" class="cell-table color-table"></div>
					</div>
				</div>
			</li>
			<li id="format-font-size" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block6{FIELD}" href="" onclick="{DISABLED_SIZE}bb_display_block('6', '{FIELD}');return false;" class="bbcode-hover{AUTH_SIZE}" aria-label="{@bbcode.size}">
					<i class="fa fa-fw bbcode-icon-size" aria-hidden="true"></i>
				</a>
				<div id="bb-block6{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.size}</div>
						</div>
						<div class="cell-form">
							<label id="font_size_picker" class="cell-label" for="bb_font_size{FIELD}">{@bbcode.size.picker}</label>
							<div class="cell-input">
								<input id="bb_font_size{FIELD}" class="font-size-input" type="number" name="bb_font_size{FIELD}" value="16" min="10" max="49" />
							</div>
						</div>
						<div class="cell-body">
							<span class="font-size-sample">{@bbcode.preview.text}</span>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_SIZE}bbcode_size('{FIELD}');bb_hide_block('6', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="format-font-family" class="bbcode-elements bkgd-color-op20-hover">
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
			<li id="format-align" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block13{FIELD}" href="" onclick="{DISABLED_ALIGN}bb_display_block('13', '{FIELD}');return false;" class="bbcode-hover{AUTH_ALIGN}" aria-label="{@bbcode.align}">
					<i class="fa fa-fw bbcode-icon-left" aria-hidden="true"></i>
				</a>
				<div id="bb-block13{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.align}</div>
						</div>
						<div class="cell-list">
							<ul>
								<li class="li-stretch" onclick="{DISABLED_ALIGN}insertbbcode('[align=left]', '[/align]', '{FIELD}');bb_hide_block('13', '{FIELD}', 0);return false;">
									<span><i class="fa fa-fw fa-align-left"></i> {@bbcode.left} </span>
									<span class="button">{@bbcode.choice.button}</span>
								</li>
								<li class="li-stretch" onclick="{DISABLED_ALIGN}insertbbcode('[align=center]', '[/align]', '{FIELD}');bb_hide_block('13', '{FIELD}', 0);return false;">
									<span><i class="fa fa-fw fa-align-center"></i> {@bbcode.center} </span>
									<span class="button">{@bbcode.choice.button}</span>
								</li>
								<li class="li-stretch" onclick="{DISABLED_ALIGN}insertbbcode('[align=right]', '[/align]', '{FIELD}');bb_hide_block('13', '{FIELD}', 0);return false;">
									<span><i class="fa fa-fw fa-align-right"></i> {@bbcode.right} </span>
									<span class="button">{@bbcode.choice.button}</span>
								</li>
								<li class="li-stretch" onclick="{DISABLED_ALIGN}insertbbcode('[align=justify]', '[/align]', '{FIELD}');bb_hide_block('13', '{FIELD}', 0);return false;">
									<span><i class="fa fa-fw fa-align-justify"></i> {@bbcode.justify} </span>
									<span class="button">{@bbcode.choice.button}</span>
								</li>
							</ul>
						</div>
					</div>

				</div>
			</li>
			<li id="format-position" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block14{FIELD}" href="" onclick="{DISABLED_POSITIONS}bb_display_block('14', '{FIELD}');return false;" class="bbcode-hover{AUTH_POSITIONS}" aria-label="{@bbcode.positions}">
					<i class="fa fa-fw bbcode-icon-indent" aria-hidden="true"></i>
				</a>
				<div id="bb-block14{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.positions}</div>
						</div>
						<div class="cell-list">
							<ul>
								<li class="li-stretch" onclick="{DISABLED_POSITIONS}insertbbcode('[float=left]', '[/float]', '{FIELD}');bb_hide_block('14', '{FIELD}', 0);return false;">
									<span class="{AUTH_ALIGN}"><i class="fa fa-fw fa-step-backward"></i> {@bbcode.float.left} </span>
									<span class="button">{@bbcode.choice.button}</span>
								</li>
								<li class="li-stretch" onclick="{DISABLED_POSITIONS}insertbbcode('[float=right]', '[/float]', '{FIELD}');bb_hide_block('14', '{FIELD}', 0);return false;">
									<span class="{AUTH_ALIGN}"><i class="fa fa-fw fa-step-forward"></i>  {@bbcode.float.right} </span>
									<span class="button">{@bbcode.choice.button}</span>
								</li>
								<li class="li-stretch" onclick="{DISABLED_POSITIONS}insertbbcode('[indent]', '[/indent]', '{FIELD}');bb_hide_block('14', '{FIELD}', 0);return false;">
									<span class="{AUTH_INDENT}"><i class="fa fa-fw fa-indent"></i> {@bbcode.indent} </span>
									<span class="button">{@bbcode.choice.button}</span>
								</li>
								<li class="li-stretch" onclick="{DISABLED_POSITIONS}insertbbcode('[sup]', '[/sup]', '{FIELD}');bb_hide_block('14', '{FIELD}', 0);return false;">
									<span class="{AUTH_SUP}"><i class="fa fa-fw fa-superscript"></i> {@bbcode.sup}</span>
									<span class="button">{@bbcode.choice.button}</span>
								</li>
								<li class="li-stretch" onclick="{DISABLED_POSITIONS}insertbbcode('[sub]', '[/sub]', '{FIELD}');bb_hide_block('14', '{FIELD}', 0);return false;">
									<span class="{AUTH_SUB}"><i class="fa fa-fw fa-subscript"></i>  {@bbcode.sub} </span>
									<span class="button">{@bbcode.choice.button}</span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</li>

			<li id="html-title" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block2{FIELD}" href="" onclick="{DISABLED_TITLE}bb_display_block('2', '{FIELD}');return false;" class="bbcode-hover{AUTH_TITLE}" aria-label="{@bbcode.title}">
					<i class="fa fa-fw bbcode-icon-title" aria-hidden="true"></i>
				</a>
				<div id="bb-block2{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.title}</div>
						</div>
						<div class="cell-body justify-between" onclick="{DISABLED_B}insertbbcode('[title=1]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;">
							<h2 class="formatter-title">{@bbcode.title.label} 1</h2>
							<span class="button">{@bbcode.choice.button}</span>
						</div>
						<div class="cell-body justify-between" onclick="{DISABLED_B}insertbbcode('[title=2]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;">
							<h3 class="formatter-title">{@bbcode.title.label} 2</h3>
							<span class="button">{@bbcode.choice.button}</span>
						</div>
						<div class="cell-body justify-between" onclick="{DISABLED_B}insertbbcode('[title=3]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;">
							<h4 class="formatter-title">{@bbcode.title.label} 3</h4>
							<span class="button">{@bbcode.choice.button}</span>
						</div>
						<div class="cell-body justify-between" onclick="{DISABLED_B}insertbbcode('[title=4]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;">
							<h5 class="formatter-title">{@bbcode.title.label} 4</h5>
							<span class="button">{@bbcode.choice.button}</span>
						</div>
						<div class="cell-body justify-between" onclick="{DISABLED_B}insertbbcode('[title=5]', '[/title]', '{FIELD}');bb_hide_block('2', '{FIELD}', 0);return false;">
							<h6 class="formatter-title">{@bbcode.title.label} 5</h6>
							<span class="button">{@bbcode.choice.button}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="html-list" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" data-trigger data-target="bb-block9{FIELD}" onclick="{DISABLED_LIST}bb_display_block('9', '{FIELD}');return false;" class="bbcode-hover{AUTH_LIST}" aria-label="{@bbcode.list}">
					<i class="fa fa-fw bbcode-icon-list{AUTH_LIST}" aria-hidden="true"></i>
				</a>
				<div id="bb-block9{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.list}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_list{FIELD}">{@bbcode.lines}</label>
							<div class="cell-input">
								<input id="bb_list{FIELD}" type="number" name="bb_list{FIELD}" value="3">
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_ordered_list{FIELD}">{@bbcode.list.ordered}</label>
							<div class="cell-input">
								<label class="checkbox" for="bb_ordered_list{FIELD}">
									<input id="bb_ordered_list{FIELD}" type="checkbox" name="bb_ordered_list{FIELD}" />
									<span></span>
								</label>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_LIST}bbcode_list('{FIELD}');bb_hide_block('9', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="html-table" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block7{FIELD}" href="" onclick="{DISABLED_TABLE}bb_display_block('7', '{FIELD}');return false;" class="bbcode-hover{AUTH_TABLE}" aria-label="{@bbcode.table}">
					<i class="fa fa-fw bbcode-icon-table" aria-hidden="true"></i>
				</a>
				<div id="bb-block7{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div id="bbtable{FIELD}" class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.table}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb-lines{FIELD}">{@bbcode.lines}</label>
							<div class="cell-input">
								<input type="number" name="bb-lines{FIELD}" id="bb-lines{FIELD}" value="2">
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb-cols{FIELD}">{@bbcode.cols}</label>
							<div class="cell-input">
								<input type="number" name="bb-cols{FIELD}" id="bb-cols{FIELD}" value="2">
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb-head{FIELD}">{@bbcode.head.table}</label>
							<div class="cell-input">
								<label class="checkbox" for="bb-head{FIELD}">
									<input type="checkbox" name="bb-head{FIELD}" id="bb-head{FIELD}" class="field-smaller">
									<span></span>
								</label>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_TABLE}bbcode_table('{FIELD}');bb_hide_block('7', '{FIELD}', 0);return false;">{@bbcode.insert.table}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="html-container" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_BLOCK}bb_display_block('3', '{FIELD}');return false;" onmouseout="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0);" class="bbcode-hover{AUTH_BLOCK}" aria-label="{@bbcode.container}">
					<i class="fa fa-fw bbcode-icon-subtitle" aria-hidden="true"></i>
				</a>
				<div id="bb-block3{FIELD}" class="bbcode-block-container arrow-submenu-color" style="display: none;">
					<ul class="bbcode-block block-submenu-color bbcode-block-list bkgd-color-op20-hover bbcode-block-block" onmouseover="bb_hide_block('3', '{FIELD}', 1);" onmouseout="bb_hide_block('3', '{FIELD}', 0);">
						<li id="html-paragraph"><a href="" onclick="{DISABLED_BLOCK}insertbbcode('[p]', '[/p]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" aria-label="{@bbcode.container} {@bbcode.paragraph.title}"> {@bbcode.paragraph} </a></li>
						<li id="html-div"><a href="" onclick="{DISABLED_BLOCK}insertbbcode('[container]', '[/container]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" aria-label="{@bbcode.container.title}"> {@bbcode.container} </a></li>
						<li id="html-div-block"><a href="" onclick="{DISABLED_BLOCK}insertbbcode('[block]', '[/block]', '{FIELD}');bb_hide_block('3', '{FIELD}', 0);return false;" aria-label="{@bbcode.container} {@bbcode.block.title}"> {@bbcode.block} </a></li>
						<li id="html-fieldset" class="modal-container cell-flex cell-modal">
							<a data-trigger data-target="bb-block19{FIELD}" href=""> {@bbcode.fieldset} </a>
							<div id="bb-block19{FIELD}" class="modal modal-animation">
								<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}" onclick="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0)"></div>
								<div id="bbfieldset{FIELD}" class="content-panel cell">
									<div class="cell-header">
										<div class="cell-name">{@bbcode.fieldset}</div>
									</div>
									<div class="cell-form">
										<label for="bb_legend{FIELD}" class="cell-label">{@bbcode.fieldset.lengend}</label>
										<div class="cell-input"><input type="text" id="bb_legend{FIELD}"></div>
									</div>
									<div class="cell-footer cell-input" onclick="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0)">
										<span class="button submit" onclick="{DISABLED_FIELDSET}bbcode_fieldset('{FIELD}');bb_hide_block('19', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
									</div>
								</div>
							</div>
						</li>
						<li id="html-abbr" class="modal-container cell-flex cell-modal">
							<a data-trigger data-target="bb-block24{FIELD}" href=""> {@bbcode.abbr} </a>
							<div id="bb-block24{FIELD}" class="modal modal-animation">
								<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}" onclick="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0)"></div>
								<div id="bbabbr{FIELD}" class="content-panel cell">
									<div class="cell-header">
										<div class="cell-name">{@bbcode.abbr}</div>
									</div>
									<div class="cell-form">
										<label for="bb_abbr_desc{FIELD" class="cell-label">{@bbcode.abbr.label}</label>
										<div class="cell-input">
											<textarea id="bb_abbr_desc{FIELD}" rows="5" cols="32"></textarea>
										</div>
									</div>
									<div class="cell-footer cell-input" onclick="{DISABLED_BLOCK}bb_hide_block('3', '{FIELD}', 0)">
										<span class="button submit" onclick="{DISABLED_ABBR}bbcode_abbr('{FIELD}');bb_hide_block('24', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</li>
			<li id="html-quote" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" data-trigger data-target="bb-block21{FIELD}" onclick="{DISABLED_QUOTE}bb_display_block('21', '{FIELD}');return false;" class="bbcode-hover{AUTH_QUOTE}" aria-label="{@bbcode.quote}">
					<i class="fa fa-fw bbcode-icon-quote{AUTH_QUOTE}" aria-hidden="true"></i>
				</a>
				<div id="bb-block21{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.quote}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_quote_author{FIELD}">{@bbcode.quote.author}</label>
							<div class="cell-input">
								<input id="bb_quote_author{FIELD}" type="text" name="bb_quote_author{FIELD}">
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_quote_extract{FIELD}">{@bbcode.quote.extract}</label>
							<div class="cell-input">
								<textarea id="bb_quote_extract{FIELD}" rows="5" cols="32"></textarea>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_QUOTE}bbcode_quote('{FIELD}');bb_hide_block('21', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="html-hidden" class="bbcode-elements bkgd-color-op20-hover">
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
			<li id="html-style" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block4{FIELD}" href="" onclick="{DISABLED_STYLE}bb_display_block('4', '{FIELD}');return false;" class="bbcode-hover{AUTH_STYLE}" aria-label="{@bbcode.style}">
					<i class="fa fa-fw bbcode-icon-style{AUTH_STYLE}" aria-hidden="true"></i>
				</a>
				<div id="bb-block4{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.style}</div>
						</div>
						<div class="cell-body" onclick="{DISABLED_B}insertbbcode('[style=notice]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;">
							<div class="message-helper notice justify-between">
								<span>${LangLoader::get_message('notice', 'main')}</span>
								<span class="button message-helper-button notice">{@bbcode.choice.button}</span>
							</div>
						</div>
						<div class="cell-body" onclick="{DISABLED_B}insertbbcode('[style=question]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;">
							<div class="message-helper question justify-between">
								<span>${LangLoader::get_message('question', 'main')}</span>
								<span class="button message-helper-button question">{@bbcode.choice.button}</span>
							</div>
						</div>
						<div class="cell-body" onclick="{DISABLED_B}insertbbcode('[style=success]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;">
							<div class="message-helper success justify-between">
								<span>${LangLoader::get_message('success', 'main')}</span>
								<span class="button message-helper-button success">{@bbcode.choice.button}</span>
							</div>
						</div>
						<div class="cell-body" onclick="{DISABLED_B}insertbbcode('[style=warning]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;">
							<div class="message-helper warning justify-between">
								<span>${LangLoader::get_message('warning', 'main')}</span>
								<span class="button message-helper-button warning">{@bbcode.choice.button}</span>
							</div>
						</div>
						<div class="cell-body" onclick="{DISABLED_B}insertbbcode('[style=error]', '[/style]', '{FIELD}');bb_hide_block('4', '{FIELD}', 0);return false;">
							<div class="message-helper error justify-between">
								<span>${LangLoader::get_message('error', 'main')}</span>
								<span class="button message-helper-button error">{@bbcode.choice.button}</span>
							</div>
						</div>
					</div>
				</div>
			</li>

			<li id="links-url" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block22{FIELD}" href="" onclick="{DISABLED_URL}bb_display_block('22', '{FIELD}');return false;" class="bbcode-hover{AUTH_URL}" aria-label="{@bbcode.link}">
					<i class="fa fa-fw bbcode-icon-url{AUTH_URL}" aria-hidden="true"></i>
				</a>
				<div id="bb-block22{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.link}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_link_url{FIELD}">{@bbcode.link.url}</label>
							<div class="cell-input">
								<input id="bb_link_url{FIELD}" type="text" name="bb_link_url{FIELD}">
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_link_name{FIELD}">{@bbcode.link.name}</label>
							<div class="cell-input">
								<input id="bb_link_name{FIELD}" type="text" name="bb_link_name{FIELD}">
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" class="bbcode-hover{AUTH_URL}" onclick="{DISABLED_URL}bbcode_link('{FIELD}');bb_hide_block('22', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-mail" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block23{FIELD}" href="" onclick="{DISABLED_MAIL}bb_display_block('23', '{FIELD}');return false;" class="bbcode-hover{AUTH_MAIL}" aria-label="{@bbcode.mail}">
					<i class="fa fa-fw bbcode-icon-mail{AUTH_MAIL}" aria-hidden="true"></i>
				</a>
				<div id="bb-block23{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.mail}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_mail_url{FIELD}">{@bbcode.mail}</label>
							<div class="cell-input">
								<input id="bb_mail_url{FIELD}" type="email" name="bb_mail_url{FIELD}">
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_mail_name{FIELD}">{@bbcode.mail.name}</label>
							<div class="cell-input">
								<input id="bb_mail_name{FIELD}" type="text" name="bb_mail_name{FIELD}">
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" class="bbcode-hover{AUTH_MAIL}" onclick="{DISABLED_MAIL}bbcode_mail('{FIELD}');bb_hide_block('23', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-wikipedia" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_WIKIPEDIA}insertbbcode('[wikipedia]', '[/wikipedia]', '{FIELD}');return false;" class="bbcode-hover{AUTH_WIKIPEDIA}" aria-label="{@bbcode.wikipedia}">
					<i class="fab fa-fw bbcode-icon-wikipedia{AUTH_WIKIPEDIA}" aria-hidden="true"></i>
				</a>
			</li>
			<li id="links-feed" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block25{FIELD}" href="" onclick="{DISABLED_FEED}bb_display_block('25', '{FIELD}');return false;" aria-label="{@bbcode.feed}">
					<i class="fa fa-fw bbcode-icon-feed{AUTH_FEED}" aria-hidden="true"></i>
				</a>
				<div id="bb-block25{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div id="bbfeed{FIELD}" class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.feed}</div>
						</div>
						<div class="cell-form">
							<label for="bb_feed_module{FIELD}" class="cell-label">{@bbcode.feed.module}</label>
							<div class="cell-input">
								<select id="bb_module_name{FIELD}">
									<option value="">{@bbcode.feed.select}</option>
									# START modules #
										<option value="{modules.VALUE}">{modules.NAME}</option>
									# END modules #
								</select>
							</div>
						</div>
						<div class="cell-form">
							<label for="bb_feed_category{FIELD}" class="cell-label">{@bbcode.feed.category}</label>
							<div class="cell-input">
								<input type="number" id="bb_feed_category{FIELD}" min="0" value="0">
							</div>
						</div>
						<div class="cell-form">
							<label for="bb_feed_number{FIELD}" class="cell-label">{@bbcode.feed.number}</label>
							<div class="cell-input">
								<input type="number" id="bb_feed_number{FIELD}" min="1" max="10" value="5">
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_FEED}bbcode_feed('{FIELD}');bb_hide_block('25', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-anchor" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block27{FIELD}" href="" onclick="{DISABLED_ANCHOR}bb_display_block('27', '{FIELD}');return false;" aria-label="{@bbcode.anchor}">
					<i class="fa fa-fw bbcode-icon-anchor{AUTH_ANCHOR}" aria-hidden="true"></i>
				</a>
				<div id="bb-block27{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div id="bbanchor{FIELD}" class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.anchor}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_anchor_id{FIELD}">{@bbcode.anchor.name}</label>
							<div class="cell-input">
								<input id="bb_anchor_id{FIELD}" type="text" name="bb_anchor_id{FIELD}" />
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_anchor_url{FIELD}">{@bbcode.anchor.url}</label>
							<div class="cell-input">
								<label for="bb_anchor_url" class="checkbox">
									<input class="checkbox-revealer" id="bb_anchor_url{FIELD}" type="checkbox" name="bb_anchor_url{FIELD}" />
									<span></span>
								</label>
							</div>
						</div>
						<div class="cell-body cell-hidden hidden"><span class="message-helper notice field-description">{@bbcode.anchor.url.desc}</span></div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_ANCHOR}bbcode_anchor('{FIELD}');bb_hide_block('27', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-sound" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block26{FIELD}"
					href=""
					onclick="{DISABLED_SOUND}bb_display_block('26', '{FIELD}');return false;"
					class="bbcode-hover{AUTH_SOUND}"
					aria-label="{@bbcode.sound}">
					<i class="fa fa-fw bbcode-icon-sound{AUTH_SOUND}" aria-hidden="true"></i>
				</a>
				<div id="bb-block26{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div id="bbsound{FIELD}" class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.sound}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_sound_url{FIELD}">{@bbcode.sound.url}</label>
							<div class="cell-input input-element-button">
								<input id="bb_sound_url{FIELD}" type="text" name="bb_sound_url{FIELD}" />
								<a href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_sound_url{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;"><i class="fa fa-cloud-upload"></i></a>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_SOUND}bbcode_sound('{FIELD}');bb_hide_block('26', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-movie" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block16{FIELD}"
					href=""
					onclick="{DISABLED_SOUND}bb_display_block('16', '{FIELD}');return false;"
					class="bbcode-hover{AUTH_MOVIE}"
					aria-label="{@bbcode.movie}">
					<i class="fa fa-fw bbcode-icon-movie{AUTH_MOVIE}" aria-hidden="true"></i>
				</a>
				<div id="bb-block16{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div id="bbmovie{FIELD}" class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.movie}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_movie_url{FIELD}">
								{@bbcode.movie.url}
							</label>
							<div class="cell-input input-element-button">
								<input id="bb_movie_url{FIELD}" type="text" name="bb_movie_url{FIELD}" />
								<a href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_movie_url{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;"><i class="fa fa-cloud-upload"></i></a>
							</div>
						</div>
						<div class="cell-body">
							<span class="field-description">{@bbcode.movie.format}</span>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_movie_width{FIELD}">{@bbcode.movie.width}</label>
							<div class="cell-input">
								<input id="bb_movie_width{FIELD}" type="number" name="bb_movie_width{FIELD}" min="100" value="800" />
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_movie_height{FIELD}">{@bbcode.movie.height}</label>
							<div class="cell-input">
								<input id="bb_movie_height{FIELD}" type="number" name="bb_movie_height{FIELD}" min="100" value="450" />
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_movie_poster{FIELD}">{@bbcode.movie.poster}</label>
							<div class="cell-input input-element-button">
								<input id="bb_movie_poster{FIELD}" type="text" name="bb_movie_poster{FIELD}" />
								<a href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_movie_poster{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;"><i class="fa fa-cloud-upload"></i></a>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_MOVIE}bbcode_movie('{FIELD}');bb_hide_block('26', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="link-lightbox" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" data-trigger data-target="bb-block18{FIELD}" onclick="{DISABLED_LIGHTBOX}bb_display_block('18','{FIELD}');return false;" class="bbcode-hover{AUTH_LIGHTBOX}" aria-label="{@bbcode.lightbox}">
					<i class="fa fa-fw bbcode-icon-lightbox{AUTH_LIGHTBOX}" aria-hidden="true"></i>
				</a>
				<div id="bb-block18{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.lightbox}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_lightbox{FIELD}">
								{@bbcode.picture.url}
							</label>
							<div class="cell-input input-element-button">
								<input id="bb_lightbox{FIELD}" type="text" name="bb_lightbox{FIELD}" />
								<a href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_lightbox{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;"><i class="fa fa-cloud-upload"></i></a>
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_lightbox_width{FIELD}">
								{@bbcode.thumbnail.width}
							</label>
							<div class="cell-input">
								<input id="bb_lightbox_width{FIELD}" type="number" min="0" value="150" name="bb_lightbox_width{FIELD}" />
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_LIGHTBOX}bbcode_lightbox('{FIELD}');bb_hide_block('18', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="link-figure" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" data-trigger data-target="bb-block17{FIELD}"
					onclick="{DISABLED_FIGURE}bb_display_block('17', '{FIELD}');return false;"
					class="bbcode-hover{AUTH_FIGURE}"
					aria-label="{@bbcode.figure}">
					<i class="fa fa-fw  bbcode-icon-image{AUTH_IMG}" aria-hidden="true"></i>
				</a>
				<div id="bb-block17{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.figure}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_figure_img{FIELD}">
								{@bbcode.picture.url}
							</label>
							<div class="cell-input input-element-button">
								<input id="bb_figure_img{FIELD}" type="text" name="bb_figure_img{FIELD}" />
								<a href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_figure_img{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;"><i class="fa fa-cloud-upload"></i></a>
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_alt_text{FIELD}">
								{@bbcode.picture.alt}
							</label>
							<div class="cell-input">
								<input id="bb_picture_alt{FIELD}" type="text" name="bb_picture_alt{FIELD}" />
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_alt_text{FIELD}">
								{@bbcode.figure.caption}
							</label>
							<div class="cell-input">
								<textarea id="bb_figure_desc{FIELD}" rows="5" cols="32"></textarea>
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_picture_width{FIELD}">
								{@bbcode.picture.width}
							</label>
							<div class="cell-input">
								<input id="bb_picture_width{FIELD}" type="number" name="bb_picture_width{FIELD}" />
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_FIGURE}bbcode_figure('{FIELD}');bb_hide_block('17', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			# IF C_UPLOAD_MANAGEMENT #
				<li id="link-upload" class="bbcode-elements bkgd-color-op20-hover">
					<a aria-label="{@bbcode.upload}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
						<i class="fa fa-fw bbcode-icon-upload" aria-hidden="true"></i>
					</a>
				</li>
			# ENDIF #

			<li id="code-smileys" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block1{FIELD}" href="" onclick="{DISABLED_SMILEYS}bb_display_block('1', '{FIELD}');return false;" class="bbcode-hover{AUTH_SMILEYS}" aria-label="{@bbcode.smileys}">
					<i class="fa fa-fw bbcode-icon-smileys" aria-hidden="true"></i>
				</a>
				<div id="bb-block1{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.smileys}</div>
						</div>
						<div class="cell-list cell-list-icons">
							<ul>
								# START smileys #
									<li>
										<a href="" onclick="insertbbcode('{smileys.CODE}', 'smile', '{FIELD}');bb_hide_block('1', '{FIELD}', 0);return false;" class="bbcode-hover" aria-label="{smileys.CODE}">
											<img src="{smileys.URL}" alt="{smileys.CODE}" aria-hidden="true" class="smiley" />
										</a>
									</li>
								# END smileys #
							</ul>
						</div>
					</div>
				</div>
			</li>
			<li id="code-fa" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block12{FIELD}" href="" onclick="{DISABLED_FA}bb_display_block('12', '{FIELD}');return false;" class="bbcode-hover{AUTH_FA}" aria-label="{@bbcode.fa}">
					<i class="fab fa-fw bbcode-icon-fa" aria-hidden="true"></i>
				</a>
				<div id="bb-block12{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.fa}</div>
						</div>
						<div class="cell-body center">{@bbcode.fa.tag}</div>
						<div class="cell-list cell-list-icons">
							<ul>
								# START code_fa #
								<li>
									<a href="" onclick="{DISABLED_FA}insertbbcode('[fa# IF code_fa.C_CUSTOM_PREFIX #={code_fa.PREFIX}# ENDIF #]{code_fa.CODE}[/fa]', '', '{FIELD}');bb_hide_block('12', '{FIELD}', 0);return false;" class="bbcode-hover" aria-label="{code_fa.CODE}">
										<i class="{code_fa.PREFIX} fa-{code_fa.CODE} fa-fw" aria-hidden="true" aria-label="{code_fa.CODE}"></i>
									</a>
								</li>
								# END code_fa #
							</ul>
						</div>
					</div>
				</div>
			</li>
			<li id="code-language" class="bbcode-elements bkgd-color-op20-hover">
				<a data-trigger data-target="bb-block8{FIELD}" href="" onclick="{DISABLED_CODE}bb_display_block('8', '{FIELD}');return false;" class="bbcode-hover{AUTH_CODE}" aria-label="{@bbcode.code}">
					<i class="fa fa-fw bbcode-icon-code{AUTH_CODE}" aria-hidden="true"></i>
				</a>
				<div id="bb-block8{FIELD}" class="modal modal-animation" style="display: none;">
					<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.code}</div>
						</div>
						<div class="cell-form">
							<label for="bb_code_custom_name{FIELD}" class="cell-label">{@bbcode.code.custom.name}</label>
							<div class="cell-input">
								<input id="bb_code_custom_name{FIELD}" type="text" name="bb_code_custom_name{FIELD}">
							</div>
						</div>
						<div class="cell-form">
							<label for="bb_code_name{FIELD}" class="cell-label">{@bbcode.code.name}</label>
							<div class="cell-input">
								<select name="bb_code_name{FIELD}" id="bb_code_name{FIELD}">
									<optgroup label="{@bbcode.text}">
										<option value="text">Text</option>
										<option value="sql">Sql</option>
										<option value="xml">Xml</option>
									</optgroup>
									<optgroup label="{@bbcode.phpboost.languages}">
										<option value="bbcode">BBCode</option>
										<option value="tpl">Template</option>
									</optgroup>
									<optgroup label="{@bbcode.web}">
										<option value="html">HTML</option>
										<option value="css">CSS</option>
										<option value="javascript">Javascript</option>
									</optgroup>
									<optgroup label="{@bbcode.script}">
										<option value="php">PHP</option>
										<option value="asp">Asp</option>
										<option value="python">Python</option>
										<option value="pearl">Pearl</option>
										<option value="ruby">Ruby</option>
										<option value="bash">Bash</option>
									</optgroup>
									<optgroup label="{@bbcode.prog}">
										<option value="c">C</option>
										<option value="cpp">C++</option>
										<option value="c#">C#</option>
										<option value="d">D</option>
										<option value="go">Go</option>
										<option value="java">Java</option>
										<option value="pascal">Pascal++</option>
										<option value="delphi#">Delphi</option>
										<option value="fortran">Fortran</option>
										<option value="vb">Vb</option>
										<option value="asm">Asm</option>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="cell-form">
							<label for="bb_code_line{FIELD}" class="cell-label">{@bbcode.code.line}</label>
							<div class="cell-input">
								<label class="checkbox" for="">
									<input id="bb_code_line{FIELD}" name="bb_code_line{FIELD}" type="checkbox">
									<span></span>
								</label>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button submit" onclick="{DISABLED_CODE}bbcode_code('{FIELD}');bb_hide_block('8', '{FIELD}', 0);return false;">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="code-math" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_MATH}insertbbcode('[math]', '[/math]', '{FIELD}');return false;" aria-label="{@bbcode.math}">
					<i class="fab fa-fw bbcode-icon-math{AUTH_MATH}" aria-hidden="true"></i>
				</a>
			</li>
			<li id="code-html" class="bbcode-elements bkgd-color-op20-hover">
				<a href="" onclick="{DISABLED_HTML}insertbbcode('[html]', '[/html]', '{FIELD}');return false;" aria-label="{@bbcode.html}">
					<i class="fab fa-fw bbcode-icon-html{AUTH_HTML}" aria-hidden="true"></i>
				</a>
			</li>
			<li id="code-help" class="bbcode-elements bkgd-color-op20-hover">
				<a href="https://www.phpboost.com/wiki/bbcode" aria-label="{@bbcode.help}<br />${LangLoader::get_message('new.window', 'main')}" target="_blank" rel="noopener">
					<i class="fa fa-fw bbcode-icon-help" aria-hidden="true"></i>
				</a>
			</li>
		</ul>
	</div>
</div>

<script>
	// bbcode size : resize lorem texte when input value is changing
	jQuery('.font-size-input').on('input', function(e){
		jQuery(".font-size-sample").css('font-size',jQuery(this).val()+'px');
	});

	$('.checkbox-revealer').on('click', checkbox_revealer);

	// bbcode color : set td's height to width (setInterval needed because of the display:hidden)
	setInterval(function() {
		jQuery('.color-td').each(function(){
			var td_width = jQuery(this).outerWidth();
			jQuery(this).outerHeight(td_width + 'px');
		});
	}, 1);
</script>
