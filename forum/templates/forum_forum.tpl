		<div class="forum_title">{FORUM_NAME}</div>
		<div class="module_position">
			<div class="row2">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a>
				</span>
				<span style="float:right;">
					{U_SEARCH}
					{U_TOPIC_TRACK}
					{U_LAST_MSG_READ}
					{U_MSG_NOT_READ}
				</span>&nbsp;
			</div>
		</div>

		<br />
		
		# START error_auth_write #
		<div class="forum_text_column" style="width:350px;margin:auto;height:auto;padding:2px;">
			{error_auth_write.L_ERROR_AUTH_WRITE}			
		</div>
			<br />
		# END error_auth_write #
		
		# START cat #		
		<br />
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<a href="rss.php" title="Rss"><img style="vertical-align:middle;margin-top: -2px;" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> 
				&nbsp;&nbsp;<strong>{cat.L_NAME}</strong>
			</div>
			<div class="module_contents forum_contents">
				<table class="module_table" style="width:100%">
					<tr>			
						<td class="forum_text_column">{L_FORUM}</td>
						<td class="forum_text_column" style="width:60px;">{L_TOPIC}</td>
						<td class="forum_text_column" style="width:80px;">{L_MESSAGE}</td>
						<td class="forum_text_column" style="width:160px;">{L_LAST_MESSAGE}</td>
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
		<br /><br />
		# END end_cat #
				
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span style="float:left;">
					<a href="rss.php?cat={IDCAT}" title="Rss"><img style="vertical-align:middle;" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> &bull; {U_FORUM_CAT} {U_POST_NEW_SUBJECT}
				</span>
				<span style="float:right;">{PAGINATION}</span>&nbsp;
			</div>
			<div class="module_contents forum_contents">
				<table class="module_table" style="width:100%">
					<tr>			
						<td class="forum_text_column" style="min-width:175px;">{L_TOPIC}</td>
						<td class="forum_text_column" style="width:100px;">{L_AUTHOR}</td>
						<td class="forum_text_column" style="width:60px;">{L_MESSAGE}</td>
						<td class="forum_text_column" style="width:60px;">{L_VIEW}</td>
						<td class="forum_text_column" style="width:150px;">{L_LAST_MESSAGE}</td>
					</tr>
				</table>
			</div>			
		</div>	
		<div class="module_position">
			<div class="module_contents forum_contents">
				<table class="module_table" style="width:100%">
					# START msg_read #
					<tr>
						<td class="forum_sous_cat" style="text-align:center;">
							0 {msg_read.L_MSG_NOT_READ}
						</td>
					</tr>	
					# END msg_read #

					# START topics #		
					<tr>
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
					
					# START no_topics #
					<tr>
						<td class="forum_sous_cat" style="text-align:center;">
							{no_topics.L_NO_TOPICS}
						</td>
					</tr>
					# END no_topics #
				</table>		
			</div>
		</div>
				
		<div class="module_position">
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left;" class="text_strong">
					<a href="rss.php?cat={IDCAT}" title="Rss"><img style="vertical-align:middle;" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> &bull; {U_FORUM_CAT} {U_POST_NEW_SUBJECT}
				</span>
				<span style="float:right;">{PAGINATION}</span>&nbsp;
			</div>
		</div>
		
		<br />

		<div class="module_position">
			<div class="row2">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a>
				</span>
				<span style="float:right;">
					{U_SEARCH}
					{U_TOPIC_TRACK}
					{U_LAST_MSG_READ}
					{U_MSG_NOT_READ}
				</span>&nbsp;
			</div>
			<div class="forum_online">
				<span style="float:left;">
					{TOTAL_ONLINE} {L_USER} {L_ONLINE} :: {ADMIN} {L_ADMIN}, {MODO} {L_MODO}, {MEMBER} {L_MEMBER} {L_AND} {GUEST} {L_GUEST}
					<br />
					{L_MEMBER} {L_ONLINE}: 
					# START online #		
						{online.ONLINE}
					# END online #
				</span>
				<span style="float:right;">
					<form action="topic{U_CHANGE_CAT}" method="post">
						<select name="change_cat" onchange="document.location = {U_ONCHANGE};">
							{SELECT_CAT}
						</select>
						<noscript>
							<input type="submit" name="valid_change_cat" value="Go" class="submit" />
						</noscript>
					</form>
				</span>
				<br /><br />
			</div>
		</div>
		