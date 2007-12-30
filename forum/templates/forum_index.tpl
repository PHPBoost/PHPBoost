		# INCLUDE forum_top #

			
	# START all #				
			# START all.cats #			
		<div style="margin-top:20px;">
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
			# END all.cats #			
			
			# START all.s_cats #		
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
			# END all.s_cats #	
			
			# START all.end_s_cats #
			<div class="module_position">
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>
		</div>	
			# END all.end_s_cats #
		
	# END all #

		
		# INCLUDE forum_bottom #

		