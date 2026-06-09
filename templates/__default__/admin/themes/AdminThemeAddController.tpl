<!-- === /templates/__default__/admin/themes/AdminThemeAddController.tpl === -->
# INCLUDE MESSAGE_HELPER_WARNING #
# INCLUDE MESSAGE_HELPER_SUCCESS #
<header>
	<h2>{@addon.themes.add}</h2>
	<div class="message-helper bgc notice">{@addon.themes.warning.install}</div>
</header>
<div id="select-source" class="tabs-container">
	<nav class="tabs-nav">
		<ul class="tabs-items">
			# IF C_IS_LOCALHOST #<li><span class="tab-item first-tab --target-github">{@addon.add.tab.github}</span></li># ENDIF #
			<li><span class="tab-item --target-website">{@addon.add.tab.website}</span></li>
			<li><span class="tab-item --target-server">{@addon.add.tab.server}</span></li>
			<li><span class="tab-item --target-archive">{@addon.add.tab.archive}</span></li>
		</ul>
	</nav>
	<div class="tabs-wrapper">

		<!-- ==================== GITHUB ==================== -->
		<div id="target-github" class="tab-content">
			<div class="cell-flex cell-columns-2 addon-source-selector">
                <div>
                    <div class="flex-between flex-between-large">
                        <span>{@addon.github.running.repo} : {GITHUB_ACTIVE_OWNER}/{GITHUB_ACTIVE_REPO}</span>
                        <span><a class="button button-mini default offload" href="${relative_url(AdminConfigUrlBuilder::addons_config())}">{@addon.add.source}</a></span>
                    </div>
                    # IF C_GITHUB_HAS_REPOS #
                        <form id="gh-select-form" method="get" action="{U_CURRENT_PAGE}">
                            <label class="text-strong" for="github-repo-select">{@addon.github.choose.repo}</label>
                            <select id="github-repo-select" onchange="ghSelectChange(this)">
                                # START github_repos #
                                    <option value="{github_repos.OWNER}" data-repo="{github_repos.REPO}" data-dir="{github_repos.DIR}"# IF github_repos.C_SELECTED # selected# ENDIF #>{github_repos.LABEL}</option>
                                # END github_repos #
                            </select>
                            <input type="hidden" id="gh_owner_hidden" name="gh_owner" value="{GITHUB_ACTIVE_OWNER}" />
                            <input type="hidden" id="gh_repo_hidden"  name="gh_repo"  value="{GITHUB_ACTIVE_REPO}" />
                            <input type="hidden" id="gh_dir_hidden"   name="gh_dir"   value="{GITHUB_ACTIVE_DIR}" />
                        </form>
                    # ENDIF #
                </div>
				<details class="addon-custom-repo">
					<summary class="text-strong">{@addon.github.custom.repo}</summary>
					<form class="grouped-inputs inputs-with-sup" method="get" action="{U_CURRENT_PAGE}">
                        <label class="grouped-element label-sup" for="gh_owner_custom">
                            <span>{@addon.repos.owner}</span>
                            <input type="text" id="gh_owner_custom" name="gh_owner" value="{GITHUB_ACTIVE_OWNER}" placeholder="owner" />
                        </label>
                        <label class="grouped-element label-sup" for="gh_repo_custom">
                            <span>{@addon.repos.repository}</span>
                            <input type="text" id="gh_repo_custom" name="gh_repo" value="{GITHUB_ACTIVE_REPO}" placeholder="repository" />
                        </label>
                        <label class="grouped-element label-sup" for="gh_dir_custom">
                            <span>{@addon.sub.directory}</span>
                            <input type="text" id="gh_dir_custom" name="gh_dir" value="{GITHUB_ACTIVE_DIR}" placeholder="{@addon.sub.directory.optional}" />
                        </label>
						<button type="submit" class="button submit grouped-element">{@form.submit}</button>
					</form>
				</details>
			</div>
			<form id="gh-install-form" method="post" action="{REWRITED_SCRIPT}">
				<input type="hidden" name="token"         value="{TOKEN}" />
				<input type="hidden" name="remote_source" value="github" />
				<input type="hidden" name="gh_owner"      value="{GITHUB_ACTIVE_OWNER}" />
				<input type="hidden" name="gh_repo"       value="{GITHUB_ACTIVE_REPO}" />
				<input type="hidden" name="gh_dir"        value="{GITHUB_ACTIVE_DIR}" />
                <article id="github-addons-container" class="addons-container not-installed-elements-container">
                    # IF C_GITHUB_ADDONS #
                        <div class="cell-flex cell-columns-3 cell-tile">
                            # START github_addons #
                                <article class="cell addon# IF NOT github_addons.C_COMPATIBLE # not-compatible error# ENDIF ## IF github_addons.C_IS_INSTALLED # installed# ENDIF #">
                                    <header id="gh-addon-{github_addons.ADDON_NUMBER}" class="cell-header mini-checkbox">
                                        # IF C_SEVERAL_GITHUB_ADDONS #
                                            # IF github_addons.C_COMPATIBLE #
                                                # IF NOT github_addons.C_IS_INSTALLED #
                                                    <label class="checkbox" for="gh-cb-{github_addons.ADDON_NUMBER}">
                                                        <input type="checkbox" class="multiple-checkbox add-checkbox" id="gh-cb-{github_addons.ADDON_NUMBER}" name="add-checkbox-{github_addons.ADDON_NUMBER}" value="{github_addons.ADDON_ID}"/>
                                                        <span>&nbsp;</span>
                                                    </label>
                                                # ENDIF #
                                            # ENDIF #
                                        # ENDIF #
                                        <h4 class="cell-name">{github_addons.ADDON_NAME}</h4>
                                        <div class="flex-between actions-container">
                                            <span class="# IF github_addons.C_COMPATIBLE #success# ELSE #error# ENDIF #" aria-label="{@addon.compatibility} PHPBoost">{github_addons.COMPATIBILITY}</span>
                                            <span class="modal-button --infos-gh-addon-{github_addons.ADDON_NUMBER}" aria-label="{@common.informations}"><i class="far fa-circle-question" aria-hidden="true"></i></span>
                                            <div id="infos-gh-addon-{github_addons.ADDON_NUMBER}" class="modal modal-half">
                                                <div class="modal-overlay close-modal" aria-label="{@common.close}"></div>
                                                <div class="modal-content">
                                                    <span class="error big hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                                    <div class="cell-list">
                                                        <ul>
                                                            <li class="li-stretch">
                                                                <h2>
                                                                    {github_addons.ADDON_NAME}
                                                                </h2>
                                                            </li>
                                                            <li class="li-stretch"><span class="text-strong">{@common.author} :</span> {github_addons.AUTHOR}# IF github_addons.C_AUTHOR_EMAIL # <a href="mailto:{github_addons.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF ## IF github_addons.C_AUTHOR_WEBSITE # <a href="{github_addons.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a># ENDIF #</li>
                                                            <li class="li-stretch"><span class="text-strong">{@common.version} :</span> {github_addons.VERSION}</li>
                                                            <li class="li-stretch"><span class="text-strong">{@common.creation.date} :</span> {github_addons.CREATION_DATE}</li>
                                                            <li class="li-stretch"><span class="text-strong">{@common.last.update} :</span> {github_addons.LAST_UPDATE}</li>
                                                            <li><span class="text-strong">{@common.description} :</span> {github_addons.DESCRIPTION}</li>
                                                            # IF NOT github_addons.C_COMPATIBLE_ADDON #
                                                                <li class="bgc-full error">{@addon.themes.not.themes}</li>
                                                            # ENDIF #
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            # IF github_addons.C_IS_INSTALLED #
                                                <button onclick="return false;" class="button button-mini bgc-full success" aria-label="{@addon.already.installed}"><i class="fa fa-fw fa-check" aria-hidden="true"></i></button>
                                            # ELSEIF github_addons.C_COMPATIBLE #
                                                <button type="button" class="button button-mini bgc-full logo-color btn-gh-install" data-id="{github_addons.ADDON_ID}" aria-label="{@addon.install}"><i class="fa fa-fw fa-arrows-spin" aria-hidden="true"></i></button>
                                            # ELSE #
                                                <button onclick="return false;" class="button button-mini bgc-full error" aria-label="{@addon.not.compatible}"><i class="fa fa-fw fa-ban" aria-hidden="true"></i></button>
                                            # ENDIF #
                                            <button type="button" class="button button-mini default offload" aria-label="{@addon.github.view.repo}" onclick="window.open('{github_addons.U_REPO}', '_blank', 'noopener');"><i class="fab fa-github fa-fw" aria-hidden="true"></i></button>
                                        </div>
                                    </header>
                                    <div class="cell-thumbnail cell-landscape">
                                        # IF github_addons.C_THUMBNAIL #
                                            <img src="{github_addons.THUMBNAIL_URL}" alt="{github_addons.ADDON_NAME}" class="addon-thumbnail" />
                                        # ELSE #
                                            <i class="far fa-fw fa-images" aria-hidden="true"></i>
                                        # ENDIF #
                                    </div>
                                </article>
                            # END github_addons #
                        </div>
                    # ELSE #
                        <div class="content">
                            <div class="message-helper bgc notice message-helper-small">{@common.no.item.now}</div>
                        </div>
                    # ENDIF #
                    # IF C_SEVERAL_GITHUB_ADDONS #
                        <div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
                            <label class="checkbox" for="gh-check-all">
                                <input type="checkbox" id="gh-check-all" />
                                <span aria-label="{@addon.themes.select.all}">&nbsp;</span>
                            </label>
                            <button type="submit" form="gh-install-form" id="gh-install-selected" class="button submit select-all-button" onclick="showLoader()">{@addon.multiple.install}</button>
                        </div>
                    # ENDIF #
                </article>
			</form>
        </div>

        <!-- ==================== WEBSITE ==================== -->
		<div id="target-website" class="tab-content">
			<div class="cell-flex cell-columns-2 addon-source-selector">
                <div>
                    <div class="flex-between flex-between-large">
                        <span>{@addon.website.running.server} : {WEBSITE_ACTIVE_URL}</span>
                        <span><a class="button button-mini default offload" href="${relative_url(AdminConfigUrlBuilder::addons_config())}">{@addon.add.source}</a></span>
                    </div>
                    # IF C_WEBSITE_HAS_SERVERS #
                        <form id="ws-select-form" method="get" action="{U_CURRENT_PAGE}">
                            <label class="text-strong" for="website-server-select">{@addon.website.choose.server}</label>
                            <select id="website-server-select" onchange="wsSelectChange(this)">
                                # START website_servers #
                                    <option value="{website_servers.URL}" data-dir="{website_servers.DIR}"# IF website_servers.C_SELECTED # selected# ENDIF #>{website_servers.LABEL}</option>
                                # END website_servers #
                            </select>
                            <input type="hidden" id="ws_url_hidden" name="ws_url" value="{WEBSITE_ACTIVE_URL}" />
                            <input type="hidden" id="ws_dir_hidden" name="ws_dir" value="{WEBSITE_ACTIVE_DIR}" />
                        </form>
                    # ENDIF #
                </div>
				<details class="addon-custom-server">
					<summary class="text-strong">{@addon.website.custom.server}</summary>
					<form class="grouped-inputs inputs-with-sup" method="get" action="{U_CURRENT_PAGE}">
                        <label class="grouped-element label-sup" for="ws_url_custom">
                            <span>{@addon.servers.url}</span>
                            <input type="text" id="ws_url_custom" name="ws_url" value="{WEBSITE_ACTIVE_URL}" placeholder="https://example.com" />
                        </label>
                        <label class="grouped-element label-sup" for="ws_dir_custom">
                            <span>{@addon.sub.directory}</span>
                            <input type="text" id="ws_dir_custom" name="ws_dir" value="{WEBSITE_ACTIVE_DIR}" placeholder="{@addon.sub.directory.optional}" />
                        </label>
						<button type="submit" class="button submit grouped-element">{@addon.website.load}</button>
					</form>
				</details>
			</div>
			<form id="ws-install-form" method="post" action="{REWRITED_SCRIPT}">
				<input type="hidden" name="token"         value="{TOKEN}" />
				<input type="hidden" name="remote_source" value="website" />
				<input type="hidden" name="ws_url"        value="{WEBSITE_ACTIVE_URL}" />
				<input type="hidden" name="ws_dir"        value="{WEBSITE_ACTIVE_DIR}" />
                <article id="website-addons-container" class="addons-container not-installed-elements-container">
                    # IF C_WEBSITE_ADDONS #
                        <div class="cell-flex cell-columns-3 cell-tile">
                            # START website_addons #
                                <article class="cell addon# IF NOT website_addons.C_COMPATIBLE # not-compatible error# ENDIF ## IF website_addons.C_IS_INSTALLED # installed# ENDIF #">
                                    <header id="ws-addon-{website_addons.ADDON_NUMBER}" class="cell-header mini-checkbox">
                                        # IF C_SEVERAL_WEBSITE_ADDONS #
                                            # IF website_addons.C_COMPATIBLE #
                                                # IF NOT website_addons.C_IS_INSTALLED #
                                                    <label class="checkbox" for="ws-cb-{website_addons.ADDON_NUMBER}">
                                                        <input type="checkbox" class="multiple-checkbox add-checkbox" id="ws-cb-{website_addons.ADDON_NUMBER}" name="add-checkbox-{website_addons.ADDON_NUMBER}" value="{website_addons.ADDON_ID}"/>
                                                        <span>&nbsp;</span>
                                                    </label>
                                                # ENDIF #
                                            # ENDIF #
                                        # ENDIF #
                                        <h4 class="cell-name">{website_addons.ADDON_NAME}</h4>
                                        <div class="flex-between actions-container">
                                            <span class="# IF website_addons.C_COMPATIBLE #success# ELSE #error# ENDIF #" aria-label="{@addon.compatibility} PHPBoost">{website_addons.COMPATIBILITY}</span>
                                            <span class="modal-button --infos-ws-addon-{website_addons.ADDON_NUMBER}" aria-label="{@common.informations}"><i class="far fa-circle-question" aria-hidden="true"></i></span>
                                            <div id="infos-ws-addon-{website_addons.ADDON_NUMBER}" class="modal modal-half">
                                                <div class="modal-overlay close-modal" aria-label="{@common.close}"></div>
                                                <div class="modal-content">
                                                    <span class="error big hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                                    <div class="cell-list">
                                                        <ul>
                                                            <li class="li-stretch"><h2>{website_addons.ADDON_NAME}</h2></li>
                                                            <li class="li-stretch"><span class="text-strong">{@common.author} :</span> {website_addons.AUTHOR}# IF website_addons.C_AUTHOR_EMAIL # <a href="mailto:{website_addons.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF ## IF website_addons.C_AUTHOR_WEBSITE # <a href="{website_addons.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a># ENDIF #</li>
                                                            <li class="li-stretch"><span class="text-strong">{@common.version} :</span> {website_addons.VERSION}</li>
                                                            <li class="li-stretch"><span class="text-strong">{@common.creation.date} :</span> {website_addons.CREATION_DATE}</li>
                                                            <li class="li-stretch"><span class="text-strong">{@common.last.update} :</span> {website_addons.LAST_UPDATE}</li>
                                                            <li><span class="text-strong">{@common.description} :</span> {website_addons.DESCRIPTION}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            # IF website_addons.C_IS_INSTALLED #
                                                <button onclick="return false;" class="button button-mini bgc-full success" aria-label="{@addon.already.installed}"><i class="fa fa-fw fa-check" aria-hidden="true"></i></button>
                                            # ELSEIF website_addons.C_COMPATIBLE #
                                                <button type="button" class="button button-mini bgc-full logo-color btn-ws-install" data-id="{website_addons.ADDON_ID}" aria-label="{@addon.install}"><i class="fa fa-fw fa-arrows-spin" aria-hidden="true"></i></button>
                                            # ELSE #
                                                <button onclick="return false;" class="button button-mini bgc-full error" aria-label="{@addon.not.compatible}"><i class="fa fa-fw fa-ban" aria-hidden="true"></i></button>
                                            # ENDIF #
                                        </div>
                                    </header>
                                    <div class="cell-thumbnail cell-landscape">
                                        # IF website_addons.C_THUMBNAIL #
                                            <img src="{website_addons.THUMBNAIL_URL}" alt="{website_addons.ADDON_NAME}" class="addon-thumbnail" />
                                        # ELSE #
                                            <i class="far fa-fw fa-images" aria-hidden="true"></i>
                                        # ENDIF #
                                    </div>
                                </article>
                            # END website_addons #
                        </div>
                    # ELSE #
                        <div class="content">
                            <div class="message-helper bgc notice message-helper-small">{@common.no.item.now}</div>
                        </div>
                    # ENDIF #
                    # IF C_SEVERAL_WEBSITE_ADDONS #
                        <div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
                            <label class="checkbox" for="ws-check-all">
                                <input type="checkbox" id="ws-check-all" />
                                <span aria-label="{@addon.themes.select.all}">&nbsp;</span>
                            </label>
                            <button type="submit" form="ws-install-form" id="ws-install-selected" class="button submit select-all-button" onclick="showLoader()">{@addon.multiple.install}</button>
                        </div>
                    # ENDIF #
                </article>
			</form>
        </div>

        <!-- ==================== SERVER ==================== -->
		<div id="target-server" class="tab-content">
            <form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
                <input type="hidden" name="token" value="{TOKEN}">
                <article id="not-installed-themes-container" class="addons-container themes-elements-container not-installed-elements-container">
                    <header class="legend">{@addon.themes.available}</header>
                    # IF C_THEME_AVAILABLE #
                        <div class="cell-flex cell-columns-3 cell-tile">
                            # START themes_not_installed #
                                <article class="cell addon# IF NOT themes_not_installed.C_COMPATIBLE # not-compatible error# ENDIF #">
                                    <header class="cell-header mini-checkbox">
                                        # IF C_SEVERAL_THEMES_AVAILABLE #
                                            # IF themes_not_installed.C_COMPATIBLE #
                                                <label class="checkbox" for="multiple-checkbox-{themes_not_installed.ADDON_NUMBER}">
                                                    <input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{themes_not_installed.ADDON_NUMBER}" name="add-checkbox-{themes_not_installed.ADDON_NUMBER}"/>
                                                    <span>&nbsp;</span>
                                                </label>
                                            # ENDIF #
                                        # ENDIF #
                                        <h4 class="cell-name# IF NOT themes_not_installed.C_COMPATIBLE # not-compatible error# ENDIF #">{themes_not_installed.ADDON_NAME}</h4>
                                        <div class="flex-between actions-container">
                                            <span class="# IF themes_not_installed.C_COMPATIBLE #success# ELSE #error# ENDIF #" aria-label="{@addon.compatibility} PHPBoost">{themes_not_installed.COMPATIBILITY}</span>
                                            <span class="modal-button --infos-server-{themes_not_installed.ADDON_NUMBER}" aria-label="{@common.informations}"><i class="far fa-circle-question" aria-hidden="true"></i></span>
                                            <div id="infos-server-{themes_not_installed.ADDON_NUMBER}" class="modal modal-half">
                                                <div class="modal-overlay close-modal" aria-label="{@common.close}"></div>
                                                <div class="modal-content">
                                                    <span class="error big hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                                    <div class="cell-list">
                                                        <ul>
                                                            <li class="li-stretch">
                                                                <h2>
                                                                    {themes_not_installed.ADDON_NAME}
                                                                </h2>
                                                            </li>
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
                                                </div>
                                            </div>
                                            # IF themes_not_installed.C_COMPATIBLE #
                                                <div class="addon-auth-container">
                                                    <a href="#" class="addon-auth" aria-label="{@addon.authorizations}"><i class="fa fa-user-shield" aria-hidden="true"></i></a>
                                                    <div class="addon-auth-content">
                                                        {themes_not_installed.AUTHORIZATIONS}
                                                        <a href="#" class="addon-auth-close" aria-label="{@common.close}"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                    </div>
                                                </div>
                                            # ENDIF #
                                            # IF themes_not_installed.C_COMPATIBLE #
                                                <button type="submit" class="button button-mini bgc-full logo-color" name="add-{themes_not_installed.ADDON_ID}" value="true"><i class="fa fa-fw fa-arrows-spin" aria-hidden="true"></i></button>
                                            # ELSE #
                                                <button onclick="return false;" class="button button-mini default" aria-label="{@addon.not.compatible}"><i class="fa fa-fw fa-ban error" aria-hidden="true"></i></button>
                                            # ENDIF #
                                        </div>
                                    </header>
                                    <div class="cell-thumbnail cell-landscape" >
                                        # IF themes_not_installed.C_THUMBNAIL #
                                            <img src="{themes_not_installed.U_MAIN_THUMBNAIL}" alt="{themes_not_installed.ADDON_NAME}" />
                                            <a class="cell-thumbnail-caption" href="{themes_not_installed.U_MAIN_THUMBNAIL}" data-lightbox="{themes_not_installed.ADDON_ID}" data-rel="lightcase:collection-{themes_not_installed.ADDON_ID}">
                                                {@addon.themes.view.real.preview}
                                            </a>
                                            # START themes_not_installed.pictures #
                                                <a href="{themes_not_installed.pictures.URL}" data-lightbox="{themes_not_installed.ADDON_ID}" data-rel="lightcase:collection-{themes_not_installed.ADDON_ID}" aria-label="{themes_not_installed.ADDON_NAME}"></a>
                                            # END themes_not_installed.pictures #
                                        # ENDIF #
                                    </div>
                                </article>
                            # END themes_not_installed #
                        </div>
                    # ELSE #
                        <div class="content">
                            <div class="message-helper bgc notice message-helper-small">{@common.no.item.now}</div>
                        </div>
                    # ENDIF #
                </article>
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
                opensubmenu('.addon-auth', {
                    osmTarget: '.addon-auth-container',
                    osmCloseExcept: '.addon-auth-content *',
                    osmCloseButton: '.addon-auth-close i',
                });
            </script>
		</div>

		<!-- ==================== ARCHIVE ==================== -->
		<div id="target-archive" class="tab-content"># INCLUDE UPLOAD_FORM #</div>

	</div>
</div>

<script>
	function ghSelectChange(sel) {
		var opt = sel.options[sel.selectedIndex];
		document.getElementById('gh_owner_hidden').value = opt.value;
		document.getElementById('gh_repo_hidden').value  = opt.getAttribute('data-repo') || '';
		document.getElementById('gh_dir_hidden').value   = opt.getAttribute('data-dir')  || '';
		document.getElementById('gh-select-form').submit();
	}
	function wsSelectChange(sel) {
		var opt = sel.options[sel.selectedIndex];
		document.getElementById('ws_url_hidden').value = opt.value;
		document.getElementById('ws_dir_hidden').value = opt.getAttribute('data-dir') || '';
		document.getElementById('ws-select-form').submit();
	}
	function showLoader() {
		var overlay = document.getElementById('install-loader');
		if (overlay) overlay.style.display = 'flex';
	}
(function () {
	// Check-all github
	var ghCheckAll = document.getElementById('gh-check-all');
	if (ghCheckAll) {
		ghCheckAll.addEventListener('change', function() {
			var c = document.getElementById('github-addons-container');
			if (c) c.querySelectorAll('.add-checkbox').forEach(function(cb) { cb.checked = ghCheckAll.checked; });
		});
	}
	// Check-all website
	var wsCheckAll = document.getElementById('ws-check-all');
	if (wsCheckAll) {
		wsCheckAll.addEventListener('change', function() {
			var c = document.getElementById('website-addons-container');
			if (c) c.querySelectorAll('.add-checkbox').forEach(function(cb) { cb.checked = wsCheckAll.checked; });
		});
	}
	// Collect checked IDs into the form before submit
	// Github: "install selected" button submits gh-install-form
	// The form only gets checked inputs; we inject hidden inputs dynamically
	var ghForm = document.getElementById('gh-install-form');
	if (ghForm) {
		ghForm.addEventListener('submit', function(e) {
			var btn = document.activeElement;
			// If triggered by the "install selected" button (not a single-addon button)
			if (btn && btn.id === 'gh-install-selected') {
				var cont = document.getElementById('github-addons-container');
				var checked = cont ? cont.querySelectorAll('.add-checkbox:checked') : [];
				if (checked.length === 0) { e.preventDefault(); return; }
				checked.forEach(function(cb) {
					var inp = document.createElement('input');
					inp.type = 'hidden'; inp.name = 'addon_ids[]'; inp.value = cb.value;
					ghForm.appendChild(inp);
				});
			}
			showLoader();
		});
	}
	var wsForm = document.getElementById('ws-install-form');
	if (wsForm) {
		wsForm.addEventListener('submit', function(e) {
			var btn = document.activeElement;
			if (btn && btn.id === 'ws-install-selected') {
				var cont = document.getElementById('website-addons-container');
				var checked = cont ? cont.querySelectorAll('.add-checkbox:checked') : [];
				if (checked.length === 0) { e.preventDefault(); return; }
				checked.forEach(function(cb) {
					var inp = document.createElement('input');
					inp.type = 'hidden'; inp.name = 'addon_ids[]'; inp.value = cb.value;
					wsForm.appendChild(inp);
				});
			}
			showLoader();
		});
	}
}());
</script>

<div id="install-loader" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9999;align-items:center;justify-content:center;flex-direction:column;gap:1rem;">
	<i class="fa fa-spinner fa-spin fa-3x" style="color:#fff;" aria-hidden="true"></i>
	<span style="color:#fff;font-size:1.1rem;">{@addon.installing}</span>
</div>