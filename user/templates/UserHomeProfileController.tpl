<section id="module-user-home">
	<header>
		<h1>{@dashboard}</h1>
	</header>
	<div class="content">
		<p class="align-center text-strong">{@welcome} {PSEUDO}</p>
		<p class="align-center">
			# IF C_AVATAR_IMG #
				<img src="{U_AVATAR_IMG}" alt="{@avatar}" />
			# ELSE #
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" alt="{@avatar}" />
			# ENDIF #
		</p>

		<ul id="profile-container" class="cell-flex cell-tile cell-columns-3">
			<li class="cell">
				<a href="{U_VIEW_PROFILE}"><i class="fa fa-user fa-2x" aria-hidden="true"></i> <span class="profile-element-title">${LangLoader::get_message('my_private_profile', 'main')}</span></a>
			</li>
			<li class="cell">
				<a href="{U_USER_PM}">
					# IF C_HAS_PM #
						<span class="stacked blink">
							<i class="fa fa-envelope fa-2x" aria-hidden="true"></i>
							<span class="stack-event stack-right stack-circle bgc-full error text-strong">{NUMBER_PM}</span>
						</span>
					# ELSE #
						<i class="fa fa-envelope fa-2x"></i>
					# END IF #
					<span class="profile-element-title">${LangLoader::get_message('private_message', 'main')}</span>
				</a>
			</li>
			# IF C_USER_AUTH_FILES #
				<li class="cell">
					<a href="{U_UPLOAD}">
						<i class="fa fa-cloud-upload-alt fa-2x" aria-hidden="true"></i><span class="profile-element-title">${LangLoader::get_message('files_management', 'main')}</span>
					</a>
				</li>
			# ENDIF #
			# IF IS_ADMIN #
				<li class="cell">
					<a href="{PATH_TO_ROOT}/admin/">
						# IF C_UNREAD_ALERT #
							<span class="stacked blink">
								<i class="fa fa-wrench fa-2x" aria-hidden="true"></i>
								<span class="stack-event stack-right stack-circle bgc-full error text-strong">{NUMBER_UNREAD_ALERTS}</span>
							</span>
						# ELSE #
							<i class="fa fa-wrench fa-2x" aria-hidden="true"></i>
						# ENDIF #
						<span class="profile-element-title">${LangLoader::get_message('admin_panel', 'main')}</span>
					</a>
				</li>
			# ENDIF #
			# IF C_IS_MODERATOR #
				<li class="cell">
					<a href="{U_MODERATION_PANEL}">
						<i class="fa fa-gavel fa-2x" aria-hidden="true"></i><span class="profile-element-title">${LangLoader::get_message('moderation_panel', 'main')}</span>
					</a>
				</li>
			# ENDIF #
			<li class="cell">
				<a href="{U_CONTRIBUTION_PANEL}">
					# IF C_KNOWN_NUMBER_OF_UNREAD_CONTRIBUTION #
						<span class="stacked blink">
							<i class="fa fa-file-alt fa-2x" aria-hidden="true"></i>
							<span class="stack-event stack-right stack-circle bgc-full error text-strong">{NUMBER_UNREAD_CONTRIBUTIONS}</span>
						</span>
					# ELSE #
						<i class="fa fa-file-alt fa-2x" aria-hidden="true"></i>
					# ENDIF #
					<span class="profile-element-title">${LangLoader::get_message('contribution_panel', 'main')}</span>
				</a>
			</li>
			# START modules_messages #
				<li class="cell">
					<a href="{modules_messages.U_LINK_USER_MSG}">
						# IF modules_messages.C_IMG_USER_MSG #<i class="{modules_messages.IMG_USER_MSG} fa-2x" aria-hidden="true"></i># ENDIF #
						<span class="profile-element-title">{modules_messages.NAME_USER_MSG} : {modules_messages.NUMBER_MESSAGES}</span>
					</a>
				</li>
			# END modules_messages #
			<li class="cell">
				<a href="${relative_url(UserUrlBuilder::disconnect())}">
					<i class="fa fa-sign-out-alt fa-2x" aria-hidden="true"></i>
					<span class="profile-element-title">{@disconnect}</span>
				</a>
			</li>
		</ul>
		<div class="spacer"></div>
		{MSG_MBR}
	</div>
	<footer></footer>
</section>
