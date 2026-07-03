<!-- === AdminThemesManagementController.tpl === -->
<div class="text-helper">
	<span class="message-helper bgc warning">{@H|addon.themes.warning.delete}</span>
	<span class="message-helper bgc notice">{@addon.themes.warning.install}</span>
</div>
<form action="{REWRITED_SCRIPT}" method="post">
	<section id="installed-themes-container">
		<header class="legend">{@addon.themes.installed}</header>
		<div class="cell-flex cell-columns-3 cell-tile">
			# START themes_installed #
				<article class="cell addon# IF themes_installed.C_IS_DEFAULT_THEME # default-addon# ENDIF ## IF NOT themes_installed.C_IS_ACTIVATED # disabled-addon# ENDIF ## IF NOT themes_installed.C_COMPATIBLE # not-compatible error# ENDIF #">
					<header class="cell-header">
						# IF C_SEVERAL_THEMES_INSTALLED #
							# IF themes_installed.C_SELECTABLE #
                                <div class="mini-checkbox">
                                    <label class="checkbox" for="multiple-checkbox-{themes_installed.THEME_NUMBER}">
                                        <input type="checkbox" class="multiple-checkbox delete-checkbox" id="multiple-checkbox-{themes_installed.THEME_NUMBER}" name="delete-checkbox-{themes_installed.THEME_NUMBER}"# IF themes_installed.C_IS_DEFAULT_THEME # disabled="disabled"# ENDIF ## IF NOT themes_installed.C_DELETE # disabled# ENDIF # />
                                        <span>&nbsp;</span>
                                    </label>
                                </div>
							# ENDIF #
						# ENDIF #
						<h4 class="cell-name">{themes_installed.THEME_NAME}</h4>
                        <div class="flex-between actions-container">
                            <span class="modal-button --theme-description-{themes_installed.THEME_NUMBER}" aria-label="{@common.informations}">
                                <i class="far fa-circle-question" aria-hidden="true"></i>
                            </span>
                            # IF themes_installed.C_COMPATIBLE #
                                # IF themes_installed.C_IS_DEFAULT_THEME #
                                    <div class="addon-menu-container">
                                        <div class="addon-menu-container">
                                            <span class="addon-menu-title bgc-full notice"><i class="fas fa-font-awesome" aria-hidden="true"></i> {@addon.themes.default}</span>
                                        </div>
                                    </div>
                                # ELSE #
                                    <div class="addon-auth-container">
                                        <a id="addon-auth-{themes_installed.THEME_NUMBER}" href="#" class="addon-auth" aria-label="{@addon.authorizations}"><i class="fa fa-user-shield" aria-hidden="true"></i></a>
                                        <div class="addon-auth-content">
                                            {themes_installed.AUTHORIZATIONS}
                                            <span class="addon-auth-close" aria-label="{@common.close}"><i class="fa fa-times" aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                    <button type="submit" class="button button-mini default" name="default-{themes_installed.THEME_ID}" value="true" aria-label="{@addon.themes.set.default}"><i class="far fa-font-awesome" aria-hidden="true"></i></button>
                                    # IF themes_installed.C_IS_ACTIVATED #
                                        <button type="submit" class="button button-mini notice" name="disable-{themes_installed.THEME_ID}" value="true" aria-label="{@common.disable}"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
                                    # ELSE #
                                        <button type="submit" class="button button-mini notice" name="enable-{themes_installed.THEME_ID}" value="true" aria-label="{@common.enable}"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    # ENDIF #
                                    # IF themes_installed.C_SELECTABLE #
                                        # IF C_IS_LOCALHOST #<button type="submit" class="button button-mini bgc-full warning" name="uninstall-{themes_installed.THEME_ID}" value="true" aria-label="{@H|addon.theme.uninstall}" data-confirmation="uninstall-element"><i class="fa fa-ban" aria-hidden="true"></i></button># ENDIF #
                                        <button type="submit" class="button button-mini bgc-full error" name="delete-{themes_installed.THEME_ID}" value="true" aria-label="{@H|addon.theme.delete}" data-confirmation="delete-element"><i class="fa fa-trash-can" aria-hidden="true"></i></button>
                                    # ENDIF #
                                # ENDIF #
                            # ELSE #
                                <div class="addon-menu-container">
                                    <div class="addon-menu-container">
                                        <span class="addon-menu-title bgc-full error"><i class="fas fa-ban" aria-hidden="true"></i> {@addon.not.compatible}</span>
                                    </div>
                                </div>
                            # ENDIF #
                        </div>
					</header>
					<div class="cell-thumbnail cell-landscape" >
						# IF themes_installed.C_THUMBNAILS #
							<img src="{themes_installed.U_MAIN_THUMBNAIL}" alt="{themes_installed.THEME_NAME}" />
							<a class="cell-thumbnail-caption" href="{themes_installed.U_MAIN_THUMBNAIL}" data-lightbox="{themes_installed.THEME_ID}" data-rel="lightcase:collection-{themes_installed.THEME_ID}">
								{@addon.themes.view.real.preview}
							</a>
							# START themes_installed.pictures #
								<a href="{themes_installed.pictures.U_THUMBNAIL}" data-lightbox="{themes_installed.THEME_ID}" data-rel="lightcase:collection-{themes_installed.THEME_ID}" aria-label="{themes_installed.THEME_NAME}"></a>
							# END themes_installed.pictures #
						# ENDIF #
					</div>
					<div id="theme-description-{themes_installed.THEME_NUMBER}" class="modal modal-half">
						<div class="modal-overlay close-modal" role="button" aria-label="{@common.close}"></div>
                        <div class="modal-content cell-list">
                            <h2>{themes_installed.THEME_NAME}</h2>
                            <span class="big error hide-modal close-modal" aria-label="Fermer"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                            <ul>
                                <li class="li-stretch">
                                    <span class="text-strong">{@common.version} :</span>
                                    <span>{themes_installed.VERSION}</span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.compatibility} :</span>
                                    <span # IF NOT themes_installed.C_COMPATIBLE # class="not-compatible bgc-full error"# ENDIF #>PHPBoost {themes_installed.COMPATIBILITY}</span>
                                </li>
                                <li>
                                    <span class="text-strong">{@common.description} :</span>
                                    <span>{themes_installed.DESCRIPTION}</span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@common.author} :</span>
                                    <span>
                                        {themes_installed.AUTHOR}
                                        # IF themes_installed.C_AUTHOR_EMAIL # <a href="mailto:{themes_installed.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
                                        # IF themes_installed.C_AUTHOR_WEBSITE # <a href="{themes_installed.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a> # ENDIF #
                                    </span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@common.creation.date} :</span>
                                    <span>{themes_installed.CREATION_DATE}</span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@common.last.update} :</span>
                                    <span>{themes_installed.LAST_UPDATE}</span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.themes.html.version} :</span>
                                    <span>{themes_installed.HTML_VERSION}</span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.themes.css.version} :</span>
                                    <span>{themes_installed.CSS_VERSION}</span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.themes.main.color} :</span>
                                    <span>{themes_installed.MAIN_COLOR}</span>
                                </li>
                                <li class="li-stretch">
                                    <span class="text-strong">{@addon.themes.width} :</span>
                                    {themes_installed.WIDTH}
                                </li>
                                # IF themes_installed.C_PARENT_THEME #
                                    <li class="li-stretch">
                                        <span class="text-strong">{@addon.themes.parent.theme} :</span>
                                        {themes_installed.PARENT_THEME}
                                    </li>
                                # ENDIF #
                                # IF NOT themes_installed.C_COMPATIBLE_ADDON #
                                    <li class="bgc-full error">{@addon.themes.not.theme}</li>
                                # ENDIF #
                                # IF NOT themes_installed.C_COMPATIBLE_VERSION #
                                    <li class="bgc-full error">{@addon.themes.warning.version}</li>
                                # ENDIF #
                            </ul>
                        </div>
					</div>
				</article>
				<script>
					opensubmenu('#addon-menu-title-{themes_installed.THEME_NUMBER}', {
						osmTarget: '.addon-menu-container'
					});

                    opensubmenu('#addon-auth-{themes_installed.THEME_NUMBER}', {
                        osmTarget: '.addon-auth-container',
                        osmCloseExcept: '.addon-auth-content *',
                        osmCloseButton: '.addon-auth-close i',
                    });
				</script>
			# END themes_installed #
		</div>
		<footer>
			<fieldset class="fieldset-submit">
				<legend>{@addon.authorizations.save}</legend>
				<button type="submit" class="button submit" name="update_themes_configuration" value="true">{@addon.authorizations.save}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="update" value="true">
			</fieldset>
		</footer>
	</section>

	# IF C_SEVERAL_THEMES_INSTALLED #
		<div class="addon-menu-container multiple-select-menu-container">
			<a href="#" class="multiple-select-menu addon-menu-title bgc-full link-color">{@addon.multiple.select}</a>
			<ul class="addon-menu-content">
				<li class="addon-menu-checkbox mini-checkbox select-all-checkbox bgc-full link-color">
					<label class="checkbox" for="toggle-all-checkbox">
						<input type="checkbox" class="check-all" id="toggle-all-checkbox" name="toggle-all-checkbox" onclick="multiple_checkbox_check(this.checked, {THEMES_NUMBER}, {DEFAULT_THEME_NUMBER}, false);" />
						<span aria-label="{@addon.themes.select.all}">&nbsp;</span>
					</label>
				</li>
				<li class="addon-menu-item"><button type="submit" name="activate-selected-themes" value="true" class="button bgc-full success" id="activate-all-button">{@addon.multiple.enable}</button></li>
				<li class="addon-menu-item"><button type="submit" name="deactivate-selected-themes" value="true" class="button bgc-full notice" id="deactivate-all-button">{@addon.multiple.disable}</button></li>
				# IF C_IS_LOCALHOST #
                    <li class="addon-menu-item"><button type="submit" name="uninstall-selected-themes" value="true" class="button bgc-full warning" id="uninstall-all-button" data-confirmation="uninstall-elements">{@addon.multiple.uninstall}</button></li>
                # ENDIF #
                <li class="addon-menu-item"><button type="submit" name="delete-selected-themes" value="true" class="button bgc-full error" id="delete-all-button">{@addon.multiple.delete}</button></li>
			</ul>
		</div>
	# ENDIF #
</form>

<script>
	opensubmenu('.addon-menu-title', {
		osmTarget: '.addon-menu-title',
		osmCloseExcept : '.addon-menu-checkbox, .addon-menu-checkbox *'
	});
</script>
