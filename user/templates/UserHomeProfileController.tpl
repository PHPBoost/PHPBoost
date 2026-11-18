<section id="module-user-home">
	<header class="section-header">
		<h1>{@user.dashboard}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				<p class="align-center text-strong">{@user.welcome} {PSEUDO}</p>
				# IF C_AVATAR_IMG #
					<p class="align-center">
						<img src="{U_AVATAR_IMG}" alt="{@user.avatar}" />
					</p>
				# ENDIF #
			</div>

			<div id="profile-container" class="cell-flex cell-tile cell-columns-3">
				<article class="dashboard-item several-items cell">
					<div class="cell-body">
						<div class="cell-content align-center">
							<a class="offload" href="{U_VIEW_PROFILE}">
								<i class="fa fa-user fa-2x" aria-hidden="true"></i>
								<span class="d-block">{@user.my.profile}</span>
							</a>
						</div>
					</div>
				</article>
				<article class="dashboard-item several-items cell">
					<div class="cell-body">
						<div class="cell-content align-center">
							<a class="offload" href="{U_USER_PM}">
								# IF C_HAS_PM #
									<span class="stacked blink">
										<i class="fa fa-people-arrows fa-2x" aria-hidden="true"></i>
										<span class="stack-event stack-right stack-circle bgc-full error text-strong">{NUMBER_PM}</span>
									</span>
								# ELSE #
									<i class="fa fa-people-arrows fa-2x"></i>
								# END IF #
								<span class="d-block">{@user.private.message}</span>
							</a>
						</div>
					</div>
				</article>
				# IF C_USER_AUTH_FILES #
					<article class="dashboard-item several-items cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a class="offload" href="{U_UPLOAD}">
									<i class="fa fa-cloud-upload-alt fa-2x" aria-hidden="true"></i>
									<span class="d-block">{@upload.files.management}</span>
								</a>
							</div>
						</div>
					</article>
				# ENDIF #
				# IF IS_ADMIN #
					<article class="dashboard-item several-items cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a class="offload" href="{PATH_TO_ROOT}/admin/">
									# IF C_UNREAD_ALERT #
										<span class="stacked blink">
											<i class="fa fa-wrench fa-2x" aria-hidden="true"></i>
											<span class="stack-event stack-right stack-circle bgc-full error text-strong">{NUMBER_UNREAD_ALERTS}</span>
										</span>
									# ELSE #
										<i class="fa fa-wrench fa-2x" aria-hidden="true"></i>
									# ENDIF #
									<span class="d-block">{@user.admin.panel}</span>
								</a>
							</div>
						</div>
					</article>
				# ENDIF #
				# IF IS_MODERATOR #
					<article class="dashboard-item several-items cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a class="offload" href="{U_MODERATION_PANEL}">
									<i class="fa fa-gavel fa-2x" aria-hidden="true"></i>
									<span class="d-block">{@user.moderation.panel}</span>
								</a>
							</div>
						</div>
					</article>
				# ENDIF #
				<article class="dashboard-item several-items cell">
					<div class="cell-body">
						<div class="cell-content align-center">
							<a class="offload" href="{U_CONTRIBUTION_PANEL}">
								# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
									<span class="stacked blink">
										<i class="fa fa-file-alt fa-2x" aria-hidden="true"></i>
										<span class="stack-event stack-right stack-circle bgc-full error text-strong">{NUMBER_UNREAD_CONTRIBUTIONS}</span>
									</span>
								# ELSE #
									<i class="fa fa-file-alt fa-2x" aria-hidden="true"></i>
								# ENDIF #
								<span class="d-block">{@user.contribution.panel}</span>
							</a>
						</div>
					</div>
				</article>
				<article class="dashboard-item several-items cell">
					<div class="cell-body">
						<div class="cell-content align-center">
							<a class="offload" href="${relative_url(UserUrlBuilder::disconnect())}">
								<i class="fa fa-sign-out-alt fa-2x" aria-hidden="true"></i>
								<span class="d-block">{@user.sign.out}</span>
							</a>
						</div>
					</div>
				</article>
			</div>
			<div class="content">
				{MSG_MBR}
			</div>
			<hr />
			<div class="content">
				<h2>{@user.my.publications}</h2>
			</div>
			<div id="user-publications" class="cell-flex cell-tile cell-columns-3">
				# START user_publications #
					<article class="dashboard-item several-items cell">
						<div class="cell-body">
							<div class="cell-content align-center">
								<a class="offload" href="{user_publications.U_MODULE_VIEW}">
									# IF user_publications.C_ICON_IS_PICTURE #
										<img src="{user_publications.MODULE_THUMBNAIL}" alt="{user_publications.MODULE_NAME}">
									# ELSE #
										<i class="{user_publications.MODULE_THUMBNAIL} fa-2x" aria-hidden="true"></i>
									# ENDIF #
									<span class="d-block">{user_publications.MODULE_NAME} : {user_publications.PUBLICATIONS_NUMBER}</span>
								</a>
							</div>
						</div>
					</article>
				# END user_publications #
			</div>
		</div>
	</div>
	<footer></footer>
</section>
