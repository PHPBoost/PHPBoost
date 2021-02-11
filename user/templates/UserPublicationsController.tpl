<section id="module-user-messages">
	<header class="section-header">
		<h1>{@user.publications}</h1>
	</header>
	<div class="sub-section">
		<article class="messages-item several-items">
			<div class="content">
				<div class="cell-flex cell-tile cell-columns-3">
					# START user_publications #
						<div class="cell">
							<div class="cell-header">
								<div class="cell-name">
									<a href="{user_publications.U_LINK_USER_MSG}">
										# IF user_publications.C_IMG_USER_MSG #<i class="{user_publications.IMG_USER_MSG}" aria-hidden="true"></i># ENDIF #
										{user_publications.NAME_USER_MSG}
									</a>
								</div>
								<span class="pinned notice">{user_publications.MESSAGES_NUMBER}</span>
							</div>
						</div>
					# END user_publications #
				</div>
			</div>
		</article>
	</div>
	<footer></footer>
</section>
