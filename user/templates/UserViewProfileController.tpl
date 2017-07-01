<fieldset id="member-view-profile_profile">
	<legend>{@profile}</legend>
	<div class="fieldset-inset">
		# IF C_DISPLAY_EDIT_LINK #
		<div id="member-view-profile_profile_edit_field" class="form-element">
			<label>{@profile.edit}</label>
			<div id="view-profile_profile_edit" class="form-field form-field-free">
				<a href="{U_EDIT_PROFILE}" title="{@profile.edit}" class="fa fa-edit"></a>
			</div>
		</div>
		# ENDIF #
		<div id="member-view-profile_display_name_field" class="form-element">
			<label>{@display_name}</label>
			<div id="view-profile_display_name" class="form-field form-field-free">
				{DISPLAY_NAME}
			</div>
		</div>
		<div id="member-view-profile_level_field" class="form-element">
			<label>{@level}</label>
			<div id="view-profile_level" class="form-field form-field-free">
				<a class="{LEVEL_CLASS}"># IF NOT C_IS_BANNED #{LEVEL}# ELSE #{@banned}# ENDIF #</a>
			</div>
		</div>
		<div id="member-view-profile_groups_field" class="form-element">
			<label>{@groups}</label>
			<div id="view-profile_groups" class="form-field form-field-free">
				# IF C_GROUPS #
				<ul class="no-list">
					# START groups #
					<li>
						<a href="{groups.U_GROUP}" class="user-group # IF groups.C_PICTURE #user-group-img # ENDIF #user-group-{groups.ID}"># IF groups.C_PICTURE #<img src="{groups.U_GROUP_PICTURE}" alt="{groups.NAME}" title="{groups.NAME}" class="valign-middle" /># ELSE #{groups.NAME}# ENDIF #</a>
					</li>
					# END groups #
				</ul>
				# ELSE #
				${LangLoader::get_message('none', 'common')}
				# ENDIF #
			</div>
		</div>
		<div id="member-view-profile_registered_on_field" class="form-element">
			<label>{@registration_date}</label>
			<div id="view-profile_registered_on" class="form-field form-field-free">
				{REGISTRATION_DATE}
			</div>
		</div>
		<div id="member-view-profile_nbr_msg_field" class="form-element">
			<label>{@number_messages}</label>
			<div id="view-profile_nbr_msg" class="form-field form-field-free">
				{MESSAGES_NUMBER}<br />
				<a href="{U_DISPLAY_USER_MESSAGES}">{messages}</a>
			</div>
		</div>
		<div id="member-view-profile_last_connect_field" class="form-element">
			<label>{@last_connection}</label>
			<div id="view-profile_last_connect" class="form-field form-field-free">
				{LAST_CONNECTION_DATE}
			</div>
		</div>
		# IF C_DISPLAY_MAIL_LINK #
		<div id="member-view-profile_email_field" class="form-element">
			<label>{@email}</label>
			<div id="view-profile_email" class="form-field form-field-free">
				<a href="mailto:{EMAIL}" class="basic-button smaller">${LangLoader::get_message('mail', 'main')}</a>
			</div>
		</div>
		# ENDIF #
		# IF C_DISPLAY_PM_LINK #
		<div id="member-view-profile_private_message_field" class="form-element">
			<label>{@private_message}</label>
			<div id="view-profile_private_message" class="form-field form-field-free">
				<a href="{U_DISPLAY_USER_PM}" class="basic-button smaller">${LangLoader::get_message('pm', 'main')}</a>
			</div>
		</div>
		# ENDIF #
	</div>
</fieldset>
# INCLUDE FORM #