<section id="module-user-view-profile">
	<header>
		<h1>{TITLE_PROFILE}</h1>
	</header>
	<article>
		<fieldset class="member-view-standard-fieldset">
			<div class="fieldset-inset">
				# IF C_DISPLAY_EDIT_LINK #
				<div class="form-element user-profile-edit">
					<label>{@profile.edit}</label>
					<div class="form-field form-field-free">
						<a href="{U_EDIT_PROFILE}" aria-label="{@profile.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
					</div>
				</div>
				# ENDIF #
				<div class="form-element user-profile-display-name">
					<label>{@display_name}</label>
					<div class="form-field form-field-free">
						{DISPLAY_NAME}
					</div>
				</div>
				<div class="form-element user-profile-level">
					<label>{@level}</label>
					<div class="form-field form-field-free">
						<a class="{LEVEL_CLASS}"># IF NOT C_IS_BANNED #{LEVEL}# ELSE #{@banned}# ENDIF #</a>
					</div>
				</div>
				<div class="form-element user-profile-groups">
					<label>{@groups}</label>
					<div class="form-field form-field-free">
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
					</div>
				</div>
				<div class="form-element user-profil-registered-date">
					<label>{@registration_date}</label>
					<div class="form-field form-field-free">
						{REGISTRATION_DATE}
					</div>
				</div>
				<div class="form-element user-profile-nbr-msg">
					<label>{@number_messages}</label>
					<div class="form-field form-field-free">
						<span>{MESSAGES_NUMBER}</span>
						<a href="{U_DISPLAY_USER_MESSAGES}">{@messages}</a>
					</div>
				</div>
				<div class="form-element user-profil-lastconnect">
					<label>{@last_connection}</label>
					<div class="form-field form-field-free">
						{LAST_CONNECTION_DATE}
					</div>
				</div>
				# IF C_DISPLAY_MAIL_LINK #
				<div class="form-element user-profil-email">
					<label>{@email}</label>
					<div class="form-field form-field-free">
						<a href="mailto:{EMAIL}" class="button alt-button smaller">${LangLoader::get_message('mail', 'main')}</a>
					</div>
				</div>
				# ENDIF #
				# IF C_DISPLAY_PM_LINK #
				<div class="form-element user-profil-private-msg">
					<label>{@private_message}</label>
					<div class="form-field form-field-free">
						<a href="{U_DISPLAY_USER_PM}" class="button alt-button smaller">${LangLoader::get_message('pm', 'main')}</a>
					</div>
				</div>
				# ENDIF #
			</div>
		</fieldset>
		# IF C_EXTENDED_FIELDS #
		<fieldset class="member-view-extand-fieldset">
			<legend>${LangLoader::get_message('other', 'main')}</legend>
			<div class="fieldset-inset">
				# START extended_fields #
				<div class="form-element user-profil-{extended_fields.REWRITED_NAME}">
					<label>{extended_fields.NAME}</label>
					<div class="form-field form-field-free">
						{extended_fields.VALUE}
					</div>
				</div>
				# END extended_fields #
			</div>
		</fieldset>
		# ENDIF #

	</article>
	<footer></footer>
</section>
