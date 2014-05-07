		# INCLUDE forum_top #

		# START error_auth_write #
		<div class="forum-text-column" style="width:350px;margin:auto;height:auto;padding:2px;margin-bottom:20px;">
			{error_auth_write.L_ERROR_AUTH_WRITE}
		</div>
		# END error_auth_write #

		# IF C_FORUM_SUB_CATS #
		<div style="margin-top:20px;margin-bottom:20px;">
			<div class="module-position forum_position_cat">
				<div class="module-top-l"></div>
				<div class="module-top-r"></div>
				<div class="module-top forum_top_cat">
					<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
					&nbsp;&nbsp;<strong>{L_SUBFORUMS}</strong>
				</div>
			</div>

			<div class="module-position forum_position_subcat">
				<div class="forum_position_subcat-top"></div>
			</div>			

			# START subcats #
			<div class="module-position forum_position_subcat">
				<div class="module-contents forum-contents forum_contents_subcat">
					<table class="module-table forum-table">
						<tr>
							# IF subcats.U_FORUM_URL #
							<td class="forum-sous-cat" style="width:40px;text-align:center;">
								<i class="fa fa-globe fa-2x"></i>
							</td>
							<td class="forum-sous-cat" style="min-width:150px;border-right:none" colspan="3">
								<a href="{subcats.U_FORUM_URL}">{subcats.NAME}</a>
								<br />
								<span class="smaller">{subcats.DESC}</span>
							</td>
							# ELSE #
							<td class="forum-sous-cat" style="width:40px;text-align:center;">
								<img src="{PICTURES_DATA_PATH}/images/{subcats.IMG_ANNOUNCE}.png" alt="" />
							</td>
							<td class="forum-sous-cat" style="min-width:150px;">
								<a href="forum{subcats.U_FORUM_VARS}">{subcats.NAME}</a>
								<br />
								<span class="smaller">{subcats.DESC}</span>
								<span class="smaller">{subcats.SUBFORUMS}</span>
							</td>
							<td class="forum-sous-cat-compteur_nbr forum-sous-cat-compteur">
								{subcats.NBR_TOPIC}<BR />{subcats.NBR_MSG}
							</td>
							<td class="forum-sous-cat-compteur_text forum-sous-cat-compteur">
								{L_TOPIC}<BR />{L_MESSAGE}
							</td>
							<td class="forum-sous-cat-last">
								{subcats.U_LAST_TOPIC}
							</td>

							# ENDIF #
						</tr>
					</table>
				</div>
			</div>
			# END subcats #
			<div class="module-position forum_position_subcat">
				<div class="forum_position_subcat-bottom"></div>
			</div>
			<div class="module-position">
				<div class="module-bottom-l"></div>
				<div class="module-bottom-r"></div>
				<div class="module-bottom"></div>
			</div>
		</div>
		# ENDIF #

		# IF C_POST_NEW_SUBJECT #
		<div class="module-position forum_position_cat">
			<div class="pbt-button pbt-button-gray">
				<a href="{U_POST_NEW_SUBJECT}" title="{L_POST_NEW_SUBJECT}" class="pbt-button-a pbt-button-add">
					<i class="fa fa-plus"></i> Créer un nouveau sujet
				</a>
			</div>
		</div>
		<div class="spacer"></div>
		# ENDIF #

		<div class="module-position forum_position_cat">
			<div class="module-top-l"></div>
			<div class="module-top-r"></div>
			<div class="module-top forum_top_cat">
				<a href="${relative_url(SyndicationUrlBuilder::rss('forum',IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a> &bull; {U_FORUM_CAT}
				<span style="float:right;">
					# IF IDCAT #
					<a href="unread.php?cat={IDCAT}" title="{L_DISPLAY_UNREAD_MSG}"><i class="fa fa-notread"></i></a>
					# ENDIF #
					# IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #
				</span>
			</div>
		</div>

		<div class="module-position forum_position_subcat">
			<div class="forum_position_subcat-top"></div>
		</div>
		
		<div class="module-position forum_position_subcat">
			<div class="module-contents forum-contents forum_contents_subcat">
				<table class="module-table forum-table">
					# IF C_NO_MSG_NOT_READ #
					<tr>
						<td class="forum-sous-cat" style="text-align:center;">
							<strong>{L_MSG_NOT_READ}</strong>
						</td>
					</tr>
					# ENDIF #

					# START topics #
					<tr>
						# IF C_MASS_MODO_CHECK #
						<td class="forum-sous-cat forum-sous-cat-pbt" style="width:40px;text-align:center;">
							<input type="checkbox" name="ck{topics.ID}">
						</td>
						# ENDIF #
						<td class="forum-sous-cat forum-sous-cat-pbt" style="width:40px;text-align:center;">
							# IF NOT topics.C_HOT_TOPIC #
							<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}.png" alt="" />
							# ELSE #
							<img src="{PICTURES_DATA_PATH}/images/{topics.IMG_ANNOUNCE}-hot.gif" alt="" />
							# ENDIF #
						</td>
						<td class="forum-sous-cat forum-sous-cat-pbt" style="width:35px;text-align:center;">
							# IF topics.C_DISPLAY_MSG #<i class="fa fa-msg-display"></i># ENDIF #
							# IF topics.C_IMG_POLL #<i class="fa fa-tasks" title="{L_POLL}"></i># ENDIF #
							# IF topics.C_IMG_TRACK #<i class="fa fa-msg-track"></i># ENDIF #
						</td>
						<td class="forum-sous-cat forum-sous-cat-pbt" style="min-width:115px;">
							{topics.ANCRE} <strong>{topics.TYPE}</strong> <a href="topic{topics.U_TOPIC_VARS}">{topics.L_DISPLAY_MSG} {topics.TITLE}</a>
							<br />
							<span class="smaller">{topics.DESC}</span># IF topics.C_PAGINATION # &nbsp;<span class="pagin-forum"># INCLUDE topics.PAGINATION #</span># ENDIF #
						</td>
						<td class="forum-sous-cat-compteur forum-sous-cat-pbt" style="width:100px;">
							<span class="smaller">Par </span>{topics.AUTHOR}
						</td>
						<td class="forum-sous-cat-compteur_nbr forum-sous-cat-compteur forum-sous-cat-pbt">
							{topics.MSG}<BR />{topics.VUS}
						</td>
						<td class="forum-sous-cat-compteur_text forum-sous-cat-compteur forum-sous-cat-pbt">
							{L_ANSWERS}
							<BR />
							{L_VIEW}
						</td>
						<td class="forum-sous-cat-last forum-sous-cat-pbt">
							{topics.U_LAST_MSG}
						</td>
					</tr>
					# END topics #

					# IF C_NO_TOPICS #
					<tr>
						<td class="forum-sous-cat" style="text-align:center;">
							<strong>{L_NO_TOPICS}</strong>
						</td>
					</tr>
					# ENDIF #
				</table>
			</div>
			<div class="forum_position_subcat">
				<div class="forum_position_subcat-bottom"></div>
			</div>
		</div>
		
		<div class="module-position">
			<div class="module-bottom-l"></div>
			<div class="module-bottom-r"></div>
			<div class="module-bottom">
				
			</div>
		</div>

		# IF C_POST_NEW_SUBJECT #
		<div class="module-position forum_position_cat">
			<div class="pbt-button pbt-button-gray">
				<a href="{U_POST_NEW_SUBJECT}" title="{L_POST_NEW_SUBJECT}" class="pbt-button-a pbt-button-add">
					<i class="fa fa-plus"></i> Créer un nouveau sujet
				</a>
			</div>
		</div>
		<div class="spacer"></div>
		# ENDIF #

		# INCLUDE forum_bottom #
