# INCLUDE MSG_WARNING #
# INCLUDE MSG_SUCCESS #
# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	<section id="upgrade-modules-container">
		<header class="legend">{@modules.updates_available}</header>
		# IF C_UPDATES #
			<div class="cell-flex cell-columns-3 cell-tile">
				# START modules_upgradable #
					<article class="cell addon">
						<header class="cell-header">
							# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
								# IF modules_upgradable.C_COMPATIBLE #
									<div class="mini-checkbox">
										<label class="checkbox" for="multiple-checkbox-{modules_upgradable.MODULE_NUMBER}">
											<input type="checkbox" class="multiple-checkbox upgrade-checkbox" id="multiple-checkbox-{modules_upgradable.MODULE_NUMBER}" name="upgrade-checkbox-{modules_upgradable.MODULE_NUMBER}"/>
											<span>&nbsp;</span>
										</label>
									</div>
								# ENDIF #
							# ENDIF #
							<h3 class="cell-name">{modules_upgradable.NAME}</h3>
							<div class="addon-menu-container">
								# IF modules_upgradable.C_COMPATIBLE #
									<button type="submit" class="button submit addon-menu-title" name="upgrade-{modules_upgradable.ID}" value="true">{@modules.upgrade_module}</button>
								# ELSE #
									<span class="addon-menu-title">${LangLoader::get_message('not_compatible', 'admin-common')}</span>
								# ENDIF #
							</div>
						</header>
						<div class="cell-list">
							<ul>
								<li>
									<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_upgradable.ICON}/{modules_upgradable.ICON}.png" alt="{modules_upgradable.NAME}" />
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('version', 'admin')} :</span>
									{modules_upgradable.VERSION}
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
									<span# IF NOT modules_upgradable.C_COMPATIBLE # class="not-compatible"# ENDIF#>PHPBoost {modules_upgradable.COMPATIBILITY}</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span>
									<span># IF modules_upgradable.C_AUTHOR_EMAIL #<a href="mailto:{modules_upgradable.AUTHOR_EMAIL}">{modules_upgradable.AUTHOR}</a># ELSE #{modules_upgradable.AUTHOR}# ENDIF # # IF modules_upgradable.C_AUTHOR_WEBSITE #<a href="{modules_upgradable.AUTHOR_WEBSITE}" class="button alt-button smaller">Web</a># ENDIF #</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} :</span>
									{modules_upgradable.CREATION_DATE}
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('last_update', 'admin')} :</span>
									{modules_upgradable.LAST_UPDATE}
								</li>
								<li>
									<span class="text-strong">${LangLoader::get_message('description', 'main')} :</span>
									{modules_upgradable.DESCRIPTION}
								</li>
							</ul>
						</div>
						<footer></footer>
					</article>
				# END modules_upgradable #
			</div>
		# ELSE #
			<div class="content">
				<div class="message-helper bgc notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
			</div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
		<div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox">
			<label class="checkbox" for="upgrade-all-checkbox">
				<input type="checkbox" class="check-all" id="upgrade-all-checkbox" name="upgrade-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MODULES_NUMBER}, null, false);" aria-label="{@modules.select_all_modules}" />
				<span>&nbsp;</span>
			</label>
			<button type="submit" name="upgrade-selected-modules" value="true" class="select-all-button">${LangLoader::get_message('multiple.upgrade_selection', 'admin-common')}</button>
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
