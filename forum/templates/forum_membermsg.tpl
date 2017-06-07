		<span id="go-top"></span>

		# INCLUDE forum_top #
		
		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-membermsg">
			<header>
				<h2>
					<span class="forum-cat-title">
						<a href="membermsg{U_FORUM_VIEW_MSG}" title="{L_VIEW_MSG_USER}">{L_VIEW_MSG_USER}</a>
					</span>
					# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
				</h2>
			</header>
			<div class="content">

				# START list #
				<div class="msg-position">
					<div class="msg-title">
						{list.U_FORUM_CAT} &raquo; {list.U_TITLE_T}
						<span class="float-right">
							<a href="#go-top" title="go top"><i class="fa fa-arrow-up"></i></a> 
							<a href="#go-bottom" title="go bottom"><i class="fa fa-arrow-down"></i></a>
						</span>
					</div>
					<div class="msg-container">
						<div class="msg-top">
							<div class="msg-top-row">
								<div class="msg-pseudo-mbr">
									{list.USER_ONLINE} # IF NOT list.C_GUEST #<a class="forum-link-pseudo {list.LEVEL_CLASS}" # IF list.C_GROUP_COLOR # style="color:{list.GROUP_COLOR}" # ENDIF # href="{list.U_USER_PROFILE}" title="{list.USER_PSEUDO}">{list.USER_PSEUDO}</a># ELSE # {list.USER_PSEUDO} # ENDIF #
								</div>
								
								<p class="center">{list.USER_RANK}</p>
								<p class="center">{list.USER_IMG_ASSOC}</p>
							</div>							
							
							<div class="msg-avatar-mbr center">
								{list.USER_AVATAR}
							</div>
							
							<div class="msg-info-mbr">
								# IF list.C_USER_GROUPS #
								<p class="center">
									# START list.usergroups #
										# IF list.usergroups.C_IMG_USERGROUP # 
										<a href="{list.usergroups.U_USERGROUP}" class="user-group user-group-img group-{list.usergroups.USERGROUP_ID} "# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #><img src="{PATH_TO_ROOT}/images/group/{list.usergroups.U_IMG_USERGROUP}" alt="{list.usergroups.USERGROUP_NAME}" title="{list.usergroups.USERGROUP_NAME}"/></a><br/>
										# ELSE #
										{list.usergroups.L_USER_GROUP} : <a href="{list.usergroups.U_USERGROUP}" class="user-group group-{list.usergroups.USERGROUP_ID}"# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #>{list.usergroups.USERGROUP_NAME}</a><br />
										# ENDIF #
									# END list.usergroups #
								</p>
								# ENDIF #
								<p class="left">{list.USER_DATE}</p>
								<p class="left">{list.USER_MSG}</p>
							</div>
							
						</div>
						
						<div class="msg-contents-container">
							<div class="msg-contents-info">
								<span class="float-left">
									&nbsp;&nbsp;<span id="m{list.ID}"></span><a href="{PATH_TO_ROOT}/forum/topic{list.U_VARS_ANCRE}#m{list.ID}" title="{list.DATE}"><i class="fa fa-hand-o-right"></i></a> {list.DATE}
								</span>

							</div>
							<div class="msg-contents">
								<div class="msg-contents-overflow">
									{list.CONTENTS}
								</div>
							</div>
							<div class="msg-sign{list.CLASS_COLOR}">
								<div class="msg-sign-overflow">
									{list.USER_SIGN}
								</div>
								<hr />
								<span class="float-left">
									{list.USER_PM} {list.USER_MAIL}
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
					<a href="membermsg{U_FORUM_VIEW_MSG}" title="{L_VIEW_MSG_USER}">{L_VIEW_MSG_USER}</a>
				</span>
				# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span><div class="spacer"></div># ENDIF #
			</footer>
		</article>
		
		<span id="go-bottom"></span>
		
		# INCLUDE forum_bottom #
		
