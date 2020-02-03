<section id="module-guestbook">
	<header>
		<h1>{@guestbook.module.title}</h1>
	</header>
	<div class="content">
		# INCLUDE MSG #
		
		# INCLUDE FORM #

		# IF C_PAGINATION #
			<div class="align-center"># INCLUDE PAGINATION #</div>
		# ENDIF #
		# IF C_NO_MESSAGE #
			<div class="message-helper bgc notice message-helper-small align-center">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		<form method="post" class="fieldset-content">
			# START messages #
				<article id="m{messages.ID}" class="guestbook-item several-items message-container message-small" itemscope="itemscope" itemtype="http://schema.org/Comment">
					<header class="message-header-container">
						# IF messages.C_AVATAR #
							<img class="message-user-avatar" src="{messages.U_AVATAR}" alt="${LangLoader::get_message('avatar', 'user-common')}" />
						# ENDIF #
						<div class="message-header-infos">
							<div class="message-user">
								<h3 class="message-user-pseudo">
									# IF messages.C_AUTHOR_EXIST #
										<a href="{messages.U_AUTHOR_PROFILE}" class="{messages.USER_LEVEL_CLASS}" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>{messages.PSEUDO}</a>
									# ELSE #
										<span class="visitor">{messages.PSEUDO}</span>
									# ENDIF #
								</h3>
								<div class="message-actions">
									# IF messages.C_DELETE #
										<label for="multiple-checkbox-{messages.MESSAGE_NUMBER}" class="checkbox">
											<input type="checkbox" class="multiple-checkbox" id="multiple-checkbox-{messages.MESSAGE_NUMBER}" name="delete-checkbox-{messages.MESSAGE_NUMBER}" onclick="delete_button_display({MESSAGES_NUMBER});" />
											<span>&nbsp;</span>
										</label>
									# ENDIF #
									# IF messages.C_EDIT #
										<a href="{messages.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a>
									# ENDIF #
									# IF messages.C_DELETE #
										<a href="{messages.U_DELETE}" data-confirmation="delete-element" aria-label="${LangLoader::get_message('delete', 'common')}"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a>
									# ENDIF #
								</div>
							</div>
							<div class="message-infos">
								<time datetime="{messages.DATE}">${LangLoader::get_message('the', 'common')} {messages.DATE}</time>
								<a href="{messages.U_ANCHOR}" aria-label="${LangLoader::get_message('link.to.anchor', 'comments-common')}">\#{messages.ID}</a>
							</div>
						</div>
					</header>
					<div class="message-content# IF messages.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
						{messages.CONTENTS}
					</div>
					# IF messages.C_USER_GROUPS #
						<footer class="message-footer-container# IF messages.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
							<div class="message-user-infos">
								# START messages.user_groups #
									<div class="message-group-level">
										# IF messages.user_groups.C_GROUP_PICTURE #
											<img src="{PATH_TO_ROOT}/images/group/{messages.user_groups.GROUP_PICTURE}" alt="{messages.user_groups.GROUP_NAME}" class="message-user-group" />
										# ELSE #
											${LangLoader::get_message('group', 'main')}: {messages.user_groups.GROUP_NAME}
										# ENDIF #
									</div>
								# END user_groups #
							</div>
						</footer>
					# ENDIF #
				</article>
			# END messages #
			# IF C_MULTIPLE_DELETE_DISPLAYED #
				<div class="mini-checkbox">
					<label for="delete-all-checkbox" class="checkbox">
						<input type="checkbox" class="check-all" id="delete-all-checkbox" name="delete-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MESSAGES_NUMBER});">
						<span aria-label="${LangLoader::get_message('select.all.elements', 'common')}">&nbsp;</span>
					</label>
					<input type="hidden" name="token" value="{TOKEN}" />
					<button type="submit" id="delete-all-button" name="delete-selected-elements" value="true" class="button submit" data-confirmation="delete-element" disabled="disabled">${LangLoader::get_message('delete', 'common')}</button>
				</div>
			# ENDIF #
		</form>
	</div>
	<footer># IF C_PAGINATION #<div class="align-center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
</section>
