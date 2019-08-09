		<span id="go-top"></span>

		# INCLUDE forum_top #

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-membermsg" class="forum-contents">
			<header>
				<h2>
					<span class="forum-cat-title">
						<a href="membermsg{U_FORUM_VIEW_MSG}">{L_VIEW_MSG_USER}</a>
					</span>
					# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
				</h2>
			</header>
			<div class="content">

				# START list #
				<div class="msg-position">
					<div class="msg-title bkgd-color-op20">
						<a href="{list.U_FORUM_CAT}" class="forum-mbrmsg-links">{list.FORUM_CAT}</a> <i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{list.U_TITLE_T}" class="forum-mbrmsg-links">{list.TITLE_T}</a>
						<span class="float-right">
							<a href="#go-top" aria-label="${LangLoader::get_message('scroll-to.top', 'user-common')}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
							<a href="#go-bottom" aria-label="${LangLoader::get_message('scroll-to.bottom', 'user-common')}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
						</span>
					</div>
					<div class="msg-container">
						<div class="msg-top bkgd-color-op20-sc">
							<div class="msg-top-row">
								<div class="msg-pseudo-mbr bkgd-color-op20">
									<i class="fa # IF list.C_USER_ONLINE #fa-online# ELSE #fa-offline# ENDIF #" aria-hidden="true"></i>
									# IF NOT list.C_GUEST #
										<a class="forum-link-pseudo {list.LEVEL_CLASS}" # IF list.C_GROUP_COLOR # style="color:{list.GROUP_COLOR}" # ENDIF # href="{list.U_USER_PROFILE}">
											{list.USER_PSEUDO}
										</a>
										<span class="sr-only"># IF C_USER_ONLINE #${LangLoader::get_message('forum.connected.mbr.yes', 'common', 'forum')}# ELSE #${LangLoader::get_message('forum.connected.mbr.no', 'common', 'forum')}# ENDIF #</span>
									# ELSE #
										${LangLoader::get_message('guest', 'main')}
									# ENDIF #
								</div>

								<p class="center"># IF list.C_USER_RANK #{list.USER_RANK}# ELSE #${LangLoader::get_message('banned', 'user-common')}# ENDIF #</p>
								# IF list.C_USER_IMG_ASSOC #<p class="center"><img src="{list.USER_IMG_ASSOC}" alt="${LangLoader::get_message('rank', 'main')}" /></p> # ENDIF #
							</div>

							<div class="msg-avatar-mbr center">
								<img src="# IF list.C_USER_AVATAR #{list.U_USER_AVATAR}# ELSE #{list.U_DEFAULT_AVATAR}# ENDIF #" alt="${LangLoader::get_message('avatar', 'user-common')}" />
							</div>

							<div class="msg-info-mbr">
								# IF list.C_USER_GROUPS #
								<p class="center">
									# START list.usergroups #
										# IF list.usergroups.C_IMG_USERGROUP #
										<span class="user-group">
											<a href="{list.usergroups.U_USERGROUP}" class="user-group user-group-img group-{list.usergroups.USERGROUP_ID} "# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #>
												<img src="{PATH_TO_ROOT}/images/group/{list.usergroups.U_IMG_USERGROUP}" alt="{list.usergroups.USERGROUP_NAME}" />
											</a>
										</span>
										# ELSE #
										<span class="user-group">
											{list.usergroups.L_USER_GROUP} : <a href="{list.usergroups.U_USERGROUP}" class="user-group group-{list.usergroups.USERGROUP_ID}"# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #>{list.usergroups.USERGROUP_NAME}</a>
										</span>
										# ENDIF #
									# END list.usergroups #
								</p>
								# ENDIF #
								# IF list.C_IS_USER #<p class="left">${LangLoader::get_message('registered_on', 'main')}: {list.USER_REGISTERED_DATE_SHORT}</p># ENDIF #
								# IF list.C_USER_MSG #
								<p class="left"><a href="{list.U_USER_MSG}" class="small">${LangLoader::get_message('message_s', 'main')}</a>: {list.USER_MSG}</p>
								# ELSE #
								<p class="left"># IF list.C_IS_USER # <a href="{list.U_USER_MEMBERMG}" class="small">${LangLoader::get_message('message', 'main')}</a> : 0# ELSE #${LangLoader::get_message('message', 'main')} : 0# ENDIF #</p>
								# ENDIF #

							</div>

						</div>

						<div class="msg-contents-container">
							<div class="msg-contents-info bkgd-color-op20">
								<span class="float-left">
									&nbsp;&nbsp;<span id="m{list.ID}"></span><a href="{PATH_TO_ROOT}/forum/topic{list.U_VARS_ANCRE}#m{list.ID}" aria-label="{list.DATE}"><i class="fa fa-hand-o-right" aria-hidden="true"></i></a>${LangLoader::get_message('on', 'main')} {list.TOPIC_DATE_FULL}
								</span>

							</div>
							<div class="msg-contents">
								<div class="msg-contents-overflow">
									{list.CONTENTS}
								</div>
							</div>
							<div class="msg-sign{list.CLASS_COLOR}">
								<div class="msg-sign-overflow">
									# IF list.C_USER_SIGN #<hr /><br />{list.USER_SIGN}# ENDIF #
								</div>
								<hr />
								<span class="float-left">
									# IF list.C_USER_PM #<a href="{list.U_USER_PM}" class="basic-button smaller user-pm">${LangLoader::get_message('pm', 'main')}</a># ENDIF # # IF list.C_USER_MAIL #<a href="{list.U_USER_MAIL}" class="basic-button smaller  user-mail">${LangLoader::get_message('mail', 'main')}</a># ENDIF #
									# START list.ext_fields #
										{list.ext_fields.BUTTON}
									# END list.ext_fields #
								</span>
							</div>
						</div>
					</div>
				</div>
				# END list #

			</div>
			<footer>
				<span class="forum-cat-title">
					<a href="membermsg{U_FORUM_VIEW_MSG}">{L_VIEW_MSG_USER}</a>
				</span>
				# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span><div class="spacer"></div># ENDIF #
			</footer>
		</article>

		<span id="go-bottom"></span>

		# INCLUDE forum_bottom #
