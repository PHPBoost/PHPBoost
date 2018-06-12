# INCLUDE UPLOAD_FORM #

<script>
<!--
	function select_themes(status)
	{
		var i;
		for(i = 1; i <= {THEMES_NUMBER}; i++)
		{
			if(status == "select")
				document.getElementById('add-checkbox-' + i).checked = true;
			else
				document.getElementById('add-checkbox-' + i).checked = false;
		}
	}
-->
</script>

<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	# INCLUDE MSG #
	<section id="not-installed-themes-container" class="admin-elements-container themes-elements-container not-installed-elements-container">
		<header class="legend">{@themes.themes_available}</header>
		# IF C_THEME_AVAILABLE #
		<div class="content elements-container columns-3">
			# START themes_not_installed #
			<article id="not-installed-theme-element-{themes_not_installed.THEME_NUMBER}" class="block admin-element theme-element not-installed-element">
				<header>
					<div id="not-installed-element-menu-container-{themes_not_installed.THEME_NUMBER}" class="admin-element-menu-container">
						<a href="#" class="admin-element-menu-title" title="${LangLoader::get_message('action_menu.open', 'admin-common')}">${LangLoader::get_message('actions', 'admin-common')}<i class="fa fa-caret-right"></i></a>
						<ul class="admin-menu-elements-content">
							<li class="admin-menu-element"><button type="submit" class="submit" name="add-{themes_not_installed.ID}" value="true">${LangLoader::get_message('install', 'admin-common')}</button></li>
							<li class="admin-menu-element"><button type="submit" class="submit" name="activate-{themes_not_installed.ID}" value="true">${LangLoader::get_message('enable', 'common')}</button></li>
						</ul>
					</div>
					# IF C_MORE_THAN_ONE_THEME_AVAILABLE #
					<div id="add-checkbox-{themes_not_installed.THEME_NUMBER}-container" class="form-field form-field-checkbox-mini admin-element-checkbox">
						<input type="checkbox" class="add-checkbox" id="add-checkbox-{themes_not_installed.THEME_NUMBER}" name="add-checkbox-{themes_not_installed.THEME_NUMBER}"/>
						<label for="add-checkbox-{themes_not_installed.THEME_NUMBER}"></label>
					</div>
					# ENDIF #

					<h2 id="not-installed-theme-{themes_not_installed.ID}" class="not-installed-theme-name">{themes_not_installed.NAME}<em>({themes_not_installed.VERSION})</em></h2>
				</header>
				
				<div class="content admin-element-content">
					<div class="admin-element-picture" >
						# IF themes_not_installed.C_PICTURES #
						<a href="{themes_not_installed.MAIN_PICTURE}" data-lightbox="{themes_not_installed.ID}" data-rel="lightcase:collection" title="{themes_not_installed.NAME}">
							<img src="{themes_not_installed.MAIN_PICTURE}" alt="{themes_not_installed.NAME}" class="picture-table" />
							<br/>{@themes.view_real_preview}
						</a>
						# START themes_not_installed.pictures #
						<a href="{themes_not_installed.pictures.URL}" data-lightbox="{themes_not_installed.ID}" data-rel="lightcase:collection" title="{themes_not_installed.NAME}"></a>
						# END themes_not_installed.pictures #
						# ENDIF #
					</div>
					<div id="admin-element-desc-{themes_not_installed.ID}" class="admin-element-desc">
						<span class="text-strong">{@themes.author} :</span> # IF themes_not_installed.C_AUTHOR_EMAIL #<a href="mailto:{themes_not_installed.AUTHOR_EMAIL}">{themes_not_installed.AUTHOR}</a># ELSE #{themes_not_installed.AUTHOR}# ENDIF # # IF themes_not_installed.C_AUTHOR_WEBSITE #<a href="{themes_not_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
						<span class="text-strong">{@themes.description} :</span> {themes_not_installed.DESCRIPTION}<br />
						<span class="text-strong">{@themes.compatibility} :</span> PHPBoost {themes_not_installed.COMPATIBILITY}<br />
						<span class="text-strong">{@themes.html_version} :</span> {themes_not_installed.HTML_VERSION}<br />
						<span class="text-strong">{@themes.css_version} :</span> {themes_not_installed.CSS_VERSION}<br />
						<span class="text-strong">{@themes.main_color} :</span> {themes_not_installed.MAIN_COLOR}<br />
						<span class="text-strong">{@themes.width} :</span> {themes_not_installed.WIDTH}<br />
					</div>
				</div>

				<footer>
					<div id="authorizations-explain-{themes_not_installed.ID}" class="admin-element-auth-container">
						<a href="" class="admin-element-auth" title="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"><i class="fa fa-user-shield"></i></a>
						<div class="admin-element-auth-content">
							{themes_not_installed.AUTHORIZATIONS}
							<a href="#" class="admin-element-auth-close" title="${LangLoader::get_message('close', 'main')}"><i class="fa fa-times"></i></a>
						</div>
					</div>
				</footer>
			</article>
						<!--
						<td class="input-radio">
							<div class="form-field-radio">
								<input id="activated-{themes_not_installed.ID}" type="radio" name="activated-{themes_not_installed.ID}" value="1" checked="checked">
								<label for="activated-{themes_not_installed.ID}"></label>
							</div>
							<span class="form-field-radio-span">${LangLoader::get_message('yes', 'common')}</span>
							<br />
							<div class="form-field-radio">
								<input id="activated-{themes_not_installed.ID}2" type="radio" name="activated-{themes_not_installed.ID}" value="0">
								<label for="activated-{themes_not_installed.ID}2"></label>
							</div>
							<span class="form-field-radio-span">${LangLoader::get_message('no', 'common')}</span>
						</td>
						-->
			# END themes_not_installed #
		</div>
		# ELSE # 
		<div class="content">
			<div class="message-helper notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
		</div>
		# ENDIF #
		<footer>
			# IF C_MORE_THAN_ONE_THEME_AVAILABLE #
			<div class="select-all-container">
				<div class="select-all-button-container">
					<button type="submit" name="add-selected-themes" value="true" class="submit" id="select-all-button">{@themes.install_all_selected_themes}</button>
					<button type="submit" name="activate-selected-themes" value="true" class="submit" id="select-all-button">{@themes.activate_all_selected_themes}</button>
				</div>
				<div class="select-all-selector">
					<a onclick="select_themes('select');">{@themes.select_all_themes}</a> <span>/</span> <a onclick="select_themes('unselect');">${LangLoader::get_message('none', 'common')}</a>
				</div>
			</div>
			# ENDIF #
			
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



