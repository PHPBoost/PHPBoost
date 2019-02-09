<nav id="admin-quick-menu">
	<a href="" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true" title="{L_WEBSITE_UPDATES}"></i> {L_WEBSITE_UPDATES}
	</a>
	<ul>
		<li>
			<a href="updates.php" class="quick-link">{L_WEBSITE_UPDATES}</a>
		</li>
		<li>
			<a href="updates.php?type=kernel" class="quick-link">{L_KERNEL}</a>
		</li>
		<li>
			<a href="updates.php?type=module" class="quick-link">{L_MODULES}</a>
		</li>
		<li>
			<a href="updates.php?type=template" class="quick-link">{L_THEMES}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">
	<fieldset>
		<legend><h1>{L_WEBSITE_UPDATES}</h1></legend>
		<div class="fieldset-inset">
		# IF C_INCOMPATIBLE_PHP_VERSION #
			<div class="message-helper warning message-helper-small">{L_INCOMPATIBLE_PHP_VERSION}</div>
		# ELSE #
			# IF C_UPDATES #
				# START apps #
				<article>
					<header>
						<h2>{apps.name} {apps.version}</h2>
					</header>
					<div class="infos options error">
						{apps.L_PRIORITY}
					</div>
					<div class="content">
						<p>{apps.short_description}</p>
						<a href="{PATH_TO_ROOT}/admin/updates/detail.php?identifier={apps.identifier}">[ {L_MORE_DETAILS} ]</a>
					</div>
				</article>
				# END apps #
			# ELSE #
				<div class="message-helper success message-helper-small">{L_NO_AVAILABLES_UPDATES}</div>
			# ENDIF #
			<p class="center">
				<a href="{U_CHECK}"><i class="fa fa-download"></i></a> <a href="{U_CHECK}">{L_CHECK_FOR_UPDATES_NOW}</a>
			</p>
		# ENDIF #
		</div>
	</fieldset>
</div>
