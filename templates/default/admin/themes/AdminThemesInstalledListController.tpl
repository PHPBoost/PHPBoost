# IF C_MORE_THAN_ONE_THEME_INSTALLED #
<script>
<!--
	function select_themes(status)
	{
		var i;
		for(i = 1; i <= {THEMES_NUMBER}; i++)
		{
			if(status == "select" && i != {SELECTED_THEME_NUMBER})
			{
				document.getElementById('delete-checkbox-' + i).checked = true;
				document.getElementById('deleted-all-button').classList.add("ready");
			}
			else
			{
				document.getElementById('delete-checkbox-' + i).checked = false;
				document.getElementById('deleted-all-button').classList.remove("ready");
			}
		}
	}
-->
</script>

# ENDIF #

${LangLoader::get_message('themes.warning_before_install', 'admin-themes-common')}
<form action="{REWRITED_SCRIPT}" method="post">
	<section id="installed-themes-container" class="admin-elements-container themes-elements-container installed-elements-container">
	<header>
		{@themes.installed_theme}
	</header>
	<div class="content elements-container columns-3">
		# START themes_installed #
		<article id="installed-theme-element-{themes_installed.THEME_NUMBER}" class="block admin-element theme-element installed-element# IF themes_installed.C_IS_DEFAULT_THEME # default-element# ENDIF ## IF themes_installed.C_IS_ACTIVATED # activate-element# ELSE # deactivate-element# ENDIF #">
			<header>
				<div id="installed-element-menu-container-{themes_installed.THEME_NUMBER}" class="admin-element-menu-container">
					# IF themes_installed.C_IS_DEFAULT_THEME #
					<a href="#" class="admin-element-menu-title">${LangLoader::get_message('themes.default', 'admin-themes-common')}</a>
					# ELSE #
					<a href="#" class="admin-element-menu-title" title="${LangLoader::get_message('action_menu.open', 'admin-common')}"># IF themes_installed.C_IS_ACTIVATED #${LangLoader::get_message('actions', 'admin-common')}# ELSE #${LangLoader::get_message('disabled', 'common')}# ENDIF #<i class="fa fa-caret-right"></i></a>
					<ul class="admin-menu-elements-content">
						<li class="admin-menu-element"><button type="submit" class="submit" name="default-{themes_installed.ID}" value="true">${LangLoader::get_message('set_to_default', 'admin-common')}</button></li>
						# IF themes_installed.C_IS_ACTIVATED #
						<li class="admin-menu-element"><button type="submit" class="submit" name="disable-{themes_installed.ID}" value="true">${LangLoader::get_message('disabled', 'common')}</button></li>
						# ELSE #
						<li class="admin-menu-element"><button type="submit" class="submit" name="enable-{themes_installed.ID}" value="true">${LangLoader::get_message('enabled', 'common')}</button></li></li>
						# ENDIF #

						<li class="admin-menu-element"><button type="submit" class="submit" name="delete-{themes_installed.ID}" value="true">${LangLoader::get_message('uninstall', 'admin-common')}</button></li>
					</ul>
					# ENDIF #
				</div>

				# IF C_MORE_THAN_ONE_THEME_INSTALLED #
				<div id="delete-checkbox-{themes_installed.THEME_NUMBER}-container" class="form-field form-field-checkbox-mini delete-checkbox">
					<input type="checkbox" class="delete-checkbox" id="delete-checkbox-{themes_installed.THEME_NUMBER}" name="delete-checkbox-{themes_installed.THEME_NUMBER}"# IF themes_installed.C_IS_DEFAULT_THEME # disabled="disabled"# ENDIF # />
					<label for="delete-checkbox-{themes_installed.THEME_NUMBER}"></label>
				</div>
				# ENDIF #

				<h2 id="installed-theme-{themes_installed.ID}" class="installed-theme-name">{themes_installed.NAME}<em>({themes_installed.VERSION})</em></h2>
			</header>
			<div class="content admin-element-content">
				<div class="admin-element-picture" >
					# IF themes_installed.C_PICTURES #
					<a href="{themes_installed.MAIN_PICTURE}" data-lightbox="{themes_installed.ID}" data-rel="lightcase:collection" title="{themes_installed.NAME}">
						<img src="{themes_installed.MAIN_PICTURE}" alt="{themes_installed.NAME}" class="picture-table" />
						<br/>
						{@themes.view_real_preview}
					</a>
					# START themes_installed.pictures #
					<a href="{themes_installed.pictures.URL}" data-lightbox="{themes_installed.ID}" data-rel="lightcase:collection" title="{themes_installed.NAME}"></a>
					# END themes_installed.pictures #
					# ENDIF #
				</div>
				<div id="admin-element-desc-{themes_installed.ID}" class="admin-element-desc">
					<span class="text-strong">{@themes.author} :</span> # IF themes_installed.C_AUTHOR_EMAIL #<a href="mailto:{themes_installed.AUTHOR_EMAIL}">{themes_installed.AUTHOR}</a># ELSE #{themes_installed.AUTHOR}# ENDIF # # IF themes_installed.C_AUTHOR_WEBSITE #<a href="{themes_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
					<span class="text-strong">{@themes.description} :</span> {themes_installed.DESCRIPTION}<br />
					<span class="text-strong">{@themes.compatibility} :</span> PHPBoost {themes_installed.COMPATIBILITY}<br />
					<span class="text-strong">{@themes.html_version} :</span> {themes_installed.HTML_VERSION}<br />
					<span class="text-strong">{@themes.css_version} :</span> {themes_installed.CSS_VERSION}<br />
					<span class="text-strong">{@themes.main_color} :</span> {themes_installed.MAIN_COLOR}<br />
					<span class="text-strong">{@themes.width} :</span> {themes_installed.WIDTH}<br />
				</div>
			</div>
			<footer>
				<div id="authorizations-explain-{themes_installed.ID}" class="admin-element-auth-container">
					# IF themes_installed.C_IS_DEFAULT_THEME #
					<span class="admin-element-auth default-element" title="${LangLoader::get_message('themes.default_theme_visibility', 'admin-themes-common')}"><i class="fa fa-user-shield"></i></span>
					# ELSE #
					<a href="" class="admin-element-auth" title="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"><i class="fa fa-user-shield"></i></a>
					<div class="admin-element-auth-content">
						{themes_installed.AUTHORIZATIONS}
						<a href="#" class="admin-element-auth-close" title="${LangLoader::get_message('close', 'main')}"><i class="fa fa-times"></i></a>
					</div>
					# ENDIF #
				</div>
			</footer>
		</article>
		# END themes_installed #
	</div>
	<footer>
		# IF C_MORE_THAN_ONE_THEME_INSTALLED #
		<div class="deleted-all-container">
			<div class="deleted-all-selector">
				<a onclick="select_themes('select');">${LangLoader::get_message('themes.select_all_themes', 'admin-themes-common')}</a> <span>/</span> <a onclick="select_themes('unselect');">${LangLoader::get_message('none', 'common')}</a>
			</div>
			<div class="deleted-all-button-container">
				<button type="submit" name="delete-selected-themes" value="true" class="submit" id="deleted-all-button">{@themes.uninstall_all_selected_themes}</button>
			</div>
		</div>
		# ENDIF #

		<fieldset class="fieldset-submit">
			<legend>{L_SUBMIT}</legend>
			<button type="submit" class="submit" name="update_themes_configuration" value="true">${LangLoader::get_message('update', 'main')}</button>
			<input type="hidden" name="token" value="{TOKEN}">
			<input type="hidden" name="update" value="true">
			<button type="reset" value="true">${LangLoader::get_message('reset', 'main')}</button>
		</fieldset>
	</footer>
	</section>
</form>
<script>
	jQuery('.admin-element-menu-title').opensubmenu({
		osmTarget: '.admin-element-menu-container'
	});
	jQuery('.admin-element-auth').opensubmenu({
		osmTarget: '.admin-element-auth-container',
		osmCloseExcept: '.admin-element-auth-content *',
		osmCloseButton: '.admin-element-auth-close i',
	});
</script>
