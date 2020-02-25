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
				alert(${escapejs(LangLoader::get_message('require_text', 'main'))});
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

<div class="bbcode-bar">
	<nav class="bbcode-containers">
		<ul class="bbcode-container modal-container cell-flex cell-modal cell-tile">
			<li id="format-bold" class="bbcode-elements">
				<span class="bbcode-button{AUTH_B}" # IF NOT C_DISABLED_B #onclick="insertbbcode('[b]', '[/b]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.bold}">
					<i class="fa fa-fw fa-bold" aria-hidden="true"></i>
				</span>
			</li>
			<li id="format-italic" class="bbcode-elements">
				<span class="bbcode-button{AUTH_I}" # IF NOT C_DISABLED_I #onclick="insertbbcode('[i]', '[/i]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.italic}">
					<i class="fa fa-fw fa-italic" aria-hidden="true"></i>
				</span>
			</li>
			<li id="format-underline" class="bbcode-elements">
				<span class="bbcode-button{AUTH_U}" # IF NOT C_DISABLED_U #onclick="insertbbcode('[u]', '[/u]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.underline}">
					<i class="fa fa-fw fa-underline{AUTH_U}" aria-hidden="true"></i>
				</span>
			</li>
			<li id="format-strike" class="bbcode-elements">
				<span class="bbcode-button{AUTH_S}" # IF NOT C_DISABLED_S #onclick="insertbbcode('[s]', '[/s]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.strike}">
					<i class="fa fa-fw fa-strikethrough{AUTH_S}" aria-hidden="true"></i>
				</span>
			</li>
			<li id="format-color" class="bbcode-elements">
				<span class="bbcode-button{AUTH_COLOR}" # IF NOT C_DISABLED_COLOR #data-modal data-target="block-color{FIELD}" onclick="bbcode_color('5', '{FIELD}', 'color');"# ENDIF # role="button" aria-label="{@bbcode.color}">
					<i class="fa fa-fw fa-tint" aria-hidden="true"></i>
				</span>
				<div id="block-color{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.color}</div>
						</div>
						<div id="bb-color{FIELD}" class="cell-table color-table"></div>
					</div>
				</div>
			</li>
			<li id="format-bg-color" class="bbcode-elements">
				<span class="bbcode-button{AUTH_BGCOLOR}" # IF NOT C_DISABLED_BGCOLOR #data-modal data-target="block-bgcolor{FIELD}" onclick="bbcode_color('15', '{FIELD}', 'bgcolor');return false;"# ENDIF # role="button" aria-label="{@bbcode.bgcolor}">
					<i class="fa fa-fw fa-paint-brush" aria-hidden="true"></i>
				</span>
				<div id="block-bgcolor{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.bgcolor}</div>
						</div>
						<div id="bb-bgcolor{FIELD}" class="cell-table color-table"></div>
					</div>
				</div>
			</li>
			<li id="format-font-size" class="bbcode-elements">
				<span class="bbcode-button{AUTH_SIZE}" # IF NOT C_DISABLED_SIZE #data-modal# ENDIF # data-target="block-size{FIELD}" role="button" aria-label="{@bbcode.size}">
					<i class="fa fa-fw fa-text-height" aria-hidden="true"></i>
				</span>
				<div id="block-size{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
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
							<div class="cell-content">
								<span class="font-size-sample">{@bbcode.preview.text}</span>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_size('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="format-font-family" class="bbcode-elements">
				<span class="bbcode-button{AUTH_FONT}" # IF NOT C_DISABLED_FONT #data-modal# ENDIF # data-target="block-font{FIELD}" role="button" aria-label="{@bbcode.font}">
					<i class="fa fa-fw fa-font" aria-hidden="true"></i>
				</span>
				<div id="block-font{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.font}</div>
						</div>
						<nav class="cell-list cell-list-inline">
							<ul>
								<li class="hide-modal" onclick="insertbbcode('[font=andale mono]', '[/font]', '{FIELD}');"> <span style="font-family: andale mono;">Andale Mono</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=arial]', '[/font]', '{FIELD}');"> <span style="font-family: arial;">Arial</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=arial black]', '[/font]', '{FIELD}');"> <span style="font-family: arial black;">Arial Black</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=book antiqua]', '[/font]', '{FIELD}');"> <span style="font-family: book antiqua;">Book Antiqua</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=comic sans ms]', '[/font]', '{FIELD}');"> <span style="font-family: comic sans ms;">Comic Sans MS</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=courier new]', '[/font]', '{FIELD}');"> <span style="font-family: courier new;">Courier New</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=georgia]', '[/font]', '{FIELD}');"> <span style="font-family: georgia;">Georgia</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=helvetica]', '[/font]', '{FIELD}');"> <span style="font-family: helvetica;">Helvetica</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=impact]', '[/font]', '{FIELD}');"> <span style="font-family: impact;">Impact</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=symbol]', '[/font]', '{FIELD}');"> <span style="font-family: symbol;">Symbol</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=tahoma]', '[/font]', '{FIELD}');"> <span style="font-family: tahoma;">Tahoma</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=terminal]', '[/font]', '{FIELD}');"> <span style="font-family: terminal;">Terminal</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=times new roman]', '[/font]', '{FIELD}');"> <span style="font-family: times new roman;">Times New Roman</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=trebuchet ms]', '[/font]', '{FIELD}');"> <span style="font-family: trebuchet ms;">Trebuchet MS</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=verdana]', '[/font]', '{FIELD}');"> <span style="font-family: verdana;">Verdana</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=webdings]', '[/font]', '{FIELD}');"> <span style="font-family: webdings;">Webdings</span></li>
								<li class="hide-modal" onclick="insertbbcode('[font=wingdings]', '[/font]', '{FIELD}');"> <span style="font-family: wingdings;">Wingdings</span></li>
							</ul>
						</nav>
					</div>
				</div>
			</li>
			<li id="format-align" class="bbcode-elements">
				<span class="bbcode-button{AUTH_ALIGN}" # IF NOT C_DISABLED_ALIGN #data-modal# ENDIF # data-target="block-align{FIELD}" role="button" aria-label="{@bbcode.align}">
					<i class="fa fa-fw fa-align-left" aria-hidden="true"></i>
				</span>
				<div id="block-align{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.align}</div>
						</div>
						<nav class="cell-list">
							<ul>
								<li class="li-stretch">
									<span><i class="fa fa-fw fa-align-left"></i> {@bbcode.left} </span>
									<span class="button hide-modal" onclick="insertbbcode('[align=left]', '[/align]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
								<li class="li-stretch">
									<span><i class="fa fa-fw fa-align-center"></i> {@bbcode.center} </span>
									<span class="button hide-modal" onclick="insertbbcode('[align=center]', '[/align]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
								<li class="li-stretch">
									<span><i class="fa fa-fw fa-align-right"></i> {@bbcode.right} </span>
									<span class="button hide-modal" onclick="insertbbcode('[align=right]', '[/align]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
								<li class="li-stretch">
									<span><i class="fa fa-fw fa-align-justify"></i> {@bbcode.justify} </span>
									<span class="button hide-modal" onclick="insertbbcode('[align=justify]', '[/align]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
							</ul>
						</nav>
					</div>
				</div>
			</li>
			<li id="format-position" class="bbcode-elements">
				<span class="bbcode-button{AUTH_POSITIONS}" # IF NOT C_DISABLED_POSITIONS #data-modal# ENDIF # data-target="block-positions{FIELD}" role="button" aria-label="{@bbcode.positions}">
					<i class="fa fa-fw fa-indent" aria-hidden="true"></i>
				</span>
				<div id="block-positions{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.positions}</div>
						</div>
						<div class="cell-list">
							<ul>
								<li class="li-stretch">
									<span><i class="fa fa-fw fa-step-backward"></i> {@bbcode.float.left} </span>
									<span class="button hide-modal" onclick="insertbbcode('[float=left]', '[/float]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
								<li class="li-stretch">
									<span><i class="fa fa-fw fa-step-forward"></i>  {@bbcode.float.right} </span>
									<span class="button hide-modal" onclick="insertbbcode('[float=right]', '[/float]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
								<li class="li-stretch">
									<span><i class="fa fa-fw fa-indent"></i> {@bbcode.indent} </span>
									<span class="button hide-modal" onclick="insertbbcode('[indent]', '[/indent]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
								<li class="li-stretch">
									<span><i class="fa fa-fw fa-superscript"></i> {@bbcode.sup}</span>
									<span class="button hide-modal" onclick="insertbbcode('[sup]', '[/sup]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
								<li class="li-stretch">
									<span><i class="fa fa-fw fa-subscript"></i>  {@bbcode.sub} </span>
									<span class="button hide-modal" onclick="insertbbcode('[sub]', '[/sub]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</li>

			<li id="html-title" class="bbcode-elements">
				<span class="bbcode-button{AUTH_TITLE}" # IF NOT C_DISABLED_TITLE #data-modal# ENDIF # data-target="block-title{FIELD}" role="button" aria-label="{@bbcode.title}">
					<i class="fa fa-fw fa-heading" aria-hidden="true"></i>
				</span>
				<div id="block-title{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.title}</div>
						</div>
						<div class="cell-body">
							<div class="cell-content flex-between">
								<h2 class="formatter-title">{@bbcode.title.label} 1</h2>
								<span class="button hide-modal" onclick="insertbbcode('[title=1]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</span>
							</div>
						</div>
						<div class="cell-body">
							<div class="cell-content flex-between">
								<h3 class="formatter-title">{@bbcode.title.label} 2</h3>
								<span class="button hide-modal" onclick="insertbbcode('[title=2]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</span>
							</div>
						</div>
						<div class="cell-body">
							<div class="cell-content flex-between">
								<h4 class="formatter-title">{@bbcode.title.label} 3</h4>
								<span class="button hide-modal" onclick="insertbbcode('[title=3]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</span>
							</div>
						</div>
						<div class="cell-body">
							<div class="cell-content flex-between">
								<h5 class="formatter-title">{@bbcode.title.label} 4</h5>
								<span class="button hide-modal" onclick="insertbbcode('[title=4]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</span>
							</div>
						</div>
						<div class="cell-body">
							<div class="cell-content flex-between">
								<h6 class="formatter-title">{@bbcode.title.label} 5</h6>
								<span class="button hide-modal" onclick="insertbbcode('[title=5]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</span>
							</div>
						</div>
					</div>
				</div>
			</li>
			<li id="html-list" class="bbcode-elements">
				<span class="bbcode-button{AUTH_LIST}" # IF NOT C_DISABLED_LIST #data-modal# ENDIF # data-target="block-list{FIELD}" role="button" aria-label="{@bbcode.list}">
					<i class="fa fa-fw fa-list{AUTH_LIST}" aria-hidden="true"></i>
				</span>
				<div id="block-list{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
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
								<label class="checkbox" for="">
									<input id="bb_ordered_list{FIELD}" type="checkbox" name="bb_ordered_list{FIELD}" />
									<span>&nbsp;<span class="sr-only">${LangLoader::get_message('select', 'common')}</span></span>
								</label>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_list('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="html-table" class="bbcode-elements">
				<span class="bbcode-button{AUTH_TABLE}" # IF NOT C_DISABLED_TABLE #data-modal# ENDIF #  data-target="block-table{FIELD}" role="button" aria-label="{@bbcode.table}">
					<i class="fa fa-fw fa-table" aria-hidden="true"></i>
				</span>
				<div id="block-table{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
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
								<label class="checkbox" for="">
									<input type="checkbox" name="bb-head{FIELD}" id="bb-head{FIELD}" class="field-smaller">
									<span>&nbsp;<span class="sr-only">${LangLoader::get_message('select', 'common')}</span></span>
								</label>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_table('{FIELD}');bb_hide_block('7', '{FIELD}', 0);">{@bbcode.insert.table}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="html-containers" class="bbcode-elements">
				<span class="bbcode-button{AUTH_CONTAINERS}" # IF NOT C_DISABLED_CONTAINERS #data-modal# ENDIF # data-target="block-container{FIELD}" role="button" aria-label="{@bbcode.container}">
					<i class="far fa-fw fa-square" aria-hidden="true"></i>
				</span>
				<div id="block-container{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.container}</div>
						</div>
						<nav class="cell-list">
							<ul>
								<li id="html-paragraph" class="li-stretch{AUTH_P}">
									<span class="bbcode-label"> {@bbcode.paragraph} </span>
									<span class="button hide-modal" # IF NOT C_DISABLED_P#onclick="insertbbcode('[p]', '[/p]', '{FIELD}');"# ENDIF #>{@bbcode.tags.add}</span>
								</li>
								<li id="html-div-block" class="li-stretch{AUTH_BLOCK}">
									<span class="bbcode-label"> {@bbcode.block} </span>
									<span class="button hide-modal" # IF NOT C_DISABLED_BLOCK #onclick="insertbbcode('[block]', '[/block]', '{FIELD}');"# ENDIF #>{@bbcode.tags.add}</span>
								</li>
								<li id="html-div-custom" class="li-stretch{AUTH_CONTAINER}">
									<span class="bbcode-label"> {@bbcode.custom.div} </span>
									<span class="button" # IF NOT C_DISABLED_CONTAINER #data-modal# ENDIF # data-target="block-custom-div{FIELD}" role="button" aria-label="{@bbcode.tags.options}"> {@bbcode.tags.choice} </span>
								</li>
								<li id="html-fieldset" class="li-stretch{AUTH_FIELDSET}">
									<span class="bbcode-label">{@bbcode.fieldset}</span>
									<span class="button" # IF NOT C_DISABLED_FIELDSET #data-modal# ENDIF # data-target="block-fieldset{FIELD}"> {@bbcode.tags.choice} </span>
								</li>
								<li id="html-abbr" class="li-stretch{AUTH_ABBR}">
									<span class="bbcode-label"> {@bbcode.abbr} </span>
									<span class="button" # IF NOT C_DISABLED_ABBR #data-modal# ENDIF # data-target="block-abbr{FIELD}"> {@bbcode.tags.choice} </span>
								</li>
							</ul>
						</nav>
					</div>
				</div>
				<div id="block-custom-div{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.custom.div}</div>
						</div>
						<div class="cell-body">
							<div class="cell-content">
								<span class="message-helper bgc notice">{@bbcode.custom.div.alert}</span>
							</div>
						</div>
						<div class="cell-form">
							<label for="bb_cd_id{FIELD}" class="cell-label">{@bbcode.custom.div.id}</label>
							<div class="cell-input"><input type="text" id="bb_cd_id{FIELD}"></div>
						</div>
						<div class="cell-form">
							<label for="bb_cd_class{FIELD}" class="cell-label">{@bbcode.class}</label>
							<div class="cell-input"><input type="text" id="bb_cd_class{FIELD}"></div>
						</div>
						<div class="cell-form">
							<label for="bb_cd_style{FIELD}" class="cell-label">{@bbcode.style}</label>
							<div class="cell-input"><textarea id="bb_cd_style{FIELD}" rows="3" cols="32"></textarea></div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_custom_div('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
				<div id="block-fieldset{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.fieldset}</div>
						</div>
						<div class="cell-form">
							<label for="bb_legend{FIELD}" class="cell-label">{@bbcode.fieldset.lengend}</label>
							<div class="cell-input"><input type="text" id="bb_legend{FIELD}"></div>
						</div>
						<div class="cell-form">
							<label for="bb_fieldset_style{FIELD}" class="cell-label">{@bbcode.style}</label>
							<div class="cell-input"><textarea id="bb_fieldset_style{FIELD}" rows="3" cols="32"></textarea></div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_fieldset('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
				<div id="block-abbr{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.abbr}</div>
						</div>
						<div class="cell-form">
							<label for="bb_abbr_name{FIELD}" class="cell-label">{@bbcode.abbr}</label>
							<div class="cell-input"><input type="text" id="bb_abbr_name{FIELD}"></div>
						</div>
						<div class="cell-form">
							<label for="bb_abbr_desc{FIELD}" class="cell-label">{@bbcode.abbr.label}</label>
							<div class="cell-input"><input type="text" id="bb_abbr_desc{FIELD}"></div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_abbr('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="html-quote" class="bbcode-elements">
				<span class="bbcode-button{AUTH_QUOTE}" # IF NOT C_DISABLED_QUOTE #data-modal# ENDIF # data-target="block-quote{FIELD}" role="button" aria-label="{@bbcode.quote}">
					<i class="fa fa-fw fa-quote-left{AUTH_QUOTE}" aria-hidden="true"></i>
				</span>
				<div id="block-quote{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
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
							<label class="cell-label" for="bb_quote_extract{FIELD}">{@bbcode.quote}</label>
							<div class="cell-input">
								<textarea id="bb_quote_extract{FIELD}" rows="3" cols="32"></textarea>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_quote('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="html-hidden" class="bbcode-elements">
				<span class="bbcode-button{AUTH_HIDDEN}" # IF NOT C_DISABLED_HIDDEN #data-modal# ENDIF # data-target="block-hide{FIELD}" role="button" aria-label="{@bbcode.hide}">
					<i class="fa fa-fw fa-eye-slash" aria-hidden="true"></i>
				</span>
				<div id="block-hide{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.hide}</div>
						</div>
						<div class="cell-list">
							<ul>
								<li class="li-stretch{AUTH_HIDE}">
									<span><i class="far fa-fw fa-eye-slash" role="contentinfo" aria-label="{@bbcode.hide.all}"></i> {@bbcode.hide}</span>
									<span class="button hide-modal" onclick="insertbbcode('[hide]', '[/hide]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
								<li class="li-stretch{AUTH_MEMBER}">
									<span><i class="fa fa-fw fa-user-friends" role="contentinfo" aria-label="{@bbcode.hide.member}"></i> {@bbcode.member}</span>
									<span class="button hide-modal" onclick="insertbbcode('[member]', '[/member]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
								<li class="li-stretch{AUTH_MODERATOR}">
									<span><i class="fa fa-fw fa-user-shield" role="contentinfo" aria-label="{@bbcode.hide.moderator}"></i> {@bbcode.moderator}</span>
									<span class="button hide-modal" onclick="insertbbcode('[moderator]', '[/moderator]', '{FIELD}');">{@bbcode.tags.add}</span>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</li>
			<li id="html-style" class="bbcode-elements">
				<span class="bbcode-button{AUTH_STYLE}" # IF NOT C_DISABLED_STYLE #data-modal# ENDIF # data-target="block-style{FIELD}" role="button" aria-label="{@bbcode.style}">
					<i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
				</span>
				<div id="block-style{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.style}</div>
						</div>
						<div class="cell-body">
							<div class="cell-content">
								<div class="message-helper bgc notice flex-between">
									<span>${LangLoader::get_message('notice', 'main')}</span>
									<span class="button bgc-full notice hide-modal" onclick="insertbbcode('[style=notice]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</span>
								</div>
							</div>
						</div>
						<div class="cell-body">
							<div class="cell-content">
								<div class="message-helper bgc question flex-between">
									<span>${LangLoader::get_message('question', 'main')}</span>
									<span class="button bgc-full question hide-modal" onclick="insertbbcode('[style=question]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</span>
								</div>
							</div>
						</div>
						<div class="cell-body">
							<div class="cell-content">
								<div class="message-helper bgc success flex-between">
									<span>${LangLoader::get_message('success', 'main')}</span>
									<span class="button bgc-full success hide-modal" onclick="insertbbcode('[style=success]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</span>
								</div>
							</div>
						</div>
						<div class="cell-body">
							<div class="cell-content">
								<div class="message-helper bgc warning flex-between">
									<span>${LangLoader::get_message('warning', 'main')}</span>
									<span class="button bgc-full warning hide-modal" onclick="insertbbcode('[style=warning]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</span>
								</div>
							</div>
						</div>
						<div class="cell-body">
							<div class="cell-content">
								<div class="message-helper bgc error flex-between">
									<span>${LangLoader::get_message('error', 'main')}</span>
									<span class="button bgc-full error hide-modal" onclick="insertbbcode('[style=error]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</li>

			<li id="links-url" class="bbcode-elements">
				<span class="bbcode-button{AUTH_URL}" # IF NOT C_DISABLED_URL #data-modal# ENDIF # data-target="block-url{FIELD}" role="button" aria-label="{@bbcode.link}">
					<i class="fa fa-fw fa-globe" aria-hidden="true"></i>
				</span>
				<div id="block-url{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
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
							<span class="button hide-modal" onclick="bbcode_link('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-mail" class="bbcode-elements">
				<span class="bbcode-button{AUTH_MAIL}" # IF NOT C_DISABLED_MAIL #data-modal# ENDIF # data-target="block-mail{FIELD}" role="button" aria-label="{@bbcode.mail}">
					<i class="fa fa-fw fa-envelope" aria-hidden="true"></i>
				</span>
				<div id="block-mail{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
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
							<span class="button hide-modal" onclick="bbcode_mail('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-wikipedia" class="bbcode-elements">
				<span class="bbcode-button{AUTH_WIKIPEDIA}" # IF NOT C_DISABLED_WIKIPEDIA #data-modal# ENDIF # data-target="block-wikipedia{FIELD}" role="button" aria-label="{@bbcode.wikipedia}">
					<i class="fab fa-fw fa-wikipedia-w{AUTH_WIKIPEDIA}" aria-hidden="true"></i>
				</span>
				<div id="block-wikipedia{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.wikipedia}</div>
						</div>
						<div class="cell-form">
							<label for="bb_wikipedia_word{FIELD}" class="cell-label">{@bbcode.wikipedia.word}</label>
							<div class="cell-input">
								<input type="text" id="bb_wikipedia_word{FIELD}" name="bb_wikipedia_word{FIELD}">
							</div>
						</div>
						<div class="cell-form">
							<label for="bb_wikipedia_lang{FIELD}" class="cell-label">{@bbcode.wikipedia.lang}</label>
							<div class="cell-input">
								<select id="bb_wikipedia_lang{FIELD}">
									# START countries #
										<option value="{countries.ID}">{countries.NAME}</option>
									# END countries #
								</select>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_wikipedia('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-feed" class="bbcode-elements">
				<span class="bbcode-button{AUTH_FEED}" # IF NOT C_DISABLED_FEED #data-modal# ENDIF # data-target="block-feed{FIELD}" role="button" aria-label="{@bbcode.feed}">
					<i class="fa fa-fw fa-rss" aria-hidden="true"></i>
				</span>
				<div id="block-feed{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.feed}</div>
						</div>
						<div class="cell-form">
							<label for="bb_module_name{FIELD}" class="cell-label">{@bbcode.feed.module}</label>
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
							<span class="button hide-modal" onclick="bbcode_feed('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-anchor" class="bbcode-elements">
				<span class="bbcode-button{AUTH_ANCHOR}" # IF NOT C_DISABLED_ANCHOR #data-modal# ENDIF # data-target="block-anchor{FIELD}" role="button" aria-label="{@bbcode.anchor}">
					<i class="fa fa-fw fa-anchor{AUTH_ANCHOR}" aria-hidden="true"></i>
				</span>
				<div id="block-anchor{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
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
								<label class="checkbox" for="">
									<input class="checkbox-revealer" id="bb_anchor_url{FIELD}" type="checkbox" name="bb_anchor_url{FIELD}" />
									<span>&nbsp;<span class="sr-only">${LangLoader::get_message('select', 'common')}</span></span>
								</label>
							</div>
						</div>
						<div class="cell-body cell-hidden hidden">
							<div class="cell-content">
								<span class="message-helper bgc notice">{@H|bbcode.anchor.url.desc}</span>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_anchor('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-sound" class="bbcode-elements">
				<span class="bbcode-button{AUTH_SOUND}" # IF NOT C_DISABLED_SOUND #data-modal# ENDIF # data-target="block-sound{FIELD}" role="button" aria-label="{@bbcode.sound}">
					<i class="fa fa-fw fa-music" aria-hidden="true"></i>
				</span>
				<div id="block-sound{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.sound}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_sound_url{FIELD}">{@bbcode.sound.url}</label>
							<div class="cell-input grouped-inputs">
								<input class="grouped-element" id="bb_sound_url{FIELD}" type="text" name="bb_sound_url{FIELD}" />
								<a class="grouped-element" aria-label="{@bbcode.upload}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_sound_url{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
									<i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
								</a>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_sound('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="links-movie" class="bbcode-elements">
				<span class="bbcode-button{AUTH_MOVIE}" # IF NOT C_DISABLED_MOVIE #data-modal# ENDIF # data-target="block-movie{FIELD}" role="button" aria-label="{@bbcode.movie}">
					<i class="fa fa-fw fa-film" aria-hidden="true"></i>
				</span>
				<div id="block-movie{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.movie}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_movie_url{FIELD}">
								{@bbcode.movie.url}
							</label>
							<div class="cell-input grouped-inputs">
								<input class="grouped-element" id="bb_movie_url{FIELD}" type="text" name="bb_movie_url{FIELD}" />
								<a class="grouped-element" aria-label="{@bbcode.upload}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_movie_url{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
									<i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
								</a>
							</div>
						</div>
						<div class="cell-body">
							<div class="cell-content">
								{@bbcode.movie.format}
							</div>
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
							<div class="cell-input grouped-inputs">
								<input class="grouped-element" id="bb_movie_poster{FIELD}" type="text" name="bb_movie_poster{FIELD}" />
								<a class="grouped-element" aria-label="{@bbcode.upload}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_movie_poster{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
									<i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
								</a>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_movie('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="link-lightbox" class="bbcode-elements">
				<span class="bbcode-button{AUTH_LIGHTBOX}" # IF NOT C_DISABLED_LIGHTBOX #data-modal# ENDIF # data-target="block-lightbox{FIELD}" role="button" aria-label="{@bbcode.lightbox}">
					<i class="fa fa-fw fa-camera" aria-hidden="true"></i>
				</span>
				<div id="block-lightbox{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.lightbox}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_lightbox{FIELD}">
								{@bbcode.picture.url}
							</label>
							<div class="cell-input grouped-inputs">
								<input class="grouped-element" id="bb_lightbox{FIELD}" type="text" name="bb_lightbox{FIELD}" />
								<a class="grouped-element" aria-label="{@bbcode.upload}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_lightbox{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
									<i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
								</a>
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
							<span class="button hide-modal" onclick="bbcode_lightbox('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="link-figure" class="bbcode-elements">
				<span class="bbcode-button{AUTH_IMG}" # IF NOT C_DISABLED_IMG #data-modal# ENDIF # data-target="block-figure{FIELD}" role="button" aria-label="{@bbcode.figure}">
					<i class="far fa-fw fa-image" aria-hidden="true"></i>
				</span>
				<div id="block-figure{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.figure}</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_figure_img{FIELD}">{@bbcode.picture.url}</label>
							<div class="cell-input grouped-inputs">
								<input class="grouped-element" id="bb_figure_img{FIELD}" type="text" name="bb_figure_img{FIELD}" />
								<a class="grouped-element" aria-label="{@bbcode.upload}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_figure_img{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
									<i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
								</a>
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_picture_alt{FIELD}">{@bbcode.picture.alt}</label>
							<div class="cell-input">
								<input id="bb_picture_alt{FIELD}" type="text" name="bb_picture_alt{FIELD}" />
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_figure_desc{FIELD}">{@bbcode.figure.caption}</label>
							<div class="cell-input">
								<textarea id="bb_figure_desc{FIELD}" rows="3" cols="32"></textarea>
							</div>
						</div>
						<div class="cell-form">
							<label class="cell-label" for="bb_picture_width{FIELD}">{@bbcode.picture.width}</label>
							<div class="cell-input">
								<input id="bb_picture_width{FIELD}" type="number" name="bb_picture_width{FIELD}" />
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_figure('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			# IF C_UPLOAD_MANAGEMENT #
				<li id="link-upload" class="bbcode-elements">
					<a class="bbcode-button" href="#" aria-label="{@bbcode.upload}" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
						<i class="fa fa-fw fa-cloud-upload-alt" aria-hidden="true"></i>
					</a>
				</li>
			# ENDIF #

			<li id="code-smileys" class="bbcode-elements">
				<span class="bbcode-button{AUTH_SMILEYS}" # IF NOT C_DISABLED_SMILEYS #data-modal# ENDIF # data-target="block-smileys{FIELD}" role="button" aria-label="{@bbcode.smileys}">
					<i class="far fa-fw fa-smile" aria-hidden="true"></i>
				</span>
				<div id="block-smileys{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.smileys}</div>
						</div>
						<div class="cell-list cell-list-inline">
							<ul>
								# START smileys #
									<li>
										<span class="hide-modal" onclick="insertbbcode('{smileys.CODE}', 'smile', '{FIELD}');bb_hide_block('1', '{FIELD}', 0);return false;" role="button" aria-label="{smileys.CODE}">
											<img src="{smileys.URL}" alt="{smileys.CODE}" aria-hidden="true" class="smiley" />
										</span>
									</li>
								# END smileys #
							</ul>
						</div>
					</div>
				</div>
			</li>
			<li id="code-fa" class="bbcode-elements">
				<span class="bbcode-button {AUTH_FA}" # IF NOT C_DISABLED_FA #data-modal# ENDIF # data-target="block-fa{FIELD}" role="button" aria-label="{@bbcode.fa}">
					<i class="far fa-fw fa-flag" aria-hidden="true"></i>
				</span>
				<div id="block-fa{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
					<div class="content-panel cell">
						<div class="cell-header">
							<div class="cell-name">{@bbcode.fa}</div>
						</div>
						<div class="cell-body">
							<div class="cell-content align-center">
								{@bbcode.fa.tag}
							</div>
						</div>
						<div class="cell-list cell-list-inline">
							<ul>
								# START code_fa #
								<li>
									<span class="hide-modal" onclick="insertbbcode('[fa# IF code_fa.C_CUSTOM_PREFIX #={code_fa.PREFIX}# ENDIF #]{code_fa.CODE}[/fa]', '', '{FIELD}');" role="button" aria-label="{code_fa.CODE}">
										<i class="{code_fa.PREFIX} fa-{code_fa.CODE} fa-fw" aria-hidden="true"></i>
									</span>
								</li>
								# END code_fa #
							</ul>
						</div>
					</div>
				</div>
			</li>
			<li id="code-language" class="bbcode-elements">
				<span class="bbcode-button{AUTH_CODE}" # IF NOT C_DISABLED_CODE #data-modal# ENDIF # data-target="block-code{FIELD}" role="button" aria-label="{@bbcode.code}">
					<i class="fa fa-fw fa-code" aria-hidden="true"></i>
				</span>
				<div id="block-code{FIELD}" class="modal modal-animation">
					<div class="close-modal" role="button" aria-label="${LangLoader::get_message('close', 'main')}"></div>
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
										<option value="php">PHP</option>
									</optgroup>
									<optgroup label="{@bbcode.script}">
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
									<span>&nbsp;<span class="sr-only">${LangLoader::get_message('select', 'common')}</span></span>
								</label>
							</div>
						</div>
						<div class="cell-footer cell-input">
							<span class="button hide-modal" onclick="bbcode_code('{FIELD}');">{@bbcode.tags.add}</span>
						</div>
					</div>
				</div>
			</li>
			<li id="code-math" class="bbcode-elements">
				<span class="bbcode-button{AUTH_MATH}" # IF NOT C_DISABLED_MATH #onclick="insertbbcode('[math]', '[/math]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.math}">
					<i class="fa fa-fw fa-calculator" aria-hidden="true"></i>
				</span>
			</li>
			<li id="code-html" class="bbcode-elements">
				<span class="bbcode-button{AUTH_HTML}" # IF NOT C_DISABLED_HTML #onclick="insertbbcode('[html]', '[/html]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.html}">
					<i class="fab fa-fw fa-html5" aria-hidden="true"></i>
				</span>
			</li>
			<li id="code-help" class="bbcode-elements">
				<a class="bbcode-button" href="https://www.phpboost.com/wiki/bbcode" aria-label="{@bbcode.help}<br />${LangLoader::get_message('new.window', 'main')}" target="_blank" rel="noopener">
					<i class="far fa-fw fa-question-circle" aria-hidden="true"></i>
				</a>
			</li>
		</ul>
	</nav>
</div>

<script>
	// bbcode size : resize lorem texte when input value is changing
	jQuery('.font-size-input').on('input', function(e){
		jQuery(".font-size-sample").css('font-size',jQuery(this).val()+'px');
	});

	$('.checkbox-revealer').on('click', checkbox_revealer);
</script>
