# INCLUDE FORUM_TOP #

<span id="go-top"></span>
<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-membermsg" class="forum-content">
	<header class="flex-between">
		<h2>
			{L_VIEW_MSG_USER}
		</h2>
		# IF C_PAGINATION #<span># INCLUDE PAGINATION #</span># ENDIF #
	</header>
	<div class="content">
		# IF C_ITEMS #
			# START list #
				<article id="d{list.ID}" class="message-container message-small category-{list.CATEGORY_ID} cell-tile cell-modal modal-container" itemscope="itemscope" itemtype="https://schema.org/Comment">
					<span id="m{list.ID}"></span>
					<header class="message-header-container">
						# IF list.C_USER_AVATAR #<img class="message-user-avatar" src="{list.U_USER_AVATAR}" alt="{@common.avatar}"># ENDIF #
						<div class="message-header-infos">
							<div class="message-user-container">
								<h3 class="message-user-name">
									# IF NOT list.C_GUEST #
										<a class="{list.LEVEL_CLASS} offload" # IF list.C_GROUP_COLOR # style="color:{list.GROUP_COLOR}" # ENDIF # href="{list.U_USER_PROFILE}">
											{list.USER_PSEUDO}
										</a>
										<span class="smaller" aria-label="{@common.see.profile.datas}" data-modal data-target="message-user-datas-{list.ID}">
											<i class="far fa-eye" aria-hidden="true"></i>
										</span>
										<span class="sr-only"># IF C_USER_ONLINE #{@forum.connected.member)}# ELSE #{@forum.not.connected.member}# ENDIF #</span>
									# ELSE #
										{@user.guest}
									# ENDIF #
								</h3>
								<div class="controls message-user-infos-preview">
									# IF list.C_USER_GROUPS #
										# START list.usergroups #
											# IF list.usergroups.C_IMG_USERGROUP #
												<a href="{list.usergroups.U_USERGROUP}" class="user-group user-group-img group-{list.usergroups.USERGROUP_ID} offload"# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #><img src="{PATH_TO_ROOT}/images/group/{list.usergroups.U_IMG_USERGROUP}" alt="{list.usergroups.USERGROUP_NAME}" /></a>
											# ELSE #
												{@user.groups} : <a href="{list.usergroups.U_USERGROUP}" class="user-group group-{list.usergroups.USERGROUP_ID} small offload"# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #>{list.usergroups.USERGROUP_NAME}</a>
											# ENDIF #
										# END list.usergroups #
									# ENDIF #
									# IF list.C_USER_RANK #<span class="pinned {list.LEVEL_CLASS} small">{list.USER_RANK}# ELSE #<span class="pinned error">{@user.banned}# ENDIF #</span>
								</div>
							</div>
							<div class="message-infos">
								<time datetime="{list.TOPIC_DATE_FULL}" itemprop="datePublished">{@common.on.date} {list.TOPIC_DATE_FULL}</time>
								<a class="offload" href="topic{list.U_VARS_ANCHOR}#m{list.ID}" aria-label="{@forum.link.to.topic}">\#{list.ID}</i></a>
							</div>
						</div>
					</header>
					<div id="message-user-datas-{list.ID}" class="modal modal-animation">
						<div class="close-modal" aria-label="{@common.close}"></div>
						<div class="content-panel cell">
							<div class="cell-list">
								<ul>
									<li class="li-stretch">
										# IF list.C_USER_RANK #<span class="pinned {list.LEVEL_CLASS}">{list.USER_RANK}</span># ELSE #<span class="error">{@user.banned}</span># ENDIF #
										# IF list.C_USER_RANK_ICON #<img class="valign-middle" src="{list.USER_RANK_ICON}" alt="{@user.rank}" /># ENDIF #
									</li>
									<li class="li-stretch">
										<span>{@common.see.profile}</span>
										<a href="{list.U_USER_PROFILE}" class="msg-link-pseudo {list.LEVEL_CLASS} offload" # IF list.C_GROUP_COLOR # style="color:{list.GROUP_COLOR}"# ENDIF #>{list.USER_PSEUDO}</a>
									</li>
									<li class="li-stretch">
										<span>{@forum.registred.on} :</span>
										<span>{list.USER_REGISTERED_DATE}</span>
									</li>
									# IF IS_USER_CONNECTED #
										# IF list.C_USER_MSG #
											<li class="li-stretch">
												<span>{@forum.messages} :</span>
												<a href="{list.U_USER_MEMBERMSG}" class="button submit smaller offload" aria-label="{@forum.show.member.messages}">{list.USER_MSG}</a>
											</li>
										# ENDIF #
									# ENDIF #
									# IF list.C_USER_PM #
										<li class="li-stretch">
											<span>{@user.pm} :</span>
											<a href="{list.U_USER_PM}" class="button submit smaller user-pm offload" aria-label="{@user.contact.pm}"><i class="fa fa-people-arrows fa-fw"></i></a>
										</li>
									# ENDIF #
									# IF list.C_USER_EMAIL #
										<li class="li-stretch">
											<span>{@user.email}</span>
											<a href="{list.U_USER_EMAIL}" class="button submit smaller user-mail offload" aria-label="{@user.contact.email}"><i class="fa iboost fa-iboost-email fa-fw"></i></a>
										</li>
									# ENDIF #
									# START list.ext_fields #
										<li>
											{list.ext_fields.BUTTON}
										</li>
									# END list.ext_fields #
									# IF list.C_USER_GROUPS #
										<li class="li-stretch">
											<span>{@user.groups} :</span>
										</li>
											# START list.usergroups #
												<li class="li-stretch">
													<a href="{list.usergroups.U_USERGROUP}" class="user-group group-{list.usergroups.USERGROUP_ID} offload"# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #>{list.usergroups.USERGROUP_NAME}</a>
													# IF list.usergroups.C_IMG_USERGROUP #
														<a href="{list.usergroups.U_USERGROUP}" class="user-group user-group-img group-{list.usergroups.USERGROUP_ID} offload"# IF list.usergroups.C_USERGROUP_COLOR # style="color: {list.usergroups.USERGROUP_COLOR}"# ENDIF #><img src="{PATH_TO_ROOT}/images/group/{list.usergroups.U_IMG_USERGROUP}" alt="{list.usergroups.USERGROUP_NAME}" /></a>
													# ENDIF #
												</li>
											# END list.usergroups #
										</li>
									# ENDIF #
									# IF list.C_USER_SIGN #<li>{list.USER_SIGN}</li># ENDIF #
									# IF IS_MODERATOR #
										<li class="li-stretch">
											<span>Sanctions: {list.USER_WARNING}%</span>
											<span>
												<a class="offload" href="moderation_forum{list.U_FORUM_WARNING}" aria-label="{@user.warnings.management}"><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i></a>
												<a class="offload" href="moderation_forum{list.U_FORUM_PUNISHEMENT}" aria-label="{@user.punishments.management}"><i class="fa fa-user-lock" aria-hidden="true"></i></a>
											</span>
										</li>
									# ENDIF #
								</ul>
							</div>
						</div>
					</div>
					<div class="message-content" >
						<div class="float-right badges">
							# START list.additional_informations #
								<span>{list.additional_informations.VALUE}</span>
							# END list.additional_informations #
						</div>
						{list.CONTENT}
					</div>
				</article>
			# END list #
		# ELSE #
			<div class="message-helper bgc notice">{@forum.no.message.now}</div>
		# ENDIF #
	</div>
	<footer class="align-right">
		# IF C_PAGINATION #<span># INCLUDE PAGINATION #</span># ENDIF #
	</footer>
</article>
<span id="go-bottom"></span>

# INCLUDE FORUM_BOTTOM #
