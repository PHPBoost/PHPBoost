# INCLUDE MSG_WARNING #
# INCLUDE MSG_SUCCESS #
# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	<section id="not-installed-modules-container" class="addons-container modules-elements-container not-installed-elements-container">
		<header><h1>{@modules.available_modules}</h1></header>
		# IF C_MODULE_AVAILABLE #
			<div class="cell-flex cell-columns-3 cell-tile">
				# START modules_not_installed #
					<article class="cell addon# IF NOT modules_not_installed.C_COMPATIBLE # not-compatible error# ENDIF #">
						<header class="cell-header">
							# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
								# IF modules_not_installed.C_COMPATIBLE #
									<div class="mini-checkbox">
										<label class="checkbox" for="multiple-checkbox-{modules_not_installed.MODULE_NUMBER}">
											<input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{modules_not_installed.MODULE_NUMBER}" name="add-checkbox-{modules_not_installed.MODULE_NUMBER}"/>
											<span>&nbsp;</span>
										</label>
									</div>
								# ENDIF #
							# ENDIF #
							<h3 class="cell-name">{modules_not_installed.NAME}</h3>
							<div class="addon-menu-container">
								# IF modules_not_installed.C_COMPATIBLE #
									<button type="submit" class="button submit addon-menu-title" name="add-{modules_not_installed.ID}" value="true">${LangLoader::get_message('install', 'admin-common')}</button>
								# ELSE #
									<span class="addon-menu-title error">${LangLoader::get_message('not_compatible', 'admin-common')}</span>
								# ENDIF #
							</div>
						</header>
						<div class="cell-list">
							<ul>
								<li class="li-stretch">
									<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_not_installed.ICON}/{modules_not_installed.ICON}.png" alt="{modules_not_installed.NAME}" />
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('version', 'admin')} :</span>
									{modules_not_installed.VERSION}
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
									<span# IF NOT modules_not_installed.C_COMPATIBLE # class="not-compatible error"# ENDIF #>PHPBoost {modules_not_installed.COMPATIBILITY}</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span>
									<span>
										# IF modules_not_installed.C_AUTHOR_EMAIL #<a href="mailto:{modules_not_installed.AUTHOR_EMAIL}">{modules_not_installed.AUTHOR}</a># ELSE #{modules_not_installed.AUTHOR}# ENDIF #
										# IF modules_not_installed.C_AUTHOR_WEBSITE #<a href="{modules_not_installed.AUTHOR_WEBSITE}" class="pinned bgc question">Web</a># ENDIF #</span>
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
							</ul>
						</div>

						<footer></footer>
					</article>
				# END modules_not_installed #
			</div>
		# ELSE #
			<div class="content">
				<div class="message-helper bgc notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
			</div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
		<div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
			<label class="checkbox" for="add-all-checkbox">
				<input type="checkbox" class="check-all" id="add-all-checkbox" name="add-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MODULES_NUMBER}, null, false);" />
				<span aria-label="{@modules.select_all_modules}">&nbsp;</span>
			</label>
			<button type="submit" name="add-selected-modules" value="true" class="button submit select-all-button">${LangLoader::get_message('multiple.install_selection', 'admin-common')}</button>
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
