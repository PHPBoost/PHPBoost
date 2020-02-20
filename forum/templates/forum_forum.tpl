
		# INCLUDE forum_top #

		# START error_auth_write #
		<div class="message-helper bgc notice">
			{error_auth_write.L_ERROR_AUTH_WRITE}
		</div>
		# END error_auth_write #

		# IF C_FORUM_SUB_CATS #
			<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-subforum" class="forum-contents">
				<header class="flex-between">
					<h2>{L_SUBFORUMS}</h2>
					<div class="controls">
						<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}"><i class="fa fa-rss warning" aria-hidden="true"></i><span class="sr-only">${LangLoader::get_message('syndication', 'common')}</span></a>
					</div>
				</header>
				<div class="content">
					<table class="table forum-table">
						<thead>
							<tr>
								<th class="forum-announce-topic"><i class="fa fa-eye" aria-hidden="true"></i></th>
								<th class="forum-topic">{L_FORUM}</th>
								<th class="forum-subject-nb" aria-label="{L_TOPIC}"><i class="fa fa-com fa-fw" aria-hidden="true"></i><span class="sr-only">{L_TOPIC}</span></th>
								<th class="forum-message-nb" aria-label="{L_MESSAGE}"><i class="fa fa-coms fa-fw" aria-hidden="true"></i><span class="sr-only">{L_MESSAGE}</span></th>
								<th class="forum-last-topic" aria-label="{L_LAST_MESSAGE}"><i class="fa fa-clock fa-fw" aria-hidden="true"></i><span class="sr-only">{L_LAST_MESSAGE}</span></th>
							</tr>
						</thead>
						<tbody>
							# START subcats #
								<tr class="category-{subcats.IDCAT}">
									# IF subcats.U_FORUM_URL #
									<td class="forum-announce-topic">
										<i class="fa fa-globe fa-2x" aria-hidden="true"></i>
									</td>
									<td class="forum-topic" colspan="4">
										<a href="{subcats.U_FORUM_URL}">{subcats.NAME}</a>
										<span class="small d-block">{subcats.DESC}</span>
									</td>
									# ELSE #
									<td class="forum-announce-topic">
										<i class="fa # IF subcats.C_BLINK #blink # ENDIF #{subcats.IMG_ANNOUNCE}" aria-hidden="true"></i>
									</td>
									<td class="forum-topic">
										<a href="forum{subcats.U_FORUM_VARS}">{subcats.NAME}</a>
										<span class="small d-block">{subcats.DESC}</span>
										# IF subcats.C_SUBFORUMS #<span class="d-block small"><span class="pinned notice">{subcats.L_SUBFORUMS}</span> : {subcats.SUBFORUMS}</span># ENDIF #
									</td>
									<td class="forum-subject-nb">
										{subcats.NBR_TOPIC}
									</td>
									<td class="forum-message-nb">
										{subcats.NBR_MSG}
									</td>
									<td class="forum-last-topic">
										# IF subcats.C_LAST_TOPIC_MSG #
											<span class="d-block">
												<i class="far fa-comment fa-fw"></i>
												<a href="{subcats.U_LAST_TOPIC}">{subcats.LAST_TOPIC_TITLE}</a>
											</span>
											<span class="d-block">
												<i class="fa fa-hand-point-right fa-fw" aria-hidden="true"></i>
												<a href="{subcats.U_LAST_MSG}">{subcats.LAST_MSG_DATE_FULL}</a>
											</span>
											<span class="d-block">
												<i class="far fa-user fa-fw"></i>
												# IF subcats.C_LAST_MSG_GUEST #
													<a href="{subcats.U_LAST_MSG_USER_PROFIL}" class="{subcats.LAST_MSG_USER_LEVEL}"# IF subcats.C_LAST_MSG_USER_GROUP_COLOR #  style="color:{subcats.LAST_MSG_USER_GROUP_COLOR}"# ENDIF #>{subcats.LAST_MSG_USER_LOGIN}</a>
												# ELSE #
													${LangLoader::get_message('guest', 'main')}
												# ENDIF #
											</span>

										# ELSE #
											<em>{subcats.L_NO_MSG}</em>
										# ENDIF #
									</td>
									# ENDIF #
								</tr>
							# END subcats #
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6">
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</article>
		# ENDIF #

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-forum" class="forum-contents">
			<header>
				<div class="align-right controls">
					# IF C_POST_NEW_SUBJECT #
						<a href="{U_POST_NEW_SUBJECT}" class="button submit">{L_POST_NEW_SUBJECT}</a>
					# ENDIF #
					<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning"></i></a>
					# IF IDCAT #<a href="unread.php?cat={IDCAT}" aria-label="{L_DISPLAY_UNREAD_MSG}"><i class="far fa-file-alt" aria-hidden="true"></i></a># ENDIF #
					# IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #
				</div>
				<h2>
					# START syndication_cats #
						# IF syndication_cats.C_DISPLAY_RAQUO # <i class="fa fa-angle-double-right" aria-hidden="true"></i> # ENDIF #<a href="{syndication_cats.LINK}">{syndication_cats.LABEL}</a>
					# END syndication_cats #
				</h2>
			</header>
			<div class="content">
				<table class="table forum-table">
					<thead>
						<tr>
							<th class="forum-announce-topic"><i class="far fa-eye" aria-hidden="true"></i></th>
							<th class="forum-fixed-topic"><i class="fa fa-check success" aria-hidden="true"></i></th>
							<th class="forum-topic" aria-label="{L_TOPIC}"><i class="far fa-file hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_TOPIC}</span></th>
							<th class="forum-author"><i class="far fa-user fa-fw hidden-small-screens" aria-hidden="true" aria-label="{L_AUTHOR}"></i><span class="hidden-large-screens">{L_AUTHOR}</span></th>
							<th class="forum-message-nb"><i class="far fa-comments fa-fw hidden-small-screens" aria-hidden="true" aria-label="{L_ANSWERS}"></i><span class="hidden-large-screens">{L_ANSWERS}</span></th>
							<th class="forum-view"><i class="far fa-eye fa-fw hidden-small-screens" aria-hidden="true" aria-label="{L_VIEW}"></i><span class="hidden-large-screens">{L_VIEW}</span></th>
							<th class="forum-last-topic"><i class="far fa-clock fa-fw hidden-small-screens" aria-hidden="true" aria-label="{L_LAST_MESSAGE}"></i><span class="hidden-large-screens">{L_LAST_MESSAGE}</span></th>
						</tr>
					</thead>
					<tbody>
					# IF C_NO_MSG_NOT_READ #
					<tr>
						<td colspan="7">
							<strong>{L_MSG_NOT_READ}</strong>
						</td>
					</tr>
					# ENDIF #

					# START topics #
						<tr class="category-{topics.CATEGORY_ID}">
							# IF C_MASS_MODO_CHECK #
							<td class="forum-mass-modo">
								<label class="checkbox" for="modo{topics.ID}">
									<input id="modo{topics.ID}" type="checkbox" name="ck{topics.ID}">
									<span>&nbsp;</span>
								</label>
							</td>
							# ENDIF #
							<td class="forum-announce-topic">
								# IF NOT topics.C_HOT_TOPIC #
									<i class="far {topics.IMG_ANNOUNCE}" aria-hidden="true"></i>
								# ELSE #
									<i class="far # IF topics.C_BLINK #blink # ENDIF #{topics.IMG_ANNOUNCE}-hot" aria-hidden="true"></i>
								# ENDIF #
							</td>
							<td class="forum-fixed-topic">
								# IF topics.C_DISPLAY_MSG #<i class="fa fa-check success" aria-hidden="true"></i># ENDIF #
								# IF topics.C_IMG_POLL #<i class="fa fa-poll-h" aria-hidden="true"></i># ENDIF #
								# IF topics.C_IMG_TRACK #<i class="fa fa-heart error" aria-hidden="true"></i># ENDIF #
							</td>
							<td class="forum-topic">
								# IF topics.C_PAGINATION #<span class="pagin-forum"># INCLUDE topics.PAGINATION #</span># ENDIF #
								# IF topics.C_ANCRE #<a href="{topics.U_ANCRE}"><i class="fa fa-hand-point-right" aria-hidden="true"></i></a># ENDIF #
								# IF topics.TYPE # <strong>{topics.TYPE}</strong> # ENDIF #
								<a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
								<span class="small d-block">{topics.DESC}</span>
							</td>
							<td class="forum-author">
								# IF topics.C_AUTHOR #
									<a href="{topics.U_AUTHOR}" class="small {topics.AUTHOR_LEVEL}"# IF topics.C_GROUP_COLOR # style="color:{topics.GROUP_COLOR}"# ENDIF #>{topics.AUTHOR}</a>
								# ELSE #
									<em>{topics.L_GUEST}</em>
								# ENDIF #
							</td>
							<td class="forum-message-nb">
								{topics.MSG}
							</td>
							<td class="forum-view">
								{topics.VUS}
							</td>
							<td class="forum-last-topic">
								<span class="d-block">
									<i class="far fa-hand-point-right fa-fw" aria-hidden="true"></i>
									<a href={topics.LAST_MSG_URL} aria-label="{topics.TITLE} <br /> {topics.LAST_MSG_DATE_FULL}">{topics.LAST_MSG_DATE_FULL}</a>
								</span>
								<span class="d-block">
									<i class="far fa-user fa-fw" aria-hidden="true"></i>
									# IF topics.C_LAST_MSG_GUEST #
										<a href="{topics.LAST_MSG_USER_PROFIL}" class="small {topics.LAST_MSG_USER_LEVEL}"# IF topics.C_LAST_MSG_USER_GROUP_COLOR # style="color:{topics.LAST_MSG_USER_GROUP_COLOR}"# ENDIF #>{topics.LAST_MSG_USER_LOGIN}</a>
									# ELSE #
										<em>${LangLoader::get_message('guest', 'main')}</em>
									# ENDIF #
								</span>
							</td>
						</tr>
					# END topics #

					# IF C_NO_TOPICS #
						<tr>
							<td colspan="7">
								<strong>{L_NO_TOPICS}</strong>
							</td>
						</tr>
					# ENDIF #
					</tbody>
					<tfoot>
						<tr>
							<th colspan="7">
								<div class="footer-forum">
									<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
									# START syndication_cats #
										# IF syndication_cats.C_DISPLAY_RAQUO # <i class="fa fa-angle-double-right" aria-hidden="true"></i> # ENDIF #<a href="{syndication_cats.LINK}">{syndication_cats.LABEL}</a>
									# END syndication_cats #
									# IF C_POST_NEW_SUBJECT #
										<i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{U_POST_NEW_SUBJECT}" class="button submit small">{L_POST_NEW_SUBJECT}</a>
									# ENDIF #
								</div>
								# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</article>

		# INCLUDE forum_bottom #
