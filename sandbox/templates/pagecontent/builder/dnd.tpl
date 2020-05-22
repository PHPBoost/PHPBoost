<div class="formatter-container formatter-hide no-js tpl">
    <span class="formatter-title title-perso">{@sandbox.source.code} : drag and drop / upload</span>
    <div class="formatter-content formatter-code">
        <div class="formatter-content"><pre class="language-html line-numbers"><code class="language-html">&lt;div class="dnd-area">
    &lt;div class="dnd-dropzone">
        &lt;label for="inputfiles" class="dnd-label">
            \# IF C_MULTIPLE \#
                \# IF IS_MOBILE_DEVICE \#${LangLoader::get_message('click.and.add.files', 'upload-common')}\# ELSE \#${LangLoader::get_message('drag.and.drop.files', 'upload-common')}\# ENDIF \#
            \# ELSE \#
                \# IF IS_MOBILE_DEVICE \#${LangLoader::get_message('click.and.add.file', 'upload-common')}\# ELSE \#${LangLoader::get_message('drag.and.drop.file', 'upload-common')}\# ENDIF \#
            \# ENDIF \#
            &lt;span class="d-block">&lt;/span>
        &lt;/label>
        &lt;input type="file" name="$\{escape(NAME)\}\# IF C_MULTIPLE \#[]\# ENDIF \#" id="$\{escape(HTML_ID)\}" class="ufiles"\# IF C_DISABLED \# disabled="disabled" \# ENDIF \# />
    &lt;/div>
    &lt;input type="hidden" name="max_file_size" value="\{MAX_FILE_SIZE\}">
    &lt;div class="ready-to-load">
        &lt;button type="button" class="button clear-list">${LangLoader::get_message('clear.list', 'upload-common')}&lt;/button>
        \# IF C_MULTIPLE \#
            &lt;span class="fa-stack fa-lg">
                &lt;i class="far fa-file fa-stack-2x ">&lt;/i>
                &lt;strong class="fa-stack-1x files-nbr">&lt;/strong>
            &lt;/span>
        \# ENDIF \#
    &lt;/div>
    &lt;div class="modal-container">
        &lt;button class="button upload-help" data-modal data-target="upload-helper" aria-label="${LangLoader::get_message('upload.helper', 'upload-common')}">&lt;i class="fa fa-question">&lt;/i>&lt;/button>
        &lt;div id="upload-helper" class="modal modal-animation">
            &lt;div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}">&lt;/div>
            &lt;div class="content-panel">
                &lt;h3>${LangLoader::get_message('upload.helper', 'upload-common')}&lt;/h3>
                \# IF IS_ADMIN \#
                    &lt;p>&lt;strong>${LangLoader::get_message('max.file.size', 'upload-common')} :&lt;/strong> \{MAX_FILE_SIZE_TEXT\}&lt;/p>
                \# ELSE \#
                    &lt;p>&lt;strong>${LangLoader::get_message('max.files.size', 'upload-common')} :&lt;/strong> \{MAX_FILES_SIZE_TEXT\}&lt;/p>
                \# ENDIF \#
                &lt;p>&lt;strong>${LangLoader::get_message('allowed.extensions', 'upload-common')} :&lt;/strong> "\{ALLOWED_EXTENSIONS\}"&lt;/p>
            &lt;/div>
        &lt;/div>
    &lt;/div>
&lt;/div>
&lt;ul class="ulist">&lt;/ul>

&lt;script>
    jQuery('\#$\{escape(HTML_ID)\}').parents('form').first()[0].enctype = "multipart/form-data";
    jQuery('\#$\{escape(HTML_ID)\}').dndfiles({
        multiple:\# IF C_MULTIPLE \# true\# ELSE \# false\# ENDIF \#,
        maxFileSize: '\{MAX_FILE_SIZE\}',
        maxFilesSize: '\{MAX_FILES_SIZE\}',
        allowedExtensions: ["\{ALLOWED_EXTENSIONS\}"],
        warningText: ${escapejs(LangLoader::get_message('warning.upload.disabled', 'upload-common'))},
        warningExtension: ${escapejs(LangLoader::get_message('warning.upload.extension', 'upload-common'))},
        warningFileSize: ${escapejs(LangLoader::get_message('warning.upload.file.size', 'upload-common'))},
        warningFilesNbr: ${escapejs(LangLoader::get_message('warning.upload.files.number', 'upload-common'))},
    });
&lt;/script>
</code></pre></div>
    </div>
</div>
