		# INCLUDE forum_top #
		
		# START error_auth_write #
		<div class="forum_text_column" style="width:350px;margin:auto;height:auto;padding:2px;margin-bottom:20px;">
			{error_auth_write.L_ERROR_AUTH_WRITE}			
		</div>
		# END error_auth_write #
		
		# START cat #		
		<div style="margin-top:20px;margin-bottom:20px;">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">
					<a href="rss.php?cat={IDCAT}" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> 
					&nbsp;&nbsp;<strong>{cat.L_NAME}</strong>
				</div>
				<div class="module_contents forum_contents">
					<table class="module_table" style="width:100%">
						<tr>			
							<td class="forum_text_column" style="min-width:175px;">{L_FORUM}</td>
							<td class="forum_text_column" style="width:60px;">{L_TOPIC}</td>
							<td class="forum_text_column" style="width:60px;">{L_MESSAGE}</td>
							<td class="forum_text_column" style="width:150px;">{L_LAST_MESSAGE}</td>
						</tr>
					</table>
				</div>
			</div>				
		# END cat #
		
		
		# START s_cats #			
			<div class="module_position">
				<div class="module_contents forum_contents">
					<table class="module_table" style="width:100%">
						<tr>
							<td class="forum_sous_cat" style="width:25px;text-align:center;">
								{s_cats.ANNOUNCE}
							</td>
							<td class="forum_sous_cat" style="min-width:150px;">
								<a href="forum{s_cats.U_FORUM_VARS}">{s_cats.NAME}</a>
								<br />
								<span class="text_small">{s_cats.DESC}</span>
								<span class="text_small">{s_cats.SUBFORUMS}</span>
							</td>
							<td class="forum_sous_cat_compteur">
								{s_cats.NBR_TOPIC}
							</td>
							<td class="forum_sous_cat_compteur">
								{s_cats.NBR_MSG}
							</td>
							<td class="forum_sous_cat_last">
								{s_cats.U_LAST_TOPIC}
							</td>
						</tr>	
					</table>		
				</div>
			</div>
		# END s_cats #
		
		# START end_cat #
			<div class="module_position">
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>		
		</div>	
		# END end_cat #
				
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span style="float:left;">
					<a href="rss.php?cat={IDCAT}" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> &bull; {U_FORUM_CAT} {U_POST_NEW_SUBJECT}
				</span>
				<span style="float:right;">{PAGINATION}</span>&nbsp;
			</div>
			<div class="module_contents forum_contents">
				<table class="module_table" style="width:100%">
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
				<table class="module_table" style="width:100%">
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
							<img src="{MODULE_DATA_PATH}/images/{topics.ANNOUNCE}.gif" alt="" />
						</td>
						<td class="forum_sous_cat" style="width:35px;text-align:center;">
							{topics.DISPLAY_MSG} {topics.TRACK} {topics.POLL}
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
				<span style="float:left;">
					<a href="rss.php?cat={IDCAT}" title="Rss"><img class="valign_middle" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> &bull; {U_FORUM_CAT} {U_POST_NEW_SUBJECT}
				</span>
				<span style="float:right;">{PAGINATION}</span>&nbsp;
			</div>
		</div>
		
		# INCLUDE forum_bottom #
		