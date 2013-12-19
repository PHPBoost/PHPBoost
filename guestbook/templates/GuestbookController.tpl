<section>
	<header>
		<h1>{@module_title}</h1>
	</header>
	<div class="content">
		# INCLUDE FORM #
		
		# IF C_PAGINATION #
			<div class="center"># INCLUDE PAGINATION #</div>
		# ENDIF #
		# IF C_NO_MESSAGE #
			<div class="center">
				${@guestbook.titles.no_message}
			</div>
		# ENDIF #
		# START messages #
			<article id="m{messages.ID}" class="message">
				<div class="message-user-infos">
					<div class="message-pseudo">
						# IF messages.C_VISITOR #
							<span class="text-italic"># IF messages.USER_PSEUDO #{messages.USER_PSEUDO}# ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF #</span>
						# ELSE #
							<a href="{messages.U_PROFILE}" class="{messages.USER_LEVEL_CLASS}" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>
								{messages.USER_PSEUDO}
							</a>
						# ENDIF #
					</div>
					# IF messages.C_AVATAR #<img src="{messages.U_AVATAR}" class="message-avatar" /># ENDIF #
					# IF messages.C_USER_GROUPS #
						# START messages.user_groups #
							# IF messages.user_groups.C_GROUP_PICTURE #
							<img src="{PATH_TO_ROOT}/images/group/{messages.user_groups.GROUP_PICTURE}" alt="{messages.user_groups.GROUP_NAME}" title="{messages.user_groups.GROUP_NAME}"/>
							# ELSE #
							${LangLoader::get_message('group', 'main')}: {messages.user_groups.GROUP_NAME}
							# ENDIF #
						# END user_groups #
					# ENDIF #
				</div>
				<div class="message-container">
					<div class="message-date">
						# IF messages.C_MODERATOR #
						<span class="actions">
							<a href="{messages.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
							<a href="{messages.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete" data-confirmation="delete-element"></a>
						</span>
						# ENDIF #
						<a href="{messages.U_ANCHOR}"><i class="icon-hand-o-right"></i></a> ${LangLoader::get_message('on', 'main')} {messages.DATE}
					</div>
					<div class="message-message">
						<div class="message-content">{messages.CONTENTS}</div>
					</div>
				</div>
			</article>
		# END messages #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
