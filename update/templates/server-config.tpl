<h1>{@step.server.title}</h1>
<a href="http://www.php.net/">
    <img src="templates/images/php.png" alt="PHP" style="float:right; margin-bottom:5px; margin-left:5px;"/>
</a>
{@H|step.server.explanation}
<fieldset>
    <legend>{@php.version}</legend>
    <p>${set(@H|php.version.check.explanation, ['min_php_version': MIN_PHP_VERSION])}</p>
    <div class="form-element">
        <label>${set(@php.version.check, ['min_php_version': MIN_PHP_VERSION])}</label>
        <div class="form-field">
        # IF PHP_VERSION_OK #
            <img src="templates/images/success.png" alt="{L_YES}" />
        # ELSE #
            <img src="templates/images/stop.png" alt="{L_NO}" />
        # ENDIF #
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>{@php.extensions}</legend>
    <p>{@php.extensions.check}</p>
    <div class="form-element">
        <label>{@php.extensions.check.gdLibrary}</label><br /><span>{@php.extensions.check.gdLibrary.explanation}</span>
        <div class="form-field">
        # IF HAS_GD_LIBRARY #
            <img src="templates/images/success.png" alt="{@yes}" />
        # ELSE #
            <img src="templates/images/stop.png" alt="{@no}" />
        # ENDIF #
        </div>
    </div>
    <div class="form-element">
        <label>{@server.urlRewriting}</label><br /><span>{@server.urlRewriting.explanation}</span>
        <div class="form-field">
        # IF URL_REWRITING_KNOWN #
            # IF URL_REWRITING_AVAILABLE #
            <img src="templates/images/success.png" alt="{@yes}" />
            # ELSE #
            <img src="templates/images/stop.png" alt="{@no}" />
            # ENDIF #
        # ELSE #
        <img src="templates/images/question.png" alt="{@unknown}" />
        # ENDIF #
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>{@folders.chmod}</legend>
    <p>{@H|folders.chmod.check}</p>
    <div id="chmod">
        # START folder #
        <div class="form-element">
            <label>{folder.NAME}</label>
            <div class="form-field">
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
            </div>
        </div>
        # END chmod #
    </div>
</fieldset>

# IF ERROR #
<fieldset id="error"><div class="error">{ERROR}</div></fieldset>
# END #

# INCLUDE CONTINUE_FORM #