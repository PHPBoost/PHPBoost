<div class="text-helper">{@H|themes.warning_before_delete}</div>
<form action="{REWRITED_SCRIPT}" method="post">
	<section id="installed-themes-container">
		<header class="legend">{@themes.installed_theme}</header>
		<div class="cell-flex cell-columns-3 cell-tile">
			# START themes_installed #
				<article class="cell addon# IF themes_installed.C_IS_DEFAULT_THEME # default-addon# ENDIF ## IF NOT themes_installed.C_IS_ACTIVATED #disabled-addon# ENDIF ## IF NOT themes_installed.C_COMPATIBLE # not-compatible error# ENDIF #">
					<header class="cell-header">
						# IF C_MORE_THAN_ONE_THEME_INSTALLED #
							# IF themes_installed.C_COMPATIBLE #
								<div class="mini-checkbox">
									<label class="checkbox" for="multiple-checkbox-{themes_installed.THEME_NUMBER}">
										<input type="checkbox" class="multiple-checkbox delete-checkbox" id="multiple-checkbox-{themes_installed.THEME_NUMBER}" name="delete-checkbox-{themes_installed.THEME_NUMBER}"# IF themes_installed.C_IS_DEFAULT_THEME # disabled="disabled"# ENDIF # />
										<span>&nbsp;</span>
									</label>
								</div>
							# ENDIF #
						# ENDIF #
						<h3 class="cell-name">{themes_installed.NAME}</h3>
						<div class="addon-menu-container">
							# IF themes_installed.C_IS_DEFAULT_THEME #
								<div class="addon-menu-container">
									<span class="addon-menu-title bgc-full notice">{@themes.default}</span>
								</div>
							# ELSE #
								<div class="addon-menu-container addon-with-menu">
									<a href="#" id="addon-menu-title-{themes_installed.THEME_NUMBER}" class="addon-menu-title bgc-full link-color">
										# IF themes_installed.C_COMPATIBLE #
											# IF themes_installed.C_IS_ACTIVATED #
												${LangLoader::get_message('actions', 'admin-common')}
											# ELSE #
												${LangLoader::get_message('disabled', 'common')}
											# ENDIF #
										# ELSE #
											${LangLoader::get_message('not_compatible', 'admin-common')}
										# ENDIF #
									</a>
									<ul class="addon-menu-content">
										# IF themes_installed.C_COMPATIBLE #
											<li class="addon-menu-item"><button type="submit" class="button submit" name="default-{themes_installed.ID}" value="true">${LangLoader::get_message('set_to_default', 'admin-common')}</button></li>
											# IF themes_installed.C_IS_ACTIVATED #
												<li class="addon-menu-item"><button type="submit" class="button submit" name="disable-{themes_installed.ID}" value="true">${LangLoader::get_message('disable', 'common')}</button></li>
											# ELSE #
												<li class="addon-menu-item"><button type="submit" class="button submit" name="enable-{themes_installed.ID}" value="true">${LangLoader::get_message('enable', 'common')}</button></li></li>
											# ENDIF #
										# ENDIF #
										<li class="addon-menu-item"><button type="submit" class="button alt-submit" name="delete-{themes_installed.ID}" value="true">${LangLoader::get_message('uninstall', 'admin-common')}</button></li>
									</ul>
								</div>
							# ENDIF #
					</header>
					<div class="cell-body">
						<div class="cell-thumbnail cell-landscape" >
							# IF themes_installed.C_PICTURES #
								<img src="{themes_installed.MAIN_PICTURE}" alt="{themes_installed.NAME}" />
								<a class="cell-thumbnail-caption" href="{themes_installed.MAIN_PICTURE}" data-lightbox="{themes_installed.ID}" data-rel="lightcase:collection-{themes_installed.ID}">
									{@themes.view_real_preview}
								</a>
								# START themes_installed.pictures #
									<a href="{themes_installed.pictures.URL}" data-lightbox="{themes_installed.ID}" data-rel="lightcase:collection-{themes_installed.ID}" aria-label="{themes_installed.NAME}"></a>
								# END themes_installed.pictures #
							# ENDIF #
						</div>
					</div>
					<div class="cell-list">
						<ul>
							<li class="li-stretch">
								<span class="text-strong">${LangLoader::get_message('version', 'admin')} :</span>
								<span>{themes_installed.VERSION}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
								<span# IF NOT themes_installed.C_COMPATIBLE # class="not-compatible"# ENDIF #>PHPBoost {themes_installed.COMPATIBILITY}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span>
								<span>
									# IF themes_installed.C_AUTHOR_EMAIL # <a href="mailto:{themes_installed.AUTHOR_EMAIL}">@{themes_installed.AUTHOR}</a> # ELSE # {themes_installed.AUTHOR} # ENDIF #
									# IF themes_installed.C_AUTHOR_WEBSITE # <a href="{themes_installed.AUTHOR_WEBSITE}" class="pinned bgc question">Web</a> # ENDIF #
								</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} :</span>
								<span>{themes_installed.CREATION_DATE}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">${LangLoader::get_message('last_update', 'admin')} :</span>
								<span>{themes_installed.LAST_UPDATE}</span>
							</li>
							<li>
								<span class="text-strong">${LangLoader::get_message('description', 'main')} :</span>
								<span>{themes_installed.DESCRIPTION}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@themes.html_version} :</span>
								<span>{themes_installed.HTML_VERSION}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@themes.css_version} :</span>
								<span>{themes_installed.CSS_VERSION}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@themes.main_color} :</span>
								<span>{themes_installed.MAIN_COLOR}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@themes.width} :</span>
								{themes_installed.WIDTH}
							</li>
						</ul>
					</div>
					<footer class="cell-footer">
						# IF themes_installed.C_COMPATIBLE #
							<div class="addon-auth-container">
								# IF themes_installed.C_IS_DEFAULT_THEME #
									<span class="addon-auth default-addon notice" aria-label="{@themes.default_theme_visibility}"><i class="fa fa-user-shield" aria-hidden="true"></i></span>
								# ELSE #
									<a href="#" class="addon-auth" aria-label="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"><i class="fa fa-user-shield" aria-hidden="true"></i></a>
									<div class="addon-auth-content">
										{themes_installed.AUTHORIZATIONS}
										<a href="#" class="addon-auth-close" aria-label="${LangLoader::get_message('close', 'main')}"><i class="fa fa-times" aria-hidden="true"></i></a>
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
				<legend>{L_SUBMIT}</legend>
				<button type="submit" class="button submit" name="update_themes_configuration" value="true">${LangLoader::get_message('save.authorizations', 'admin-common')}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="update" value="true">
			</fieldset>
		</footer>
	</section>

	# IF C_MORE_THAN_ONE_THEME_INSTALLED #
		<div class="addon-menu-container multiple-select-menu-container">
			<a href="#" class="multiple-select-menu addon-menu-title bgc-full link-color">${LangLoader::get_message('multiple.select', 'admin-common')}</a>
			<ul class="addon-menu-content">
				<li class="addon-menu-checkbox mini-checkbox select-all-checkbox bgc-full link-color">
					<label class="checkbox" for="delete-all-checkbox">
						<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {THEMES_NUMBER}, {DEFAULT_THEME_NUMBER}, false);" />
						<span aria-label="{@themes.select_all_themes}">&nbsp;</span>
					</label>
				</li>
				<li class="addon-menu-item"><button type="submit" name="delete-selected-themes" value="true" class="button alt-submit" id="delete-all-button">${LangLoader::get_message('multiple.uninstall_selection', 'admin-common')}</button></li>
				<li class="addon-menu-item"><button type="submit" name="deactivate-selected-themes" value="true" class="button submit" id="deactivate-all-button">${LangLoader::get_message('multiple.deactivate_selection', 'admin-common')}</button></li>
				<li class="addon-menu-item"><button type="submit" name="activate-selected-themes" value="true" class="button submit" id="activate-all-button">${LangLoader::get_message('multiple.activate_selection', 'admin-common')}</button></li>
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
