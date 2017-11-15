
	# INCLUDE forum_top #

	# START forums_list #
		# START forums_list.endcats #
					</tbody>
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

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-{forums_list.cats.IDCAT}">
			<header>
				<h2>
					<span class="forum-cat-title">
						<a href="${relative_url(SyndicationUrlBuilder::rss('forum',forums_list.cats.IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'common')}"></a>
						&nbsp;&nbsp;<a href="{forums_list.cats.U_FORUM_VARS}" class="forum-link-cat" title="{forums_list.cats.NAME}">{forums_list.cats.NAME}</a>
					</span>
					# IF C_DISPLAY_UNREAD_DETAILS #
					<span class="float-right">
						<a href="{PATH_TO_ROOT}/forum/unread.php?cat={forums_list.cats.IDCAT}" title="{L_DISPLAY_UNREAD_MSG}"><i class="fa fa-notread"></i></a>
					</span>
					# ENDIF #
				</h2>
			</header>
			<div class="module-contents forum-contents">
				<table id="table-{forums_list.cats.IDCAT}" class="forum-table">
					<thead>
						<tr>
							<th class="forum-announce-topic"><i class="fa fa-eye"></i></th>
							<th class="forum-topic">{L_FORUM}</th>
							<th class="forum-subject-nb">{L_TOPIC}</th>
							<th class="forum-message-nb">{L_MESSAGE}</th>
							<th class="forum-last-topic">{L_LAST_MESSAGE}</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="5">
							</td>
						</tr>
					</tfoot>
					<tbody>
		# END forums_list.cats #
		# START forums_list.subcats #
						<tr>
							# IF forums_list.subcats.U_FORUM_URL #
							<td class="forum-announce-topic">
								<i class="fa fa-globe"></i>
							</td>
							<td class="forum-topic" colspan="4">
								<a href="{forums_list.subcats.U_FORUM_URL}" title="{forums_list.subcats.NAME}">{forums_list.subcats.NAME}</a>
								<br />
								<span class="smaller">{forums_list.subcats.DESC}</span>
							</td>
							# ELSE #
							<td class="forum-announce-topic">
								<i class="fa # IF forums_list.subcats.C_BLINK #blink # ENDIF #{forums_list.subcats.IMG_ANNOUNCE}"></i>
							</td>
							<td class="forum-topic">
								<a href="{forums_list.subcats.U_FORUM_VARS}" title="{forums_list.subcats.NAME}">{forums_list.subcats.NAME}</a>
								<br />
								<span class="smaller">{forums_list.subcats.DESC}</span>
								<span class="small">{forums_list.subcats.SUBFORUMS}</span>
							</td>
							<td class="forum-subject-nb">
								{forums_list.subcats.NBR_TOPIC}
							</td>
							<td class="forum-message-nb">
								{forums_list.subcats.NBR_MSG}
							</td>
							<td class="forum-last-topic">
							# IF forums_list.subcats.C_LAST_TOPIC_MSG #
								<span class="last-topic-date"><a href="{forums_list.subcats.U_LAST_TOPIC}" class="last-topic-title small">{forums_list.subcats.LAST_TOPIC_TITLE}</a>
								</span><br />
								<span class="last-topic-date">
									<a href="{forums_list.subcats.U_LAST_MSG}" title=""><i class="fa fa-hand-o-right"></i></a> ${LangLoader::get_message('on', 'main')} {forums_list.subcats.LAST_MSG_DATE_FULL}
								</span><br />
								<span class="last-topic-user">
									${LangLoader::get_message('by', 'main')}
									# IF forums_list.subcats.C_LAST_MSG_GUEST #
									<a href="{forums_list.subcats.U_LAST_MSG_USER_PROFIL}" class=" small {forums_list.subcats.LAST_MSG_USER_LEVEL}" {forums_list.subcats.LAST_MSG_USER_GROUP_COLOR}>{forums_list.subcats.LAST_MSG_USER_LOGIN}</a>
									# ELSE #
									${LangLoader::get_message('guest', 'main')}
									# ENDIF #
								</span>
							# ELSE #
								<br /><em>{forums_list.subcats.L_NO_MSG}</em><br /><br />
							# ENDIF #
							</td>
							# ENDIF #
						</tr>
		# END forums_list.subcats #

	# END forums_list #

	# INCLUDE forum_bottom #
