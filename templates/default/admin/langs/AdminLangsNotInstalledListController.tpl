# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	# INCLUDE MSG #
	<section id="not-installed-langs-container" class="addons-container langs-elements-container not-installed-elements-container">
		<header class="legend">{@langs.available_langs}</header>
		# IF C_LANG_AVAILABLE #
			<div class="cell-flex cell-columns-3 cell-tile">
				# START langs_not_installed #
					<article class="cell addon# IF NOT langs_not_installed.C_COMPATIBLE # not-compatible error# ENDIF #">
						<header class="cell-header">
							# IF C_MORE_THAN_ONE_LANG_AVAILABLE #
								# IF langs_not_installed.C_COMPATIBLE #
									<div class="mini-checkbox">
										<label class="checkbox" for="multiple-checkbox-{langs_not_installed.LANG_NUMBER}">
											<input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{langs_not_installed.LANG_NUMBER}" name="add-checkbox-{langs_not_installed.LANG_NUMBER}"/>
											<span>&nbsp;</span>
										</label>
									</div>
								# ENDIF #
							# ENDIF #
							# IF langs_not_installed.C_HAS_PICTURE #
								<img src="{langs_not_installed.PICTURE_URL}" alt="{langs_not_installed.NAME}" class="valign-middle" />
							# ENDIF #
							<h3 class="cell-name">
								{langs_not_installed.NAME}
							</h3>
							<div class="addon-menu-container">
								# IF langs_not_installed.C_COMPATIBLE #
									<button type="submit" class="button submit addon-menu-title" name="add-{langs_not_installed.ID}" value="true">${LangLoader::get_message('install', 'admin-common')}</button>
								# ELSE #
									<span class="addon-menu-title">${LangLoader::get_message('not_compatible', 'admin-common')}</span>
								# ENDIF #
							</div>
						</header>

						<div class="cell-list">
							<ul>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('version', 'admin')} :</span>
									{langs_not_installed.VERSION}
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
									<span# IF NOT langs_not_installed.C_COMPATIBLE # class="not-compatible error"# ENDIF #>PHPBoost {langs_not_installed.COMPATIBILITY}</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span>
									<span>
										# IF langs_not_installed.C_AUTHOR_EMAIL #
											<a href="mailto:{langs_not_installed.AUTHOR_EMAIL}">{langs_not_installed.AUTHOR}</a>
										# ELSE #
											{langs_not_installed.AUTHOR}
										# ENDIF #
										# IF langs_not_installed.C_AUTHOR_WEBSITE #
											<a href="{langs_not_installed.AUTHOR_WEBSITE}" class="pinned bgc question">Web</a>
										# ENDIF #
									</span>
								</li>
							</ul>
						</div>

						<footer class="cell-footer">
							# IF langs_not_installed.C_COMPATIBLE #
							<div class="addon-auth-container">
								<a href="#" class="addon-auth" aria-label="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"><i class="fa fa-user-shield" aria-hidden="true"></i></a>
								<div class="addon-auth-content">
									{langs_not_installed.AUTHORIZATIONS}
									<a href="#" class="addon-auth-close" aria-label="${LangLoader::get_message('close', 'main')}"><i class="fa fa-times" aria-hidden="true"></i></a>
								</div>
							</div>
							# ENDIF #
						</footer>
					</article>
				# END langs_not_installed #
			</div>
		# ELSE #
			<div class="content">
				<div class="message-helper bgc notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
			</div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_MORE_THAN_ONE_LANG_AVAILABLE #
		<div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
			<label class="checkbox" for="add-all-checkbox">
				<input type="checkbox" class="check-all" id="add-all-checkbox" name="add-all-checkbox" onclick="multiple_checkbox_check(this.checked, {LANGS_NUMBER}, null, false);" />
				<span aria-label="{@langs.select_all_langs}">&nbsp;</span>
			</label>
			<button type="submit" name="add-selected-langs" value="true" class="button submit select-all-button">${LangLoader::get_message('multiple.install_selection', 'admin-common')}</button>
		</div>
	# ENDIF #
</form>
<script>
	jQuery('.addon-auth').opensubmenu({
		osmTarget: '.addon-auth-container',
		osmCloseExcept: '.addon-auth-content *',
		osmCloseButton: '.addon-auth-close i',
	});
</script>
