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
                        jQuery('html, body').animate({scrollTop: jQuery('#' + preview_field).offset().top - 100});
                        jQuery('#loading-preview-' + field).hide();
                    }
                });
            }
            else
                alert(${escapejs(@warning.text)});
        }
    </script>

    <script src="{PATH_TO_ROOT}/BBCode/templates/js/bbcode.js"></script>
# ENDIF #

<div id="loading-preview-{FIELD}" class="loading-preview-container" style="display: none;">
    <div class="loading-preview">
        <i class="fa fa-spinner fa-2x fa-spin"></i>
    </div>
</div>
<div id="xmlhttprequest-preview{FIELD}" class="xmlhttprequest-preview" style="display: none"></div>

<div class="bbcode-bar">
    <nav class="bbcode-containers">
        <ul class="bbcode-groups">
            <li class="bbcode-group bbcode-text">
                <a href="#format" class="bbcode-group-title bbcode-button" aria-label="{@bbcode.format}"><i class="fa fa-fw fa-font" aria-hidden="true"></i></a>
                <ul class="bbcode-container cell-tile">
                    <li id="format-bold" class="bbcode-elements">
                        <a href="#strong" class="bbcode-button{AUTH_B} close-bbcode-sub" # IF NOT C_DISABLED_B #onclick="insertbbcode('[b]', '[/b]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.bold}">
                            <i class="fa fa-fw fa-bold" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li id="format-italic" class="bbcode-elements">
                        <a href="#italic" class="bbcode-button{AUTH_I} close-bbcode-sub" # IF NOT C_DISABLED_I #onclick="insertbbcode('[i]', '[/i]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.italic}">
                            <i class="fa fa-fw fa-italic" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li id="format-underline" class="bbcode-elements">
                        <a href="#underline" class="bbcode-button{AUTH_U} close-bbcode-sub" # IF NOT C_DISABLED_U #onclick="insertbbcode('[u]', '[/u]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.underline}">
                            <i class="fa fa-fw fa-underline" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li id="format-strike" class="bbcode-elements">
                        <a href="#strike" class="bbcode-button{AUTH_S} close-bbcode-sub" # IF NOT C_DISABLED_S #onclick="insertbbcode('[s]', '[/s]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.strike}">
                            <i class="fa fa-fw fa-strikethrough" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li id="format-color" class="bbcode-elements">
                        <a href="#color" class="bbcode-button{AUTH_COLOR}# IF NOT C_DISABLED_COLOR # modal-button --block-color{FIELD}# ENDIF #"# IF NOT C_DISABLED_COLOR # onclick="bbcode_color('5', '{FIELD}', 'color');"# ENDIF # role="button" aria-label="{@bbcode.color}">
                            <i class="fa fa-fw fa-tint" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_COLOR #
                            <div id="block-color{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.color}</h6>
                                    </div>
                                    <div id="bb-color{FIELD}" class="cell-table color-table close-bbcode-sub"></div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="format-bg-color" class="bbcode-elements">
                        <a href="#bgcolor" class="bbcode-button{AUTH_BGCOLOR}# IF NOT C_DISABLED_BGCOLOR # modal-button --block-bgcolor{FIELD}# ENDIF #"# IF NOT C_DISABLED_BGCOLOR # onclick="bbcode_color('15', '{FIELD}', 'bgcolor');return false;"# ENDIF # role="button" aria-label="{@bbcode.bgcolor}">
                            <i class="fa fa-fw fa-paint-brush" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_BGCOLOR #
                            <div id="block-bgcolor{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.bgcolor}</h6>
                                    </div>
                                    <div id="bb-bgcolor{FIELD}" class="cell-table color-table close-bbcode-sub"></div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="format-font-size" class="bbcode-elements">
                        <a href="#size" class="bbcode-button{AUTH_SIZE}# IF NOT C_DISABLED_SIZE # modal-button --block-size{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.size}">
                            <i class="fa fa-fw fa-text-height" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_SIZE #
                            <div id="block-size{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.size}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label id="font_size_picker" class="cell-label" for="bb_font_size{FIELD}">{@bbcode.size.picker}</label>
                                        <div class="cell-input">
                                            <input id="bb_font_size{FIELD}" class="font-size-input" type="number" name="bb_font_size{FIELD}" value="16" min="10" max="49" />
                                        </div>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content">
                                            <a href="#size-sample" class="font-size-sample">{@bbcode.preview.text}</a>
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#size-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_size('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="format-font-family" class="bbcode-elements">
                        <a href="#font-family" class="bbcode-button{AUTH_FONT}# IF NOT C_DISABLED_FONT # modal-button --block-font{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.font}">
                            <i class="fa fa-fw fa-font" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_FONT #
                            <div id="block-font{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.font}</h6>
                                    </div>
                                    <nav class="cell-list cell-list-inline">
                                        <ul>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=andale mono]', '[/font]', '{FIELD}');"> <a href="#font-andale" style="font-family: andale mono;">Andale Mono</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=arial]', '[/font]', '{FIELD}');"> <a href="#font-arial" style="font-family: arial;">Arial</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=arial black]', '[/font]', '{FIELD}');"> <a href="#font-arial-black" style="font-family: arial black;">Arial Black</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=book antiqua]', '[/font]', '{FIELD}');"> <a href="#font-book" style="font-family: book antiqua;">Book Antiqua</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=comic sans ms]', '[/font]', '{FIELD}');"> <a href="#font-comic" style="font-family: comic sans ms;">Comic Sans MS</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=courier new]', '[/font]', '{FIELD}');"> <a href="#font-courier" style="font-family: courier new;">Courier New</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=georgia]', '[/font]', '{FIELD}');"> <a href="#font-georgia" style="font-family: georgia;">Georgia</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=helvetica]', '[/font]', '{FIELD}');"> <a href="#font-helvetica" style="font-family: helvetica;">Helvetica</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=impact]', '[/font]', '{FIELD}');"> <a href="#font-impact" style="font-family: impact;">Impact</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=symbol]', '[/font]', '{FIELD}');"> <a href="#font-symbol" style="font-family: symbol;">Symbol</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=tahoma]', '[/font]', '{FIELD}');"> <a href="#font-tahoma" style="font-family: tahoma;">Tahoma</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=terminal]', '[/font]', '{FIELD}');"> <a href="#font-terminal" style="font-family: terminal;">Terminal</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=times new roman]', '[/font]', '{FIELD}');"> <a href="#font-times" style="font-family: times new roman;">Times New Roman</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=trebuchet ms]', '[/font]', '{FIELD}');"> <a href="#font-trebuchet" style="font-family: trebuchet ms;">Trebuchet MS</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=verdana]', '[/font]', '{FIELD}');"> <a href="#font-verdana" style="font-family: verdana;">Verdana</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=webdings]', '[/font]', '{FIELD}');"> <a href="#font-webdings" style="font-family: webdings;">Webdings</a></li>
                                            <li class="close-modal close-bbcode-sub" onclick="insertbbcode('[font=wingdings]', '[/font]', '{FIELD}');"> <a href="#font-wingdings" style="font-family: wingdings;">Wingdings</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="format-abbr" class="bbcode-elements">
                        <a href="#abbr" class="bbcode-button{AUTH_ABBR}# IF NOT C_DISABLED_ABBR # modal-button --block-abbr{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.abbr}">
                            <i class="fa fa-fw fa-austral-sign" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_ABBR #
                            <div id="block-abbr{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.abbr}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label for="bb_abbr_name{FIELD}" class="cell-label">{@bbcode.abbr}</label>
                                        <div class="cell-input"><input type="text" id="bb_abbr_name{FIELD}"></div>
                                    </div>
                                    <div class="cell-form">
                                        <label for="bb_abbr_desc{FIELD}" class="cell-label">{@bbcode.abbr.label}</label>
                                        <div class="cell-input"><input type="text" id="bb_abbr_desc{FIELD}"></div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#abbr-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_abbr('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                </ul>
            </li>
            <li class="bbcode-group bbcode-format">
                <a href="#bbcode-format" class="bbcode-group-title bbcode-button" aria-label="{@bbcode.layout}"><i class="fa fa-fw fa-table-columns" aria-hidden="true"></i></a>
                <ul class="bbcode-container cell-tile">
                    <li id="format-align" class="bbcode-elements">
                        <a href="#align" class="bbcode-button{AUTH_ALIGN}# IF NOT C_DISABLED_ALIGN # modal-button --block-align{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.align}">
                            <i class="fa fa-fw fa-align-left" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_ALIGN #
                            <div id="block-align{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.align}</h6>
                                    </div>
                                    <nav class="cell-list">
                                        <ul>
                                            <li class="li-stretch">
                                                <span href="#font"><i class="fa fa-fw fa-align-left"></i> {@bbcode.left} </span>
                                                <a href="#align-left-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[align=left]', '[/align]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch">
                                                <span><i class="fa fa-fw fa-align-center"></i> {@bbcode.center} </span>
                                                <a href="#align-center-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[align=center]', '[/align]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch">
                                                <span><i class="fa fa-fw fa-align-right"></i> {@bbcode.right} </span>
                                                <a href="#align-right-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[align=right]', '[/align]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch">
                                                <span><i class="fa fa-fw fa-align-justify"></i> {@bbcode.justify} </span>
                                                <a href="#align-justify-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[align=justify]', '[/align]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="format-position" class="bbcode-elements">
                        <a href="#position" class="bbcode-button{AUTH_POSITIONS}# IF NOT C_DISABLED_POSITIONS # modal-button --block-positions{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.positions}">
                            <i class="fa fa-fw fa-indent" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_POSITIONS #
                            <div id="block-positions{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.positions}</h6>
                                    </div>
                                    <div class="cell-list">
                                        <ul>
                                            <li class="li-stretch{AUTH_FLOAT}">
                                                <span><i class="fa fa-fw fa-step-backward"></i> {@bbcode.float.left} </span>
                                                <a href="#float-left-insert" class="button close-modal close-bbcode-sub"# IF NOT C_DISABLED_FLOAT # onclick="insertbbcode('[float=left]', '[/float]', '{FIELD}');"# ENDIF #>{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch{AUTH_FLOAT}">
                                                <span><i class="fa fa-fw fa-step-forward"></i>  {@bbcode.float.right} </span>
                                                <a href="#float-right-insert" class="button close-modal close-bbcode-sub"# IF NOT C_DISABLED_FLOAT # onclick="insertbbcode('[float=right]', '[/float]', '{FIELD}');"# ENDIF #>{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch{AUTH_INDENT}">
                                                <span><i class="fa fa-fw fa-indent"></i> {@bbcode.indent} </span>
                                                <a href="#indent-insert" class="button close-modal close-bbcode-sub"# IF NOT C_DISABLED_INDENT # onclick="insertbbcode('[indent]', '[/indent]', '{FIELD}');"# ENDIF #>{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch{AUTH_SUP}">
                                                <span><i class="fa fa-fw fa-superscript"></i> {@bbcode.sup}</span>
                                                <a href="#sup-insert" class="button close-modal close-bbcode-sub"# IF NOT C_DISABLED_SUP # onclick="insertbbcode('[sup]', '[/sup]', '{FIELD}');"# ENDIF #>{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch{AUTH_SUB}">
                                                <span><i class="fa fa-fw fa-subscript"></i>  {@bbcode.sub} </span>
                                                <a href="#sub-insert" class="button close-modal close-bbcode-sub"# IF NOT C_DISABLED_SUB # onclick="insertbbcode('[sub]', '[/sub]', '{FIELD}');"# ENDIF #>{@bbcode.tags.add}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-title" class="bbcode-elements">
                        <a href="#html-title" class="bbcode-button{AUTH_TITLE}# IF NOT C_DISABLED_TITLE # modal-button --block-title{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.title}">
                            <i class="fa fa-fw fa-heading" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_TITLE #
                            <div id="block-title{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.title}</h6>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content flex-between">
                                            <h2 class="formatter-title">{@bbcode.title.label} 1</h2>
                                            <a href="#title-1-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[title=1]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</a>
                                        </div>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content flex-between">
                                            <h3 class="formatter-title">{@bbcode.title.label} 2</h3>
                                            <a href="#title-2-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[title=2]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</a>
                                        </div>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content flex-between">
                                            <h4 class="formatter-title">{@bbcode.title.label} 3</h4>
                                            <a href="#title-3-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[title=3]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</a>
                                        </div>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content flex-between">
                                            <h5 class="formatter-title">{@bbcode.title.label} 4</h5>
                                            <a href="#title-4-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[title=4]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</a>
                                        </div>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content flex-between">
                                            <h6 class="formatter-title">{@bbcode.title.label} 5</h6>
                                            <a href="#title-5-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[title=5]', '[/title]', '{FIELD}');">{@bbcode.tags.add}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-list" class="bbcode-elements">
                        <a href="#html-list" class="bbcode-button{AUTH_LIST}# IF NOT C_DISABLED_LIST # modal-button --block-list{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.list}">
                            <i class="fa fa-fw fa-list{AUTH_LIST}" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_LIST #
                            <div id="block-list{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.list}</h6>
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
                                                <span>&nbsp;<span class="sr-only">{@common.select}</span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#html-list-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_list('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-table" class="bbcode-elements">
                        <a href="#html-table" class="bbcode-button{AUTH_TABLE}# IF NOT C_DISABLED_TABLE # modal-button --block-table{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.table}">
                            <i class="fa fa-fw fa-table" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_TABLE #
                            <div id="block-table{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.table}</h6>
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
                                                <span>&nbsp;<span class="sr-only">{@common.select}</span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#html-table-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_table('{FIELD}');">{@bbcode.insert.table}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-paragraph" class="bbcode-elements">
                        <a href="#paragraph-insert" class="bbcode-button{AUTH_P} close-bbcode-sub" # IF NOT C_DISABLED_P#onclick="insertbbcode('[p]', '[/p]', '{FIELD}');"# ENDIF # aria-label="{@bbcode.paragraph}">
                            <i class="fa fa-fw fa-paragraph" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li id="html-div-block" class="bbcode-elements">
                        <a href="#block-insert" class="bbcode-button{AUTH_BLOCK} close-bbcode-sub" # IF NOT C_DISABLED_BLOCK #onclick="insertbbcode('[block]', '[/block]', '{FIELD}');"# ENDIF # aria-label="{@bbcode.block}">
                            <i class="far fa-fw fa-square" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li id="html-fieldset" class="bbcode-elements">
                        <a href="#fielset-select" class="bbcode-button{AUTH_FIELDSET}# IF NOT C_DISABLED_FIELDSET # modal-button --block-fieldset{FIELD}# ENDIF #" aria-label="{@bbcode.fieldset}">
                            <i class="far fa-fw fa-window-maximize" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_FIELDSET #
                            <div id="block-fieldset{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.fieldset}</h6>
                                        <span class="error close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    </div>
                                    <div class="cell-form">
                                        <label for="bb_legend{FIELD}" class="cell-label">{@bbcode.fieldset.legend}</label>
                                        <div class="cell-input"><input type="text" id="bb_legend{FIELD}"></div>
                                    </div>
                                    <div class="cell-form">
                                        <label for="bb_fieldset_style{FIELD}" class="cell-label">{@bbcode.style}</label>
                                        <div class="cell-input"><textarea id="bb_fieldset_style{FIELD}" rows="3" cols="32"></textarea></div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#fielset-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_fieldset('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-containers" class="bbcode-elements">
                        <a href="#html-container" class="bbcode-button{AUTH_CONTAINER}# IF NOT C_DISABLED_CONTAINER # modal-button --block-container{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.container}">
                            <i class="fa fa-fw fa-laptop-code" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_CONTAINER #
                            <div id="block-container{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.custom.div}</h6>
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
                                    <div class="cell-footer">
                                        <a href="#custom-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_custom_div('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-modal" class="bbcode-elements">
                        <a href="#modal" class="bbcode-button{AUTH_MODAL}# IF NOT C_DISABLED_MODAL # modal-button --block-modal{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.modal}">
                            <i class="fa fa-fw fa-expand" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_MODAL #
                            <div id="block-modal{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.modal}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb-modal-name{FIELD}">{@bbcode.modal.name}</label>
                                        <div class="cell-input">
                                            <input type="text" name="bb-modal-name{FIELD}" id="bb-modal-name{FIELD}">
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#modal-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_modal('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-quote" class="bbcode-elements">
                        <a href="#html-quote" class="bbcode-button{AUTH_QUOTE}# IF NOT C_DISABLED_QUOTE # modal-button --block-quote{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.quote}">
                            <i class="fa fa-fw fa-quote-left{AUTH_QUOTE}" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_QUOTE #
                            <div id="block-quote{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.quote}</h6>
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
                                    <div class="cell-footer">
                                        <a href="#quote-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_quote('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-hidden" class="bbcode-elements">
                        <a href="#html-hidden" class="bbcode-button{AUTH_HIDDEN}# IF NOT C_DISABLED_HIDDEN # modal-button --block-hide{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.hide}">
                            <i class="fa fa-fw fa-eye-slash" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_HIDDEN #
                            <div id="block-hide{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.hide}</h6>
                                    </div>
                                    <div class="cell-list">
                                        <ul>
                                            <li class="li-stretch{AUTH_HIDE}">
                                                <span><i class="far fa-fw fa-eye-slash" role="contentinfo"></i> {@bbcode.hide.all}</span>
                                                <a href="#hide-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[hide]', '[/hide]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch{AUTH_MEMBER}">
                                                <span><i class="fa fa-fw fa-user-friends" role="contentinfo"></i> {@bbcode.hide.member}</span>
                                                <a href="#hide-guest-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[member]', '[/member]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch{AUTH_TEASER}">
                                                <span><i class="fa fa-fw fa-user" role="contentinfo"></i> {@bbcode.hide.teaser}</span>
                                                <a href="#hide-guest-begin-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[teaser]', '[/teaser]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </li>
                                            <li class="li-stretch{AUTH_MODERATOR}">
                                                <span><i class="fa fa-fw fa-user-shield" role="contentinfo"></i> {@bbcode.hide.moderator}</span>
                                                <a href="#hide-member-insert" class="button close-modal close-bbcode-sub" onclick="insertbbcode('[moderator]', '[/moderator]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-style" class="bbcode-elements">
                        <a href="#html-style" class="bbcode-button{AUTH_STYLE}# IF NOT C_DISABLED_STYLE # modal-button --block-style{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.style}">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_STYLE #
                            <div id="block-style{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.style}</h6>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content">
                                            <div class="message-helper bgc notice flex-between">
                                                <span>{@common.notice}</span>
                                                <a href="#style-notice-insert" class="button bgc-full notice close-modal close-bbcode-sub" onclick="insertbbcode('[style=notice]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content">
                                            <div class="message-helper bgc question flex-between">
                                                <span>{@common.question}</span>
                                                <a href="#style-question-insert" class="button bgc-full question close-modal close-bbcode-sub" onclick="insertbbcode('[style=question]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content">
                                            <div class="message-helper bgc success flex-between">
                                                <span>{@common.success}</span>
                                                <a href="#style-success-insert" class="button bgc-full success close-modal close-bbcode-sub" onclick="insertbbcode('[style=success]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content">
                                            <div class="message-helper bgc warning flex-between">
                                                <span>{@common.warning}</span>
                                                <a href="#style-warning-insert" class="button bgc-full warning close-modal close-bbcode-sub" onclick="insertbbcode('[style=warning]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cell-body">
                                        <div class="cell-content">
                                            <div class="message-helper bgc error flex-between">
                                                <span>{@common.error}</span>
                                                <a href="#style-error-insert" class="button bgc-full error close-modal close-bbcode-sub" onclick="insertbbcode('[style=error]', '[/style]', '{FIELD}');">{@bbcode.tags.add}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="html-hr" class="bbcode-elements">
                        <a href="#html-hr" class="bbcode-button{AUTH_LINE} close-bbcode-sub" onclick="# IF NOT C_DISABLED_LINE #insertbbcode('[line]', '', '{FIELD}'); # ENDIF #return false;" role="button" aria-label="{@bbcode.line}">
                            <i class="fa fa-fw fa-arrow-down-up-across-line {AUTH_LINE}" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="bbcode-group bbcode-layers">
                <a href="#layers" class="bbcode-group-title bbcode-button{AUTH_CONTAINER}" aria-label="{@bbcode.extra}"><i class="fa fa-fw fa-layer-group" aria-hidden="true"></i></a>
                # IF NOT C_DISABLED_CONTAINER #
                    <ul class="bbcode-container modal-container cell-modal cell-tile">
                        <li class="bbcode-elements">
                            <a href="#page-path" class="bbcode-button close-bbcode-sub" onclick="bbcode_page_path('{FIELD}');" role="button" aria-label="{@bbcode.page.path}">
                                <i class="fa fa-fw fa-road" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="bbcode-elements">
                            <a href="#file-path" class="bbcode-button close-bbcode-sub" onclick="bbcode_file_path('{FIELD}');" role="button" aria-label="{@bbcode.file.path}">
                                <i class="fa fa-fw fa-sitemap fa-rotate-270" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li id="pin" class="bbcode-elements">
                            <a href="#pin" class="bbcode-button modal-button --block-pin{FIELD}" role="button" aria-label="{@bbcode.pin}">
                                <i class="fa fa-fw fa-tags" aria-hidden="true"></i>
                            </a>
                            <div id="block-pin{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.pin.bgc}</h6>
                                    </div>
                                    <div class="cell-list cell-list-inline">
                                        <ul>
                                            <li><label for="no"><input type="radio" name="pinned-background" id="no" value="" checked="checked"> <span>{@bbcode.pin.bgc.none} &nbsp;</span></label></li>
                                            <li><label for="bgc"><input type="radio" name="pinned-background" id="bgc" value="bgc"> <span>{@bbcode.pin.bgc.transparent} &nbsp;</span></label></li>
                                            <li><label for="full"><input type="radio" name="pinned-background" id="full" value="bgc-full"> <span>{@bbcode.pin.bgc.full} &nbsp;</span></label></li>
                                        </ul>
                                    </div>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.pin.color.picker}</h6>
                                    </div>
                                    <div class="cell-list cell-list-inline">
                                        <ul id="pinned-color">
                                            <li class="no-style">
                                                <a href="#tag-moderator" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'moderator');" aria-label="moderator">
                                                    <i class="fa fa-fw fa-tag moderator" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-error" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'error');" aria-label="error">
                                                    <i class="fa fa-fw fa-tag error" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-warning" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'warning');" aria-label="warning">
                                                    <i class="fa fa-fw fa-tag warning" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-star" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'star');" aria-label="star">
                                                    <i class="fa fa-fw fa-tag star" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-success" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'success');" aria-label="success">
                                                    <i class="fa fa-fw fa-tag success" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-member" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'member');" aria-label="member">
                                                    <i class="fa fa-fw fa-tag member" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-question" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'question');" aria-label="question">
                                                    <i class="fa fa-fw fa-tag question" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-visitor" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'visitor');" aria-label="visitor">
                                                    <i class="fa fa-fw fa-tag visitor" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-link-color" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'link-color');" aria-label="link-color">
                                                    <i class="fa fa-fw fa-tag link-color" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-logo-color" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'logo-color');" aria-label="logo-color">
                                                    <i class="fa fa-fw fa-tag logo-color" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-notice" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'notice');" aria-label="notice">
                                                    <i class="fa fa-fw fa-tag notice" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-custom-author" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'custom-author');" aria-label="custom-author">
                                                    <i class="fa fa-fw fa-tag custom-author" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li class="no-style">
                                                <a href="#tag-administrator" class="bbcode-button close-modal close-bbcode-sub" onclick="bbcode_pin('{FIELD}', 'administrator');" aria-label="administrator">
                                                    <i class="fa fa-fw fa-tag administrator" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li id="columns" class="bbcode-elements">
                            <a href="#columns" class="bbcode-button modal-button --block-columns{FIELD}" role="button" aria-label="{@bbcode.columns}">
                                <i class="fa fa-fw fa-table-columns" aria-hidden="true"></i>
                            </a>
                            <div id="block-columns{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.columns}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb-cols{FIELD}">{@bbcode.columns.number}</label>
                                        <div class="cell-input">
                                            <input type="number" name="bb-cols{FIELD}" id="bb-cols{FIELD}" value="2" min="2" max="4">
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#columns-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_column('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li id="tabs" class="bbcode-elements">
                            <a href="#tabs" class="bbcode-button modal-button --block-tabs{FIELD}" role="button" aria-label="{@bbcode.tabs}">
                                <i class="fa fa-fw fa-table-list fa-rotate-90" aria-hidden="true"></i>
                            </a>
                            <div id="block-tabs{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.tabs}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb-tabs-id{FIELD}">{@bbcode.tabs.id}</label>
                                        <div class="cell-input">
                                            <input type="text" name="bb-tabs-id{FIELD}" id="bb-tabs-id{FIELD}">
                                        </div>
                                        <span>{@bbcode.tabs.location}</span>
                                        <div class="cell-list cell-list-inline">
                                            <ul>
                                                <li><label for="top"><input type="radio" name="bb-tabs-location{FIELD}" id="top" value="top" checked="checked"> <span>{@bbcode.tabs.location.top} &nbsp;</span></label></li>
                                                <li><label for="bottom"><input type="radio" name="bb-tabs-location{FIELD}" id="bottom" value="bottom"> <span>{@bbcode.tabs.location.bottom} &nbsp;</span></label></li>
                                                <li><label for="left"><input type="radio" name="bb-tabs-location{FIELD}" id="left" value="left"> <span>{@bbcode.tabs.location.left} &nbsp;</span></label></li>
                                                <li><label for="right"><input type="radio" name="bb-tabs-location{FIELD}" id="right" value="right"> <span>{@bbcode.tabs.location.right} &nbsp;</span></label></li>
                                            </ul>
                                        </div>
                                        <label class="cell-label" for="bb-tabs-nb{FIELD}">{@bbcode.tabs.number}</label>
                                        <div class="cell-input">
                                            <input type="number" name="bb-tabs-nb{FIELD}" id="bb-tabs-nb{FIELD}" value="2" min="2" max="50">
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#tabs-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_tabs('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li id="slider" class="bbcode-elements">
                            <a href="#slider" class="bbcode-button modal-button --block-slide{FIELD}" role="button" aria-label="{@bbcode.slide}">
                                <i class="fa fa-fw fa-panorama" aria-hidden="true"></i>
                            </a>
                            <div id="block-slide{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.slide}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb-slide-id{FIELD}">{@bbcode.slide.id}</label>
                                        <div class="cell-input">
                                            <input type="text" name="bb-slide-id{FIELD}" id="bb-slide-id{FIELD}">
                                        </div>
                                        <label class="cell-label" for="bb-slide-nb{FIELD}">{@bbcode.slide.number}</label>
                                        <div class="cell-input">
                                            <input type="number" name="bb-slide-nb{FIELD}" id="bb-slide-nb{FIELD}" value="1" min="1" max="4">
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#slide-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_slide('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                # ENDIF #
            </li>
            <li class="bbcode-group bbcode-links">
                <a href="#links" class="bbcode-group-title bbcode-button" aria-label="{@bbcode.links}"><i class="fa fa-fw fa-link" aria-hidden="true"></i></a>
                <ul class="bbcode-container cell-tile">
                    <li id="links-url" class="bbcode-elements">
                        <a href="#url" class="bbcode-button{AUTH_URL}# IF NOT C_DISABLED_URL # modal-button --block-url{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.link}">
                            <i class="fa fa-fw fa-globe" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_URL #
                            <div id="block-url{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.link}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_link_url{FIELD}">{@bbcode.link.url}</label>
                                        <div class="cell-input">
                                            <input id="bb_link_url{FIELD}" type="text" name="bb_link_url{FIELD}">
                                        </div>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_link_name{FIELD}">{@bbcode.link.label}</label>
                                        <div class="cell-input">
                                            <input id="bb_link_name{FIELD}" type="text" name="bb_link_name{FIELD}">
                                        </div>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_link_target{FIELD}">{@common.open.new.window}</label>
                                        <div class="cell-input">
                                            <label class="checkbox" for="">
                                                <input id="bb_link_target{FIELD}" type="checkbox" name="bb_link_target{FIELD}" />
                                                <span>&nbsp;<span class="sr-only">{@common.select}</span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#url-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_link('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="links-mail" class="bbcode-elements">
                        <a href="#email" class="bbcode-button{AUTH_MAIL}# IF NOT C_DISABLED_MAIL # modal-button --block-mail{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.mail}">
                            <i class="fa fa-fw iboost fa-iboost-email" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_MAIL #
                            <div id="block-mail{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.mail}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_mail_url{FIELD}">{@bbcode.mail}</label>
                                        <div class="cell-input">
                                            <input id="bb_mail_url{FIELD}" type="email" name="bb_mail_url{FIELD}">
                                        </div>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_mail_name{FIELD}">{@bbcode.mail.label}</label>
                                        <div class="cell-input">
                                            <input id="bb_mail_name{FIELD}" type="text" name="bb_mail_name{FIELD}">
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#email-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_mail('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="links-wikipedia" class="bbcode-elements">
                        <a href="#wikipedia" class="bbcode-button{AUTH_WIKIPEDIA}# IF NOT C_DISABLED_WIKIPEDIA # modal-button --block-wikipedia{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.wikipedia}">
                            <i class="fab fa-fw fa-wikipedia-w{AUTH_WIKIPEDIA}" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_WIKIPEDIA #
                            <div id="block-wikipedia{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.wikipedia}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label for="bb_wikipedia_word{FIELD}" class="cell-label">{@bbcode.wikipedia.word}</label>
                                        <div class="cell-input">
                                            <input type="text" id="bb_wikipedia_word{FIELD}" name="bb_wikipedia_word{FIELD}">
                                        </div>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_wikipedia_lang_cb{FIELD}">
                                            {@bbcode.wikipedia.add.lang}
                                            <span class="field-description">{@H|bbcode.wikipedia.add.lang.clue}</span>
                                        </label>
                                        <div class="cell-input">
                                            <label class="checkbox" for="">
                                                <input class="checkbox-revealer" id="bb_wikipedia_lang_cb{FIELD}" type="checkbox" name="bb_anchor_url{FIELD}" />
                                                <span>&nbsp;<span class="sr-only">{@common.select}</span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cell-form cell-hidden hidden">
                                        <label for="bb_wikipedia_lang{FIELD}" class="cell-label">{@bbcode.wikipedia.lang}</label>
                                        <div class="cell-input">
                                            <select id="bb_wikipedia_lang{FIELD}">
                                                # START countries #
                                                    <option value="{countries.ID}">{countries.NAME}</option>
                                                # END countries #
                                            </select>
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#wikipedia-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_wikipedia('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="links-feed" class="bbcode-elements">
                        <a href="#links-feed" class="bbcode-button{AUTH_FEED} # IF NOT C_DISABLED_FEED #modal-button --block-feed{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.feed}">
                            <i class="fa fa-fw fa-rss" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_FEED #
                            <div id="block-feed{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.feed}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label for="bb_feed_module_name{FIELD}" class="cell-label">{@bbcode.feed.module}</label>
                                        <div class="cell-input">
                                            <select id="bb_feed_module_name{FIELD}">
                                                <option value="">{@bbcode.feed.select}</option>
                                                # START feeds_modules #
                                                    <option value="{feeds_modules.VALUE}">{feeds_modules.NAME}</option>
                                                # END feeds_modules #
                                            </select>
                                        </div>
                                    </div>
                                    <div class="cell-form">
                                        <label for="bb_feed_category{FIELD}" class="cell-label">{@common.category}</label>
                                        <div class="cell-input">
                                            <select id="bb_feed_category{FIELD}" disabled="disabled"></select>
                                        </div>
                                    </div>
                                    <div class="cell-form">
                                        <label for="bb_feed_number{FIELD}" class="cell-label">{@bbcode.feed.number}</label>
                                        <div class="cell-input">
                                            <input type="number" id="bb_feed_number{FIELD}" min="1" max="10" value="5">
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#links-feed-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_feed('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="links-anchor" class="bbcode-elements">
                        <a href="#anchor" class="bbcode-button{AUTH_ANCHOR} # IF NOT C_DISABLED_ANCHOR #modal-button --block-anchor{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.anchor}">
                            <i class="fa fa-fw fa-anchor{AUTH_ANCHOR}" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_ANCHOR #
                            <div id="block-anchor{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.anchor}</h6>
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
                                                <input id="bb_anchor_url{FIELD}" type="checkbox" name="bb_anchor_url{FIELD}" />
                                                <span>&nbsp;<span class="sr-only">{@common.select}</span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#anchor-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_anchor('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                </ul>
            </li>
            <li class="bbcode-group bbcode-files">
                <a href="#files" class="bbcode-group-title bbcode-button" aria-label="{@bbcode.files}"><i class="fa fa-fw fa-file-import" aria-hidden="true"></i></a>
                <ul class="bbcode-container cell-tile">
                    <li id="files-sound" class="bbcode-elements">
                        <a href="#sound" class="bbcode-button{AUTH_SOUND}# IF NOT C_DISABLED_SOUND # modal-button --block-sound{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.sound}">
                            <i class="fa fa-fw fa-music" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_SOUND #
                            <div id="block-sound{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.sound}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_sound_url{FIELD}">{@bbcode.sound.url}</label>
                                        <div class="cell-input grouped-inputs">
                                            <input class="grouped-element" id="bb_sound_url{FIELD}" type="text" name="bb_sound_url{FIELD}" />
                                            # IF C_UPLOAD_MANAGEMENT #
                                                <a class="grouped-element button" aria-label="{@upload.file.add}" onclick="direct_upload(this, 'bb_sound_url{FIELD}', '${escape(TOKEN)}')">
                                                    <i class="fa fa-laptop" aria-hidden="true"></i>
                                                </a>
                                                <a class="grouped-element submit" aria-label="{@upload.files.management}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_sound_url{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
                                                    <i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
                                                </a>
                                            # ENDIF #
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#sound-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_sound('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="files-movie" class="bbcode-elements">
                        <a href="#movie" class="bbcode-button{AUTH_MOVIE}# IF NOT C_DISABLED_MOVIE # modal-button --block-movie{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.movie}">
                            <i class="fa fa-fw fa-film" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_MOVIE #
                            <div id="block-movie{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.movie}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_movie_url{FIELD}">
                                            {@bbcode.movie.url}
                                        </label>
                                        <div class="cell-input grouped-inputs">
                                            <input class="grouped-element" id="bb_movie_url{FIELD}" type="text" name="bb_movie_url{FIELD}" />
                                            # IF C_UPLOAD_MANAGEMENT #
                                                <a class="grouped-element button" aria-label="{@upload.file.add}" onclick="direct_upload(this, 'bb_movie_url{FIELD}', '${escape(TOKEN)}')">
                                                    <i class="fa fa-laptop" aria-hidden="true"></i>
                                                </a>
                                                <a class="grouped-element submit" aria-label="{@upload.files.management}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_movie_url{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
                                                    <i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
                                                </a>
                                            # ENDIF #
                                        </div>
                                    </div>
                                    <div class="cell-form">
                                        <label for="bb_movie_host{FIELD}" class="cell-label">{@bbcode.movie.host}</label>
                                        <div class="cell-input">
                                            <select name="bb_movie_host{FIELD}" id="bb_movie_host{FIELD}">
                                                <option value="local">{@bbcode.movie.host.local}</option>
                                                <option value="youtube">{@bbcode.movie.host.youtube}</option>
                                                <option value="dailymotion">{@bbcode.movie.host.dailymotion}</option>
                                                <option value="vimeo">{@bbcode.movie.host.vimeo}</option>
                                            </select>
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
                                    <div class="cell-footer">
                                        <a href="#movie-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_movie('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="files-lightbox" class="bbcode-elements">
                        <a href="#lightbox" class="bbcode-button{AUTH_LIGHTBOX}# IF NOT C_DISABLED_LIGHTBOX # modal-button --block-lightbox{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.lightbox}">
                            <i class="fa fa-fw fa-camera" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_LIGHTBOX #
                            <div id="block-lightbox{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.lightbox}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_lightbox{FIELD}">
                                            {@bbcode.picture.url}
                                        </label>
                                        <div class="cell-input grouped-inputs">
                                            <input class="grouped-element" id="bb_lightbox{FIELD}" type="text" name="bb_lightbox{FIELD}" />
                                            # IF C_UPLOAD_MANAGEMENT #
                                                <a class="grouped-element button" aria-label="{@upload.file.add}" onclick="direct_upload(this, 'bb_lightbox{FIELD}', '${escape(TOKEN)}')">
                                                    <i class="fa fa-laptop" aria-hidden="true"></i>
                                                </a>
                                                <a class="grouped-element submit" aria-label="{@upload.files.management}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_lightbox{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
                                                    <i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
                                                </a>
                                            # ENDIF #
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
                                    <div class="cell-footer">
                                        <a href="#lightbox-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_lightbox('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="files-figure" class="bbcode-elements">
                        <a href="#picture" class="bbcode-button{AUTH_IMG}# IF NOT C_DISABLED_IMG # modal-button --block-figure{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.figure}">
                            <i class="far fa-fw fa-image" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_IMG #
                            <div id="block-figure{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.figure}</h6>
                                    </div>
                                    <div class="cell-form">
                                        <label class="cell-label" for="bb_figure_img{FIELD}">{@bbcode.picture.url}</label>
                                        <div class="cell-input grouped-inputs">
                                            <input class="grouped-element" id="bb_figure_img{FIELD}" type="text" name="bb_figure_img{FIELD}" />
                                            # IF C_UPLOAD_MANAGEMENT #
                                                <a class="grouped-element button" aria-label="{@upload.file.add}" onclick="direct_upload(this, 'bb_figure_img{FIELD}', '${escape(TOKEN)}')">
                                                    <i class="fa fa-laptop" aria-hidden="true"></i>
                                                </a>
                                                <a class="grouped-element submit" aria-label="{@upload.files.management}" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd=bb_figure_img{FIELD}&amp;parse=true&amp;no_path=true', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;">
                                                    <i class="fa fa-cloud-upload-alt" aria-hidden="true"></i>
                                                </a>
                                            # ENDIF #
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
                                    <div class="cell-footer">
                                        <a href="#picture-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_figure('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    # IF C_UPLOAD_MANAGEMENT #
                        <li id="files-upload" class="bbcode-elements">
                            <a class="bbcode-button{AUTH_UPLOAD} close-bbcode-sub" href="#" aria-label="{@bbcode.upload}" # IF NOT C_DISABLED_UPLOAD #onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&amp;fd={FIELD}&amp;edt=BBCode', '', 'height=550,width=769,resizable=yes,scrollbars=yes');return false;"# ENDIF #>
                                <i class="fa fa-fw fa-cloud-upload-alt" aria-hidden="true"></i>
                            </a>
                        </li>
                    # ENDIF #
                </ul>
            </li>
            <li class="bbcode-group bbcode-icons">
                <a href="#icons" class="bbcode-group-title bbcode-button" aria-label="{@bbcode.icons}">
                    <i class="fab fa-fw fa-fort-awesome-alt" aria-hidden="true"></i>
                </a>
                <ul class="bbcode-container cell-tile">
                    <li id="code-smileys" class="bbcode-elements">
                        <a href="#smileys" class="bbcode-button{AUTH_SMILEYS}# IF NOT C_DISABLED_SMILEYS # modal-button --block-smileys{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.smileys}">
                            <i class="far fa-fw fa-smile" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_SMILEYS #
                            <div id="block-smileys{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.smileys}</h6>
                                    </div>
                                    <div class="cell-list cell-list-inline">
                                        <ul>
                                            # START smileys #
                                                <li>
                                                    <a href="#smiley{smileys.CODE}" class="close-modal close-bbcode-sub" onclick="insertbbcode('{smileys.CODE}', 'smile', '{FIELD}');" role="button" aria-label="{smileys.CODE}">
                                                        <img src="{smileys.URL}" alt="{smileys.CODE}" aria-hidden="true" class="smiley" />
                                                    </a>
                                                </li>
                                            # END smileys #
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="emojis" class="bbcode-elements">
                        <a href="#emojis" class="bbcode-button {AUTH_EMOJI}# IF NOT C_DISABLED_EMOJI # modal-button --block-emojis{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.emojis}">
                            <span class="stacked">
                                <i class="far fa-fw fa-smile" aria-hidden="true"></i>
                                <span class="stack-event stack-top-right small">
                                    <i class="fa fa-fw fa-code" aria-hidden="true"></i>
                                </span>
                            </span>
                        </a>
                        # IF NOT C_DISABLED_EMOJI #
                            <div id="block-emojis{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.emojis}</h6>
                                    </div>
                                    <div class="cell-content align-center">
                                        {@H|bbcode.emojis.link}
                                    </div>
                                    <div class="cell-list cell-list-inline cell-overflow-y emojis-list">
                                        <ul class="flex-start">
                                            # START emojis #
                                                # IF emojis.C_CATEGORY #
                                                    </ul>
                                                    <ul class="flex-start">
                                                        <li> <h6>{emojis.CATEGORY_NAME}</h6> </li>
                                                    </ul>
                                                    <ul class="flex-start">
                                                # ELSE #
                                                    # IF emojis.C_SUB_CATEGORY #
                                                        </ul>
                                                        <ul class="flex-start">
                                                            <li> <h5>{emojis.CATEGORY_NAME}</h5> </li>
                                                        </ul>
                                                        <ul class="flex-start">
                                                    # ELSE #
                                                        <li# IF emojis.C_END_LINE # class="hidden"# ENDIF #>
                                                            <span class="close-modal close-bbcode-sub bigger emoji-tag" onclick="insertbbcode('{emojis.DECIMAL}', '', '{FIELD}');" role="button"# IF emojis.C_NAME # aria-label="{emojis.NAME}"# ENDIF #>
                                                                {emojis.DECIMAL}
                                                            </span>
                                                        </li>
                                                    # ENDIF #
                                                # ENDIF #
                                            # END emojis #
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="code-fa" class="bbcode-elements">
                        <a href="#fas" class="bbcode-button {AUTH_FA}# IF NOT C_DISABLED_FA # modal-button --block-fa{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.fa}">
                            <i class="fab fa-fw fa-fort-awesome" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_FA #
                            <div id="block-fa{FIELD}" class="modal modal-half">
                                <div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.fa}</h6>
                                    </div>
                                    <div class="cell-content align-center">
                                        {@bbcode.fa.tag}
                                    </div>
                                    <div class="cell-list cell-list-inline">
                                        <ul>
                                            # START code_fa #
                                                <li>
                                                    <a href="#fa" class="close-modal close-bbcode-sub" onclick="insertbbcode('[fa# IF code_fa.C_CUSTOM_PREFIX #={code_fa.PREFIX}# ENDIF #]{code_fa.CODE}[/fa]', '', '{FIELD}');" role="button" aria-label="{code_fa.CODE}">
                                                        <i class="fa# IF code_fa.C_CUSTOM_PREFIX # {code_fa.PREFIX}# ENDIF # fa-{code_fa.CODE} fa-fw" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            # END code_fa #
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                </ul>
            </li>
            <li class="bbcode-group bbcode-code">
                <a href="#code" class="bbcode-group-title bbcode-button" aria-label="{@bbcode.format.code}"><i class="fa fa-fw fa-file-code" aria-hidden="true"></i></a>
                <ul class="bbcode-container cell-tile">
                    <li id="code-language" class="bbcode-elements">
                        <a href="#code-format" class="bbcode-button{AUTH_CODE}# IF NOT C_DISABLED_CODE # modal-button --block-code{FIELD}# ENDIF #" role="button" aria-label="{@bbcode.code}">
                            <i class="fa fa-fw fa-code" aria-hidden="true"></i>
                        </a>
                        # IF NOT C_DISABLED_CODE #
                            <div id="block-code{FIELD}" class="modal modal-half">
                                <div href="#close-code" class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                                <div class="modal-content cell cell-bbcode">
                                    <span class="error hide-modal close-modal close-bbcode-sub" aria-label="{@bbcode.tags.cancel}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                    <div class="cell-header">
                                        <h6 class="cell-name">{@bbcode.code}</h6>
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
                                                <span>&nbsp;<span class="sr-only">{@common.select}</span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="cell-footer">
                                        <a href="#code-format-insert" class="button close-modal close-bbcode-sub" onclick="bbcode_code('{FIELD}');">{@bbcode.tags.add}</a>
                                    </div>
                                </div>
                            </div>
                        # ENDIF #
                    </li>
                    <li id="code-math" class="bbcode-elements">
                        <a href="#math" class="bbcode-button{AUTH_MATH} close-bbcode-sub"# IF NOT C_DISABLED_MATH # onclick="insertbbcode('[math]', '[/math]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.math}">
                            <i class="fa fa-fw fa-calculator" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li id="code-html" class="bbcode-elements">
                        <a href="#html" class="bbcode-button{AUTH_HTML} close-bbcode-sub"# IF NOT C_DISABLED_HTML # onclick="insertbbcode('[html]', '[/html]', '{FIELD}');"# ENDIF # role="button" aria-label="{@bbcode.html}">
                            <i class="fab fa-fw fa-html5" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="bbcode-group bbcode-help">
                <a class="bbcode-button offload" href="https://www.phpboost.com/wiki/bbcode" aria-label="{@bbcode.help}<br />{@common.is.new.window}" target="_blank" rel="noopener">
                    <i class="far fa-fw fa-question-circle" aria-hidden="true"></i>
                </a>
            </li>
            <li class="bbcode-group bbcode-resize">
                <a href="#resize" id="resize-textarea" class="collapse bbcode-group-title bbcode-button" aria-label="{@bbcode.collapse}"><i class="fa fa-fw fa-compress" aria-hidden="true"></i></a>
            </li>
        </ul>
    </nav>
</div>
<script>
    var EXPAND = ${escapejs(@bbcode.expand)};
    var COLLAPSE = ${escapejs(@bbcode.collapse)};
    jQuery("#bb_feed_module_name{FIELD}").change(function () {
        var feed_module = jQuery("#bb_feed_module_name{FIELD}").children(":selected").attr("value");
        if (feed_module != '' && feed_module != null) {
            jQuery.ajax({
                url: PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/categories/list/',
                type: "post",
                dataType: "json",
                data: {token: ${escapejs(TOKEN)}, module_id: feed_module},
                success: function(returnData){
                    jQuery("#bb_feed_category{FIELD}").empty().append(returnData.options);
                    jQuery("#bb_feed_category{FIELD}").prop("disabled", false);
                },
                error: function(e){
                    jQuery("#bb_feed_category{FIELD}").empty();
                    jQuery("#bb_feed_category{FIELD}").prop("disabled", true);
                }
            });
        } else {
            jQuery("#bb_feed_category{FIELD}").prop("disabled", true);
        }
    });

    // bbcode size : resize lorem texte when input value is changing
    jQuery('.font-size-input').on('input', function(e){
        jQuery(".font-size-sample").css('font-size',jQuery(this).val()+'px');
    });

    jQuery('.checkbox-revealer').on('click', checkbox_revealer);
</script>
