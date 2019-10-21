# INCLUDE MSG_WARNING #
# INCLUDE MSG_SUCCESS #
# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	<section id="not-installed-modules-container" class="admin-elements-container modules-elements-container not-installed-elements-container">
		<header><h1>{@modules.available_modules}</h1></header>
		# IF C_MODULE_AVAILABLE #
			<div class="cell-flex cell-flex-3">
				# START modules_not_installed #
					<article class="cell admin-element module-element not-installed-element# IF NOT modules_not_installed.C_COMPATIBLE # not-compatible# ENDIF #">
						<header class="cell-title">
							<div class="admin-element-menu-container">
								# IF modules_not_installed.C_COMPATIBLE #
									<button type="submit" class="submit admin-element-menu-title" name="add-{modules_not_installed.ID}" value="true">${LangLoader::get_message('install', 'admin-common')}</button>
								# ELSE #
									<span class="admin-element-menu-title">${LangLoader::get_message('not_compatible', 'admin-common')}</span>
								# ENDIF #
							</div>
							# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
								# IF modules_not_installed.C_COMPATIBLE #
									<div class="form-field form-field-checkbox multiple-checkbox-container mini-checkbox">
										<label class="checkbox" for="multiple-checkbox-{modules_not_installed.MODULE_NUMBER}">
											<input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{modules_not_installed.MODULE_NUMBER}" name="add-checkbox-{modules_not_installed.MODULE_NUMBER}"/>
											<span>&nbsp;</span>
										</label>
									</div>
								# ENDIF #
							# ENDIF #

							<h2 class="not-installed-module-name">{modules_not_installed.NAME}<em> ({modules_not_installed.VERSION})</em></h2>
						</header>
						<div class="cell-list">
							<ul>
								<li class="li-stretch">
									<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_not_installed.ICON}/{modules_not_installed.ICON}.png" alt="{modules_not_installed.NAME}" />
								</li>
								<li class="li-stretch"></li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span>
									<span># IF modules_not_installed.C_AUTHOR_EMAIL #<a href="mailto:{modules_not_installed.AUTHOR_EMAIL}">{modules_not_installed.AUTHOR}</a># ELSE #{modules_not_installed.AUTHOR}# ENDIF # # IF modules_not_installed.C_AUTHOR_WEBSITE #<a href="{modules_not_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} :</span>
									{modules_not_installed.CREATION_DATE}
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('last_update', 'admin')} :</span>
									{modules_not_installed.LAST_UPDATE}
								</li>
								<li>
									<span class="text-strong">${LangLoader::get_message('description', 'main')} :</span>
									{modules_not_installed.DESCRIPTION}
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
									<span# IF NOT modules_not_installed.C_COMPATIBLE # class="not-compatible"# ENDIF #>PHPBoost {modules_not_installed.COMPATIBILITY}</span>
								</li>
							</ul>
						</div>

						<footer></footer>
					</article>
				# END modules_not_installed #
			</div>
		# ELSE #
			<div class="content">
				<div class="message-helper notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
			</div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
		<div class="multiple-select-menu-container admin-element-menu-title">
			<div class="form-field form-field-checkbox select-all-checkbox mini-checkbox">
				<label class="checkbox" for="add-all-checkbox">
					<input type="checkbox" class="check-all" id="add-all-checkbox" name="add-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MODULES_NUMBER});" aria-label="{@modules.select_all_modules}" />
					<span>&nbsp;</span>
				</label>
			</div>
			<button type="submit" name="add-selected-modules" value="true" class="submit select-all-button">${LangLoader::get_message('multiple.install_selection', 'admin-common')}</button>
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
