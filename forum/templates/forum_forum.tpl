# INCLUDE FORUM_TOP #

# START error_auth_write #
	<div class="message-helper bgc notice">
		{error_auth_write.L_ERROR_AUTH_WRITE}
	</div>
# END error_auth_write #

# IF C_FORUM_SUB_CATS #
	<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-subforum" class="forum-content">
		<header class="flex-between">
			<h2>{@forum.sub.forums}</h2>
			<div class="controls">
				<a href="${relative_url(SyndicationUrlBuilder::rss('forum',CATEGORY_ID))}"><i class="fa fa-rss warning" aria-hidden="true"></i><span class="sr-only">{@common.syndication}</span></a>
			</div>
		</header>
		<div class="content">
			<table class="table forum-table">
				<thead>
					<tr>
						<th class="forum-announce-topic" aria-label="{@forum.topic.status}"><i class="far fa-flag" aria-hidden="true"></i></th>
						<th class="forum-topic">{@forum.forums}</th>
						<th class="forum-subject-nb" aria-label="{@forum.topics}"><i class="far fa-file-alt fa-fw" aria-hidden="true"></i><span class="sr-only">{@forum.topics}</span></th>
						<th class="forum-message-nb" aria-label="{@forum.messages.number}"><i class="far fa-comments fa-fw" aria-hidden="true"></i><span class="sr-only">{@forum.messages.number}</span></th>
						<th class="forum-last-topic" aria-label="{@forum.last.messages}"><i class="far fa-clock fa-fw" aria-hidden="true"></i><span class="sr-only">{@forum.last.messages}</span></th>
					</tr>
				</thead>
				<tbody>
					# START subcats #
						<tr class="category-{subcats.CATEGORY_ID}">
							# IF subcats.U_LINK #
								<td class="forum-announce-topic">
									<i class="fa fa-globe fa-2x" aria-hidden="true"></i>
								</td>
								<td class="forum-topic" colspan="4">
									<a href="{subcats.U_LINK}">{subcats.CATEGORY_NAME}</a>
									<span class="small d-block">{subcats.DESCRIPTION}</span>
								</td>
							# ELSE #
								<td class="forum-announce-topic">
									<i class="fa # IF subcats.C_BLINK #blink # ENDIF #{subcats.TOPIC_ICON}" aria-hidden="true"></i>
								</td>
								<td class="forum-topic">
									<a href="forum{subcats.U_CATEGORY}">{subcats.CATEGORY_NAME}</a>
									<span class="small d-block">{subcats.DESCRIPTION}</span>
									# IF subcats.C_SUBFORUMS #<span class="d-block small"><span class="pinned notice">{@forum.sub.forums}</span> : {subcats.SUBFORUMS}</span># ENDIF #
								</td>
								<td class="forum-subject-nb">
									{subcats.TOPICS_NUMBER}
								</td>
								<td class="forum-message-nb">
									{subcats.MESSAGES_NUMBER}
								</td>
								<td class="forum-last-topic">
									# IF subcats.C_LAST_TOPIC_MSG #
										<span class="d-block">
											<i class="far fa-comment fa-fw"></i>
											<a href="{subcats.U_LAST_TOPIC}">{subcats.LAST_TOPIC_TITLE}</a>
										</span>
										<span class="d-block">
											<i class="fa fa-hand-point-right fa-fw" aria-hidden="true"></i>
											<a href="{subcats.U_LAST_MESSAGE}">{subcats.LAST_MESSAGE_DATE_FULL}</a>
										</span>
										<span class="d-block">
											<i class="far fa-user fa-fw"></i>
											# IF subcats.C_LAST_MESSAGE_GUEST #
												<a href="{subcats.U_LAST_USER_PROFILE}" class="small {subcats.LAST_USER_LEVEL}"# IF subcats.C_LAST_USER_GROUP_COLOR #  style="color:{subcats.LAST_USER_GROUP_COLOR}"# ENDIF #>{subcats.LAST_USER_LOGIN}</a>
											# ELSE #
												<span class="small">{@user.guest}</span>
											# ENDIF #
										</span>

									# ELSE #
										<span class="message-helper bgc notice">{@forum.no.message.now}</span>
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

<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-forum" class="forum-content">
	<header>
		<!-- <div class="controls align-right small">
			# START syndication_cats #
				# IF syndication_cats.C_DISPLAY_RAQUO # <i class="fa fa-angle-double-right" aria-hidden="true"></i> # ENDIF #<a href="{syndication_cats.LINK}">{syndication_cats.LABEL}</a>
			# END syndication_cats #
		</div> -->
		<div class="flex-between">
			<h2>
				{CURRENT_SUBCAT_NAME}
			</h2>
			<div class="controls align-right">
				# IF C_POST_NEW_TOPIC #
					<a href="{U_POST_NEW_SUBJECT}" class="button bgc member small">{@forum.post.new.topic}</a>
				# ENDIF #
				<a href="${relative_url(SyndicationUrlBuilder::rss('forum',CATEGORY_ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning"></i></a>
				# IF CATEGORY_ID #<a href="unread.php?cat={CATEGORY_ID}" aria-label="{@forum.unread.messages}"><i class="far fa-file-alt" aria-hidden="true"></i></a># ENDIF #
				# IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #
			</div>
		</div>
	</header>
	<div class="content">
		<table class="table forum-table">
			<thead>
				<tr>
					# IF C_CONTROLS #<th class="w5" aria-label="{@common.moderation}"><i class="fa fa-gavel" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.topics}</span></th># ENDIF #
					<th class="forum-announce-topic" aria-label="{@forum.topic.status}"><i class="far fa-flag" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.topics}</span></th>
					<th class="forum-fixed-topic" aria-label="{@forum.topic.options}"><i class="fa fa-check success" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.topic.options}</span></th>
					<th class="forum-topic" aria-label="{@forum.topics}"><i class="far fa-file hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.topics}</span></th>
					<th class="forum-author" aria-label="{@forum.topic.author}"><i class="far fa-user fa-fw hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.topic.author}</span></th>
					<th class="forum-message-nb" aria-label="{@forum.answers.number}"><i class="far fa-comments fa-fw hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.answers.number}</span></th>
					<th class="forum-view" aria-label="{@forum.views.number}"><i class="far fa-eye fa-fw hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.views.number}</span></th>
					<th class="forum-last-topic" aria-label="{@forum.last.messages}"><i class="far fa-clock fa-fw hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.last.messages}</span></th>
				</tr>
			</thead>
			<tbody>
				# IF C_NO_UNREAD_MESSAGE #
					<tr>
						<td colspan="# IF C_CONTROLS #8# ELSE #7# ENDIF #">
							<div class="message-helper bgc notice">{@forum.no.message.now}</div>
						</td>
					</tr>
				# ENDIF #

				# START topics #
					<tr class="category-{topics.CATEGORY_ID}">
						# IF C_CONTROLS #
							<td class="forum-mass-modo">
								<label class="checkbox" for="modo{topics.ID}">
									<input id="modo{topics.ID}" type="checkbox" name="ck{topics.ID}">
									<span>&nbsp;</span>
								</label>
							</td>
						# ENDIF #
						<td class="forum-announce-topic">
							# IF NOT topics.C_HOT_TOPIC #
								<i class="fa {topics.TOPIC_ICON}" aria-hidden="true"></i>
							# ELSE #
								<i class="fa # IF topics.C_BLINK #blink # ENDIF #{topics.TOPIC_ICON}-hot" aria-hidden="true"></i>
							# ENDIF #
						</td>
						<td class="forum-fixed-topic">
							# IF topics.C_DISPLAY_ISSUE_STATUS #<i class="fa fa-check success" aria-hidden="true"></i># ENDIF #
							# IF topics.C_IMG_POLL #<i class="fa fa-poll-h" aria-hidden="true"></i># ENDIF #
							# IF topics.C_IMG_TRACK #<i class="fa fa-heartbeat error" aria-hidden="true"></i># ENDIF #
						</td>
						<td class="forum-topic">
							# IF topics.C_ANCHOR #<a href="{topics.U_ANCHOR}"><i class="fa fa-hand-point-right" aria-hidden="true"></i></a># ENDIF #
							# IF topics.TYPE # <strong>{topics.TYPE}</strong> # ENDIF #
							<a href="topic{topics.U_TOPIC}">{topics.L_ISSUE_STATUS_MESSAGE} {topics.TITLE}</a>
							<span class="small d-block">{topics.DESCRIPTION}</span>
						</td>
						<td class="forum-author">
							# IF topics.C_AUTHOR #
								<a href="{topics.U_AUTHOR}" class="small {topics.AUTHOR_LEVEL}"# IF topics.C_GROUP_COLOR # style="color:{topics.GROUP_COLOR}"# ENDIF #>{topics.AUTHOR}</a>
							# ELSE #
								<span class="small">{@user.guest}</span>
							# ENDIF #
						</td>
						<td class="forum-message-nb">
							{topics.MESSAGES_NUMBER}
						</td>
						<td class="forum-view">
							{topics.VIEWS_NUMBER}
						</td>
						<td class="forum-last-topic">
							<span class="d-block">
								<i class="far fa-hand-point-right fa-fw" aria-hidden="true"></i>
								<a href={topics.U_LAST_MESSAGE} aria-label="{@forum.see.message}">{topics.LAST_MESSAGE_DATE_FULL}</a>
							</span>
							<span class="d-block">
								<i class="far fa-user fa-fw" aria-hidden="true"></i>
								# IF topics.C_LAST_MESSAGE_GUEST #
									<a href="{topics.U_LAST_USER_PROFILE}" class="small {topics.LAST_USER_LEVEL}"# IF topics.C_LAST_USER_GROUP_COLOR # style="color:{topics.LAST_USER_GROUP_COLOR}"# ENDIF #>{topics.LAST_USER_LOGIN}</a>
								# ELSE #
									<span class="small">{@user.guest}</span>
								# ENDIF #
							</span>
						</td>
					</tr>
				# END topics #

				# IF C_NO_TOPICS #
					<tr>
						<td colspan="# IF C_CONTROLS #8# ELSE #7# ENDIF #">
							<div class="message-helper bgc notice">{@forum.no.topic}</div>
						</td>
					</tr>
				# ENDIF #
			</tbody>
			<tfoot>
				<tr>
					<th colspan="# IF C_CONTROLS #8# ELSE #7# ENDIF #">
						<div class="footer-forum">
							<a href="${relative_url(SyndicationUrlBuilder::rss('forum',CATEGORY_ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
							# START syndication_cats #
								# IF syndication_cats.C_DISPLAY_RAQUO # <i class="fa fa-angle-double-right" aria-hidden="true"></i> # ENDIF #<a href="{syndication_cats.LINK}">{syndication_cats.LABEL}</a>
							# END syndication_cats #
							# IF C_POST_NEW_TOPIC #
								<i class="fa fa-angle-double-right" aria-hidden="true"></i> <a href="{U_POST_NEW_SUBJECT}" class="button bgc member small">{@forum.post.new.topic}</a>
							# ENDIF #
						</div>
						# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
</article>

# INCLUDE FORUM_BOTTOM #
