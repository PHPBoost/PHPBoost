<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars"></i> {@admin.updates}
	</a>
	<ul>
		<li>
			<a href="updates.php" class="quick-link">{@admin.updates}</a>
		</li>
		<li>
			<a href="updates.php?type=kernel" class="quick-link">{@admin.kernel}</a>
		</li>
		<li>
			<a href="updates.php?type=module" class="quick-link">{@addon.modules}</a>
		</li>
		<li>
			<a href="updates.php?type=template" class="quick-link">{@addon.themes}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<fieldset>
		<legend>{L_APP_UPDATE_MESSAGE}</legend>
		<div class="fieldset-inset">
			# INCLUDE MESSAGE_HELPER #
			# IF C_UNEXISTING_UPDATE #
				<div class="message-helper bgc warning message-helper-small">{@admin.no.available.update}</div>
			# ELSE #
				<article>
					<header>
						<h2>{APP_NAME} - {APP_VERSION}</h2>
						# IF C_APP_WARNING #
							<div class="message-helper bgc {WARNING_CSS_CLASS} message-update">
								<p class="align-center">{@admin.warning}</p>
								<p>{APP_WARNING}</p>
							</div>
						# ENDIF #
					</header>
					<div class="more">
						<span class="pinned" aria-label="{@common.creation.date}">{APP_PUBDATE}</span>
						# START authors #
							<span class="pinned" aria-label="{@common.author}"><a href="mailto:{authors.EMAIL}">{authors.NAME}</a></span>
						# END authors #
					</div>
					<div class="content">
						# IF C_DISPLAY_LINKS_AND_PRIORITY #
							<div class="infos options align-center alert-priority bgc {PRIORITY_CSS_CLASS}">
								<p>{PRIORITY}</p>
								# IF C_DISPLAY_UPDATE_BUTTON #
									<form action="{PATH_TO_ROOT}/admin/updates/detail.php?identifier={IDENTIFIER}" method="post">
										<button type="submit" name="execute_update" class="button bgc-full {PRIORITY_CSS_CLASS}" onclick="" value="true">{@addon.install}</button>
										<input type="hidden" name="token" value="{TOKEN}">
									</form>
								# ELSE #
									<a href="{U_APP_DOWNLOAD}"><i class="fa fa-cloud-download-alt" aria-hidden="true"></i> {@admin.download.pack}</a>
									# IF U_APP_UPDATE #
										<a href="{U_APP_UPDATE}"><i class="fa fa-sync-alt" aria-hidden="true"></i> {@admin.update.pack}</a>
									# ENDIF #
								# ENDIF #
							</div>
						# ENDIF #
						<p>{APP_DESCRIPTION}</p>
					</div>
					<aside>
						# IF C_IMPROVEMENTS #
							<h5>{@admin.improvements}</h5>
							<ul>
								# START improvements #<li>{improvements.DESCRIPTION}</li># END improvements #
							</ul>
						# ENDIF #
						# IF C_SECURITY_IMPROVEMENTS #
							<h5>{@admin.security.improvements}</h5>
							<ul>
								# START security #<li>{security.DESCRIPTION}</li># END security #
							</ul>
						# ENDIF #
						# IF C_NEW_FEATURES #
							<h5>{@admin.new.features}</h5>
							<ul>
								# START new_features #<li>{new_features.DESCRIPTION}</li># END new_features #
							</ul>
						# ENDIF #
						# IF C_BUG_CORRECTIONS #
							<h5>{@admin.fixed.bugs}</h5>
							<ul>
								# START bugs #<li>{bugs.DESCRIPTION}</li># END bugs #
							</ul>
						# ENDIF #
					</aside>
				</article>
			# ENDIF #
		</div>
	</fieldset>
</div>
