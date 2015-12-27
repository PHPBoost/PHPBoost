<section id="module-shoutbox">
	<header>
		<h1>{@module_title}</h1>
	</header>
	<div class="content">
		# INCLUDE MSG #
		# INCLUDE FORM #

		# IF C_PAGINATION #
			<div class="center"># INCLUDE PAGINATION #</div>
			<div class="spacer"></div>
		# ENDIF #
		# IF C_NO_MESSAGE #
			<div class="notice message-helper-small center">${LangLoader::get_message('no_item_now', 'common')}</div>
		# ENDIF #
		# START messages #
			<article id="article-shoutbox-{messages.ID}" class="article-shoutbox article-several message">
				<header>
					<h2>${LangLoader::get_message('message', 'main')}</h2>
				</header>
				<div class="message-container">

					<div class="message-user-infos">
						<div class="message-pseudo">
							# IF messages.C_AUTHOR_EXIST #
							<a href="{messages.U_AUTHOR_PROFILE}" class="{messages.USER_LEVEL_CLASS}" # IF messages.C_USER_GROUP_COLOR # style="color:{messages.USER_GROUP_COLOR}" # ENDIF #>{messages.PSEUDO}</a>
							# ELSE #
							{messages.PSEUDO}
							# ENDIF #
						</div>
						# IF messages.C_AVATAR #<img src="{messages.U_AVATAR}" alt="${LangLoader::get_message('avatar', 'user-common')}" class="message-avatar" /># ENDIF #
						# IF messages.C_USER_GROUPS #
							<div class="spacer"></div>
							# START messages.user_groups #
								# IF messages.user_groups.C_GROUP_PICTURE #
								<img src="{PATH_TO_ROOT}/images/group/{messages.user_groups.GROUP_PICTURE}" alt="{messages.user_groups.GROUP_NAME}" title="{messages.user_groups.GROUP_NAME}" class="message-user-group"/>
								# ELSE #
								${LangLoader::get_message('group', 'main')}: {messages.user_groups.GROUP_NAME}
								# ENDIF #
							# END user_groups #
						# ENDIF #
					</div>

					<div class="message-date">
						<span class="actions">
							# IF messages.C_EDIT #
							<a href="{messages.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
							# ENDIF #
							# IF messages.C_DELETE #
							<a href="{messages.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
							# ENDIF #
						</span>
						<a href="{messages.U_ANCHOR}"><i class="fa fa-hand-o-right"></i></a> ${LangLoader::get_message('the', 'common')} {messages.DATE}
					</div>

					<div class="message-message">
						<div class="message-content">{messages.CONTENTS}</div>
					</div>
					
				</div>
				<footer></footer>
			</article>
		# END messages #
	</div>
	<footer># IF C_PAGINATION #<div class="center"># INCLUDE PAGINATION #</div># ENDIF #</footer>
</section>
