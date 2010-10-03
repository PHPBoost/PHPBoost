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
        <dt><label>{@php.extensions.check.gdLibrary}</label><br /><span>{@php.extensions.check.gdLibrary.explanation}</span></dt>
        <dd>
        # IF HAS_GD_LIBRARY #
            <img src="templates/images/success.png" alt="{@yes}" />
        # ELSE #
            <img src="templates/images/stop.png" alt="{@no}" />
        # ENDIF #
        </dd>                               
    </dl>
    <dl>
        <dt><label>{@server.urlRewriting}</label><br /><span>{@server.urlRewriting.explanation}</span></dt>
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
    <legend>{@folders.chmod}</legend>
    <p>{@H|folders.chmod.check}</p>
    <div id="chmod">
        # START folder #                         
        <dl>
            <dt><label>{folder.NAME}</label></dt>
            <dd>
                # IF folder.EXISTS #
                    <div class="success_block">{@folder.exists}</div>
                # ELSE #
                    <div class="failure_block">{@folder.doesNotExists}</div>
                # ENDIF #
                # IF folder.IS_WRITABLE #
                    <div class="success_block">{@folder.isWritable}</div>
                # ELSE #
                    <div class="failure_block">{@folder.isNotWritable}</div>
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

# INCLUDE CONTINUE_FORM #