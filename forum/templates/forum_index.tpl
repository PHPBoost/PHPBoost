	# INCLUDE forum_top #
		
	# START forums_list #
		# START forums_list.endcats #
					</tbody>
				</table>
			</div>
		</div>

		# END forums_list.endcats #
			
		# START forums_list.cats #
		<div class="module-position">
			<div class="module-top-l"></div>
			<div class="module-top-r"></div>
			<div class="module-top">
				<span class="forum-cat-title">
					<a href="${relative_url(SyndicationUrlBuilder::rss('forum',forums_list.cats.IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
					&nbsp;&nbsp;<a href="{forums_list.cats.U_FORUM_VARS}" class="forum-link-cat">{forums_list.cats.NAME}</a>
				</span>
				# IF C_DISPLAY_UNREAD_DETAILS #
				<span style="float:right">
					<a href="{PATH_TO_ROOT}/forum/unread.php?cat={forums_list.cats.IDCAT}" title="{L_DISPLAY_UNREAD_MSG}"><i class="fa fa-notread"></i></a>
				</span>
				# ENDIF #
			</div>
			<div class="module-contents forum-contents">
				<table class="forum-table">
					<thead>
						<tr>
							<th class="forum-text-column" colspan="2">{L_FORUM}</th>
							<th class="forum-text-column">{L_TOPIC}</th>
							<th class="forum-text-column">{L_MESSAGE}</th>
							<th class="forum-text-column">{L_LAST_MESSAGE}</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="5">
							</th>
						</tr>
					</tfoot>
					<tbody>
		# END forums_list.cats #
		# START forums_list.subcats #
						<tr>
							# IF forums_list.subcats.U_FORUM_URL #
							<td class="forum-sous-cat-img">
								<i class="fa fa-globe"></i>
							</td>
							<td class="forum-sous-cat" colspan="4">
								<a href="{forums_list.subcats.U_FORUM_URL}">{forums_list.subcats.NAME}</a>
								<br />
								<span class="smaller">{forums_list.subcats.DESC}</span>
							</td>
							# ELSE #
							<td class="forum-sous-cat-img">
								<i class="fa # IF forums_list.subcats.C_BLINK #blink # ENDIF #{forums_list.subcats.IMG_ANNOUNCE}"></i>
							</td>
							<td class="forum-sous-cat" style="min-width:150px;">
								<a href="{forums_list.subcats.U_FORUM_VARS}">{forums_list.subcats.NAME}</a>
								<br />
								<span class="smaller">{forums_list.subcats.DESC}</span>
								<span class="smaller">{forums_list.subcats.SUBFORUMS}</span>
							</td>
							<td class="forum-sous-cat-compteur">
								{forums_list.subcats.NBR_TOPIC}
							</td>
							<td class="forum-sous-cat-compteur">
								{forums_list.subcats.NBR_MSG}
							</td>
							<td class="forum-sous-cat-last">
								{forums_list.subcats.U_LAST_TOPIC}
							</td>
							# ENDIF #
						</tr>
		# END forums_list.subcats #
		
	# END forums_list #
		
		# INCLUDE forum_bottom #
		