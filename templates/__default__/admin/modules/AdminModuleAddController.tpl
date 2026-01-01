# INCLUDE MESSAGE_HELPER_WARNING #
# INCLUDE MESSAGE_HELPER_SUCCESS #
# INCLUDE CONTENT #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	<section id="not-installed-modules-container" class="addons-container modules-elements-container not-installed-elements-container">
		<header><h1>{@addon.modules.available}</h1></header>
		# IF C_MODULE_AVAILABLE #
			<div class="cell-flex cell-columns-3 cell-tile">
				# START modules_not_installed #
					<article class="cell addon# IF NOT modules_not_installed.C_COMPATIBLE # not-compatible error# ENDIF #">
						<header class="cell-header">
							# IF C_SEVERAL_MODULES_AVAILABLE #
								# IF modules_not_installed.C_COMPATIBLE #
									<div class="mini-checkbox">
										<label class="checkbox" for="multiple-checkbox-{modules_not_installed.MODULE_NUMBER}">
											<input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{modules_not_installed.MODULE_NUMBER}" name="add-checkbox-{modules_not_installed.MODULE_NUMBER}"/>
											<span>&nbsp;</span>
										</label>
									</div>
								# ENDIF #
							# ENDIF #
							<h3 class="cell-name">{modules_not_installed.MODULE_NAME}</h3>
							<div class="addon-menu-container">
								# IF modules_not_installed.C_COMPATIBLE #
									<button type="submit" class="button submit addon-menu-title" name="add-{modules_not_installed.MODULE_ID}" value="true">{@addon.install}</button>
								# ELSE #
									<span class="addon-menu-title bgc-full error">{@addon.not.compatible}</span>
								# ENDIF #
							</div>
						</header>
						<div class="cell-list">
							<ul>
								<li class="li-stretch">
									# IF modules_not_installed.C_THUMBNAIL #
										<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_not_installed.MODULE_ID}/{modules_not_installed.MODULE_ID}.png" alt="{modules_not_installed.MODULE_NAME}" />
									# ELSE #
										# IF modules_not_installed.C_FA_ICON #
											<i class="{modules_not_installed.FA_ICON} fa-2x"></i>
										# ELSE #
											# IF modules_not_installed.C_HEXA_ICON #
												<span class="hexa-icon bigger">{modules_not_installed.HEXA_ICON}</span>
											# ELSE #
												{@addon.modules.no.icon}
											# ENDIF #
										# ENDIF #
									# ENDIF #
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.version} :</span>
									{modules_not_installed.VERSION}
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@addon.compatibility} :</span>
									<span# IF NOT modules_not_installed.C_COMPATIBLE_VERSION # class="not-compatible bgc-full error"# ENDIF #>PHPBoost {modules_not_installed.COMPATIBILITY}</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.author} :</span>
									<span>
										{modules_not_installed.AUTHOR}
										# IF modules_not_installed.C_AUTHOR_EMAIL # <a href="mailto:{modules_not_installed.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
										# IF modules_not_installed.C_AUTHOR_WEBSITE # <a href="{modules_not_installed.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a> # ENDIF #
									</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.creation.date} :</span>
									{modules_not_installed.CREATION_DATE}
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.last.update} :</span>
									{modules_not_installed.LAST_UPDATE}
								</li>
								<li>
									<span class="text-strong">{@common.description} :</span>
									{modules_not_installed.DESCRIPTION}
								</li>
                                # IF NOT modules_not_installed.C_COMPATIBLE_ADDON #
                                    <li class="bgc-full error">{@addon.modules.not.module}</li>
                                # ENDIF #
							</ul>
						</div>

						<footer></footer>
					</article>
				# END modules_not_installed #
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
			<label class="checkbox" for="add-all-checkbox">
				<input type="checkbox" class="check-all" id="add-all-checkbox" name="add-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MODULES_NUMBER}, null, false);" />
				<span aria-label="{@addon.modules.select.all}">&nbsp;</span>
			</label>
			<button type="submit" name="add-selected-modules" value="true" class="button submit select-all-button">{@addon.multiple.install}</button>
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
