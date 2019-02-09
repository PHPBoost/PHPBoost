<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;" title="{L_WEBSITE_UPDATES}">
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
			# IF C_UNEXISTING_UPDATE #
				<div class="message-helper warning message-helper-small">{L_UNEXISTING_UPDATE}</div>
			# ELSE #
				<article>
					<header>
						<h2>{APP_NAME} - {APP_VERSION}</h2>
						# IF C_APP_WARNING #
						<div class="{PRIORITY_CSS_CLASS}">
							<p class="center">{L_WARNING} - {APP_WARNING_LEVEL}</p>
							<p>{APP_WARNING}</p>
						</div>
						# ENDIF #
					</header>
					<div class="float-right">
						<a href="{U_APP_DOWNLOAD}"><i class="fa fa-cloud-download-alt" aria-hidden="true"></i> {L_DOWNLOAD_PACK}</a>
						# IF U_APP_UPDATE #
							<br />
							<a href="{U_APP_UPDATE}"><i class="fa fa-sync-alt" aria-hidden="true"></i> {L_UPDATE_PACK}</a>
						# ENDIF #
					</div>
					<div class="more">
						{APP_PUBDATE} | # START authors #<a href="mailto:{authors.email}">{authors.name}</a> | # END authors #
					</div>
					<div class="content">
						<p>{APP_DESCRIPTION}</p>
					</div>
					<aside>
						# IF C_IMPROVMENTS #
							<h5>{L_IMPROVMENTS}</h5>
							<ul>
								# START improvments #<li>{improvments.description}</li># END improvments #
							</ul>
						# ENDIF #
						# IF C_SECURITY_IMPROVMENTS #
							<h5>{L_SECURITY_IMPROVMENTS}</h5>
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
