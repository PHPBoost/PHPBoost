<section id="module-user-view-profile">
	<header class="section-header">
		<h1>{TITLE_PROFILE}</h1>
	</header>
	<div class="sub-section">
		<article class="user-profil-item several-items cell cell-tile">
			<div class="cell-list member-view-standard-fieldset">
				<ul>
					# IF C_DISPLAY_EDIT_LINK #
						<li class="li-stretch user-profile-edit">
							<strong>{@profile.edit}</strong>
							<span><a href="{U_EDIT_PROFILE}" aria-span="{@profile.edit}"><i class="far fa-edit" aria-hidden="true"></i></a></span>
						</li>
					# ENDIF #
					<li class="li-stretch user-profile-display-name">
						<strong>{@display_name}</strong>
						<span>{DISPLAY_NAME}</span>
					</li>
					<li class="li-stretch user-profile-level">
						<strong>{@level}</strong>
						<span><a class="{LEVEL_CLASS}"># IF NOT C_IS_BANNED #{LEVEL}# ELSE #{@banned}# ENDIF #</a></span>
					</li>
					<li class="li-stretch user-profile-groups">
						<strong>{@groups}</strong>
						<span>
							# IF C_GROUPS #
								<ul class="no-list user-profil-groups-container">
									# START groups #
										<li>
											<a href="{groups.U_GROUP}" class="user-group # IF groups.C_PICTURE #user-group-img # ENDIF #user-group-{groups.ID}"># IF groups.C_PICTURE #<img src="{groups.U_GROUP_PICTURE}" alt="{groups.NAME}" class="valign-middle" /># ELSE #{groups.NAME}# ENDIF #</a>
										</li>
									# END groups #
								</ul>
							# ELSE #
								${LangLoader::get_message('none', 'common')}
							# ENDIF #
						</span>
					</li>
					<li class="li-stretch user-profil-registered-date">
						<strong>{@registration_date}</strong>
						<span>{REGISTRATION_DATE}</span>
					</li>
					<li class="li-stretch user-profile-nbr-msg">
						<strong>{@number_messages}</strong>
						<span><a href="{U_DISPLAY_USER_MESSAGES}">{MESSAGES_NUMBER} {@messages}</a></span>
					</li>
					<li class="li-stretch user-profil-lastconnect">
						<strong>{@last_connection}</strong>
						<span>{LAST_CONNECTION_DATE}</span>
					</li>
					# IF C_DISPLAY_MAIL_LINK #
						<li class="li-stretch user-profil-email">
							<strong>{@email}</strong>
							<span><a href="mailto:{EMAIL}" class="button alt-button smaller">${LangLoader::get_message('mail', 'main')}</a></span>
						</li>
					# ENDIF #
					# IF C_DISPLAY_PM_LINK #
						<li class="li-stretch user-profil-private-msg">
							<strong>{@private_message}</strong>
							<span><a href="{U_DISPLAY_USER_PM}" class="button alt-button smaller">${LangLoader::get_message('pm', 'main')}</a></span>
						</li>
					# ENDIF #
				</ul>
			</div>
		</article>
		# IF C_EXTENDED_FIELDS #
			<article class="user-profil-item several-items cell cell-tile">
				<header class="cell-header"><h5>${LangLoader::get_message('other', 'main')}</h5></header>
				<div class="cell-list member-view-extand-fieldset">
					<ul>
						# START extended_fields #
							<li class="# IF extended_fields.C_AVATAR #li-stretch # ENDIF #user-profil-{extended_fields.REWRITED_NAME}">
								<strong>{extended_fields.NAME} : </strong>
								<span>{extended_fields.VALUE}</span>
							</li>
						# END extended_fields #
					</ul>
				</div>
			</article>
		# ENDIF #
	</div>
	<footer></footer>
</section>
