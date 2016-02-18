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
		<legend>{L_APP_UPDATE_MESSAGE}</legend>
		<div class="fieldset-inset">
			<div style="clear:right;"></div>
			# IF C_UNEXISTING_UPDATE #
				<div class="warning message-helper-small">{L_UNEXISTING_UPDATE}</div>
			# ELSE #
				<table id="table">
					<caption>{L_APP_UPDATE_MESSAGE}</caption>
					<thead>
						<tr>
							<th>
								${LangLoader::get_message('last_update', 'admin')}
							</th>
							<th>
								${LangLoader::get_message('langs.description', 'admin-langs-common')}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="valign-top">
								<div class="block-container">
									<div class="block-top"><span>{APP_NAME} - {APP_VERSION} ({APP_LANGUAGE})</span></div>
									<div class="block-contents">
										{APP_DESCRIPTION}
										<p class="smaller">{APP_PUBDATE}</p>
									</div>
								</div>
								# IF C_NEW_FEATURES #
									<div class="block-container">
										<div class="block-top"><span>{L_NEW_FEATURES}</span></div>
										<div class="block-contents">
											<ul class="list"># START new_features #<li>{new_features.description}</li># END new_features #</ul>
										</div>
									</div>
								# END IF #
								# IF C_IMPROVMENTS #
									<div class="block-container">
										<div class="block-top"><span>{L_IMPROVMENTS}</span></div>
										<div class="block-contents">
											<ul class="list"># START improvments #<li>{improvments.description}</li># END improvments #</ul>
										</div>
									</div>
								# END IF #
								<div class="block-container">
									<div class="block-top"><span class="{PRIORITY_CSS_CLASS}">{L_WARNING} - {APP_WARNING_LEVEL}</span></div>
									<div class="block-contents">
										{APP_WARNING}
									</div>
								</div>
							</td>
							<td class="valign-top">
								<div class="block-container">
									<div class="block-top"><span>{L_DOWNLOAD}</span></div>
									<div class="block-contents">
										<ul class="list">
											<li><a href="{U_APP_DOWNLOAD}">{L_DOWNLOAD_PACK}</a></li>
											# IF U_APP_UPDATE #
											<li><a href="{U_APP_UPDATE}">{L_UPDATE_PACK}</a></li>
											# END IF #
										</ul>
									</div>
								</div>
								<div class="block-container">
									<div class="block-top"><span>{L_AUTHORS}</span></div>
									<div class="block-contents">
										<ul class="list"># START authors #<li><a href="mailto:{authors.email}">{authors.name}</a></li># END authors #</ul>
									</div>
								</div>
								# IF C_BUG_CORRECTIONS #
									<div class="block-container">
										<div class="block-top"><span>{L_FIXED_BUGS}</span></div>
										<div class="block-contents">
											<ul class="list"># START bugs #<li>{bugs.description}</li># END bugs #</ul>
										</div>
									</div>
								# END IF #
								# IF C_SECURITY_IMPROVMENTS #
									<div class="block-container">
										<div class="block-top"><span>{L_SECURITY_IMPROVMENTS}</span></div>
										<div class="block-contents">
											<ul class="list"># START security #<li>{security.description}</li># END security #</ul>
										</div>
									</div>
								# END IF #
							</td>
						</tr>
					</tbody>
				</table>
			# END IF #
		</div>
	</fieldset>
</div>
    