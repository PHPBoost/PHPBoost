		# INCLUDE forum_top #

		# START error_auth_write #
		<div class="forum_text_column" style="width:350px;margin:auto;height:auto;padding:2px;margin-bottom:20px;">
			{error_auth_write.L_ERROR_AUTH_WRITE}
		</div>
		# END error_auth_write #

		# IF C_FORUM_SUB_CATS #
		<div style="margin-top:20px;margin-bottom:20px;">
			<div class="module_position">
				<div class="module_top_l"></div>
				<div class="module_top_r"></div>
				<div class="module_top">
					<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
					&nbsp;&nbsp;<strong>{L_SUBFORUMS}</strong>
				</div>
				<div class="module_contents forum_contents">
					<table class="module_table forum_table">
						<tr>
							<td class="forum_text_column" style="min-width:175px;">{L_FORUM}</td>
							<td class="forum_text_column" style="width:60px;">{L_TOPIC}</td>
							<td class="forum_text_column" style="width:60px;">{L_MESSAGE}</td>
							<td class="forum_text_column" style="width:150px;">{L_LAST_MESSAGE}</td>
						</tr>
					</table>
				</div>
			</div>
			# START subcats #
			<div class="module_position">
				<div class="module_contents forum_contents">
					<table class="module_table forum_table">
						<tr>
							# IF subcats.U_FORUM_URL #
							<td class="forum_sous_cat" style="width:25px;text-align:center;">
								<img src="{PICTURES_DATA_PATH}/images/weblink.png" alt="" />
							</td>
							<td class="forum_sous_cat" style="min-width:150px;border-right:none" colspan="3">
								<a href="{subcats.U_FORUM_URL}">{subcats.NAME}</a>
								<br />
								<span class="text_small">{subcats.DESC}</span>
							</td>
							# ELSE #
							<td class="forum_sous_cat" style="width:25px;text-align:center;">
								<img src="{PICTURES_DATA_PATH}/images/{subcats.IMG_ANNOUNCE}.png" alt="" />
							</td>
							<td class="forum_sous_cat" style="min-width:150px;">
								<a href="forum{subcats.U_FORUM_VARS}">{subcats.NAME}</a>
								<br />
								<span class="text_small">{subcats.DESC}</span>
								<span class="text_small">{subcats.SUBFORUMS}</span>
							</td>
							<td class="forum_sous_cat_compteur">
								{subcats.NBR_TOPIC}
							</td>
							<td class="forum_sous_cat_compteur">
								{subcats.NBR_MSG}
							</td>
							<td class="forum_sous_cat_last">
								{subcats.U_LAST_TOPIC}
							</td>
							# ENDIF #
						</tr>
					</table>
				</div>
			</div>
			# END subcats #
			<div class="module_position">
				<div class="module_bottom_l"></div>
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>
		</div>
		# ENDIF #

		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
				<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" title="Rss"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a> &bull; {U_FORUM_CAT}
				# IF C_POST_NEW_SUBJECT #
					&raquo; <a href="{U_POST_NEW_SUBJECT}" title="{L_POST_NEW_SUBJECT}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/post.png" alt="{L_POST_NEW_SUBJECT}" title="{L_POST_NEW_SUBJECT}" /></a>
				# ENDIF #
				<span style="float:right;">
					# IF IDCAT #
					<a href="unread.php?cat={IDCAT}" title="{L_DISPLAY_UNREAD_MSG}"><img src="{PICTURES_DATA_PATH}/images/new_mini.png" alt="" class="valign_middle" /></a>
					# ENDIF #
					{PAGINATION}
				</span>
			</div>
			<div class="module_contents forum_contents">
				<table class="module_table forum_table">
					<tr>
						<td class="forum_text_column" style="min-width:175px;">{L_TOPIC}</td>
						<td class="forum_text_column" style="width:100px;">{L_AUTHOR}</td>
						<td class="forum_text_column" style="width:60px;">{L_ANSWERS}</td>
						<td class="forum_text_column" style="width:60px;">{L_VIEW}</td>
						<td class="forum_text_column" style="width:150px;">{L_LAST_MESSAGE}</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="module_position">
			<div class="module_contents forum_contents">
				<table class="module_table forum_table">
					# IF C_NO_MSG_NOT_READ #
					<tr>
						<td class="forum_sous_cat" style="text-align:center;">
							<strong>{L_MSG_NOT_READ}</strong>
						</td>
					</tr>
					# ENDIF #

					# START topics #
					<tr>
						# IF C_MASS_MODO_CHECK #
						<td class="forum_sous_cat" style="width:25px;text-align:center;">
							<input type="checkbox" name="ck{topics.ID}" />
						</td>
						# ENDIF #
						<td class="forum_sous_cat" style="width:25px;text-align:center;">
							# IF NOT topics.C_HOT_TOPIC #
							<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}.png" alt="" />
							# ELSE #
							<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}_hot.gif" alt="" />
							# ENDIF #
						</td>
						<td class="forum_sous_cat" style="width:35px;text-align:center;">
							# IF topics.C_DISPLAY_MSG # <img src="{PICTURES_DATA_PATH}/images/msg_display_mini.png" alt="" class="valign_middle" /> # ENDIF #
							# IF topics.C_IMG_POLL # <img src="{PICTURES_DATA_PATH}/images/poll_mini.png" class="valign_middle" alt="" /> # ENDIF #
							# IF topics.C_IMG_TRACK # <img src="{PICTURES_DATA_PATH}/images/track_mini.png" class="valign_middle" alt="" /> # ENDIF #
						</td>
						<td class="forum_sous_cat" style="min-width:115px;">
							{topics.ANCRE} <strong>{topics.TYPE}</strong> <a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
							<br />
							<span class="text_small">{topics.DESC}</span> &nbsp;<span class="pagin_forum">{topics.PAGINATION_TOPICS}</span>
						</td>
						<td class="forum_sous_cat_compteur" style="width:100px;">
							{topics.AUTHOR}
						</td>
						<td class="forum_sous_cat_compteur">
							{topics.MSG}
						</td>
						<td class="forum_sous_cat_compteur">
							{topics.VUS}
						</td>
						<td class="forum_sous_cat_last">
							{topics.U_LAST_MSG}
						</td>
					</tr>
					# END topics #

					# IF C_NO_TOPICS #
					<tr>
						<td class="forum_sous_cat" style="text-align:center;">
							<strong>{L_NO_TOPICS}</strong>
						</td>
					</tr>
					# ENDIF #
				</table>
			</div>
		</div>

		<div class="module_position">
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" title="Rss"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a> &bull; {U_FORUM_CAT}
				# IF C_POST_NEW_SUBJECT #
					&raquo; <a href="{U_POST_NEW_SUBJECT}" title="{L_POST_NEW_SUBJECT}"><img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/post.png" alt="{L_POST_NEW_SUBJECT}" title="{L_POST_NEW_SUBJECT}" /></a>
				# ENDIF #
				<span style="float:right;">{PAGINATION}</span>&nbsp;
			</div>
		</div>

		# INCLUDE forum_bottom #
