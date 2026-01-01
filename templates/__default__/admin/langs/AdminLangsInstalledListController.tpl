<div class="text-helper">
	<span class="message-helper bgc warning">{@H|addon.langs.warning.delete}</span>
	<span class="message-helper bgc notice">{@addon.langs.warning.install}</span>
</div>
<form action="{REWRITED_SCRIPT}" method="post">
	<section id="installed-langs-container">
		<header class="legend">{@addon.langs.installed}</header>
		<div class="cell-flex cell-columns-3 cell-tile">
			# START langs_installed #
				<article class="cell addon# IF langs_installed.C_IS_DEFAULT_LANG # default-addon# ENDIF ## IF NOT langs_installed.C_IS_ACTIVATED # disabled-addon# ENDIF ## IF NOT langs_installed.C_COMPATIBLE # not-compatible bgc error# ENDIF #">
					<header class="cell-header">
						# IF C_SEVERAL_LANGS_INSTALLED #
							# IF langs_installed.C_COMPATIBLE #
								<div class="mini-checkbox">
									<label class="checkbox" for="multiple-checkbox-{langs_installed.LANG_NUMBER}">
										<input type="checkbox" class="multiple-checkbox delete-checkbox" id="multiple-checkbox-{langs_installed.LANG_NUMBER}" name="delete-checkbox-{langs_installed.LANG_NUMBER}"# IF langs_installed.C_IS_DEFAULT_LANG # disabled="disabled"# ENDIF # />
										<span>&nbsp;</span>
									</label>
								</div>
							# ENDIF #
						# ENDIF #
						# IF langs_installed.C_HAS_PICTURE #
							<img src="{langs_installed.PICTURE_URL}" alt="{langs_installed.NAME}" class="flag-icon" />
						# ENDIF #
						<h3 class="cell-name">
							{langs_installed.NAME}
						</h3>
						# IF langs_installed.C_IS_DEFAULT_LANG #
							<div class="addon-menu-container bgc-full notice">
								<span class="addon-menu-title">{@addon.langs.default}</span>
							</div>
						# ELSE #
							<div class="addon-menu-container addon-with-menu">
								<a href="#" id="addon-menu-title-{langs_installed.LANG_NUMBER}" class="addon-menu-title bgc-full # IF langs_installed.C_COMPATIBLE #link-color# ELSE #error# ENDIF #" aria-label="{@common.actions}">
									# IF langs_installed.C_COMPATIBLE #
										# IF langs_installed.C_IS_ACTIVATED #
											{@common.actions}
										# ELSE #
											{@common.disabled}
										# ENDIF #
									# ELSE #
										{@addon.not.compatible}
									# ENDIF #
								</a>
								<ul class="addon-menu-content">
									# IF langs_installed.C_COMPATIBLE #
										<li class="addon-menu-item"><button type="submit" class="button submit" name="default-{langs_installed.ID}" value="true">{@addon.langs.default}</button></li>
										# IF langs_installed.C_IS_ACTIVATED #
											<li class="addon-menu-item"><button type="submit" class="button submit" name="disable-{langs_installed.ID}" value="true">{@common.disable}</button></li>
										# ELSE #
											<li class="addon-menu-item"><button type="submit" class="button submit" name="enable-{langs_installed.ID}" value="true">{@common.enable}</button></li></li>
										# ENDIF #
									# ENDIF #
									<li class="addon-menu-item"><button type="submit" class="button alt-submit" name="delete-{langs_installed.ID}" value="true">{@addon.uninstall}</button></li>
                                </ul>
							</div>
						# ENDIF #
					</header>
					<div class="cell-list">
						<ul>
							<li class="li-stretch">
								<span class="text-strong">{@common.version} :</span>
								{langs_installed.VERSION}
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@addon.compatibility} :</span>
								<span# IF NOT langs_installed.C_COMPATIBLE_VERSION # class="not-compatible bgc-full error"# ENDIF #>PHPBoost {langs_installed.COMPATIBILITY}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@common.author} :</span>
								<span>
									{langs_installed.AUTHOR}
									# IF langs_installed.C_AUTHOR_EMAIL # <a href="mailto:{langs_installed.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
									# IF langs_installed.C_AUTHOR_WEBSITE # <a href="{langs_installed.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a> # ENDIF #
								</span>
							</li>
                            # IF NOT langs_installed.C_COMPATIBLE_ADDON #
                                <li class="bgc-full error">{@addon.langs.not.lang}</li>
                            # ENDIF #
                        </ul>
					</div>
					<footer class="cell-footer">
						# IF langs_installed.C_COMPATIBLE #
							<div class="addon-auth-container">
								# IF langs_installed.C_IS_DEFAULT_LANG #
									<span class="addon-auth default-addon notice" aria-label="{@addon.langs.default.auth}"><i class="fa fa-user-shield" aria-hidden="true"></i></span>
								# ELSE #
									<a href="#" class="addon-auth" aria-label="{@addon.authorizations}"><i class="fa fa-user-shield"  aria-hidden="true"></i></a>
									<div class="addon-auth-content">
										{langs_installed.AUTHORIZATIONS}
										<a href="#" class="addon-auth-close" aria-label="{@common.close}"><i class="fa fa-times" aria-hidden="true"></i></a>
									</div>
								# ENDIF #
							</div>
						# ENDIF #
					</footer>
				</article>
				<script>
					jQuery('#addon-menu-title-{langs_installed.LANG_NUMBER}').opensubmenu({
						osmTarget: '.addon-menu-container'
					});
				</script>
			# END langs_installed #
		</div>
		<footer>
			<fieldset class="fieldset-submit">
				<legend>{@addon.authorizations.save}</legend>
				<button type="submit" class="button submit" name="update_langs_configuration" value="true">{@addon.authorizations.save}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="update" value="true">
			</fieldset>
		</footer>
	</section>

	# IF C_SEVERAL_LANGS_INSTALLED #
		<div class="addon-menu-container multiple-select-menu-container">
			<a href="#" class="multiple-select-menu addon-menu-title bgc-full link-color">{@addon.multiple.select}</a>
			<ul class="addon-menu-content">
				<li class="addon-menu-checkbox mini-checkbox bgc-full link-color">
					<div class="form-field form-field-checkbox select-all-checkbox">
						<label class="checkbox" for="delete-all-checkbox">
							<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {LANGS_NUMBER}, {DEFAULT_LANG_NUMBER}, false);" />
							<span aria-label="{@addon.langs.select.all}">&nbsp;</span>
						</label>
					</div>
				</li>
				<li class="addon-menu-item"><button type="submit" name="delete-selected-langs" value="true" class="button alt-submit" id="delete-all-button">{@addon.multiple.uninstall}</button></li>
				<li class="addon-menu-item"><button type="submit" name="deactivate-selected-langs" value="true" class="button submit" id="deactivate-all-button">{@addon.multiple.disable}</button></li>
				<li class="addon-menu-item"><button type="submit" name="activate-selected-langs" value="true" class="button submit" id="activate-all-button">{@addon.multiple.enable}</button></li>
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
