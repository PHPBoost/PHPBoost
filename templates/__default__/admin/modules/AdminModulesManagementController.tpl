# INCLUDE MESSAGE_HELPER #
# START errors #
	# INCLUDE errors.MESSAGE_HELPER #
# END errors #

<div class="text-helper">
	<span class="message-helper bgc warning">{@H|addon.modules.warning.delete}</span>
	<span class="message-helper bgc notice">{@addon.modules.warning.install}</span>
</div>
<form action="{REWRITED_SCRIPT}" method="post">
	<section id="installed-modules-container">
		<header class="legend">{@addon.modules.installed}</header>
		<div class="cell-flex cell-columns-3 cell-tile">
			# START modules_installed #
				<article class="cell addon# IF NOT modules_installed.C_IS_ACTIVATED # disabled-addon# ENDIF ## IF NOT modules_installed.C_COMPATIBLE # not-compatible error# ENDIF #">
					<header class="cell-header">
						# IF C_SEVERAL_MODULES_INSTALLED #
							# IF modules_installed.C_COMPATIBLE #
								<div class="mini-checkbox">
									<label class="checkbox" for="multiple-checkbox-{modules_installed.MODULE_NUMBER}">
										<input type="checkbox" id="multiple-checkbox-{modules_installed.MODULE_NUMBER}" name="delete-checkbox-{modules_installed.MODULE_NUMBER}"/>
										<span>&nbsp;</span>
									</label>
								</div>
							# ENDIF #
						# ENDIF #
						<h3 class="cell-name">{modules_installed.MODULE_NAME}</h3>
						<div class="addon-menu-container addon-with-menu">
							<a href="#" id="addon-menu-title-{modules_installed.MODULE_NUMBER}" class="addon-menu-title bgc-full # IF modules_installed.C_COMPATIBLE #link-color# ELSE #error# ENDIF #">
								# IF modules_installed.C_COMPATIBLE #
									# IF modules_installed.C_IS_ACTIVATED #
										{@common.actions}
									# ELSE #
										{@common.disabled}
									# ENDIF #
								# ELSE #
									{@addon.not.compatible}
								# ENDIF #
							</a>
							<ul class="addon-menu-content">
								# IF modules_installed.C_COMPATIBLE #
									# IF modules_installed.C_IS_ACTIVATED #
										<li class="addon-menu-item"><button type="submit" class="button submit" name="disable-{modules_installed.MODULE_ID}" value="true">{@common.disable}</button></li>
									# ELSE #
										<li class="addon-menu-item"><button type="submit" class="button submit" name="enable-{modules_installed.MODULE_ID}" value="true">{@common.enable}</button></li>
									# ENDIF #
								# ENDIF #
								<li class="addon-menu-item"><button type="submit" class="button alt-submit" name="delete-{modules_installed.MODULE_ID}" value="true">{@addon.uninstall}</button></li>
							</ul>
						</div>
					</header>
					<div class="cell-list">
						<ul>
							<li class="li-stretch">
								# IF modules_installed.C_THUMBNAIL #
									<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_installed.MODULE_ID}/{modules_installed.MODULE_ID}.png" alt="{modules_installed.MODULE_NAME}" />
								# ELSE #
									# IF modules_installed.C_FA_ICON #
										<i class="{modules_installed.FA_ICON} fa-2x"></i>
									# ELSE #
										# IF modules_installed.C_HEXA_ICON #
											<span class="hexa-icon bigger">{modules_installed.HEXA_ICON}</span>
										# ELSE #
											{@addon.modules.no.icon}
										# ENDIF #
									# ENDIF #
								# ENDIF #
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@common.version} :</span>
								{modules_installed.VERSION}
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@common.author} :</span>
								<span>
									{modules_installed.AUTHOR}
									# IF modules_installed.C_AUTHOR_EMAIL # <a href="mailto:{modules_installed.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
									# IF modules_installed.C_AUTHOR_WEBSITE # <a href="{modules_installed.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a> # ENDIF #
								</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@common.creation.date} :</span>
								{modules_installed.CREATION_DATE}
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@common.last.update} :</span>
								{modules_installed.LAST_UPDATE}
							</li>
							<li>
								<span class="text-strong">{@common.description} :</span>
								{modules_installed.DESCRIPTION}
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@addon.compatibility} :</span>
								<span# IF NOT modules_installed.C_COMPATIBLE_VERSION # class="not-compatible bgc-full error"# ENDIF #>PHPBoost {modules_installed.COMPATIBILITY}</span>
							</li>
							<li class="li-stretch">
								<span class="text-strong">{@addon.modules.php.version} :</span>
								{modules_installed.PHP_VERSION}
							</li>
                            # IF NOT modules_installed.C_COMPATIBLE_ADDON #
                                <li class="bgc-full error">{@addon.modules.not.module}</li>
                            # ENDIF #
						</ul>
					</div>
					# IF modules_installed.C_DOCUMENTATION #
						<div class="cell-footer align-center">
							<a class="pinned bgc moderator" href="{modules_installed.U_DOCUMENTATION}"><i class="fa fa-book-reader"></i> {@addon.modules.documentation}</a>
						</div>
					# ENDIF #
				</article>
				<script>
					jQuery('#addon-menu-title-{modules_installed.MODULE_NUMBER}').opensubmenu({
						osmTarget: '.addon-menu-container'
					});
				</script>
			# END modules_installed #
		</div>
		<footer>
			<input type="hidden" name="token" value="{TOKEN}">
		</footer>
	</section>

	# IF C_SEVERAL_MODULES_INSTALLED #
	<div class="addon-menu-container multiple-select-menu-container">
		<a href="#" class="multiple-select-menu addon-menu-title bgc-full link-color">{@addon.multiple.select}</a>
		<ul class="addon-menu-content">
			<li class="addon-menu-checkbox mini-checkbox select-all-checkbox bgc-full link-color">
				<label class="checkbox" for="delete-all-checkbox">
					<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MODULES_NUMBER}, null, false);" />
					<span aria-label="{@addon.modules.select.all}">&nbsp;</span>
				</label>
			</li>
			<li class="addon-menu-item"><button type="submit" name="activate-selected-modules" value="true" class="button submit" id="activate-all-button">{@addon.multiple.enable}</button></li>
			<li class="addon-menu-item"><button type="submit" name="deactivate-selected-modules" value="true" class="button submit" id="deactivate-all-button">{@addon.multiple.disable}</button></li>
			<li class="addon-menu-item"><button type="submit" name="delete-selected-modules" value="true" class="button alt-submit" id="delete-all-button">{@addon.multiple.uninstall}</button></li>
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
