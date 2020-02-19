<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars"></i> {L_WEBSITE_UPDATES}
	</a>
	<ul>
		<li>
			<a href="updates.php" class="quick-link">{L_WEBSITE_UPDATES}</a>
		</li>
		<li>
			<a href="updates.php?type=kernel" class="quick-link">{L_KERNEL}</a>
		</li>
		<li>
			<a href="updates.php?type=module" class="quick-link">{L_MODULES}</a>
		</li>
		<li>
			<a href="updates.php?type=template" class="quick-link">{L_THEMES}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<fieldset>
		<legend><h1>{L_APP_UPDATE_MESSAGE}</h1></legend>
		<div class="fieldset-inset">
			# INCLUDE MSG #
			# IF C_UNEXISTING_UPDATE #
				<div class="message-helper bgc warning message-helper-small">{L_UNEXISTING_UPDATE}</div>
			# ELSE #
				<article>
					<header>
						<h2>{APP_NAME} - {APP_VERSION}</h2>
						# IF C_APP_WARNING #
							<div class="message-helper bgc {WARNING_CSS_CLASS} message-update">
								<p class="align-center">{L_WARNING}</p>
								<p>{APP_WARNING}</p>
							</div>
						# ENDIF #
					</header>
					<div class="more">
						{APP_PUBDATE} | # START authors #<a href="mailto:{authors.email}">{authors.name}</a> | # END authors #
					</div>
					<div class="content">
						# IF C_DISPLAY_LINKS_AND_PRIORITY #
							<div class="infos options align-center alert-priority bgc {PRIORITY_CSS_CLASS}">
								<p>{PRIORITY}</p>
								# IF C_DISPLAY_UPDATE_BUTTON #
									<form action="{PATH_TO_ROOT}/admin/updates/detail.php?identifier={IDENTIFIER}" method="post">
										<button type="submit" name="execute_update" class="button bgc-full {PRIORITY_CSS_CLASS}" onclick="" value="true">${LangLoader::get_message('install', 'admin-common')}</button>
										<input type="hidden" name="token" value="{TOKEN}">
									</form>
								# ELSE #
									<a href="{U_APP_DOWNLOAD}"><i class="fa fa-cloud-download-alt" aria-hidden="true"></i> {L_DOWNLOAD_PACK}</a>
									# IF U_APP_UPDATE #
										<a href="{U_APP_UPDATE}"><i class="fa fa-sync-alt" aria-hidden="true"></i> {L_UPDATE_PACK}</a>
									# ENDIF #
								# ENDIF #
							</div>
						# ENDIF #
						<p>{APP_DESCRIPTION}</p>
					</div>
					<aside>
						# IF C_IMPROVEMENTS #
							<h5>{L_IMPROVEMENTS}</h5>
							<ul>
								# START improvements #<li>{improvements.description}</li># END improvements #
							</ul>
						# ENDIF #
						# IF C_SECURITY_IMPROVEMENTS #
							<h5>{L_SECURITY_IMPROVEMENTS}</h5>
							<ul>
								# START security #<li>{security.description}</li># END security #
							</ul>
						# ENDIF #
						# IF C_NEW_FEATURES #
							<h5>{L_NEW_FEATURES}</h5>
							<ul>
								# START new_features #<li>{new_features.description}</li># END new_features #
							</ul>
						# ENDIF #
						# IF C_BUG_CORRECTIONS #
							<h5>{L_FIXED_BUGS}</h5>
							<ul>
								# START bugs #<li>{bugs.description}</li># END bugs #
							</ul>
						# ENDIF #
					</aside>
				</article>
			# ENDIF #
		</div>
	</fieldset>
</div>
