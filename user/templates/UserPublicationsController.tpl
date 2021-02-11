<section id="module-user-messages">
	<header class="section-header">
		<h1>{@user.publications}</h1>
	</header>
	<div class="sub-section">
		<article class="messages-item several-items">
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
								<a href="{user_publications.U_MODULE_LINK}">
									{user_publications.MODULE_NAME}
								</a>
							</div>
							<span class="pinned notice">{user_publications.PUBLICATIONS_NUMBER}</span>
						</div>
					</div>
				# END user_publications #
			</div>
		</article>
	</div>
	<footer></footer>
</section>
