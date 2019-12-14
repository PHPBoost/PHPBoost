<div class="text-helper">{@H|langs.warning_before_delete}</div>
<form action="{REWRITED_SCRIPT}" method="post">
	<section id="installed-langs-container">
		<header class="legend">{@langs.installed_langs}</header>
		<div class="cell-flex cell-columns-3 cell-tile">
			# START langs_installed #
			<article class="cell addon# IF langs_installed.C_IS_DEFAULT_LANG # default-addon# ENDIF ## IF NOT langs_installed.C_IS_ACTIVATED # disabled-addon# ENDIF ## IF NOT langs_installed.C_COMPATIBLE # not-compatible# ENDIF #">
				<header class="cell-header">
					# IF C_MORE_THAN_ONE_LANG_INSTALLED #
						# IF langs_installed.C_COMPATIBLE #
							<div class="mini-checkbox">
								<label class="checkbox" for="multiple-checkbox-{langs_installed.LANG_NUMBER}">
									<input type="checkbox" class="multiple-checkbox delete-checkbox" id="multiple-checkbox-{langs_installed.LANG_NUMBER}" name="delete-checkbox-{langs_installed.LANG_NUMBER}"# IF langs_installed.C_IS_DEFAULT_LANG # disabled="disabled"# ENDIF # />
									<span>&nbsp;</span>
								</label>
							</div>
						# ENDIF #
					# ENDIF #
					<h3 class="cell-name">
						# IF langs_installed.C_HAS_PICTURE #
							<img src="{langs_installed.PICTURE_URL}" alt="{langs_installed.NAME}" class="valign-middle" />
						# ENDIF #
						{langs_installed.NAME}
					</h3>
					# IF langs_installed.C_IS_DEFAULT_LANG #
						<div class="addon-menu-container">
							<span class="addon-menu-title">{@langs.default}</span>
						</div>
					# ELSE #
						<div class="addon-menu-container addon-with-menu">
							<a href="#" id="addon-menu-title-{langs_installed.LANG_NUMBER}" class="addon-menu-title" aria-label="${LangLoader::get_message('action_menu.open', 'admin-common')}">
								# IF langs_installed.C_COMPATIBLE #
									# IF langs_installed.C_IS_ACTIVATED #
										${LangLoader::get_message('actions', 'admin-common')}
									# ELSE #
										${LangLoader::get_message('disabled', 'common')}
									# ENDIF #
								# ELSE #
									${LangLoader::get_message('not_compatible', 'admin-common')}
								# ENDIF #
							</a>
							<ul class="addon-menu-content">
								# IF langs_installed.C_COMPATIBLE #
									<li class="addon-menu-item"><button type="submit" class="button submit" name="default-{langs_installed.ID}" value="true">${LangLoader::get_message('set_to_default', 'admin-common')}</button></li>
									# IF langs_installed.C_IS_ACTIVATED #
										<li class="addon-menu-item"><button type="submit" class="button submit" name="disable-{langs_installed.ID}" value="true">${LangLoader::get_message('disable', 'common')}</button></li>
									# ELSE #
										<li class="addon-menu-item"><button type="submit" class="button submit" name="enable-{langs_installed.ID}" value="true">${LangLoader::get_message('enable', 'common')}</button></li></li>
									# ENDIF #
								# ENDIF #
								<li class="addon-menu-item"><button type="submit" class="button alt-submit" name="delete-{langs_installed.ID}" value="true">${LangLoader::get_message('uninstall', 'admin-common')}</button></li>
							</ul>
						</div>
					# ENDIF #
				</header>
				<div class="cell-list">
					<ul>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('version', 'admin')} :</span>
							{langs_installed.VERSION}
						</li>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
							<span# IF NOT langs_installed.C_COMPATIBLE # class="not-compatible"# ENDIF #>PHPBoost {langs_installed.COMPATIBILITY}</span>
						</li>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span>
							<span>
								# IF langs_installed.C_AUTHOR_EMAIL #
									<a href="mailto:{langs_installed.AUTHOR_EMAIL}">{langs_installed.AUTHOR}</a>
								# ELSE #
									{langs_installed.AUTHOR}
								# ENDIF #
								# IF langs_installed.C_AUTHOR_WEBSITE #
									<a href="{langs_installed.AUTHOR_WEBSITE}" class="button alt-button smaller">Web</a>
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
					<div class="addon-auth-container">
						# IF langs_installed.C_IS_DEFAULT_LANG #
						<span class="addon-auth default-addon" aria-label="{@langs.default_lang_visibility}"><i class="fa fa-user-shield" aria-hidden="true"></i></span>
						# ELSE #
						<a href="" class="addon-auth" aria-label="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"><i class="fa fa-user-shield"  aria-hidden="true"></i></a>
						<div class="addon-auth-content">
							{langs_installed.AUTHORIZATIONS}
							<a href="#" class="addon-auth-close" aria-label="${LangLoader::get_message('close', 'main')}"><i class="fa fa-times" aria-hidden="true"></i></a>
						</div>
						# ENDIF #
					</div>
					# ENDIF #
				</footer>
			</article>
			<script>
				jQuery('#addon-menu-title-{langs_installed.LANG_NUMBER}').opensubmenu({
					osmTarget: '.addon-menu-container'
				});
			</script>
			# END langs_installed #
		</div>
		<footer>
			<fieldset class="fieldset-submit">
				<legend>{L_SUBMIT}</legend>
				<button type="submit" class="button submit" name="update_langs_configuration" value="true">${LangLoader::get_message('save.authorizations', 'admin-common')}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="update" value="true">
			</fieldset>
		</footer>
	</section>

	# IF C_MORE_THAN_ONE_LANG_INSTALLED #
	<div class="addon-menu-container multiple-select-menu-container">
		<a href="#" class="multiple-select-menu addon-menu-title">${LangLoader::get_message('multiple.select', 'admin-common')}</a>
		<ul class="addon-menu-content">
			<li class="addon-menu-checkbox mini-checkbox">
				<div class="form-field form-field-checkbox select-all-checkbox">
					<label class="checkbox" for="delete-all-checkbox">
						<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {LANGS_NUMBER}, {DEFAULT_LANG_NUMBER}, false);" aria-label="{@langs.select_all_langs}" />
						<span>&nbsp;</span>
					</label>
				</div>
			</li>
			<li class="addon-menu-item"><button type="submit" name="delete-selected-langs" value="true" class="button alt-submit" id="delete-all-button">${LangLoader::get_message('multiple.uninstall_selection', 'admin-common')}</button></li>
			<li class="addon-menu-item"><button type="submit" name="deactivate-selected-langs" value="true" class="button submit" id="deactivate-all-button">${LangLoader::get_message('multiple.deactivate_selection', 'admin-common')}</button></li>
			<li class="addon-menu-item"><button type="submit" name="activate-selected-langs" value="true" class="button submit" id="activate-all-button">${LangLoader::get_message('multiple.activate_selection', 'admin-common')}</button></li>
		</ul>
	</div>
	# ENDIF #
</form>

<script>
	jQuery('.addon-menu-title').opensubmenu({
		osmTarget: '.addon-menu-title',
		osmCloseExcept : '.addon-menu-checkbox, .addon-menu-checkbox *'
	});

	jQuery('.addon-auth').opensubmenu({
		osmTarget: '.addon-auth-container',
		osmCloseExcept: '.addon-auth-content *',
		osmCloseButton: '.addon-auth-close i',
	});
</script>
