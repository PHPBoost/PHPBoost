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
							# IF themes_installed.C_COMPATIBLE #
								<div class="mini-checkbox">
									<label class="checkbox" for="multiple-checkbox-{themes_installed.THEME_NUMBER}">
										<input type="checkbox" class="multiple-checkbox delete-checkbox" id="multiple-checkbox-{themes_installed.THEME_NUMBER}" name="delete-checkbox-{themes_installed.THEME_NUMBER}"# IF themes_installed.C_IS_DEFAULT_THEME # disabled="disabled"# ENDIF # />
										<span>&nbsp;</span>
									</label>
								</div>
							# ENDIF #
						# ENDIF #
						<h3 class="cell-name">{themes_installed.MODULE_NAME}</h3>
						<div class="addon-menu-container">
							# IF themes_installed.C_IS_DEFAULT_THEME #
								<div class="addon-menu-container">
									<span class="addon-menu-title bgc-full notice">{@addon.themes.default}</span>
								</div>
							# ELSE #
								<div class="addon-menu-container addon-with-menu">
									<a href="#" id="addon-menu-title-{themes_installed.THEME_NUMBER}" class="addon-menu-title bgc-full # IF themes_installed.C_COMPATIBLE #link-color# ELSE #error# ENDIF #">
										# IF themes_installed.C_COMPATIBLE #
											# IF themes_installed.C_IS_ACTIVATED #
												{@common.actions}
											# ELSE #
												{@common.disabled}
											# ENDIF #
										# ELSE #
											{@addon.not.compatible}
										# ENDIF #
									</a>
									<ul class="addon-menu-content">
										# IF themes_installed.C_COMPATIBLE #
											<li class="addon-menu-item"><button type="submit" class="button submit" name="default-{themes_installed.MODULE_ID}" value="true">{@addon.themes.default}</button></li>
											# IF themes_installed.C_IS_ACTIVATED #
												<li class="addon-menu-item"><button type="submit" class="button submit" name="disable-{themes_installed.MODULE_ID}" value="true">{@common.disable}</button></li>
											# ELSE #
												<li class="addon-menu-item"><button type="submit" class="button submit" name="enable-{themes_installed.MODULE_ID}" value="true">{@common.enable}</button></li></li>
											# ENDIF #
										# ENDIF #
										<li class="addon-menu-item"><button type="submit" class="button alt-submit" name="delete-{themes_installed.MODULE_ID}" value="true">{@addon.uninstall}</button></li>
									</ul>
								</div>
							# ENDIF #
					</header>
					<div class="cell-thumbnail cell-landscape" >
						# IF themes_installed.C_THUMBNAILS #
							<img src="{themes_installed.U_MAIN_THUMBNAIL}" alt="{themes_installed.MODULE_NAME}" />
							<a class="cell-thumbnail-caption" href="{themes_installed.U_MAIN_THUMBNAIL}" data-lightbox="{themes_installed.MODULE_ID}" data-rel="lightcase:collection-{themes_installed.MODULE_ID}">
								{@addon.themes.view.real.preview}
							</a>
							# START themes_installed.pictures #
								<a href="{themes_installed.pictures.U_THUMBNAIL}" data-lightbox="{themes_installed.MODULE_ID}" data-rel="lightcase:collection-{themes_installed.MODULE_ID}" aria-label="{themes_installed.MODULE_NAME}"></a>
							# END themes_installed.pictures #
						# ENDIF #
					</div>
					<div class="cell-list">
						<ul>
							<li class="li-stretch">
								<span class="text-strong">{@common.version} :</span>
								<span>{themes_installed.VERSION}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@addon.compatibility} :</span>
								<span # IF NOT themes_installed.C_COMPATIBLE # class="not-compatible bgc-full error"# ENDIF #>PHPBoost {themes_installed.COMPATIBILITY}</span>
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
							<li>
								<span class="text-strong">{@common.description} :</span>
								<span>{themes_installed.DESCRIPTION}</span>
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
					<footer class="cell-footer">
						# IF themes_installed.C_COMPATIBLE #
							<div class="addon-auth-container">
								# IF themes_installed.C_IS_DEFAULT_THEME #
									<span class="addon-auth default-addon notice" aria-label="{@addon.themes.default.auth}"><i class="fa fa-user-shield" aria-hidden="true"></i></span>
								# ELSE #
									<a href="#" class="addon-auth" aria-label="{@addon.authorizations}"><i class="fa fa-user-shield" aria-hidden="true"></i></a>
									<div class="addon-auth-content">
										{themes_installed.AUTHORIZATIONS}
										<a href="#" class="addon-auth-close" aria-label="{@common.close}"><i class="fa fa-times" aria-hidden="true"></i></a>
									</div>
								# ENDIF #
							</div>
						# ENDIF #
					</footer>
				</article>
				<script>
					jQuery('#addon-menu-title-{themes_installed.THEME_NUMBER}').opensubmenu({
						osmTarget: '.addon-menu-container'
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
					<label class="checkbox" for="delete-all-checkbox">
						<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {THEMES_NUMBER}, {DEFAULT_THEME_NUMBER}, false);" />
						<span aria-label="{@addon.themes.select.all}">&nbsp;</span>
					</label>
				</li>
				<li class="addon-menu-item"><button type="submit" name="delete-selected-themes" value="true" class="button alt-submit" id="delete-all-button">{@addon.multiple.uninstall}</button></li>
				<li class="addon-menu-item"><button type="submit" name="deactivate-selected-themes" value="true" class="button submit" id="deactivate-all-button">{@addon.multiple.disable}</button></li>
				<li class="addon-menu-item"><button type="submit" name="activate-selected-themes" value="true" class="button submit" id="activate-all-button">{@addon.multiple.enable}</button></li>
			</ul>
		</div>
	# ENDIF #
</form>

<script>
	jQuery('.addon-menu-title').opensubmenu({
		osmTarget: '.addon-menu-title',
		osmCloseExcept : '.addon-menu-checkbox, .addon-menu-checkbox *'
	});

	jQuery('.addon-auth').opensubmenu({
		osmTarget: '.addon-auth-container',
		osmCloseExcept: '.addon-auth-content *',
		osmCloseButton: '.addon-auth-close i',
	});
</script>
