<!-- === /templates/__default__/admin/modules/AdminModuleAddController.tpl === -->
# INCLUDE MESSAGE_HELPER_WARNING #
# INCLUDE MESSAGE_HELPER_SUCCESS #
<header>
	<h2>{@addon.modules.add}</h2>
	<div class="message-helper bgc notice">{@addon.modules.warning.install}</div>
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
			<article id="github-modules-container" class="addons-container modules-elements-container not-installed-elements-container">
				# IF C_GITHUB_MODULES #
					<div class="col-v-3">
						# START github_genres #
							<div>
								<header><h3>{github_genres.GENRE_NAME}</h3></header>
								<div class="cell-list mini-checkbox">
									<ul>
										# START github_genres.github_modules #
											<li class="li-stretch addon# IF NOT github_genres.github_modules.C_COMPATIBLE # not-compatible error# ENDIF ## IF github_genres.github_modules.C_IS_INSTALLED # installed# ENDIF #">
												<div id="gh-module-{github_genres.github_modules.MODULE_NUMBER}" class="addon-name align-left">
													# IF C_SEVERAL_GITHUB_MODULES #
														# IF github_genres.github_modules.C_COMPATIBLE #
															# IF NOT github_genres.github_modules.C_IS_INSTALLED #
																<label class="checkbox" for="gh-multiple-checkbox-{github_genres.github_modules.MODULE_NUMBER}">
																	<input type="checkbox" class="multiple-checkbox add-checkbox" id="gh-multiple-checkbox-{github_genres.github_modules.MODULE_NUMBER}" name="add-checkbox-{github_genres.github_modules.MODULE_NUMBER}" value="{github_genres.github_modules.MODULE_ID}"/>
																	<span>&nbsp;</span>
																</label>
															# ENDIF #
														# ENDIF #
													# ENDIF #
													{github_genres.github_modules.MODULE_NAME}
												</div>
												<div class="addon-infos">
													<span class="# IF github_genres.github_modules.C_COMPATIBLE #success# ELSE #error# ENDIF #" aria-label="{@addon.compatibility} PHPBoost">{github_genres.github_modules.COMPATIBILITY}</span>
													<button onclick="return false;" class="button button-mini default modal-button --infos-gh-module-{github_genres.github_modules.MODULE_NUMBER}" aria-label="{@common.informations}"><i class="far fa-circle-question" aria-hidden="true"></i></button>
													<div id="infos-gh-module-{github_genres.github_modules.MODULE_NUMBER}" class="modal modal-half">
														<div class="modal-overlay close-modal" aria-label="{@common.close}"></div>
														<div class="modal-content">
															<span class="error big hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
															<div class="cell-list">
																<ul>
																	<li class="li-stretch">
																		<h2>
																			# IF github_genres.github_modules.C_THUMBNAIL #
																				<img src="{github_genres.github_modules.THUMBNAIL_URL}" alt="{github_genres.github_modules.MODULE_NAME}" class="addon-thumbnail" />
																			# ELSEIF github_genres.github_modules.C_FA_ICON #
																				<i class="fa {github_genres.github_modules.FA_ICON}" aria-hidden="true"></i>
																			# ELSEIF github_genres.github_modules.C_HEXA_ICON #
																				<span class="hexa-icon bigger">{github_genres.github_modules.HEXA_ICON}</span>
																			# ELSE #
																				<i class="far fa-fw fa-puzzle-piece" aria-hidden="true"></i>
																			# ENDIF #
																			{github_genres.github_modules.MODULE_NAME}
																		</h2>
																	</li>
																	<li class="li-stretch">
																		<span class="text-strong">{@common.author} :</span>
																		<span>
																			{github_genres.github_modules.AUTHOR}
																			# IF github_genres.github_modules.C_AUTHOR_EMAIL # <a href="mailto:{github_genres.github_modules.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
																			# IF github_genres.github_modules.C_AUTHOR_WEBSITE # <a href="{github_genres.github_modules.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a># ENDIF #
																		</span>
																	</li>
																	<li class="li-stretch">
																		<span class="text-strong">{@common.version}</span>
																		{github_genres.github_modules.VERSION}
																	</li>
																	<li class="li-stretch">
																		<span class="text-strong">{@common.creation.date} :</span>
																		{github_genres.github_modules.CREATION_DATE}
																	</li>
																	<li class="li-stretch">
																		<span class="text-strong">{@common.last.update} :</span>
																		{github_genres.github_modules.LAST_UPDATE}
																	</li>
																	<li>
																		<span class="text-strong">{@common.description} :</span>
																		{github_genres.github_modules.DESCRIPTION}
																	</li>
																	<li class="li-stretch">
																		<span class="text-strong">{@addon.modules.php.version} :</span>
																		{github_genres.github_modules.PHP_VERSION}
																	</li>
																	# IF NOT github_genres.github_modules.C_COMPATIBLE_ADDON #
																		<li class="bgc-full error">{@addon.modules.not.module}</li>
																	# ENDIF #
																</ul>
															</div>
														</div>
													</div>
													# IF github_genres.github_modules.C_IS_INSTALLED #
														<button onclick="return false;" class="button button-mini bgc-full success" aria-label="{@addon.already.installed}"><i class="fa fa-fw fa-check" aria-hidden="true"></i></button>
													# ELSEIF github_genres.github_modules.C_COMPATIBLE #
														<button type="submit" form="gh-install-form" class="button button-mini bgc-full logo-color" name="addon_ids[]" value="{github_genres.github_modules.MODULE_ID}" aria-label="{@addon.install}" onclick="showLoader()"><i class="fa fa-fw fa-arrows-spin" aria-hidden="true"></i></button>
													# ELSE #
														<button onclick="return false;" class="button button-mini bgc-full error" aria-label="{@addon.not.compatible}"><i class="fa fa-fw fa-ban" aria-hidden="true"></i></button>
													# ENDIF #
													<button type="button" class="button button-mini default offload" aria-label="{@addon.github.view.repo}" onclick="window.open('{github_genres.github_modules.U_REPO}', '_blank', 'noopener');"><i class="fab fa-github fa-fw" aria-hidden="true"></i></button>
												</div>
											</li>
										# END github_genres.github_modules #
										</ul>
									</div>
								</div>
						# END github_genres #
					</div>
				# ELSE #
					<div class="content">
						<div class="message-helper bgc notice message-helper-small">{@common.no.item.now}</div>
					</div>
				# ENDIF #
							# IF C_SEVERAL_GITHUB_MODULES #
					<div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
						<label class="checkbox" for="gh-check-all">
							<input type="checkbox" id="gh-check-all" />
							<span aria-label="{@addon.modules.select.all}">&nbsp;</span>
						</label>
						<button type="submit" form="gh-install-form" id="gh-install-selected" class="button submit select-all-button" onclick="showLoader()">{@addon.multiple.install}</button>
					</div>
				# ENDIF #
            </article>
			</form>
		</div>

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
						<button type="submit" class="button submit grouped-element">{@form.submit}</button>
					</form>
				</details>
			</div>
			<form id="ws-install-form" method="post" action="{REWRITED_SCRIPT}">
				<input type="hidden" name="token"         value="{TOKEN}" />
				<input type="hidden" name="remote_source" value="website" />
				<input type="hidden" name="ws_url"        value="{WEBSITE_ACTIVE_URL}" />
				<input type="hidden" name="ws_dir"        value="{WEBSITE_ACTIVE_DIR}" />
                <article id="website-modules-container" class="addons-container modules-elements-container not-installed-elements-container">
                    # IF C_WEBSITE_MODULES #
                        <div class="col-v-3">
                            # START website_genres #
                                <div>
                                    <header><h3>{website_genres.GENRE_NAME}</h3></header>
                                    <div class="cell-list mini-checkbox">
                                        <ul>
                                            # START website_genres.website_modules #
                                                <li class="li-stretch addon# IF NOT website_genres.website_modules.C_COMPATIBLE # not-compatible error# ENDIF ## IF website_genres.website_modules.C_IS_INSTALLED # installed# ENDIF #">
                                                    <div id="ws-module-{website_genres.website_modules.MODULE_NUMBER}" class="addon-name align-left">
                                                        # IF C_SEVERAL_WEBSITE_MODULES #
                                                            # IF website_genres.website_modules.C_COMPATIBLE #
                                                                # IF NOT website_genres.website_modules.C_IS_INSTALLED #
                                                                    <label class="checkbox" for="ws-multiple-checkbox-{website_genres.website_modules.MODULE_NUMBER}">
                                                                        <input type="checkbox" class="multiple-checkbox add-checkbox" id="ws-multiple-checkbox-{website_genres.website_modules.MODULE_NUMBER}" name="add-checkbox-{website_genres.website_modules.MODULE_NUMBER}" value="{website_genres.website_modules.MODULE_ID}"/>
                                                                        <span>&nbsp;</span>
                                                                    </label>
                                                                # ENDIF #
                                                            # ENDIF #
                                                        # ENDIF #
                                                        {website_genres.website_modules.MODULE_NAME}
                                                    </div>
                                                    <div class="addon-infos">
                                                        <span class="# IF website_genres.website_modules.C_COMPATIBLE #success# ELSE #error# ENDIF #" aria-label="{@addon.compatibility} PHPBoost">{website_genres.website_modules.COMPATIBILITY}</span>
                                                        <button onclick="return false;" class="button button-mini default modal-button --infos-ws-module-{website_genres.website_modules.MODULE_NUMBER}" aria-label="{@common.informations}"><i class="far fa-circle-question" aria-hidden="true"></i></button>
                                                        <div id="infos-ws-module-{website_genres.website_modules.MODULE_NUMBER}" class="modal modal-half">
                                                            <div class="modal-overlay close-modal" aria-label="{@common.close}"></div>
                                                            <div class="modal-content">
                                                                <span class="error big hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
                                                                <div class="cell-list">
                                                                    <ul>
                                                                        <li class="li-stretch">
                                                                            <h2>
                                                                                # IF website_genres.website_modules.C_THUMBNAIL #
                                                                                    <img src="{website_genres.website_modules.THUMBNAIL_URL}" alt="{website_genres.website_modules.MODULE_NAME}" class="addon-thumbnail" />
                                                                                # ELSEIF website_genres.website_modules.C_FA_ICON #
                                                                                    <i class="fa {website_genres.website_modules.FA_ICON}" aria-hidden="true"></i>
                                                                                # ELSEIF website_genres.website_modules.C_HEXA_ICON #
                                                                                    <span class="hexa-icon bigger">{website_genres.website_modules.HEXA_ICON}</span>
                                                                                # ELSE #
                                                                                    <i class="far fa-fw fa-puzzle-piece" aria-hidden="true"></i>
                                                                                # ENDIF #
                                                                                {website_genres.website_modules.MODULE_NAME}
                                                                            </h2>
                                                                        </li>
                                                                        <li class="li-stretch">
                                                                            <span class="text-strong">{@common.author} :</span>
                                                                            <span>
                                                                                {website_genres.website_modules.AUTHOR}
                                                                                # IF website_genres.website_modules.C_AUTHOR_EMAIL # <a href="mailto:{website_genres.website_modules.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
                                                                                # IF website_genres.website_modules.C_AUTHOR_WEBSITE # <a href="{website_genres.website_modules.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a># ENDIF #
                                                                            </span>
                                                                        </li>
                                                                        <li class="li-stretch">
                                                                            <span class="text-strong">{@common.version}</span>
                                                                            {website_genres.website_modules.VERSION}
                                                                        </li>
                                                                        <li class="li-stretch">
                                                                            <span class="text-strong">{@common.creation.date} :</span>
                                                                            {website_genres.website_modules.CREATION_DATE}
                                                                        </li>
                                                                        <li class="li-stretch">
                                                                            <span class="text-strong">{@common.last.update} :</span>
                                                                            {website_genres.website_modules.LAST_UPDATE}
                                                                        </li>
                                                                        <li>
                                                                            <span class="text-strong">{@common.description} :</span>
                                                                            {website_genres.website_modules.DESCRIPTION}
                                                                        </li>
                                                                        <li class="li-stretch">
                                                                            <span class="text-strong">{@addon.modules.php.version} :</span>
                                                                            {website_genres.website_modules.PHP_VERSION}
                                                                        </li>
                                                                        # IF NOT website_genres.website_modules.C_COMPATIBLE_ADDON #
                                                                            <li class="bgc-full error">{@addon.modules.not.module}</li>
                                                                        # ENDIF #
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        # IF website_genres.website_modules.C_IS_INSTALLED #
                                                            <button onclick="return false;" class="button button-mini bgc-full success" aria-label="{@addon.already.installed}"><i class="fa fa-fw fa-check" aria-hidden="true"></i></button>
                                                        # ELSEIF website_genres.website_modules.C_COMPATIBLE #
                                                            <button type="submit" form="ws-install-form" class="button button-mini bgc-full logo-color" name="addon_ids[]" value="{website_genres.website_modules.MODULE_ID}" aria-label="{@addon.install}" onclick="showLoader()"><i class="fa fa-fw fa-arrows-spin" aria-hidden="true"></i></button>
                                                        # ELSE #
                                                            <button onclick="return false;" class="button button-mini bgc-full error" aria-label="{@addon.not.compatible}"><i class="fa fa-fw fa-ban" aria-hidden="true"></i></button>
                                                        # ENDIF #
                                                    </div>
                                                </li>
                                            # END website_genres.website_modules #
                                        </ul>
                                    </div>
                                </div>
                            # END website_genres #
                        </div>
                    # ELSE #
                        <div class="content">
                            <div class="message-helper bgc notice message-helper-small">{@common.no.item.now}</div>
                        </div>
                    # ENDIF #
                    # IF C_SEVERAL_WEBSITE_MODULES #
                        <div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
                            <label class="checkbox" for="ws-check-all">
                                <input type="checkbox" id="ws-check-all" />
                                <span aria-label="{@addon.modules.select.all}">&nbsp;</span>
                            </label>
                            <button type="submit" form="ws-install-form" id="ws-install-selected" class="button submit select-all-button" onclick="showLoader()">{@addon.multiple.install}</button>
                        </div>
                    # ENDIF #
                </article>
			</form>
        </div>
		<div id="target-server" class="tab-content">
			<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
				<input type="hidden" name="token" value="{TOKEN}">
				<article id="not-installed-modules-container" class="addons-container modules-elements-container not-installed-elements-container">
					# IF C_MODULE_AVAILABLE #
						<div class="col-v-3">
							# START genres #
								<div>
									<header><h3>{genres.GENRE_NAME}</h3></header>
									<div class="cell-list mini-checkbox">
										<ul>
											# START genres.server_modules #
												<li class="li-stretch addon# IF NOT genres.server_modules.C_COMPATIBLE # not-compatible error# ENDIF #">
													<div id="module-{genres.server_modules.MODULE_NUMBER}" class="addon-name align-left">
														# IF C_SEVERAL_MODULES_AVAILABLE #
															# IF genres.server_modules.C_COMPATIBLE #
																<label class="checkbox" for="multiple-checkbox-{genres.server_modules.MODULE_NUMBER}">
																	<input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{genres.server_modules.MODULE_NUMBER}" name="add-checkbox-{genres.server_modules.MODULE_NUMBER}"/>
																	<span>&nbsp;</span>
																</label>
															# ENDIF #
														# ENDIF #
														{genres.server_modules.MODULE_NAME}
													</div>
													<div class="addon-infos">
														<span class="# IF genres.server_modules.C_COMPATIBLE #success# ELSE #error# ENDIF #" aria-label="{@addon.compatibility} PHPBoost">{genres.server_modules.COMPATIBILITY}</span>
														<button onclick="return false;" class="button button-mini default modal-button --infos-module-{genres.server_modules.MODULE_NUMBER}" aria-label="{@common.informations}"><i class="far fa-circle-question" aria-hidden="true"></i></button>
														<div id="infos-module-{genres.server_modules.MODULE_NUMBER}" class="modal modal-half">
															<div class="modal-overlay close-modal" aria-label="{@common.close}"></div>
															<div class="modal-content">
																<span class="error big hide-modal close-modal" aria-label="{@common.close}"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>
																<div class="cell-list">
																	<ul>
																		<li class="li-stretch">
																			<h2>
																				# IF genres.server_modules.C_THUMBNAIL #
																					<img src="{genres.server_modules.THUMBNAIL_URL}" alt="{genres.server_modules.MODULE_NAME} thumbnail" class="addon-thumbnail" />
																				# ELSEIF genres.server_modules.C_FA_ICON #
																					<i class="fa {genres.server_modules.FA_ICON}" aria-hidden="true"></i>
																				# ELSEIF genres.server_modules.C_HEXA_ICON #
																					<span class="hexa-icon bigger">{genres.server_modules.HEXA_ICON}</span>
																				# ELSE #
																					<i class="far fa-fw fa-puzzle-piece" aria-hidden="true"></i>
																				# ENDIF #
																				{genres.server_modules.MODULE_NAME}
																			</h2>
																		</li>
																		<li class="li-stretch">
																			<span class="text-strong">{@common.author} :</span>
																			<span>
																				{genres.server_modules.AUTHOR}
																				# IF genres.server_modules.C_AUTHOR_EMAIL # <a href="mailto:{genres.server_modules.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
																				# IF genres.server_modules.C_AUTHOR_WEBSITE # <a href="{genres.server_modules.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a> # ENDIF #
																			</span>
																		</li>
																		<li class="li-stretch">
																			<span class="text-strong">{@common.version}</span>
																			{genres.server_modules.VERSION}
																		</li>
																		<li class="li-stretch">
																			<span class="text-strong">{@common.creation.date} :</span>
																			{genres.server_modules.CREATION_DATE}
																		</li>
																		<li class="li-stretch">
																			<span class="text-strong">{@common.last.update} :</span>
																			{genres.server_modules.LAST_UPDATE}
																		</li>
																		<li>
																			<span class="text-strong">{@common.description} :</span>
																			{genres.server_modules.DESCRIPTION}
																		</li>
																		<li class="li-stretch">
																			<span class="text-strong">{@addon.modules.php.version} :</span>
																			{genres.server_modules.PHP_VERSION}
																		</li>
																		# IF NOT genres.server_modules.C_COMPATIBLE_ADDON #
																			<li class="bgc-full error">{@addon.modules.not.module}</li>
																		# ENDIF #
																	</ul>
																</div>
															</div>
														</div>
														# IF genres.server_modules.C_COMPATIBLE #
															<button type="submit" class="button button-mini bgc-full logo-color" name="add-{genres.server_modules.MODULE_ID}" value="true" aria-label="{@addon.install}"><i class="fa fa-fw fa-arrows-spin" aria-hidden="true"></i></button>
														# ELSE #
															<button type="submit" class="button button-mini bgc-full error" name="add-{genres.server_modules.MODULE_ID}" onclick="return false;" aria-label="{@addon.not.compatible}"><i class="fa fa-fw fa-ban" aria-hidden="true"></i></button>
														# ENDIF #
													</div>
												</li>
											# END genres.server_modules #
										</ul>
									</div>
								</div>
							# END genres #
						</div>
					# ELSE #
						<div class="content">
							<div class="message-helper bgc notice message-helper-small">{@common.no.item.now}</div>
						</div>
					# ENDIF #
				</article>
				# IF C_SEVERAL_MODULES_AVAILABLE #
					<div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
						<label class="checkbox" for="add-all-checkbox">
							<input type="checkbox" class="check-all" id="add-all-checkbox" name="add-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MODULES_NUMBER}, null, false);" />
							<span aria-label="{@addon.modules.select.all}">&nbsp;</span>
						</label>
						<button type="submit" name="add-selected-modules" value="true" class="button submit select-all-button">{@addon.multiple.install}</button>
					</div>
				# ENDIF #
			</form>
		</div>

		<div id="target-archive" class="tab-content"># INCLUDE CONTENT #</div>

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
			var c = document.getElementById('github-modules-container');
			if (c) c.querySelectorAll('.add-checkbox').forEach(function(cb) { cb.checked = ghCheckAll.checked; });
		});
	}
	// Check-all website
	var wsCheckAll = document.getElementById('ws-check-all');
	if (wsCheckAll) {
		wsCheckAll.addEventListener('change', function() {
			var c = document.getElementById('website-modules-container');
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
				var cont = document.getElementById('github-modules-container');
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
				var cont = document.getElementById('website-modules-container');
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