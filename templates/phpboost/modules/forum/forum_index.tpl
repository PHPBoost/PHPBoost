	# INCLUDE forum_top #
		
	# START forums_list #	
		# START forums_list.endcats #
		<div class="module_position forum_position_subcat">
			<div class="forum_position_subcat-bottom"></div>
		</div>
		<div class="module_position">
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# END forums_list.endcats #	
			
		# START forums_list.cats #		
		<div class="module_position forum_position_cat">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top forum_top_cat">
				<span class="forum_cat_title">
					<a href="${relative_url(SyndicationUrlBuilder::rss('forum',forums_list.cats.IDCAT))}" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
					&nbsp;&nbsp;<a href="{forums_list.cats.U_FORUM_VARS}" class="forum_link_cat">{forums_list.cats.NAME}</a>
				</span>
				<span style="float:right;margin-right:5px;">
					<a href="{PATH_TO_ROOT}/forum/unread.php?cat={forums_list.cats.IDCAT}" title="{L_DISPLAY_UNREAD_MSG}"><img src="{PICTURES_DATA_PATH}/images/new_mini.png" alt="" /></a>
				</span>
			</div>
		</div>
		<div class="module_position forum_position_subcat">
			<div class="forum_position_subcat-top"></div>
		</div>
		# END forums_list.cats #
		
		# START forums_list.subcats #		
		<div class="module_position forum_position_subcat">	
			<div class="module_contents forum_contents_subcat">
				<table class="module_table forum_table">
					<tr>
						# IF forums_list.subcats.U_FORUM_URL #
						<td class="forum_sous_cat" style="width:25px;text-align:center;">
							<img src="{PICTURES_DATA_PATH}/images/weblink.png" alt="" />
						</td>
						<td class="forum_sous_cat" style="min-width:150px;border-right:none" colspan="3">
							<a href="{forums_list.subcats.U_FORUM_URL}">{forums_list.subcats.NAME}</a>
							<br />
							<span class="text_small">{forums_list.subcats.DESC}</span>
						</td>
						# ELSE #
						<td class="forum_sous_cat" style="width:25px;text-align:center;">
							<img src="{PICTURES_DATA_PATH}/images/{forums_list.subcats.IMG_ANNOUNCE}.png" alt="" />
						</td>
						<td class="forum_sous_cat" style="min-width:150px;">
							<a href="{forums_list.subcats.U_FORUM_VARS}">{forums_list.subcats.NAME}</a>
							<br />
							<span class="text_small">{forums_list.subcats.DESC}</span>
							<span class="text_small">{forums_list.subcats.SUBFORUMS}</span>
						</td>
						<td class="forum_sous_cat_compteur_nbr forum_sous_cat_compteur" >
							{forums_list.subcats.NBR_TOPIC}
							<br />
							{forums_list.subcats.NBR_MSG}
						</td>
						<td class="forum_sous_cat_compteur_text forum_sous_cat_compteur" >
							{L_TOPIC}
							<BR />
							{L_MESSAGE}
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
		