		# INCLUDE forum_top #

		# START error_auth_write #
		<div class="forum-text-column" style="width:350px;margin:auto;height:auto;padding:2px;margin-bottom:20px;">
			{error_auth_write.L_ERROR_AUTH_WRITE}
		</div>
		# END error_auth_write #

		# IF C_FORUM_SUB_CATS #
			<div class="module-position">
				<div class="module-top-l"></div>
				<div class="module-top-r"></div>
				<div class="module-top">
					<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
					&nbsp;&nbsp;<strong>{L_SUBFORUMS}</strong>
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
								<th colspan="6">
								</th>
							</tr>
						</tfoot>
						<tbody>
				# START subcats #
							<tr>
								# IF subcats.U_FORUM_URL #
								<td class="forum-sous-cat" style="width:40px;text-align:center;">
									<i class="fa fa-globe fa-2x"></i>
								</td>
								<td class="forum-sous-cat" style="min-width:150px;border-right:none" colspan="4">
									<a href="{subcats.U_FORUM_URL}">{subcats.NAME}</a>
									<br />
									<span class="smaller">{subcats.DESC}</span>
								</td>
								# ELSE #
								<td class="forum-sous-cat" style="width:40px;text-align:center;">
									<i class="fa # IF subcats.C_BLINK #blink # ENDIF #{subcats.IMG_ANNOUNCE}"></i>
								</td>
								<td class="forum-sous-cat" style="min-width:150px;">
									<a href="forum{subcats.U_FORUM_VARS}">{subcats.NAME}</a>
									<br />
									<span class="smaller">{subcats.DESC}</span>
									<span class="smaller">{subcats.SUBFORUMS}</span>
								</td>
								<td class="forum-sous-cat-compteur">
									{subcats.NBR_TOPIC}
								</td>
								<td class="forum-sous-cat-compteur">
									{subcats.NBR_MSG}
								</td>
								<td class="forum-sous-cat-last">
									{subcats.U_LAST_TOPIC}
								</td>
								# ENDIF #
							</tr>
				# END subcats #
						</tbody>
					</table>
				</div>
			</div>
		# ENDIF #

		<div class="module-position">
			<div class="module-top-l"></div>
			<div class="module-top-r"></div>
			<div class="module-top">
				<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a> &bull; {U_FORUM_CAT}
				# IF C_POST_NEW_SUBJECT #
					&raquo; <a href="{U_POST_NEW_SUBJECT}" class="basic-button">{L_POST_NEW_SUBJECT}</a>
				# ENDIF #
				<span style="float:right;">
					# IF IDCAT #
					<a href="unread.php?cat={IDCAT}" title="{L_DISPLAY_UNREAD_MSG}"><i class="fa fa-notread"></i></a>
					# ENDIF #
					# IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #
				</span>
			</div>
			<div class="module-contents forum-contents">
				<table class="forum-table">
					<thead>
						<tr>
							<th class="forum-text-column" colspan="3">{L_TOPIC}</th>
							<th class="forum-text-column">{L_AUTHOR}</th>
							<th class="forum-text-column">{L_ANSWERS}</th>
							<th class="forum-text-column">{L_VIEW}</th>
							<th class="forum-text-column">{L_LAST_MESSAGE}</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="7">
								<div style="float:left;">
									<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a> &bull; {U_FORUM_CAT}
									# IF C_POST_NEW_SUBJECT #
										&raquo; <a href="{U_POST_NEW_SUBJECT}" class="basic-button">{L_POST_NEW_SUBJECT}</a>
									# ENDIF #
								</div>
								# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
							</th>
						</tr>
					</tfoot>
					# IF C_NO_MSG_NOT_READ #
					<tr>
						<td colspan="7" class="forum-sous-cat" style="text-align:center;">
							<strong>{L_MSG_NOT_READ}</strong>
						</td>
					</tr>
					# ENDIF #

					# START topics #
					<tr>
						# IF C_MASS_MODO_CHECK #
						<td class="forum-sous-cat" style="width:25px;text-align:center;">
							<input type="checkbox" name="ck{topics.ID}">
						</td>
						# ENDIF #
						<td class="forum-sous-cat" style="width:40px;text-align:center;">
							# IF NOT topics.C_HOT_TOPIC #
							<i class="fa {topics.IMG_ANNOUNCE}"></i>
							# ELSE #
							<i class="fa # IF topics.C_BLINK #blink # ENDIF #{topics.IMG_ANNOUNCE}-hot"></i>
							# ENDIF #
						</td>
						<td class="forum-sous-cat" style="width:35px;text-align:center;">
							# IF topics.C_DISPLAY_MSG #<i class="fa fa-msg-display"></i># ENDIF #
							# IF topics.C_IMG_POLL #<i class="fa fa-tasks" title="{L_POLL}"></i># ENDIF #
							# IF topics.C_IMG_TRACK #<i class="fa fa-msg-track"></i># ENDIF #
						</td>
						<td class="forum-sous-cat" style="min-width:115px;">
							# IF topics.C_PAGINATION #<span class="pagin-forum"># INCLUDE topics.PAGINATION #</span># ENDIF #
							{topics.ANCRE} <strong>{topics.TYPE}</strong> <a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
							<br />
							<span class="smaller">{topics.DESC}</span>
						</td>
						<td class="forum-sous-cat-compteur" style="width:100px;">
							{topics.AUTHOR}
						</td>
						<td class="forum-sous-cat-compteur">
							{topics.MSG}
						</td>
						<td class="forum-sous-cat-compteur">
							{topics.VUS}
						</td>
						<td class="forum-sous-cat-last">
							{topics.U_LAST_MSG}
						</td>
					</tr>
					# END topics #

					# IF C_NO_TOPICS #
					<tr>
						<td colspan="7" class="forum-sous-cat" style="text-align:center;">
							<strong>{L_NO_TOPICS}</strong>
						</td>
					</tr>
					# ENDIF #
				</table>
			</div>
		</div>



		# INCLUDE forum_bottom #
