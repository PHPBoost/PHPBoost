		<div class="forum_title">{FORUM_NAME}</div>
		<div class="module_position">
			<div class="row2">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a>
				</span>
				<span style="float:right;">
					{U_SEARCH}
					{U_TOPIC_TRACK}
					{U_MSG_NOT_READ}
					{U_MSG_SET_VIEW}
				</span>&nbsp;
			</div>
		</div>

			
	# START all #				
		# START cats #			
		<br />
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<a href="rss.php" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="../templates/{THEME}/images/rss.gif" alt="Rss" title="Rss" /></a> 
				&nbsp;&nbsp;<a href="{all.cats.U_FORUM_VARS}" class="forum_link_cat">{all.cats.NAME}</a>
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
		# END cats #
		
		
		# START s_cats #		
		<div class="module_position">
			<div class="module_contents forum_contents">
				<table class="module_table" style="width:100%">
					<tr>
						<td class="forum_sous_cat" style="width:25px;text-align:center;">
							{all.s_cats.ANNOUNCE}
						</td>
						<td class="forum_sous_cat" style="min-width:150px;">
							<a href="forum{all.s_cats.U_FORUM_VARS}">{all.s_cats.NAME}</a>
							<br />
							<span class="text_small">{all.s_cats.DESC}</span>
							<span class="text_small">{all.s_cats.SUBFORUMS}</span>
						</td>
						<td class="forum_sous_cat_compteur">
							{all.s_cats.NBR_TOPIC}
						</td>
						<td class="forum_sous_cat_compteur">
							{all.s_cats.NBR_MSG}
						</td>
						<td class="forum_sous_cat_last">
							{all.s_cats.U_LAST_TOPIC}
						</td>
					</tr>	
				</table>		
			</div>
		</div>
		# END s_cats #	
		
		# START end_s_cats #
		<div class="module_position">
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		# END end_s_cats #
			
	# END all #

		
		<br />	
		<div class="module_position">
			<div class="row2">
				<span style="float:left;">
					&bull; <a href="index.php{SID}">{L_FORUM_INDEX}</a>  &bull; <a href="stats.php{SID}">{L_STATS}</a> <a href="stats.php{SID}"><img src="{MODULE_DATA_PATH}/images/stats.png" alt="" class="valign_middle" /></a>
				</span>
				<span style="float:right;">
					{U_SEARCH}
					{U_TOPIC_TRACK}
					{U_MSG_NOT_READ}
					{U_MSG_SET_VIEW}
				</span>&nbsp;
			</div>
			<div class="forum_online">
				{L_TOTAL_POST}: <strong>{NBR_MSG}</strong> {L_MESSAGE} {L_DISTRIBUTED} <strong>{NBR_TOPIC}</strong> {L_TOPIC}.
			</div>
			<div class="forum_online">
				{TOTAL_ONLINE} {L_USER} {L_ONLINE} :: {ADMIN} {L_ADMIN}, {MODO} {L_MODO}, {MEMBER} {L_MEMBER} {L_AND} {GUEST} {L_GUEST}
				<br />
				{L_MEMBER} {L_ONLINE}: 
				# START online #		
					{online.ONLINE}
				# END online #
			</div>
		</div>
		