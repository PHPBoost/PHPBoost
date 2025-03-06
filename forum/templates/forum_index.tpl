# INCLUDE FORUM_TOP #

# START forums_list #
	# START forums_list.endcats #
				</tbody>
				<tfoot>
					<tr>
						<td colspan="# IF C_THUMBNAILS_DISPLAYED #6# ELSE #5# ENDIF #">
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</article>

	# END forums_list.endcats #

	# START forums_list.cats #
	<script>
		jQuery('#table-{forums_list.cats.CATEGORY_ID}').basictable();
	</script>
	<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-{forums_list.cats.CATEGORY_ID}" class="forum-content">
		<header class="flex-between">
			<div class="forum-category-title">
				# IF C_THUMBNAILS_DISPLAYED #
					# IF forums_list.cats.C_HAS_THUMBNAIL #
						<img class="forum-category-thumbnail" src="{forums_list.cats.U_CATEGORY_THUMBNAIL}" alt="{forums_list.cats.CATEGORY_NAME}" />
					# ELSE #
						# IF forums_list.cats.C_HAS_CATEGORY_ICON #
							<i class="{forums_list.cats.CATEGORY_ICON}" aria-hidden="true"# IF forums_list.cats.C_HAS_CATEGORY_COLOR # style="color: {forums_list.cats.CATEGORY_COLOR}"# ENDIF #></i>
						# ENDIF #
					# ENDIF #
				# ENDIF #
				<h2 class="d-inline-block">
					<a href="{forums_list.cats.U_CATEGORY}" class="forum-link-cat offload">{forums_list.cats.CATEGORY_NAME}</a>
				</h2>
			</div>
			<div class="controls">
				<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('forum',forums_list.cats.CATEGORY_ID))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
				# IF C_DISPLAY_UNREAD_DETAILS #
					<a class="offload" href="{PATH_TO_ROOT}/forum/unread.php?cat={forums_list.cats.CATEGORY_ID}" aria-label="{@forum.unread.messages}"><i class="far fa-file-alt" aria-hidden="true"></i></a>
				# ENDIF #
			</div>
		</header>
		<div class="content">
			<table id="table-{forums_list.cats.CATEGORY_ID}" class="table forum-table">
				<thead>
					<tr>
						<th class="forum-announce-topic w5" aria-label="{@forum.topic.status}"><i class="far fa-flag" aria-hidden="true"></i></th>
						# IF C_THUMBNAILS_DISPLAYED #<th class="forum-thumbnail w10" aria-label="{@form.thumbnail}"><i class="far fa-image" aria-hidden="true"></i><span class="hidden-large-screens">{@form.thumbnail}</span></th># ENDIF #
						<th class="forum-topic" aria-label="{@forum.forum}"><i class="far fa-file hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.forum}</span></th>
						<th class="forum-topic" aria-label="{@forum.topics.number}"><i class="far fa-copy hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.topics.number}</span></th>
						<th class="forum-message-nb" aria-label="{@forum.messages.number}"><i class="far fa-comments fa-fw hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.messages.number}</span></th>
						<th class="forum-last-topic" aria-label="{@forum.last.messages}"><i class="far fa-clock fa-fw hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.last.messages}</span></th>
					</tr>
				</thead>
				<tbody>
	# END forums_list.cats #
	# START forums_list.subcats #
					<tr class="category-{forums_list.subcats.CATEGORY_ID}">
						# IF forums_list.subcats.U_LINK #
							<td class="forum-announce-topic">
								<i class="fa fa-globe" aria-hidden="true"></i>
							</td>
							# IF C_THUMBNAILS_DISPLAYED #
								<td class="forum-thumbnail">
									# IF forums_list.subcats.C_HAS_THUMBNAIL #
										<img src="{forums_list.subcats.U_CATEGORY_THUMBNAIL}" alt="{forums_list.subcats.CATEGORY_NAME}" />
									# ELSE #
										# IF forums_list.subcats.C_HAS_CATEGORY_ICON #
											<i class="{forums_list.subcats.CATEGORY_ICON}" aria-hidden="true"# IF forums_list.subcats.C_HAS_CATEGORY_COLOR # style="color: {forums_list.subcats.CATEGORY_COLOR}"# ENDIF #></i>
										# ENDIF #
									# ENDIF #
								</td>
							# ENDIF #
							<td class="forum-topic" colspan="4">
								<a class="offload" href="{forums_list.subcats.U_LINK}">{forums_list.subcats.CATEGORY_NAME}</a>
								<span class="small d-block hidden-small-screens">{forums_list.subcats.DESCRIPTION}</span>
							</td>
						# ELSE #
							<td class="forum-announce-topic">
								<i class="fa # IF forums_list.subcats.C_BLINK #blink # ENDIF #{forums_list.subcats.TOPIC_ICON}" aria-hidden="true"></i>
							</td>
							# IF C_THUMBNAILS_DISPLAYED #
								<td class="forum-thumbnail">
									# IF forums_list.subcats.C_HAS_THUMBNAIL #
										<img src="{forums_list.subcats.U_CATEGORY_THUMBNAIL}" alt="{forums_list.subcats.CATEGORY_NAME}" />
									# ELSE #
										# IF forums_list.subcats.C_HAS_CATEGORY_ICON #
											<i class="{forums_list.subcats.CATEGORY_ICON}" aria-hidden="true"# IF forums_list.subcats.C_HAS_CATEGORY_COLOR # style="color: {forums_list.subcats.CATEGORY_COLOR}"# ENDIF #></i>
										# ENDIF #
									# ENDIF #
								</td>
							# ENDIF #
							<td class="forum-topic">
								<a class="offload" href="{forums_list.subcats.U_CATEGORY}">{forums_list.subcats.CATEGORY_NAME}</a>
								<span class="small d-block hidden-small-screens">{forums_list.subcats.DESCRIPTION}</span>
								# IF forums_list.subcats.C_SUBFORUMS #<span class="small d-block"><span class="pinned notice">{@forum.sub.forums}</span> : {forums_list.subcats.SUBFORUMS}</span># ENDIF #
							</td>
							<td class="forum-subject-nb">
								{forums_list.subcats.TOPICS_NUMBER}
							</td>
							<td class="forum-message-nb">
								{forums_list.subcats.MESSAGES_NUMBER}
							</td>
							<td class="forum-last-topic">
								# IF forums_list.subcats.C_LAST_TOPIC_MSG #
									<span class="d-block">
										<i class="far fa-comments fa-fw" aria-hidden="true"></i>
										<a class="offload" href="{forums_list.subcats.U_LAST_TOPIC}">{forums_list.subcats.LAST_TOPIC_TITLE}</a>
									</span>
									<span class="d-block">
										<i class="far fa-hand-point-right fa-fw" aria-hidden="true"></i>
										<a class="offload" href="{forums_list.subcats.U_LAST_MESSAGE}">{forums_list.subcats.LAST_MESSAGE_DATE_FULL}</a>
									</span>
									<span class="d-block">
										<i class="far fa-user fa-fw"></i>
										# IF forums_list.subcats.C_LAST_MESSAGE_GUEST #
											<a href="{forums_list.subcats.U_LAST_USER_PROFILE}" class="{forums_list.subcats.LAST_USER_LEVEL} offload"# IF forums_list.subcats.C_LAST_USER_GROUP_COLOR # style="color:{forums_list.subcats.LAST_USER_GROUP_COLOR}"# ENDIF #>{forums_list.subcats.LAST_USER_LOGIN}</a>
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
	# END forums_list.subcats #

# END forums_list #

# INCLUDE FORUM_BOTTOM #
