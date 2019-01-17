{@H|themes.warning_before_install}
<form action="{REWRITED_SCRIPT}" method="post">
	<section id="installed-themes-container" class="admin-elements-container themes-elements-container installed-elements-container">
		<header class="legend">{@themes.installed_theme}</header>
		<div class="content elements-container columns-3">
			# START themes_installed #
			<article class="block admin-element theme-element installed-element# IF themes_installed.C_IS_DEFAULT_THEME # default-element# ENDIF ## IF themes_installed.C_IS_ACTIVATED # activate-element# ELSE # deactivate-element# ENDIF ## IF NOT themes_installed.C_COMPATIBLE # not-compatible# ENDIF #">
				<header>
					<div class="admin-element-menu-container">
						# IF themes_installed.C_IS_DEFAULT_THEME #
						<a href="#" class="admin-element-menu-title">{@themes.default}</a>
						# ELSE #
						<a href="#" id="admin-element-menu-title-{themes_installed.THEME_NUMBER}" class="admin-element-menu-title" title="${LangLoader::get_message('action_menu.open', 'admin-common')}"># IF themes_installed.C_COMPATIBLE ## IF themes_installed.C_IS_ACTIVATED #${LangLoader::get_message('actions', 'admin-common')}# ELSE #${LangLoader::get_message('disabled', 'common')}# ENDIF ## ELSE #${LangLoader::get_message('not_compatible', 'admin-common')}# ENDIF #<i class="fa fa-caret-right" aria-hidden="true"></i></a>
						<ul class="admin-menu-elements-content">
							# IF themes_installed.C_COMPATIBLE #
							<li class="admin-menu-element"><button type="submit" class="submit" name="default-{themes_installed.ID}" value="true">${LangLoader::get_message('set_to_default', 'admin-common')}</button></li>
							# IF themes_installed.C_IS_ACTIVATED #
							<li class="admin-menu-element"><button type="submit" class="submit" name="disable-{themes_installed.ID}" value="true">${LangLoader::get_message('disable', 'common')}</button></li>
							# ELSE #
							<li class="admin-menu-element"><button type="submit" class="submit" name="enable-{themes_installed.ID}" value="true">${LangLoader::get_message('enable', 'common')}</button></li></li>
							# ENDIF #
							# ENDIF #
							<li class="admin-menu-element"><button type="submit" class="submit alt" name="delete-{themes_installed.ID}" value="true">${LangLoader::get_message('uninstall', 'admin-common')}</button></li>
						</ul>
						# ENDIF #
					</div>

					# IF C_MORE_THAN_ONE_THEME_INSTALLED #
					# IF themes_installed.C_COMPATIBLE #
					<div class="form-field form-field-checkbox-mini multiple-checkbox-container">
						<input type="checkbox" class="multiple-checkbox delete-checkbox" id="multiple-checkbox-{themes_installed.THEME_NUMBER}" name="delete-checkbox-{themes_installed.THEME_NUMBER}"# IF themes_installed.C_IS_DEFAULT_THEME # disabled="disabled"# ENDIF # />
						<label for="multiple-checkbox-{themes_installed.THEME_NUMBER}"></label>
					</div>
					# ENDIF #
					# ENDIF #

					<h2 class="installed-theme-name">{themes_installed.NAME}<em> ({themes_installed.VERSION})</em></h2>
				</header>
				<div class="content admin-element-content">
					<div class="admin-element-picture" >
						# IF themes_installed.C_PICTURES #
						<a href="{themes_installed.MAIN_PICTURE}" data-lightbox="{themes_installed.ID}" data-rel="lightcase:collection" title="{themes_installed.NAME}">
							<img src="{themes_installed.MAIN_PICTURE}" alt="{themes_installed.NAME}" title="{themes_installed.NAME}" class="picture-table" />
							<br/>{@themes.view_real_preview}
						</a>
						# START themes_installed.pictures #
						<a href="{themes_installed.pictures.URL}" data-lightbox="{themes_installed.ID}" data-rel="lightcase:collection" title="{themes_installed.NAME}"></a>
						# END themes_installed.pictures #
						# ENDIF #
					</div>
					<div class="admin-element-desc">
						<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span> # IF themes_installed.C_AUTHOR_EMAIL #<a href="mailto:{themes_installed.AUTHOR_EMAIL}">{themes_installed.AUTHOR}</a># ELSE #{themes_installed.AUTHOR}# ENDIF # # IF themes_installed.C_AUTHOR_WEBSITE #<a href="{themes_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
						<span class="text-strong">${LangLoader::get_message('description', 'main')} :</span> {themes_installed.DESCRIPTION}<br />
						<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span> <span# IF NOT themes_installed.C_COMPATIBLE # class="not-compatible"# ENDIF #>PHPBoost {themes_installed.COMPATIBILITY}</span><br />
						<span class="text-strong">{@themes.html_version} :</span> {themes_installed.HTML_VERSION}<br />
						<span class="text-strong">{@themes.css_version} :</span> {themes_installed.CSS_VERSION}<br />
						<span class="text-strong">{@themes.main_color} :</span> {themes_installed.MAIN_COLOR}<br />
						<span class="text-strong">{@themes.width} :</span> {themes_installed.WIDTH}<br />
					</div>
				</div>
				<footer>
					# IF themes_installed.C_COMPATIBLE #
					<div class="admin-element-auth-container">
						# IF themes_installed.C_IS_DEFAULT_THEME #
						<span class="admin-element-auth default-element" aria-label="{@themes.default_theme_visibility}"><i class="fa fa-user-shield" aria-hidden="true" title="{@themes.default_theme_visibility}"></i></span>
						# ELSE #
						<a href="" class="admin-element-auth" aria-label="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"><i class="fa fa-user-shield" aria-hidden="true" title="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"></i></a>
						<div class="admin-element-auth-content">
							{themes_installed.AUTHORIZATIONS}
							<a href="#" class="admin-element-auth-close" aria-label="${LangLoader::get_message('close', 'main')}"><i class="fa fa-times" aria-hidden="true" title="${LangLoader::get_message('close', 'main')}"></i></a>
						</div>
						# ENDIF #
					</div>
					# ENDIF #
				</footer>
			</article>
			<script>
				jQuery('#admin-element-menu-title-{themes_installed.THEME_NUMBER}').opensubmenu({
					osmTarget: '.admin-element-menu-container'
				});
			</script>
			# END themes_installed #
		</div>
		<footer>
			<fieldset class="fieldset-submit">
				<legend>{L_SUBMIT}</legend>
				<button type="submit" class="submit" name="update_themes_configuration" value="true">${LangLoader::get_message('save.authorizations', 'admin-common')}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="update" value="true">
			</fieldset>
		</footer>
	</section>

	# IF C_MORE_THAN_ONE_THEME_INSTALLED #
	<div class="admin-element-menu-container multiple-select-menu-container">
		<div class="admin-element-menu-title">
			<a href="#" class="multiple-select-menu" title="${LangLoader::get_message('action_menu.open', 'admin-common')}">${LangLoader::get_message('multiple.select', 'admin-common')}<i class="fa fa-caret-right" aria-hidden="true"></i></a>
		</div>
		<ul class="admin-menu-elements-content">
			<li class="admin-menu-checkbox">
				<div class="form-field form-field-checkbox-mini select-all-checkbox">
					<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {THEMES_NUMBER}, {DEFAULT_THEME_NUMBER});" aria-label="{@themes.select_all_themes}" />
					<label for="delete-all-checkbox"></label>
				</div>
			</li>
			<li class="admin-menu-element"><button type="submit" name="delete-selected-themes" value="true" class="submit alt" id="delete-all-button">${LangLoader::get_message('multiple.uninstall_selection', 'admin-common')}</button></li>
			<li class="admin-menu-element"><button type="submit" name="deactivate-selected-themes" value="true" class="submit" id="deactivate-all-button">${LangLoader::get_message('multiple.deactivate_selection', 'admin-common')}</button></li>
			<li class="admin-menu-element"><button type="submit" name="activate-selected-themes" value="true" class="submit" id="activate-all-button">${LangLoader::get_message('multiple.activate_selection', 'admin-common')}</button></li>
		</ul>
	</div>
	# ENDIF #
</form>

<script>
	jQuery('.admin-element-menu-title').opensubmenu({
		osmTarget: '.admin-element-menu-title',
		osmCloseExcept : '.admin-menu-checkbox, .admin-menu-checkbox *'
	});

	jQuery('.admin-element-auth').opensubmenu({
		osmTarget: '.admin-element-auth-container',
		osmCloseExcept: '.admin-element-auth-content *',
		osmCloseButton: '.admin-element-auth-close i',
	});
</script>
