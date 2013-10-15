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
			<article id="m{messages.ID}" class="comment">
				<div class="comment-user_infos">
					<div class="comment-pseudo">
						# IF messages.C_VISITOR #
							<span class="text_italic"># IF messages.USER_PSEUDO #{messages.USER_PSEUDO}# ELSE #{L_GUEST}# ENDIF #</span>
						# ELSE #
							<a href="{messages.U_PROFILE}" class="{messages.USER_LEVEL_CLASS}" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>
								{messages.USER_PSEUDO}
							</a>
						# ENDIF #
					</div>
					# IF messages.C_AVATAR #<img src="{messages.U_AVATAR}" class="comment-avatar" /># ENDIF #
					# IF messages.C_USER_GROUPS #
						# START messages.user_groups #
							# IF messages.user_groups.C_GROUP_PICTURE #
							<img src="{PATH_TO_ROOT}/images/group/{messages.user_groups.GROUP_PICTURE}" alt="{messages.user_groups.GROUP_NAME}" title="{messages.user_groups.GROUP_NAME}"/>
							# ELSE #
							{L_GROUP}: {messages.user_groups.GROUP_NAME}
							# ENDIF #
						# END user_groups #
					# ENDIF #
				</div>
				<div class="comment-content">
					<div class="comment-date">
						# IF messages.C_MODERATOR #
						<span class="tools">
							<a href="{messages.U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="edit"></a>
							<a href="{messages.U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="delete"></a>
						</span>
						# ENDIF #
						<a href="{messages.U_ANCHOR}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/ancre.png" alt="{messages.ID}" /></a> {L_ON} {messages.DATE}
					</div>
					<div class="comment-message">
						<div class="message-containt">{messages.CONTENTS}</div>
					</div>
				</div>
			</article>
		# END messages #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
