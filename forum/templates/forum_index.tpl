		# INCLUDE forum_top #
			
	# START forums_list #
		# START forums_list.endcats #
		<div class="module_position">
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>

		# END forums_list.endcats #
			
		# START forums_list.cats #
		<div style="margin-top:20px;">
			<div class="module_position">
				<div class="module_top_l"></div>
				<div class="module_top_r"></div>
				<div class="module_top">
					<span class="forum_cat_title">
						<a href="${relative_url(SyndicationUrlBuilder::rss('forum',forums_list.cats.IDCAT))}" class="fa fa-syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
						&nbsp;&nbsp;<a href="{forums_list.cats.U_FORUM_VARS}" class="forum_link_cat">{forums_list.cats.NAME}</a>
					</span>
					<span style="float:right">
						<a href="{PATH_TO_ROOT}/forum/unread.php?cat={forums_list.cats.IDCAT}" title="{L_DISPLAY_UNREAD_MSG}"><i class="fa fa-notread"></i></a>
					</span>
				</div>
				<div class="module_contents forum_contents">
					<table class="module-table forum_table">
						<tr>
							<td class="forum_text_column" style="min-width:175px;">{L_FORUM}</td>
							<td class="forum_text_column" style="width:60px;">{L_TOPIC}</td>
							<td class="forum_text_column" style="width:60px;">{L_MESSAGE}</td>
							<td class="forum_text_column" style="width:150px;">{L_LAST_MESSAGE}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		# END forums_list.cats #
		# START forums_list.subcats #
		<div class="module_position">
			<div class="module_contents forum_contents">
				<table class="module-table forum_table">
					<tr>
						# IF forums_list.subcats.U_FORUM_URL #
						<td class="forum_sous_cat" style="width:25px;text-align:center;">
							<i class="fa fa-globe"></i>
						</td>
						<td class="forum_sous_cat" style="min-width:150px;border-right:none" colspan="3">
							<a href="{forums_list.subcats.U_FORUM_URL}">{forums_list.subcats.NAME}</a>
							<br />
							<span class="smaller">{forums_list.subcats.DESC}</span>
						</td>
						# ELSE #
						<td class="forum_sous_cat" style="width:30px;text-align:center;">
							<i class="{forums_list.subcats.IMG_ANNOUNCE}"></i>
						</td>
						<td class="forum_sous_cat" style="min-width:150px;">
							<a href="{forums_list.subcats.U_FORUM_VARS}">{forums_list.subcats.NAME}</a>
							<br />
							<span class="smaller">{forums_list.subcats.DESC}</span>
							<span class="smaller">{forums_list.subcats.SUBFORUMS}</span>
						</td>
						<td class="forum_sous_cat_compteur">
							{forums_list.subcats.NBR_TOPIC}
						</td>
						<td class="forum_sous_cat_compteur">
							{forums_list.subcats.NBR_MSG}
						</td>
						<td class="forum_sous_cat_last">
							{forums_list.subcats.U_LAST_TOPIC}
						</td>
						# ENDIF #
					</tr>
				</table>
			</div>
		</div>
		# END forums_list.subcats #
	# END forums_list #
		
		# INCLUDE forum_bottom #
		