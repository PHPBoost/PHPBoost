
	# INCLUDE forum_top #

	# START forums_list #
		# START forums_list.endcats #
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</article>

		# END forums_list.endcats #

		# START forums_list.cats #
		<script>
			<!--
			jQuery('#table-{forums_list.cats.IDCAT}').basictable();
			-->
		</script>

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-{forums_list.cats.IDCAT}" class="forum-contents">
			<header>
				<h2>
					<span class="forum-cat-title">
						<a href="${relative_url(SyndicationUrlBuilder::rss('forum',forums_list.cats.IDCAT))}" aria-label="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication" aria-hidden="true"></i></a>
						&nbsp;<a href="{forums_list.cats.U_FORUM_VARS}" class="forum-link-cat">{forums_list.cats.NAME}</a>
					</span>
					# IF C_DISPLAY_UNREAD_DETAILS #
					<span class="float-right">
						<a href="{PATH_TO_ROOT}/forum/unread.php?cat={forums_list.cats.IDCAT}" aria-label="{L_DISPLAY_UNREAD_MSG}"><i class="fa fa-notread" aria-hidden="true"></i></a>
					</span>
					# ENDIF #
				</h2>
			</header>
			<div class="content">
				<table id="table-{forums_list.cats.IDCAT}" class="table forum-table">
					<thead>
						<tr>
							<th class="forum-announce-topic"><i class="fa fa-eye" aria-hidden="true"></i></th>
							<th class="forum-topic" aria-label="{L_FORUM}"><i class="fa fa-file-o hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_FORUM}</span></th>
							<th class="forum-topic" aria-label="{L_TOPIC}"><i class="fa fa-files-o hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_TOPIC}</span></th>
							<th class="forum-message-nb" aria-label="{L_MESSAGE}"><i class="fa fa-comments-o fa-fw hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_MESSAGE}</span></th>
							<th class="forum-last-topic" aria-label="{L_LAST_MESSAGE}"><i class="fa fa-clock-o fa-fw hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{L_LAST_MESSAGE}</span></th>
						</tr>
					</thead>
					<tbody>
		# END forums_list.cats #
		# START forums_list.subcats #
						<tr>
							# IF forums_list.subcats.U_FORUM_URL #
							<td class="forum-announce-topic">
								<i class="fa fa-globe" aria-hidden="true"></i>
							</td>
							<td class="forum-topic" colspan="4">
								<a href="{forums_list.subcats.U_FORUM_URL}">{forums_list.subcats.NAME}</a>
								<span class="smaller hidden-small-screens">{forums_list.subcats.DESC}</span>
							</td>
							# ELSE #
							<td class="forum-announce-topic">
								<i class="fa # IF forums_list.subcats.C_BLINK #blink # ENDIF #{forums_list.subcats.IMG_ANNOUNCE}" aria-hidden="true"></i>
							</td>
							<td class="forum-topic">
								<a href="{forums_list.subcats.U_FORUM_VARS}">{forums_list.subcats.NAME}</a>
								<span class="smaller hidden-small-screens">{forums_list.subcats.DESC}</span>
								# IF forums_list.subcats.C_SUBFORUMS #<span class="small"><span class="strong">{forums_list.subcats.L_SUBFORUMS} : </span>{forums_list.subcats.SUBFORUMS}</span># ENDIF #
							</td>
							<td class="forum-subject-nb">
								{forums_list.subcats.NBR_TOPIC}
							</td>
							<td class="forum-message-nb">
								{forums_list.subcats.NBR_MSG}
							</td>
							<td class="forum-last-topic">
							# IF forums_list.subcats.C_LAST_TOPIC_MSG #
								<span class="last-topic-title">
									<a href="{forums_list.subcats.U_LAST_TOPIC}"><i class="fa fa-file-o fa-fw" aria-hidden="true"></i> {forums_list.subcats.LAST_TOPIC_TITLE}</a>
								</span>
								<span class="last-topic-date">
									<a href="{forums_list.subcats.U_LAST_MSG}"><i class="fa fa-hand-o-right fa-fw" aria-hidden="true"></i> {forums_list.subcats.LAST_MSG_DATE_FULL}</a>
								</span>
								<span class="last-topic-user">
									<i class="fa fa-user-o fa-fw"></i>
									# IF forums_list.subcats.C_LAST_MSG_GUEST #
										<a href="{forums_list.subcats.U_LAST_MSG_USER_PROFIL}" class="{forums_list.subcats.LAST_MSG_USER_LEVEL}"# IF forums_list.subcats.C_LAST_MSG_USER_GROUP_COLOR # style="color:{forums_list.subcats.LAST_MSG_USER_GROUP_COLOR}"# ENDIF #>{forums_list.subcats.LAST_MSG_USER_LOGIN}</a>
									# ELSE #
										${LangLoader::get_message('guest', 'main')}
									# ENDIF #
								</span>
							# ELSE #
								<em>{forums_list.subcats.L_NO_MSG}</em>
							# ENDIF #
							</td>
							# ENDIF #
						</tr>
		# END forums_list.subcats #

	# END forums_list #

	# INCLUDE forum_bottom #
