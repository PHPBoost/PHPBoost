# INCLUDE MESSAGE_HELPER #
# INCLUDE CONTENT #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	<section id="not-installed-langs-container" class="addons-container langs-elements-container not-installed-elements-container">
		<header class="legend">{@addon.langs.available}</header>
		# IF C_LANG_AVAILABLE #
			<div class="cell-flex cell-columns-3 cell-tile">
				# START langs_not_installed #
					<article class="cell addon# IF NOT langs_not_installed.C_COMPATIBLE # not-compatible error# ENDIF #">
						<header class="cell-header">
							# IF C_SEVERAL_LANGS_AVAILABLE #
								# IF langs_not_installed.C_COMPATIBLE #
									<div class="mini-checkbox">
										<label class="checkbox" for="multiple-checkbox-{langs_not_installed.LANG_NUMBER}">
											<input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{langs_not_installed.LANG_NUMBER}" name="add-checkbox-{langs_not_installed.LANG_NUMBER}"/>
											<span>&nbsp;</span>
										</label>
									</div>
								# ENDIF #
							# ENDIF #
							# IF langs_not_installed.C_HAS_THUMBNAIL #
								<img src="{langs_not_installed.U_THUMBNAIL}" alt="{langs_not_installed.NAME}" class="flag-icon" />
							# ENDIF #
							<h3 class="cell-name">
								{langs_not_installed.NAME}
							</h3>
							<div class="addon-menu-container">
								# IF langs_not_installed.C_COMPATIBLE #
									<button type="submit" class="button submit addon-menu-title" name="add-{langs_not_installed.ID}" value="true">{@addon.install}</button>
								# ELSE #
									<span class="addon-menu-title">{@addon.not.compatible}</span>
								# ENDIF #
							</div>
						</header>

						<div class="cell-list">
							<ul>
								<li class="li-stretch">
									<span class="text-strong">{@common.version} :</span>
									{langs_not_installed.VERSION}
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@addon.compatibility} :</span>
									<span# IF NOT langs_not_installed.C_COMPATIBLE # class="not-compatible error"# ENDIF #>PHPBoost {langs_not_installed.COMPATIBILITY}</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">{@common.author} :</span>
									<span>
										{langs_not_installed.AUTHOR}
										# IF langs_not_installed.C_AUTHOR_EMAIL # <a href="mailto:{langs_not_installed.AUTHOR_EMAIL}" class="pinned bgc notice" aria-label="{@common.email}"><i class="fa iboost fa-iboost-email fa-fw" aria-hidden="true"></i></a># ENDIF #
										# IF langs_not_installed.C_AUTHOR_WEBSITE # <a href="{langs_not_installed.AUTHOR_WEBSITE}" class="pinned bgc question" aria-label="{@common.website}"><i class="fa fa-share-square fa-fw" aria-hidden="true"></i></a> # ENDIF #
									</span>
								</li>
							</ul>
						</div>

						<footer class="cell-footer">
							# IF langs_not_installed.C_COMPATIBLE #
								<div class="addon-auth-container">
									<a href="#" class="addon-auth" aria-label="{@addon.authorizations}"><i class="fa fa-user-shield" aria-hidden="true"></i></a>
									<div class="addon-auth-content">
										{langs_not_installed.AUTHORIZATIONS}
										<a href="#" class="addon-auth-close" aria-label="{@common.close}"><i class="fa fa-times" aria-hidden="true"></i></a>
									</div>
								</div>
							# ENDIF #
						</footer>
					</article>
				# END langs_not_installed #
			</div>
		# ELSE #
			<div class="content">
				<div class="message-helper bgc notice message-helper-small">{@common.no.item.now}</div>
			</div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_SEVERAL_LANGS_AVAILABLE #
		<div class="multiple-select-button select-all-checkbox mini-checkbox inline-checkbox bgc-full link-color">
			<label class="checkbox" for="add-all-checkbox">
				<input type="checkbox" class="check-all" id="add-all-checkbox" name="add-all-checkbox" onclick="multiple_checkbox_check(this.checked, {LANGS_NUMBER}, null, false);" />
				<span aria-label="{@addon.langs.select.all}">&nbsp;</span>
			</label>
			<button type="submit" name="add-selected-langs" value="true" class="button submit select-all-button">{@addon.multiple.install}</button>
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
