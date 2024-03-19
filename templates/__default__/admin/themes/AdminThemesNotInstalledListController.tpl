# INCLUDE MESSAGE_HELPER #
# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	<section id="not-installed-themes-container" class="addons-container themes-elements-container not-installed-elements-container">
		<header class="legend">{@addon.themes.available}</header>
		# IF C_THEME_AVAILABLE #
            <div class="cell-flex cell-columns-3 cell-tile">
                # START themes_not_installed #
                    <article class="cell addon# IF NOT themes_not_installed.C_COMPATIBLE # not-compatible error# ENDIF #">
                        <header class="cell-header">
                            # IF C_SEVERAL_THEMES_AVAILABLE #
                                # IF themes_not_installed.C_COMPATIBLE #
                                    <div class="mini-checkbox">
                                        <label class="checkbox" for="multiple-checkbox-{themes_not_installed.THEME_NUMBER}">
                                            <input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{themes_not_installed.THEME_NUMBER}" name="add-checkbox-{themes_not_installed.THEME_NUMBER}"/>
                                            <span>&nbsp;</span>
                                        </label>
                                    </div>
                                # ENDIF #
                            # ENDIF #
                            <h3 class="cell-name# IF NOT themes_not_installed.C_COMPATIBLE # not-compatible error# ENDIF #">{themes_not_installed.MODULE_NAME}</h3>
                            <div class="addon-menu-container">
                                # IF themes_not_installed.C_COMPATIBLE #
                                    <button type="submit" class="button submit addon-menu-title" name="add-{themes_not_installed.MODULE_ID}" value="true">{@addon.install}</button>
                                # ELSE #
                                    <span class="addon-menu-title# IF NOT themes_not_installed.C_COMPATIBLE # not-compatible low-opacity bgc-full error# ENDIF #">{@addon.not.compatible}</span>
                                # ENDIF #
                            </div>
                        </header>
                        <div class="cell-thumbnail cell-landscape" >
                            # IF themes_not_installed.C_THUMBNAIL #
                                <img src="{themes_not_installed.U_MAIN_THUMBNAIL}" alt="{themes_not_installed.MODULE_NAME}" />
                                <a class="cell-thumbnail-caption" href="{themes_not_installed.U_MAIN_THUMBNAIL}" data-lightbox="{themes_not_installed.MODULE_ID}" data-rel="lightcase:collection-{themes_not_installed.MODULE_ID}">
                                    {@addon.themes.view.real.preview}
                                </a>
                                # START themes_not_installed.pictures #
                                    <a href="{themes_not_installed.pictures.URL}" data-lightbox="{themes_not_installed.MODULE_ID}" data-rel="lightcase:collection-{themes_not_installed.MODULE_ID}" aria-label="{themes_not_installed.MODULE_NAME}"></a>
                                # END themes_not_installed.pictures #
                            # ENDIF #
                        </div>
                        <div class="cell-list">
                            <ul>
                                <li class="li-stretch">
                                    <span class="text-strong">{@common.version} :</span>
                                    {themes_not_installed.VERSION}
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.compatibility} :</span>
                                    <span # IF NOT themes_not_installed.C_COMPATIBLE_VERSION # class="not-compatible bgc-full error"# ENDIF #>PHPBoost {themes_not_installed.COMPATIBILITY}</span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">
                                        {@common.author} :
                                    </span>
                                    <span>
                                        {themes_not_installed.AUTHOR}
                                        # IF themes_not_installed.C_AUTHOR_EMAIL # <a href="mailto:{themes_not_installed.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
                                        # IF themes_not_installed.C_AUTHOR_WEBSITE # <a href="{themes_not_installed.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a> # ENDIF #
                                    </span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@common.creation.date} :</span>
                                    {themes_not_installed.CREATION_DATE}
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@common.last.update} :</span>
                                    {themes_not_installed.LAST_UPDATE}
                                </li>
                                <li>
                                    <span class="text-strong">{@common.description} :</span>
                                    {themes_not_installed.DESCRIPTION}
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.themes.html.version} :</span>
                                    {themes_not_installed.HTML_VERSION}
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.themes.css.version} :</span>
                                    {themes_not_installed.CSS_VERSION}
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.themes.main.color} :</span>
                                    {themes_not_installed.MAIN_COLOR}
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.themes.width} :</span>
                                    {themes_not_installed.WIDTH}
                                </li>
                                # IF themes_not_installed.C_PARENT_THEME #
                                    <li class="li-stretch# IF NOT themes_not_installed.C_PARENT_COMPATIBLE # not-compatible error# ENDIF #">
                                        <span class="text-strong">{@addon.themes.parent.theme} :</span>
                                        {themes_not_installed.PARENT_THEME}
                                    </li>
                                # ENDIF #
                                # IF NOT themes_not_installed.C_COMPATIBLE_ADDON #
                                    <li class="bgc-full error">{@addon.themes.not.theme}</li>
                                # ENDIF #
                                # IF NOT themes_not_installed.C_COMPATIBLE_VERSION #
                                    <li class="bgc-full error">{@addon.themes.warning.version}</li>
                                # ENDIF #
                                # IF NOT themes_not_installed.C_PARENT_COMPATIBLE #
                                    <li class="bgc-full error">{themes_not_installed.L_PARENT_COMPATIBLE}</li>
                                # ENDIF #
                            </ul>
                        </div>

                        <footer class="cell-footer">
                            # IF themes_not_installed.C_COMPATIBLE #
                                <div class="addon-auth-container">
                                    <a href="#" class="addon-auth" aria-label="{@addon.authorizations}"><i class="fa fa-user-shield" aria-hidden="true"></i></a>
                                    <div class="addon-auth-content">
                                        {themes_not_installed.AUTHORIZATIONS}
                                        <a href="#" class="addon-auth-close" aria-label="{@common.close}"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            # ENDIF #
                        </footer>
                    </article>
                # END themes_not_installed #
            </div>
		# ELSE #
            <div class="content">
                <div class="message-helper bgc notice message-helper-small">{@common.no.item.now}</div>
            </div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_SEVERAL_THEMES_AVAILABLE #
        <div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
            <label class="checkbox" for="add-all-checkbox">
                <input type="checkbox" class="check-all" id="add-all-checkbox" name="add-all-checkbox" onclick="multiple_checkbox_check(this.checked, {THEMES_NUMBER}, null, false);" />
                <span aria-label="{@addon.themes.select.all}">&nbsp;</span>
            </label>
            <button type="submit" name="add-selected-themes" value="true" class="button submit select-all-button">{@addon.multiple.install}</button>
        </div>
	# ENDIF #
</form>
<script>
	jQuery('.addon-auth').opensubmenu({
		osmTarget: '.addon-auth-container',
		osmCloseExcept: '.addon-auth-content *',
		osmCloseButton: '.addon-auth-close i',
	});
</script>
