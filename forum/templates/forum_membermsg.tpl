		<span id="go-top"></span>

		# INCLUDE forum_top #

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-membermsg" class="forum-contents">
			<header class="flex-between">
				<h2>
					{L_VIEW_MSG_USER}
				</h2>
				# IF C_PAGINATION #<span># INCLUDE PAGINATION #</span># ENDIF #
			</header>
			<div class="content">

				# START list #
					<article id="d{list.ID}" class="message-container message-small" itemscope="itemscope" itemtype="http://schema.org/Comment">
						<span id="m{list.ID}"></span>
				        <header class="message-header-container">
							<img class="message-user-avatar" src="# IF list.C_USER_AVATAR #{list.U_USER_AVATAR}# ELSE #{list.U_DEFAULT_AVATAR}# ENDIF #" alt="${LangLoader::get_message('avatar', 'user-common')}">
				            <div class="message-header-infos">
				                <div class="message-user">
				                    <h3 class="message-user-pseudo">
										# IF NOT list.C_GUEST #
											<a class="{list.LEVEL_CLASS}" # IF list.C_GROUP_COLOR # style="color:{list.GROUP_COLOR}" # ENDIF # href="{list.U_USER_PROFILE}">
												{list.USER_PSEUDO}
											</a>
											<span class="sr-only"># IF C_USER_ONLINE #${LangLoader::get_message('forum.connected.mbr.yes', 'common', 'forum')}# ELSE #${LangLoader::get_message('forum.connected.mbr.no', 'common', 'forum')}# ENDIF #</span>
										# ELSE #
											${LangLoader::get_message('guest', 'main')}
										# ENDIF #
				                    </h3>
				                </div>
				                <div class="message-infos">
				                    <time datetime="{list.TOPIC_DATE_FULL}" itemprop="datePublished">${LangLoader::get_message('on', 'main')} {list.TOPIC_DATE_FULL}</time>
				                    <a href="topic{list.U_VARS_ANCRE}#m{list.ID}" aria-label="${LangLoader::get_message('link.to.anchor', 'comments-common')}">\#{list.ID}</i></a>
				                </div>
				            </div>
				        </header>
				        <div class="message-content" >
							{list.CONTENTS}
				        </div>
						<div class="message-user-sign# IF list.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
							# IF list.C_USER_SIGN #{list.USER_SIGN}# ENDIF #
						</div>
				        <footer class="message-footer-container# IF list.C_CURRENT_USER_MESSAGE # current-user-message# ENDIF #">
				            <div class="message-user-assoc">
				                <div class="message-group-level">
									# IF list.C_USER_GROUPS #
										# START list.usergroups #
											# IF list.usergroups.C_IMG_USERGROUP #
												<a href="{list.usergroups.U_USERGROUP}" class="user-group user-group-img group-{list.usergroups.USERGROUP_ID} "# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #><img src="{PATH_TO_ROOT}/images/group/{list.usergroups.U_IMG_USERGROUP}" alt="{list.usergroups.USERGROUP_NAME}" /></a>
											# ELSE #
												{list.usergroups.L_USER_GROUP} : <a href="{list.usergroups.U_USERGROUP}" class="user-group group-{list.usergroups.USERGROUP_ID}"# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #>{list.usergroups.USERGROUP_NAME}</a>
											# ENDIF #
										# END list.usergroups #
									# ENDIF #
								</div>
								<div class="message-user-rank">
									# IF list.C_USER_RANK #<span class="pinned {list.LEVEL_CLASS}">{list.USER_RANK}# ELSE #<span class="pinned error">${LangLoader::get_message('banned', 'user-common')}# ENDIF #</span>
									# IF list.C_USER_IMG_ASSOC #<img class="valign-middle" src="{list.USER_IMG_ASSOC}" alt="${LangLoader::get_message('rank', 'main')}" /># ENDIF #
								</div>
				            </div>
				            <div class="message-user-management">
					            <div class="message-moderation-level">
									# IF list.C_FORUM_MODERATOR #
										{list.USER_WARNING}%
										<a href="moderation_forum{list.U_FORUM_WARNING}" aria-label="{L_WARNING_MANAGEMENT}"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></a>
										<a href="moderation_forum{list.U_FORUM_PUNISHEMENT}" aria-label="{L_PUNISHMENT_MANAGEMENT}"><i class="fa fa-user-lock" aria-hidden="true"></i></a>
									# ENDIF #
								</div>
				            </div>
				        </footer>
					</article>
				# END list #

			</div>
			<footer class="align-right">
				# IF C_PAGINATION #<span># INCLUDE PAGINATION #</span># ENDIF #
			</footer>
		</article>

		<span id="go-bottom"></span>

		# INCLUDE forum_bottom #
