<div class="content">
    <div class="cell-flex cell-columns-2">
        <div class="cell cell-3-4">
            <div class="cell-body">
                <div class="cell-content">{@H|install.server.description}</div>
            </div>
        </div>
        <div class="cell cell-1-4">
            <div class="cell-thumbnail cell-center">
                <img src="templates/images/php.webp" alt="PHP" />
                <a class="cell-thumbnail-caption" href="https://www.php.net/" target="_blank" rel="noopener noreferrer">php.net</a>
            </div>
        </div>
    </div>

    <fieldset class="fieldset-content">
        <legend>{@install.php.version}</legend>
        <div class="fieldset-inset">
            <div class="form-field-free-large">${set(@H|install.php.version.check.description, ['min_php_version': MIN_PHP_VERSION])}</div>
            <div class="form-element">
                <label>${set(@install.php.version.check, ['min_php_version': MIN_PHP_VERSION])} <span class="field-description">${set(@H|install.php.version.check.clue, ['php_version': PHP_VERSION])}</span></label>
                <div class="form-field"# IF PHP_VERSION_OK # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF #>
                # IF PHP_VERSION_OK #
                    <i class="fa fa-check fa-2x success" aria-hidden="true"></i>
                # ELSE #
                    <i class="fa fa-times fa-2x error" aria-hidden="true"></i>
                # ENDIF #
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="fieldset-content">
        <legend>{@install.php.extensions}</legend>
        <div class="fieldset-inset">
            <div class="form-field-free-large">{@install.php.extensions.check}</div>
            <div class="form-element">
                <label>{@install.php.extensions.check.gd} <span class="field-description">{@install.php.extensions.check.gd.clue}</span></label>
                <div class="form-field"# IF HAS_GD_LIBRARY # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF #>
                # IF HAS_GD_LIBRARY #
                    <i class="fa fa-check fa-2x success" aria-hidden="true"></i>
                # ELSE #
                    <i class="fa fa-times fa-2x error" aria-hidden="true"></i>
                # ENDIF #
                </div>
            </div>
            <div class="form-element">
                <label>{@install.php.extensions.check.curl} <span class="field-description">{@install.php.extensions.check.curl.clue}</span></label>
                <div class="form-field"# IF HAS_CURL_LIBRARY # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF #>
                # IF HAS_CURL_LIBRARY #
                    <i class="fa fa-check fa-2x success" aria-hidden="true"></i>
                # ELSE #
                    <i class="fa fa-times fa-2x error" aria-hidden="true"></i>
                # ENDIF #
                </div>
            </div>
            <div class="form-element">
                <label>{@install.php.extensions.check.mbstring} <span class="field-description">{@install.php.extensions.check.mbstring.clue}</span></label>
                <div class="form-field"# IF HAS_MBSTRING_LIBRARY # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF #>
                # IF HAS_MBSTRING_LIBRARY #
                    <i class="fa fa-check fa-2x success" aria-hidden="true"></i>
                # ELSE #
                    <i class="fa fa-times fa-2x error" aria-hidden="true"></i>
                # ENDIF #
                </div>
            </div>
            <div class="form-element">
                <label>{@install.url.rewriting} <span class="field-description">{@install.url.rewriting.clue}</span></label>
                <div class="form-field"# IF URL_REWRITING_KNOWN ## IF URL_REWRITING_AVAILABLE # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF ## ELSE # aria-label="{@common.unknown}"# ENDIF #>
                # IF URL_REWRITING_KNOWN #
                    # IF URL_REWRITING_AVAILABLE #
                    <i class="fa fa-check fa-2x success" aria-hidden="true"></i>
                    # ELSE #
                    <i class="fa fa-times fa-2x error" aria-hidden="true"></i>
                    # ENDIF #
                # ELSE #
                <i class="fa fa-question fa-2x" aria-hidden="true"></i>
                # ENDIF #
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="fieldset-content">
        <legend>{@install.folders.chmod}</legend>
        <div id="chmod" class="fieldset-inset">
            <div class="form-field-free-large">{@H|install.folders.chmod.check}</div>
            # START folder #
                <div class="form-element">
                    <label>{folder.NAME}</label>
                    <div class="form-field">
                        # IF folder.EXISTS #
                            <div class="message-helper bgc success">{@install.folder.existing}</div>
                        # ELSE #
                            <div class="message-helper bgc error">{@install.folder.non.existent}</div>
                        # ENDIF #
                        # IF folder.IS_WRITABLE #
                            <div class="message-helper bgc success">{@install.folder.writable}</div>
                        # ELSE #
                            <div class="message-helper bgc error">{@install.folder.not.writable}</div>
                        # ENDIF #
                    </div>
                </div>
            # END folder #
        </div>
    </fieldset>

    # IF C_MBSTRING_ERROR #
        <fieldset id="mbstring-error"><div class="message-helper bgc error">{@install.php.extensions.check.mbstring.error}</div></fieldset>
    # END #
    # IF C_FOLDERS_ERROR #
        <fieldset id="folders-error"><div class="message-helper bgc error">{@install.folders.chmod.error}</div></fieldset>
    # END #
</div>

<footer>
    <div class="next-step">
        # INCLUDE CONTINUE_FORM #
    </div>
</footer>
