<section id="module-guestbook">
	<header>
		<h1>{@guestbook.module.title}</h1>
	</header>
	<div class="content">
		# INCLUDE MSG #
		<div class="modal-container">
			<div class="right">
				<button class="modal-btn" data-trigger data-target="guestbook-add-message">{@guestbook.add}</button>
			</div>
			<div id="guestbook-add-message" class="modal modal-animation">
				<div class="close-modal" aria-label="${LangLoader::get_message('close', 'main')}"></div>
				<div class="content-panel"># INCLUDE FORM #</div>
			</div>
		</div>
		# IF C_PAGINATION #
			<div class="center"># INCLUDE PAGINATION #</div>
			<div class="spacer"></div>
		# ENDIF #
		# IF C_NO_MESSAGE #
			<div class="message-helper notice message-helper-small center">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		# START messages #
			<article id="m{messages.ID}" class="guestbook-item guestbook-items message# IF messages.C_CURRENT_USER_MESSAGE # current-user-color# ENDIF #">
				<header class="message-header-container">
					# IF messages.C_AVATAR #<img src="{messages.U_AVATAR}" alt="${LangLoader::get_message('avatar', 'user-common')}" class="message-avatar" /># ENDIF #
					<div class="message-header-infos">
						<div class="message-user">
							<h2>
								<span class="message-pseudo">
									# IF messages.C_AUTHOR_EXIST #
										<a href="{messages.U_AUTHOR_PROFILE}" class="{messages.USER_LEVEL_CLASS}" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>{messages.PSEUDO}</a>
									# ELSE #
										{messages.PSEUDO}
									# ENDIF #
								</span>
							</h2>
							<span>
								# IF messages.C_EDIT #
									<a href="{messages.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
								# ENDIF #
								# IF messages.C_DELETE #
									<a href="{messages.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="fa fa-delete" aria-hidden="true"></i></a>
								# ENDIF #
							</span>
						</div>
						<div class="message-info">
							<span>${LangLoader::get_message('the', 'common')} {messages.DATE}</span>
							<a href="{messages.U_ANCHOR}" aria-label="${LangLoader::get_message('link.to.anchor', 'comments-common')}">\#{messages.ID}</a>
						</div>
					</div>
				</header>
				<div class="message-content">
					{messages.CONTENTS}
				</div>
				# IF messages.C_USER_GROUPS #
					<footer class="message-footer-container">
						# START messages.user_groups #
							# IF messages.user_groups.C_GROUP_PICTURE #
							<img src="{PATH_TO_ROOT}/images/group/{messages.user_groups.GROUP_PICTURE}" alt="{messages.user_groups.GROUP_NAME}" class="message-user-group" />
							# ELSE #
							${LangLoader::get_message('group', 'main')}: {messages.user_groups.GROUP_NAME}
							# ENDIF #
						# END user_groups #
					</footer>
				# ENDIF #
			</article>
		# END messages #
	</div>
	<footer># IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
</section>
