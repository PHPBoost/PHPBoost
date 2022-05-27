<section id="module-user-view-profile">
	<header class="section-header">
		<h1>{TITLE_PROFILE}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<article class="user-profile-item several-items cell cell-tile">
				<div class="cell-list member-view-standard-fieldset">
					<ul>
						# IF C_DISPLAY_EDIT_LINK #
							<li class="li-stretch user-profile-edit">
								<strong>{@user.profile.edit}</strong>
								<a class="offload" href="{U_EDIT_PROFILE}" aria-label="{@user.profile.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
							</li>
						# ENDIF #
						<li class="li-stretch user-profile-display-name">
							<strong>{@user.display.name}</strong>
							<span>{DISPLAY_NAME}</span>
						</li>
						<li class="li-stretch user-profile-level">
							<strong>{@user.level}</strong>
							<span class="{LEVEL_CLASS}"># IF NOT C_IS_BANNED #{LEVEL}# ELSE #{@user.banned}# ENDIF #</span>
						</li>
						<li class="li-stretch user-profile-groups">
							<strong>{@user.groups}</strong>
							<span>
								# IF C_GROUPS #
									<ul class="no-list user-profile-groups-container">
										# START groups #
											<li>
												<a href="{groups.U_GROUP}" class="user-group offload # IF groups.C_PICTURE #user-group-img # ENDIF #user-group-{groups.ID}"># IF groups.C_PICTURE #<img src="{groups.U_GROUP_PICTURE}" alt="{groups.NAME}" class="valign-middle" /># ELSE #{groups.NAME}# ENDIF #</a>
											</li>
										# END groups #
									</ul>
								# ELSE #
									{@common.none}
								# ENDIF #
							</span>
						</li>
						<li class="li-stretch user-profile-registered-date">
							<strong>{@user.registration.date}</strong>
							<span>{REGISTRATION_DATE}</span>
						</li>
						<li class="li-stretch user-profile-nbr-msg">
							<strong>{@user.publications}</strong>
							<a class="offload" href="{U_USER_PUBLICATIONS}" class="button bgc-full link-color small">{PUBLICATIONS_NUMBER}</a>
						</li>
						<li class="li-stretch user-profile-lastconnect">
							<strong>{@user.last.connection}</strong>
							<span>{LAST_CONNECTION_DATE}</span>
						</li>
						# IF C_DISPLAY_MAIL_LINK #
							<li class="li-stretch user-profile-email">
								<strong>{@user.email}</strong>
								<a href="mailto:{EMAIL}" class="button bgc visitor small" aria-label="{@user.email}"><i class="fa iboost fa-iboost-email fa-lg" aria-hidden="true"></i></a>
							</li>
						# ENDIF #
						# IF C_DISPLAY_PM_LINK #
							<li class="li-stretch user-profile-private-message">
								<strong>{@user.private.message}</strong>
								<a class="offload" href="{U_DISPLAY_USER_PM}" class="button bgc visitor small" aria-label="{@user.private.message}"><i class="fa fa-people-arrows fa-lg" aria-hidden="true"></i></a>
							</li>
						# ENDIF #
					</ul>
				</div>
			</article>
			# IF C_DISPLAY_OTHER_INFORMATIONS #
				<article class="user-profile-item several-items cell cell-tile">
					<header class="cell-header"><h5>{@common.other}</h5></header>
					<div class="cell-list member-view-extand-fieldset">
						<ul>
							# START extended_fields #
								<li class="# IF extended_fields.C_AVATAR #li-stretch # ENDIF #user-profile-{extended_fields.REWRITED_NAME}">
									<strong>{extended_fields.NAME}</strong>
									<span>{extended_fields.VALUE}</span>
								</li>
							# END extended_fields #
							# START additional_informations #
								<li>
									<span>{additional_informations.VALUE}</span>
								</li>
							# END additional_informations #
						</ul>
					</div>
				</article>
			# ENDIF #
		</div>
	</div>
	<footer></footer>
</section>
