<h1>{@step.server.title}</h1>
<a href="http://www.php.net/">
    <img src="templates/images/php.png" alt="PHP" style="float:right; margin-bottom:5px; margin-left:5px;"/>
</a>
{@H|step.server.explanation}

<fieldset>
    <legend>{@php.version}</legend>
    <p>${set(@H|php.version.check.explanation, ['min_php_version': MIN_PHP_VERSION])}</p>
    <dl>
        <dt><label>${set(@php.version.check, ['min_php_version': MIN_PHP_VERSION])}</label></dt>
        <dd>
        # IF PHP_VERSION_OK #
            <img src="templates/images/success.png" alt="{L_YES}" />
        # ELSE #
            <img src="templates/images/stop.png" alt="{L_NO}" />
        # ENDIF #
        </dd>                               
    </dl>
</fieldset>

<fieldset>
    <legend>{@php.extensions}</legend> 
    <p>{@php.extensions.check}</p>
    <dl>
        <dt><label>{@php.extensions.check.gd_library}</label><br /><span>{@php.extensions.check.gd_library.explanation}</span></dt>
        <dd>
        # IF HAS_GD_LIBRARY #
            <img src="templates/images/success.png" alt="{@yes}" />
        # ELSE #
            <img src="templates/images/stop.png" alt="{@no}" />
        # ENDIF #
        </dd>                               
    </dl>
    <dl>
        <dt><label>{@server.url_rewriting}</label><br /><span>{@server.url_rewriting.explanation}</span></dt>
        <dd>
        # IF URL_REWRITING_KNOWN #
            # IF URL_REWRITING_AVAILABLE #
            <img src="templates/images/success.png" alt="{@yes}" />
            # ELSE #
            <img src="templates/images/stop.png" alt="{@no}" />
            # ENDIF #
        # ELSE #
        <img src="templates/images/question.png" alt="{@unknown}" />
        # ENDIF #
        </dd>                               
    </dl>
</fieldset>

<fieldset>
    <legend>{L_AUTH_DIR}</legend>
    <p>{L_CHECK_AUTH_DIR}</p>
    <div id="chmod">
        # START chmod #                         
        <dl>
            <dt><label>{chmod.TITLE}</label></dt>
            <dd>
                # IF chmod.C_EXISTING_DIR #
                    <div class="success_block">{L_EXISTING}</div>
                # ELSE #
                    <div class="failure_block">{L_NOT_EXISTING}</div>
                # ENDIF #
                # IF chmod.C_WRITIBLE_DIR #
                    <div class="success_block">{L_WRITABLE}</div>
                # ELSE #
                    <div class="failure_block">{L_NOT_WRITABLE}</div>
                # ENDIF #
            </dd>                               
        </dl>
        # END chmod #
    </div>
</fieldset> 

<fieldset style="display:none;" id="result_box">
    <legend>
        {L_RESULT}
    </legend>
    # IF C_ERROR #
        <div class="error">
            {L_ERROR}
        </div>
    # ENDIF #
    <div style="margin:auto;width:500px;">
        <div id="progress_info" style="text-align:center;"></div>
        <div style="float:left;height:13px;border:1px solid black;background:white;width:448px;padding:2px;padding-top:1px;padding-left:3px;padding-right:1px;" id="progress_bar"></div>
        &nbsp;<span id="progress_percent">0</span>%
    </div>
</fieldset>

# IF C_ERROR #
<script type="text/javascript">
<!--
    document.getElementById("result_box").style.display = "block";
    load_progress_bar(5, '');
    progress_bar(100, "{L_QUERY_SUCCESS}");
-->
</script>
# ENDIF #

<form action="{U_CURRENT_STEP}#result_box" method="post">
    <fieldset class="submit_case">
        <a href="{U_PREVIOUS_STEP}" title="{L_PREVIOUS_STEP}"><img src="templates/images/left.png" alt="{L_PREVIOUS_STEP}" class="valign_middle" /></a>&nbsp;&nbsp;
        <a href="{U_CURRENT_STEP}" title="{L_REFRESH}" id="enougth_js_preview">
            <img src="templates/images/refresh.png" alt="{L_REFRESH}" class="valign_middle" />
        </a>
        <script type="text/javascript">
        <!--
            document.getElementById("enougth_js_preview").style.display = "none";
            document.write("<a title=\"{L_REFRESH}\" href=\"javascript:refresh();\" ><img src=\"templates/images/refresh.png\" alt=\"{L_REFRESH}\" class=\"valign_middle\" /></a>&nbsp;<span id=\"image_loading\"></span>&nbsp;");
        -->
        </script>
        <input type="image" src="templates/images/right.png" title="{L_NEXT_STEP}" class="img_submit" />
        <input type="hidden"  name="submit" value="next" />
    </fieldset>
</form>