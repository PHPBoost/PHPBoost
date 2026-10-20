<header></header>

<div class="content">
    <div class="cell-flex cell-columns-2">
        <div class="cell cell-3-4">
            <div class="cell-body">
                <div class="cell-content">{@H|update.step.server.description}</div>
            </div>
        </div>
        <div class="cell cell-1-4">
            <div class="cell-thumbnail cell-center">
                <img src="templates/images/php.webp" alt="PHP" class="float-right" />
                <a href="https://www.php.net/" target="_blank" rel="noopener noreferrer">php.net</a>
            </div>
        </div>
    </div>

    <div class="cell-flex cell-columns-2">
        <div class="">
            <h2>{@update.php.version}</h2>
            <div class="content">
                <p>${set(@H|update.php.version.check.clue, ['min_php_version': MIN_PHP_VERSION])}</p>
                <div class="flex-between checked-element">
                    <label>${set(@update.php.version.check, ['min_php_version': MIN_PHP_VERSION])}</label>
                    <span></span>
                    <span class=""# IF PHP_VERSION_OK # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF #>
                        # IF PHP_VERSION_OK #
                            <i class="fa fa-check fa-fw success" aria-hidden="true"></i><span class="sr-only">{@common.yes}</span>
                        # ELSE #
                            <i class="fa fa-times fa-fw error" aria-hidden="true"></i><span class="sr-only">{@common.no}</span>
                        # ENDIF #
                    </span>
                </div>
            </div>
        </div>

        <div class="">
            <h2>{@update.php.extensions}</h2>
            <div class="content">
                <p>{@update.php.extensions.check}</p>
                <div class="cell-flex cell-columns-3">
                    <div class="flex-between checked-element">
                        <span>{@update.php.extensions.check.gd}</span>
                        <div>
                            <span aria-label="{@update.php.extensions.check.gd.clue}"><i class="fa fa-fw fa-question" aria-hidden="true"></i></span>
                            <span# IF HAS_GD_EXTENSION # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF #>
                                # IF HAS_GD_EXTENSION #
                                    <i class="fa fa-check fa-fw success" aria-hidden="true"></i><span class="sr-only">{@common.yes}</span>
                                # ELSE #
                                    <i class="fa fa-times fa-fw error" aria-hidden="true"></i><span class="sr-only">{@common.no}</span>
                                # ENDIF #
                            </span>
                        </div>
                    </div>
                    <div class="flex-between checked-element">
                        <span>{@update.php.extensions.check.curl}</span>
                        <div>
                            <span aria-label="{@update.php.extensions.check.curl.clue}"><i class="fa fa-fw fa-question" aria-hidden="true"></i></span>
                            <span# IF HAS_CURL_EXTENSION # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF #>
                                # IF HAS_CURL_EXTENSION #
                                    <i class="fa fa-check fa-fw success" aria-hidden="true"></i><span class="sr-only">{@common.yes}</span>
                                # ELSE #
                                    <i class="fa fa-times fa-fw error" aria-hidden="true"></i><span class="sr-only">{@common.no}</span>
                                # ENDIF #
                            </span>
                        </div>
                    </div>
                    <div class="flex-between checked-element">
                        <span>{@update.php.extensions.check.zip}</span>
                        <div>
                            <span aria-label="{@update.php.extensions.check.zip.clue}"><i class="fa fa-fw fa-question" aria-hidden="true"></i></span>
                            <span# IF HAS_ZIP_EXTENSION # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF #>
                                # IF HAS_ZIP_EXTENSION #
                                    <i class="fa fa-check fa-fw success" aria-hidden="true"></i><span class="sr-only">{@common.yes}</span>
                                # ELSE #
                                    <i class="fa fa-times fa-fw error" aria-hidden="true"></i><span class="sr-only">{@common.no}</span>
                                # ENDIF #
                            </span>
                        </div>
                    </div>
                    <div class="flex-between checked-element">
                        <span>{@update.php.extensions.check.mbstring}</span>
                        <div>
                            <span aria-label="{@update.php.extensions.check.mbstring.clue}"><i class="fa fa-fw fa-question" aria-hidden="true"></i></span>
                            <span# IF HAS_MBSTRING_EXTENSION # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF #>
                                # IF HAS_MBSTRING_EXTENSION #
                                    <i class="fa fa-check fa-fw success" aria-hidden="true"></i><span class="sr-only">{@common.yes}</span>
                                # ELSE #
                                    <i class="fa fa-times fa-fw error" aria-hidden="true"></i><span class="sr-only">{@common.no}</span>
                                # ENDIF #
                            </span>
                        </div>
                    </div>
                    <div class="flex-between checked-element">
                        <span>{@update.server.url.rewriting}</span>
                        <div>
                            <span aria-label="{@update.server.url.rewriting.clue}"><i class="fa fa-fw fa-question" aria-hidden="true"></i></span>
                            <span# IF URL_REWRITING_KNOWN ## IF URL_REWRITING_AVAILABLE # aria-label="{@common.yes}"# ELSE # aria-label="{@common.no}"# ENDIF ## ELSE # aria-label="{@common.unknown}"# ENDIF #>
                                # IF URL_REWRITING_KNOWN #
                                    # IF URL_REWRITING_AVAILABLE #
                                        <i class="fa fa-check fa-fw success" aria-hidden="true"></i><span class="sr-only">{@common.yes}</span>
                                    # ELSE #
                                        <i class="fa fa-times fa-fw error" aria-hidden="true"></i><span class="sr-only">{@common.no}</span>
                                    # ENDIF #
                                # ELSE #
                                    <i class="fa fa-question fa-fw" aria-hidden="true"></i><span class="sr-only">{@common.unknown}</span>
                                # ENDIF #
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <h2>{@update.folders.chmod}</h2>
        <div id="chmod" class="content">
            <p>{@H|update.folders.chmod.check}</p>
            <div class="cell-flex cell-columns-4">
                # START folder #
                    <div class="flex-between checked-element">
                        <span>{folder.NAME}</span>
                        <div>
                            <span>
                                # IF folder.EXISTS #
                                    <span aria-label="{@update.folder.exists}"><i class="fa fa-folder fa-fw success" aria-hidden="true"></i></span>
                                # ELSE #
                                    <span aria-label="{@update.folder.non.existent}"><i class="fa fa-folder fa-fw error" aria-hidden="true"></i></span>
                                # ENDIF #
                                # IF folder.IS_WRITABLE #
                                    <span aria-label="{@update.folder.is.writable}"><i class="fa fa-pen-to-square fa-fw success" aria-hidden="true"></i></span>
                                # ELSE #
                                    <span aria-label="{@update.folder.not.writable}"><i class="fa fa-pen-to-square fa-fw error" aria-hidden="true"></i></span>
                                # ENDIF #
                            </span>
                            
                        </div>
                    </div>
                # END folder #
            </div>
        </div>
    </div>

    # IF C_MBSTRING_ERROR #
    <div id="mbstring-error"><div class="message-helper bgc error">{@update.php.extensions.check.mbstring.error}</div></div>
    # END #
    # IF C_FOLDERS_ERROR #
    <div id="folders-error"><div class="message-helper bgc error">{@update.folders.chmod.error}</div></div>
    # END #
</div>

<footer>
    <div class="next-step">
        # INCLUDE CONTINUE_FORM #
    </div>
</footer>
