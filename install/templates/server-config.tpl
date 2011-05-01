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
                    <div class="failure_block">{@folder.doesNotExist}</div>
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

# IF ERROR #
<fieldset id="error"><div class="error">{ERROR}</div></fieldset>
# END #

# INCLUDE CONTINUE_FORM #