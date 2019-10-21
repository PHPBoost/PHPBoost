# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	# INCLUDE MSG #
	<section id="not-installed-langs-container" class="admin-elements-container langs-elements-container not-installed-elements-container">
		<header class="legend">{@langs.available_langs}</header>
		# IF C_LANG_AVAILABLE #
		<div class="cell-flex cell-flex-3">
			# START langs_not_installed #
			<article class="cell admin-element lang-element not-installed-element# IF NOT langs_not_installed.C_COMPATIBLE # not-compatible# ENDIF #">
				<header class="cell-title">
					<div class="admin-element-menu-container">
						# IF langs_not_installed.C_COMPATIBLE #
							<button type="submit" class="submit admin-element-menu-title" name="add-{langs_not_installed.ID}" value="true">${LangLoader::get_message('install', 'admin-common')}</button>
						# ELSE #
							<span class="admin-element-menu-title">${LangLoader::get_message('not_compatible', 'admin-common')}</span>
						# ENDIF #
					</div>
					# IF C_MORE_THAN_ONE_LANG_AVAILABLE #
						# IF langs_not_installed.C_COMPATIBLE #
							<div class="form-field form-field-checkbox multiple-checkbox-container mini-checkbox">
								<label class="checkbox" for="multiple-checkbox-{langs_not_installed.LANG_NUMBER}">
									<input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{langs_not_installed.LANG_NUMBER}" name="add-checkbox-{langs_not_installed.LANG_NUMBER}"/>
									<span>&nbsp;</span>
								</label>
							</div>
						# ENDIF #
					# ENDIF #

					<h2 class="not-installed-lang-name">
						# IF langs_not_installed.C_HAS_PICTURE #
							<img src="{langs_not_installed.PICTURE_URL}" alt="{langs_not_installed.NAME}" class="valign-middle" />
						# ENDIF #
						{langs_not_installed.NAME} <em>({langs_not_installed.VERSION})</em>
					</h2>
				</header>

				<div class="cell-list">
					<ul>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span>
							<span>
								# IF langs_not_installed.C_AUTHOR_EMAIL #
									<a href="mailto:{langs_not_installed.AUTHOR_EMAIL}">{langs_not_installed.AUTHOR}</a>
								# ELSE #
									{langs_not_installed.AUTHOR}
								# ENDIF #
								# IF langs_not_installed.C_AUTHOR_WEBSITE #
									<a href="{langs_not_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a>
								# ENDIF #
							</span>
						</li>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
							<span# IF NOT langs_not_installed.C_COMPATIBLE # class="not-compatible"# ENDIF #>PHPBoost {langs_not_installed.COMPATIBILITY}</span>
						</li>
					</ul>
					<div class="admin-element-desc">


					</div>
				</div>

				<footer class="cell-footer">
					# IF langs_not_installed.C_COMPATIBLE #
					<div class="admin-element-auth-container">
						<a href="" class="admin-element-auth" aria-label="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"><i class="fa fa-user-shield" aria-hidden="true"></i></a>
						<div class="admin-element-auth-content">
							{langs_not_installed.AUTHORIZATIONS}
							<a href="#" class="admin-element-auth-close" aria-label="${LangLoader::get_message('close', 'main')}"><i class="fa fa-times" aria-hidden="true"></i></a>
						</div>
					</div>
					# ENDIF #
				</footer>
			</article>
			# END langs_not_installed #
		</div>
		# ELSE #
		<div class="content">
			<div class="message-helper notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
		</div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_MORE_THAN_ONE_LANG_AVAILABLE #
	<div class="multiple-select-menu-container admin-element-menu-title">
		<div class="form-field form-field-checkbox select-all-checkbox mini-checkbox">
			<label class="checkbox" for="add-all-checkbox">
				<input type="checkbox" class="check-all" id="add-all-checkbox" name="add-all-checkbox" onclick="multiple_checkbox_check(this.checked, {LANGS_NUMBER});" aria-label="{@langs.select_all_langs}" />
				<span>&nbsp;</span>
			</label>
		</div>
		<button type="submit" name="add-selected-langs" value="true" class="submit select-all-button">${LangLoader::get_message('multiple.install_selection', 'admin-common')}</button>
	</div>
	# ENDIF #
</form>
<script>
	jQuery('.admin-element-auth').opensubmenu({
		osmTarget: '.admin-element-auth-container',
		osmCloseExcept: '.admin-element-auth-content *',
		osmCloseButton: '.admin-element-auth-close i',
	});
</script>
