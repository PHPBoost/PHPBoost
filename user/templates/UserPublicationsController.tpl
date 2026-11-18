<section id="module-user-messages">
	<header class="section-header">
		<h1># IF C_CURRENT_USER #{@user.my.publications}# ELSE #{@user.publications}# ENDIF #</h1>
		# IF NOT C_CURRENT_USER #<h2 class="align-center">{USER_NAME}</h2># ENDIF #
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="cell-flex cell-tile cell-columns-3">
				# START user_publications #
					<div class="cell">
						<div class="cell-header">
							# IF user_publications.C_ICON_IS_PICTURE #
								<img src="{user_publications.MODULE_THUMBNAIL}" alt="{user_publications.MODULE_NAME}">
							# ELSE #
								<i class="{user_publications.MODULE_THUMBNAIL}" aria-hidden="true"></i>
							# ENDIF #
							<div class="cell-name">
								<a class="offload" href="{user_publications.U_MODULE_VIEW}">
									{user_publications.MODULE_NAME}
								</a>
							</div>
							<span class="pinned notice">{user_publications.PUBLICATIONS_NUMBER}</span>
						</div>
					</div>
				# END user_publications #
			</div>
		</div>
	</div>
	<footer></footer>
</section>
