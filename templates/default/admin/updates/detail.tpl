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
				<div class="message-helper warning message-helper-small">{L_UNEXISTING_UPDATE}</div>
			# ELSE #
				<table id="table">
					<caption>{L_APP_UPDATE_MESSAGE}</caption>
					<thead>
						<tr>
							<th
								# IF C_NEW_FEATURES #class="tdw50"# ENDIF #
								# IF C_BUG_CORRECTIONS #class="tdw50"# ENDIF #
								# IF C_NEW_FEATURES ## IF C_BUG_CORRECTIONS #class="tdw25"# ENDIF ## ENDIF #>
								${LangLoader::get_message('last_update', 'admin')}
							</th>
							# IF C_NEW_FEATURES #
								<th>{L_NEW_FEATURES}</th>
							# ENDIF #
							# IF C_BUG_CORRECTIONS #
								<th>{L_FIXED_BUGS}</th>
							# ENDIF #
							<th class="td150">{L_AUTHORS}</th>
							<th class="td200">{L_DOWNLOAD}</th>
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
								# IF C_IMPROVMENTS #
									<div class="block-container">
										<div class="block-top"><span>{L_IMPROVMENTS}</span></div>
										<div class="block-contents">
											<ul class="list"># START improvments #<li>{improvments.description}</li># END improvments #</ul>
										</div>
									</div>
								# ENDIF #
								# IF C_APP_WARNING #
								<div class="block-container">
									<div class="block-top"><span class="{PRIORITY_CSS_CLASS}">{L_WARNING} - {APP_WARNING_LEVEL}</span></div>
									<div class="block-contents">
										{APP_WARNING}
									</div>
								</div>
								# ENDIF #
							</td>
							# IF C_NEW_FEATURES #
								<td class="valign-top">
									<div class="block-container">
										<div class="block-top"><span></span></div>
										<div class="block-contents">
											<ul class="list"># START new_features #<li>{new_features.description}</li># END new_features #</ul>
										</div>
									</div>
								</td>
							# ENDIF #
							# IF C_BUG_CORRECTIONS #
								<td class="valign-top">
									<div class="block-container">
										<div class="block-contents">
											<ul class="list"># START bugs #<li>{bugs.description}</li># END bugs #</ul>
										</div>
									</div>
								</td>
							# ENDIF #
							<td class="valign-top">
								<div class="block-container">
									<div class="block-contents">
										<ul class="list"># START authors #<li><a href="mailto:{authors.email}">{authors.name}</a></li># END authors #</ul>
									</div>
								</div>
							</td>
							<td class="valign-top">
								<div class="block-container">
									<div class="block-contents">
										<ul class="list">
											<li><a href="{U_APP_DOWNLOAD}"><i class="fa fa-cloud-download-alt" aria-hidden="true"></i> {L_DOWNLOAD_PACK}</a></li>
											# IF U_APP_UPDATE #
												<li><a href="{U_APP_UPDATE}"><i class="fa fa-sync-alt" aria-hidden="true"></i> {L_UPDATE_PACK}</a></li>
											# ENDIF #
										</ul>
									</div>
								</div>
								# IF C_SECURITY_IMPROVMENTS #
									<div class="block-container">
										<div class="block-top"><span>{L_SECURITY_IMPROVMENTS}</span></div>
										<div class="block-contents">
											<ul class="list">
												# START security #<li>{security.description}</li># END security #
											</ul>
										</div>
									</div>
								# ENDIF #
							</td>
						</tr>
					</tbody>
				</table>
			# ENDIF #
		</div>
	</fieldset>
</div>
