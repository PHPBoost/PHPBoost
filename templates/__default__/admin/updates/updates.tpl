<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@admin.updates}
	</a>
	<ul>
		<li>
			<a href="updates.php" class="quick-link">{@admin.updates}</a>
		</li>
		<li>
			<a href="updates.php?type=kernel" class="quick-link">{@admin.kernel}</a>
		</li>
		<li>
			<a href="updates.php?type=module" class="quick-link">{@addon.modules}</a>
		</li>
		<li>
			<a href="updates.php?type=template" class="quick-link">{@addon.themes}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<fieldset>
		<legend>{@admin.updates}</legend>
		<div class="fieldset-inset">
		# IF C_INCOMPATIBLE_PHP_VERSION #
			<div class="message-helper bgc warning message-helper-small">{L_INCOMPATIBLE_PHP_VERSION}</div>
		# ELSE #
			# IF C_UPDATES #
				# START apps #
					<article>
						<header>
							<h2>{apps.NAME} {apps.VERSION}</h2>
						</header>
						<div class="content">
							<div class="infos options align-center alert-priority bgc {apps.PRIORITY_CSS_CLASS}">
								<p>{apps.L_PRIORITY}</p>
								<p><a href="{PATH_TO_ROOT}/admin/updates/detail.php?identifier={apps.IDENTIFIER}">[ {@admin.more.details} ]</a></p>
							</div>
							<p>{apps.SHORT_DESCRIPTION}</p>
						</div>
					</article>
				# END apps #
			# ELSE #
				# IF C_AUTOMATIC_UPDATE_CHECK_AVAILABLE #
					<div class="message-helper bgc success message-helper-small">{@admin.no.available.update}</div>
				# ELSE #
					<div class="message-helper bgc warning message-helper-small">{@H|admin.update.verification.impossible}</div>
				# ENDIF #
			# ENDIF #
			# IF C_AUTOMATIC_UPDATE_CHECK_AVAILABLE #
			<p class="align-center">
				<a href="{U_CHECK}" class="button link-color"><i class="fa fa-download" aria-hidden="true"></i> {@admin.updates.check}</a>
			</p>
			# ENDIF #
		# ENDIF #
		</div>
	</fieldset>
</div>
