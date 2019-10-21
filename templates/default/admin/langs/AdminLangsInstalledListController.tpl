{@H|langs.warning_before_delete}
<form action="{REWRITED_SCRIPT}" method="post">
	<section id="installed-langs-container" class="admin-elements-container langs-elements-container installed-elements-container">
		<header class="legend">{@langs.installed_langs}</header>
		<div class="cell-flex cell-flex-3">
			# START langs_installed #
			<article class="cell admin-element lang-element installed-element# IF langs_installed.C_IS_DEFAULT_LANG # default-element# ENDIF ## IF langs_installed.C_IS_ACTIVATED # activate-element# ELSE # deactivate-element# ENDIF ## IF NOT langs_installed.C_COMPATIBLE # not-compatible# ENDIF #">
				<header class="cell-title">
					<div class="admin-element-menu-container">
						# IF langs_installed.C_IS_DEFAULT_LANG #
							<a href="#" class="admin-element-menu-title">{@langs.default}</a>
						# ELSE #
						<a href="#" id="admin-element-menu-title-{langs_installed.LANG_NUMBER}" class="admin-element-menu-title" aria-label="${LangLoader::get_message('action_menu.open', 'admin-common')}"># IF langs_installed.C_COMPATIBLE ## IF langs_installed.C_IS_ACTIVATED #${LangLoader::get_message('actions', 'admin-common')}# ELSE #${LangLoader::get_message('disabled', 'common')}# ENDIF ## ELSE #${LangLoader::get_message('not_compatible', 'admin-common')}# ENDIF #<i class="fa fa-caret-right" aria-hidden="true"></i></a>
						<ul class="admin-menu-elements-content">
							# IF langs_installed.C_COMPATIBLE #
							<li class="admin-menu-element"><button type="submit" class="submit" name="default-{langs_installed.ID}" value="true">${LangLoader::get_message('set_to_default', 'admin-common')}</button></li>
							# IF langs_installed.C_IS_ACTIVATED #
							<li class="admin-menu-element"><button type="submit" class="submit" name="disable-{langs_installed.ID}" value="true">${LangLoader::get_message('disable', 'common')}</button></li>
							# ELSE #
							<li class="admin-menu-element"><button type="submit" class="submit" name="enable-{langs_installed.ID}" value="true">${LangLoader::get_message('enable', 'common')}</button></li></li>
							# ENDIF #
							# ENDIF #
							<li class="admin-menu-element"><button type="submit" class="submit alt" name="delete-{langs_installed.ID}" value="true">${LangLoader::get_message('uninstall', 'admin-common')}</button></li>
						</ul>
						# ENDIF #
					</div>

					# IF C_MORE_THAN_ONE_LANG_INSTALLED #
						# IF langs_installed.C_COMPATIBLE #
							<div class="form-field form-field-checkbox multiple-checkbox-container mini-checkbox">
								<label class="checkbox" for="multiple-checkbox-{langs_installed.LANG_NUMBER}">
									<input type="checkbox" class="multiple-checkbox delete-checkbox" id="multiple-checkbox-{langs_installed.LANG_NUMBER}" name="delete-checkbox-{langs_installed.LANG_NUMBER}"# IF langs_installed.C_IS_DEFAULT_LANG # disabled="disabled"# ENDIF # />
									<span>&nbsp;</span>
								</label>
							</div>
						# ENDIF #
					# ENDIF #

					<h2 class="installed-theme-name">
						# IF langs_installed.C_HAS_PICTURE #
						<img src="{langs_installed.PICTURE_URL}" alt="{langs_installed.NAME}" class="valign-middle" />
						# ENDIF #
						{langs_installed.NAME}<em> ({langs_installed.VERSION})</em></h2>
				</header>
				<div class="cell-list">
					<ul>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span>
							<span>
								# IF langs_installed.C_AUTHOR_EMAIL #
									<a href="mailto:{langs_installed.AUTHOR_EMAIL}">{langs_installed.AUTHOR}</a>
								# ELSE #
									{langs_installed.AUTHOR}
								# ENDIF #
								# IF langs_installed.C_AUTHOR_WEBSITE #
									<a href="{langs_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a>
								# ENDIF #
							</span>
						</li>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
							<span# IF NOT langs_installed.C_COMPATIBLE # class="not-compatible"# ENDIF #>PHPBoost {langs_installed.COMPATIBILITY}</span>
						</li>
					</ul>
				</div>
				<footer class="cell-footer">
					# IF langs_installed.C_COMPATIBLE #
					<div class="admin-element-auth-container">
						# IF langs_installed.C_IS_DEFAULT_LANG #
						<span class="admin-element-auth default-element" aria-label="{@langs.default_lang_visibility}"><i class="fa fa-user-shield" aria-hidden="true"></i></span>
						# ELSE #
						<a href="" class="admin-element-auth" aria-label="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"><i class="fa fa-user-shield"  aria-hidden="true"></i></a>
						<div class="admin-element-auth-content">
							{langs_installed.AUTHORIZATIONS}
							<a href="#" class="admin-element-auth-close" aria-label="${LangLoader::get_message('close', 'main')}"><i class="fa fa-times" aria-hidden="true"></i></a>
						</div>
						# ENDIF #
					</div>
					# ENDIF #
				</footer>
			</article>
			<script>
				jQuery('#admin-element-menu-title-{langs_installed.LANG_NUMBER}').opensubmenu({
					osmTarget: '.admin-element-menu-container'
				});
			</script>
			# END langs_installed #
		</div>
		<footer>
			<fieldset class="fieldset-submit">
				<legend>{L_SUBMIT}</legend>
				<button type="submit" class="submit" name="update_langs_configuration" value="true">${LangLoader::get_message('save.authorizations', 'admin-common')}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="update" value="true">
			</fieldset>
		</footer>
	</section>

	# IF C_MORE_THAN_ONE_LANG_INSTALLED #
	<div class="admin-element-menu-container multiple-select-menu-container">
		<div class="admin-element-menu-title">
			<a href="#" class="multiple-select-menu">${LangLoader::get_message('multiple.select', 'admin-common')} <i class="fa fa-caret-right" aria-hidden="true"></i></a>
		</div>
		<ul class="admin-menu-elements-content">
			<li class="admin-menu-checkbox mini-checkbox">
				<div class="form-field form-field-checkbox select-all-checkbox">
					<label class="checkbox" for="delete-all-checkbox">
						<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {LANGS_NUMBER}, {DEFAULT_LANG_NUMBER});" aria-label="{@langs.select_all_langs}" />
						<span>&nbsp;</span>
					</label>
				</div>
			</li>
			<li class="admin-menu-element"><button type="submit" name="delete-selected-langs" value="true" class="submit alt" id="delete-all-button">${LangLoader::get_message('multiple.uninstall_selection', 'admin-common')}</button></li>
			<li class="admin-menu-element"><button type="submit" name="deactivate-selected-langs" value="true" class="submit" id="deactivate-all-button">${LangLoader::get_message('multiple.deactivate_selection', 'admin-common')}</button></li>
			<li class="admin-menu-element"><button type="submit" name="activate-selected-langs" value="true" class="submit" id="activate-all-button">${LangLoader::get_message('multiple.activate_selection', 'admin-common')}</button></li>
		</ul>
	</div>
	# ENDIF #
</form>

<script>
	jQuery('.admin-element-menu-title').opensubmenu({
		osmTarget: '.admin-element-menu-title',
		osmCloseExcept : '.admin-menu-checkbox, .admin-menu-checkbox *'
	});

	jQuery('.admin-element-auth').opensubmenu({
		osmTarget: '.admin-element-auth-container',
		osmCloseExcept: '.admin-element-auth-content *',
		osmCloseButton: '.admin-element-auth-close i',
	});
</script>
