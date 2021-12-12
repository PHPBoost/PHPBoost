# INCLUDE MESSAGE_HELPER_WARNING #
# INCLUDE MESSAGE_HELPER_SUCCESS #
# INCLUDE CONTENT #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	<section id="upgrade-modules-container">
		<header class="legend">{@addon.modules.available.updates}</header>
		# IF C_UPDATES #
			<div class="cell-flex cell-columns-3 cell-tile">
				# START modules_upgradable #
					<article class="cell addon# IF NOT modules_upgradable.C_COMPATIBLE # not-compatible error# ENDIF#">
						<header class="cell-header">
							# IF C_SEVERAL_MODULES_AVAILABLE #
								# IF modules_upgradable.C_COMPATIBLE #
									<div class="mini-checkbox">
										<label class="checkbox" for="multiple-checkbox-{modules_upgradable.MODULE_NUMBER}">
											<input type="checkbox" class="multiple-checkbox upgrade-checkbox" id="multiple-checkbox-{modules_upgradable.MODULE_NUMBER}" name="upgrade-checkbox-{modules_upgradable.MODULE_NUMBER}"/>
											<span>&nbsp;</span>
										</label>
									</div>
								# ENDIF #
							# ENDIF #
							<h3 class="cell-name">{modules_upgradable.MODULE_NAME}</h3>
							<div class="addon-menu-container">
								# IF modules_upgradable.C_COMPATIBLE #
									<button type="submit" class="button submit addon-menu-title" name="upgrade-{modules_upgradable.MODULE_ID}" value="true">{@addon.modules.upgrade}</button>
								# ELSE #
									<span class="addon-menu-title">{@addon.not.compatible}</span>
								# ENDIF #
							</div>
						</header>
						<div class="cell-list">
							<ul>
								<li>
									<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_upgradable.MODULE_ID}/{modules_upgradable.MODULE_ID}.png" alt="{modules_upgradable.MODULE_NAME}" />
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.version} :</span>
									{modules_upgradable.VERSION}
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@addon.compatibility} :</span>
									<span# IF NOT modules_upgradable.C_COMPATIBLE # class="not-compatible error"# ENDIF#>PHPBoost {modules_upgradable.COMPATIBILITY}</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.author} :</span>
									<span># IF modules_upgradable.C_AUTHOR_EMAIL #<a href="mailto:{modules_upgradable.AUTHOR_EMAIL}">{modules_upgradable.AUTHOR}</a># ELSE #{modules_upgradable.AUTHOR}# ENDIF # # IF modules_upgradable.C_AUTHOR_WEBSITE #<a href="{modules_upgradable.AUTHOR_WEBSITE}" class="button alt-button small">Web</a># ENDIF #</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.creation.date} :</span>
									{modules_upgradable.CREATION_DATE}
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.last.update} :</span>
									{modules_upgradable.LAST_UPDATE}
								</li>
								<li>
									<span class="text-strong">{@common.description} :</span>
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
				<div class="message-helper bgc notice message-helper-small">{@common.no.item.now}</div>
			</div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_SEVERAL_MODULES_AVAILABLE #
		<div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
			<label class="checkbox" for="upgrade-all-checkbox">
				<input type="checkbox" class="check-all" id="upgrade-all-checkbox" name="upgrade-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MODULES_NUMBER}, null, false);" />
				<span aria-label="{@addon.modules.select.all}">&nbsp;</span>
			</label>
			<button type="submit" name="upgrade-selected-modules" value="true" class="button submit select-all-button">{@addon.multiple.upgrade}</button>
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
